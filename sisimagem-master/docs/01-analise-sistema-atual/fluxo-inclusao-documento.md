# Fluxo de inclusão de documento, passo a passo

Levantamento feito por leitura de `controller/OperacaoIncluirDocumento.java`,
`bean/Documento.java`, `controller/Utilitaria.java` (`montaNomeArquivo`, `setDocumento`)
e `model/DAOTrim.java` (`inserirDocumento` e os métodos privados que ele chama:
`inserirRecord`, `inserirField`, `inserirRecElec`, `inserirRecLoc`, `proximoURI`,
`buscaProximoRecordId`). Cobre só o caminho de sucesso "feliz" primeiro, depois os
pontos onde algo pode falhar deixando dado gravado pela metade.

**Achado central, adiantado:** o arquivo é gravado em disco **antes** de qualquer
gravação no banco, e essa escrita em disco **não faz parte da transação** que protege
as gravações no banco. Se a transação do banco falhar (rollback) depois que o arquivo já
foi escrito, o arquivo físico fica órfão — gravado, mas sem nenhum registro
correspondente em `TSRECORD`/`TSRECELEC`. O inverso (registro no banco sem arquivo em
disco) não acontece nesse fluxo, porque a ordem real é disco → banco, nunca o contrário.

---

## Passo a passo, na ordem em que o código executa

### 1. `OperacaoIncluirDocumento.executar()` — preparação (nada é gravado ainda)

1. Recupera `documento` do atributo de sessão `"documento"`; se não existir, cria um
   `Documento` novo vazio (linha 39-44).
2. Seta `documento.responsavel` = usuário logado (`session.getAttribute("usuario")`,
   linha 49). **Único dado que não vem do formulário** — vem da sessão, então não pode
   ser falsificado pelo campo `readonly` da tela.
3. Checa se a requisição é multipart (`ServletFileUpload.isMultipartContent`, linha 52).
   Se **não** for, mensagem **"Desculpe este Servlet só lida com pedido de upload de
   arquivos"** e `return` — nada é lido do formulário, nada é gravado (nem disco nem
   banco). Mesmo assim a tela de sucesso (`content.jsp`) é exibida, porque `proxPagina`
   já foi fixado no início do método (achado já registrado no Bloco 4).

### 2. Parse do multipart — leitura dos campos e **primeira gravação em disco**

Dentro de um `try` isolado (linhas 54-83), iterando sobre cada `FileItem` do POST:

- **Campo de formulário comum** (texto, select, checkbox — `item.isFormField()` true):
  `Utilitaria.setDocumento(nomeCampo, valor, documento)` (linha 64) faz um `if/else if`
  manual por nome de campo e chama o setter correspondente no bean — só popula o objeto
  em memória, nada é gravado ainda. Campos sem `case` correspondente em `setDocumento`
  (ex.: `title`, que não está nesse `if/else`) **nunca chegam ao bean por essa via** —
  olhando `Documento.java`, `title` teria que ser setado de outro jeito; como não há
  `jsp:setProperty` nem outro ponto de atribuição para `title` em
  `incluirDocumento.jsp`, o campo "Título do Documento" digitado na tela **é
  descartado silenciosamente** e nunca chega ao banco.
- **Campo de arquivo** (`nomeArquivoOriginal`, `!item.isFormField()`, linhas 65-75):
  1. `Utilitaria.montaNomeArquivo(path, item.getName())` (linha 67) — calcula o nome
     sequencial do arquivo no padrão `001+AAAAMM+NNNNNNNNNN.ext`, **e já cria o
     diretório de destino no disco** (`diretorioFileSystem.mkdirs()`, dentro do próprio
     `montaNomeArquivo`) se ele não existir. O número sequencial vem de
     `diretorioFileSystem.listFiles().length + 1` — **sem nenhum lock**: dois uploads
     simultâneos no mesmo mês podem calcular o mesmo número e um arquivo sobrescrever o
     outro (mesma classe de falha de concorrência do `proximoURI` do banco, ver abaixo).
  2. `documento.setNomeArquivoCompleto(...)` guarda esse nome no bean.
  3. **`item.write(fileNovo)` (linha 73) — grava o conteúdo do arquivo no disco, no
     caminho calculado.** Esta é a primeira gravação persistente de todo o fluxo,
     e acontece **antes de qualquer contato com o banco**.
- **Falha no parse** (`catch (Exception ex)`, linhas 78-83): mensagem **"Falha no
  Recebimento do Documento"**, `return` sem chamar o banco. Se o arquivo já tinha sido
  escrito em disco (passo acima) antes do erro ocorrer em outro campo do mesmo
  multipart, **o arquivo fica no disco, órfão, sem nenhuma tentativa de gravar (ou
  reverter) nada no banco.** E, de novo, `content.jsp` (sucesso) é exibido mesmo assim.

### 3. Persistência no banco — `DAOTrim.inserirDocumento(documento)` (linha 97)

Só é chamado se o parse do multipart terminou sem exceção. Abre uma transação JDBC
explícita (`conn.setAutoCommit(false)`, `DAOTrim.java:1403`) que cobre, nesta ordem:

1. **`inserirRecord(documento)`** (`DAOTrim.java:1223`) — grava a linha principal em
   `TSRECORD`: obtém `idArquivo` via `proximoURI("TSRECORD")` (mesmo padrão `SELECT
   MAX(uri)+1` sem lock — condição de corrida idêntica à do nome de arquivo),
   **recalcula o `recordNumber`** chamando `buscaProximoRecordId` de novo (ignora
   qualquer valor de `recordNumber` que a tela já tivesse mostrado ao usuário via
   `cmd=buscaProximoRecordId` — se outro documento do mesmo tipo foi incluído nesse
   meio-tempo, o número exibido na tela e o número realmente gravado podem divergir).
   Faz `parse()` de `dataDocumento`/`dataInclusao` no formato `dd/MM/yyyy HH:mm:ss`; se
   o valor não bater esse formato (ex.: JS de máscara não rodou), `ParseException` é
   capturada e só logada (`e1.printStackTrace()`) — o método cai sem executar o
   `INSERT`, retorna `false` implicitamente.
2. **`inserirField(...)` — um `INSERT` em `TSEXFIELDV` por campo opcional não-vazio**
   (`DAOTrim.java:1195`), na ordem: Beneficiário, Consignado, CPF, Data de Criação
   (Legado), Data de Protocolo, Data de Protocolo (Legado), Entidade Consignatária,
   NIP/Matrícula, Número do Documento, Observações, Ofício Judicial Anexo, Origem,
   Protocolo, Tipo de Documento, Urgente (linhas 1407-1469). Cada chamada só executa se
   a anterior teve sucesso (`if(flagTransation && ...)`) — encadeamento manual, não é a
   transação em si que interrompe o laço.
3. **`inserirRecElec(...)`** (`DAOTrim.java:1158`) — grava em `TSRECELEC` o vínculo
   entre o registro e **o arquivo físico já gravado no passo 2** (nome no sistema de
   arquivos, extensão, nome original, responsável). É este insert que formalmente liga
   o arquivo em disco ao registro do banco — só ocorre depois de todos os campos
   opcionais.
4. **`inserirRecLoc(...)`** (`DAOTrim.java:1127`) — grava em `TSRECLOC` a localização
   (pasta) e o responsável, último insert da cadeia.
5. Se **todos** os passos acima retornaram `true`: `conn.commit()`
   (`DAOTrim.java:1479`). Se **qualquer um** falhou: `conn.rollback()` (linha 1482) —
   desfaz `TSRECORD`, todos os `TSEXFIELDV`, `TSRECELEC` e `TSRECLOC` já inseridos
   nesta chamada. **A transação cobre só o banco — o arquivo em disco do passo 2 não
   está sob controle transacional nenhum e nunca é apagado nesse rollback.**

### 4. Retorno para `OperacaoIncluirDocumento` (linhas 97-106)

- `inserirDocumento` retornou `true` → mensagem **"Documento `<recordNumber>` inserido
  no sistema!"**.
- `inserirDocumento` retornou `false` (rollback ocorreu) → mensagem **"Falha na
  execução da operação"** — mas o fluxo segue exatamente igual: `session.
  removeAttribute("documento")` roda de qualquer jeito, e o redirecionamento é sempre
  para `content.jsp` (não existe uma tela de erro distinta para esse caso). **O arquivo
  gravado em disco no passo 2 não é removido nem referenciado em lugar nenhum a partir
  daqui** — fica órfão até uma limpeza manual.
- Exceção não prevista no meio do processo (`catch (Exception e)`, linha 108): vira
  `OperacaoException` → `erro.jsp` (fluxo já documentado no Bloco 1/4). Mesma
  consequência: se o arquivo já tinha sido gravado em disco antes da exceção, continua
  órfão.
- Há ainda um `catch (Throwable e)` mais externo (linha 111) que só faz
  `e.printStackTrace()` — nenhuma mensagem chega ao usuário nesse caso, e a resposta
  HTTP fica indefinida (nem sucesso nem erro renderizado).

---

## Resumo — onde exatamente um dado pode ficar "gravado pela metade"

| Etapa | O que foi gravado até aqui | O que pode falhar depois | Resultado da falha |
|---|---|---|---|
| Parse do multipart, campo de arquivo (`item.write`) | **Arquivo físico já no disco**, diretório já criado | Erro em outro campo do mesmo multipart (exceção no `parseRequest`/loop) | Arquivo órfão no disco; função retorna antes de chamar o banco; `content.jsp` mostrado como sucesso |
| `inserirRecord` (dentro da transação do banco) | Arquivo em disco + nada ainda no banco | `ParseException` de data, ou falha SQL | `flagTransation=false` → nenhum insert seguinte roda; como o `TSRECORD` nem chegou a ser commitado, não há "meio registro" no banco — mas o arquivo em disco permanece |
| `inserirField` (1 a 14 chamadas) | Arquivo em disco; `TSRECORD` inserido **dentro da transação, ainda não commitado** | Falha SQL em qualquer campo opcional | `rollback()` desfaz `TSRECORD` e os `TSEXFIELDV` já inseridos nesta transação — banco fica consistente entre si, mas o arquivo em disco não tinha nenhuma relação transacional e continua lá |
| `inserirRecElec` (vincula arquivo ↔ registro) | Arquivo em disco; `TSRECORD`+campos inseridos, não commitados | Falha SQL neste insert | Rollback do que já estava na transação; **arquivo em disco sem nenhum registro `TSRECELEC` que o referencie — o caso mais direto de "arquivo órfão"**, pois é exatamente este insert que seria a prova de vínculo |
| `inserirRecLoc` (pasta/responsável) | Tudo acima, ainda não commitado | Falha SQL | Rollback total no banco; arquivo em disco permanece órfão |
| Depois do `commit()` | Tudo gravado (banco consistente + arquivo referenciado) | — | Caminho feliz |

**Não existe o caso inverso** (registro gravado no banco, arquivo ausente no disco)
neste fluxo — a ordem de execução garante que o arquivo é sempre escrito antes de
qualquer tentativa de gravação no banco. O risco real e recorrente é sempre o mesmo:
**arquivo físico órfão em disco, sem contrapartida no banco**, e o sistema não tem
nenhuma rotina de limpeza ou de nova tentativa para esses casos — o arquivo simplesmente
fica ocupando espaço, sem ser referenciado por nenhum registro.
