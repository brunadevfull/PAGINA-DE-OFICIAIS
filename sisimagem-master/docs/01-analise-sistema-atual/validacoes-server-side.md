# Validações server-side (classes `Operacao*.java`)

Levantamento feito por leitura de todas as classes `controller.Operacao*` em
`src/main/java/controller/`. Cobre as 17 registradas no dispatcher (`mapOperacoes`,
ver `docs/01-analise-sistema-atual/formularios-jsp.md`), as 3 órfãs
(`OperacaoCadastroUsuario`, `OperacaoIncluirAnexo`, `OperacaoTeste`) e os dois
pseudo-"Operacao" que na verdade são `HttpServlet` (`OperacaoAbrirDocumento`,
`OperacaoScanearDocumento`).

**Achado geral, antes do detalhe por classe:** ao contrário do client-side (que tem
várias checagens de "campo obrigatório"), no servidor **quase nenhuma classe valida
formato, tamanho ou obrigatoriedade de campo antes de gravar**. O `catch` genérico de
cada classe só existe para capturar falha técnica (SQLException, NPE, parse de upload)
e converter em `OperacaoException` — não para checar se os dados fazem sentido. As
únicas regras de negócio reais no servidor estão listadas na coluna "regra não óbvia"
de cada classe abaixo; fora delas, a integridade do dado depende inteiramente do que
o JavaScript deixou passar (bloco 3, `validacoes-client-side.md`).

---

## `OperacaoLogin` (`cmd=login`)

- **Campos usados:** `usuario`, `senha`.
- **Valida:** usuário existe e senha (hash Whirlpool) confere, via `daoTrim.validaUsuarioSenha(usuario)` + comparação de hash.
- **Falha:** contador `senhaInvalida` (guardado na sessão) incrementa a cada erro.
  - Antes de 5 erros: mensagem **"Usuário e/ou senha inválida."**, redireciona para `login.jsp`.
  - Na 5ª tentativa: chama `daoTrim.bloquearUsuario(usuario)` e mostra **"Usuário Bloqueado"**, redireciona para `login.jsp`.
  - Exceção técnica (BD indisponível etc.): **"Falha na execução da operação"**, redireciona para `erro.jsp`. *(única operação que não lança `OperacaoException` — trata tudo com `catch` interno e nunca deixa o `ServletControlador` cair no seu `catch(OperacaoException)`.)*
- **Regra não óbvia:** se a senha em texto claro digitada for literalmente `"marinha"` (e a autenticação for bem-sucedida), o fluxo **não** vai para `index.jsp` — é redirecionado para `alterarSenha.jsp`, forçando a troca. Isso é uma convenção de "senha padrão de reset" que não aparece em nenhum lugar do formulário.

## `OperacaoAlterarUsuario` (`cmd=alterarUsuario`, admin altera outro usuário)

- **Campos usados:** `senha`, `grupo`, `bloqueio` (lidos do request; o usuário-alvo vem do atributo de sessão `usuarioDetalhe`, setado antes por `detalharUsuario`).
- **Valida:** nada de formato/obrigatoriedade. Só executa `daoTrim.alterarUsuario(usuario)` e checa o retorno booleano.
- **Falha:** `daoTrim.alterarUsuario` retorna `false` → **"Falha na execução da operação"**, mas segue para `content.jsp` (não é tratado como erro real, é só uma mensagem). Exceção técnica → `OperacaoException` → `erro.jsp`.
- **Regra não óbvia:** a senha só é alterada **se `request.getParameter("senha")` não for uma string vazia** (`!equals("")`). Deixar o campo em branco no formulário = mantém a senha atual — mas isso não está explícito em nenhum lugar da tela (o campo não tem indicação de "opcional se não quiser trocar"). `grupo` e `bloqueio` são sempre sobrescritos com o que vier no request, sem checar se são valores válidos (ex.: `bloqueio` pode receber qualquer string, não só `"B"`/vazio).

## `OperacaoAlterarSenha` (`cmd=alterarSenha`, usuário troca a própria senha)

- **Campos usados:** `senha` (o usuário-alvo é o da própria sessão).
- **Valida:** nada — mesmo padrão do item acima.
- **Falha:** retorno `false` do DAO → **"Falha na execucao da operacao"**, segue para `index.jsp` mesmo assim. Exceção técnica → `OperacaoException` → `erro.jsp`.
- **Regra não óbvia:** mesma regra de "senha vazia = não altera" do `OperacaoAlterarUsuario`. Não há checagem de força de senha nem de confirmação no servidor — ambas são só client-side (e, note-se, o client-side de força de senha é o único que realmente executa nessa tela).

## `OperacaoExcluirUsuario` (`cmd=excluirUsuario`)

- **Campos usados:** `idUsuario` (query string do link "Deletar").
- **Valida:** nada — passa `idUsuario` direto para `daoTrim.excluirUsuario(idUsuario)`.
- **Falha:** retorno `false` → **"Falha na execução da operação"**, ainda assim segue para `topo.jsp`. Exceção técnica → `OperacaoException` → `erro.jsp`.
- **Regra não óbvia:** nenhuma confirmação é pedida antes de excluir (o link "Deletar" em `cadastroUsuario.jsp` exclui direto, sem `confirm()` no JS nem segunda tela no servidor).

## `OperacaoIncluirUsuario` (`cmd=incluirUsuario`)

- **Campos usados:** `rlcname` (usuário), `senha`, `rjgname` (grupo).
- **Valida:** **única checagem de negócio real no servidor deste grupo de operações** — `daoTrim.verificaExisteUsuario(usuario.getUsuario())` antes de inserir.
- **Falha (usuário duplicado):** mensagem **"Usuário `<nome>` já cadastrado no sistema"**, redireciona de volta para `cadastroUsuario.jsp` (recarrega a tela de cadastro, não é tratado como `erro.jsp`).
- **Falha (retorno `false` do insert):** **"Falha na execução da operação"**, segue para `content.jsp` mesmo assim.
- **Falha técnica:** `OperacaoException` → `erro.jsp`.
- **Regra não óbvia:** não valida tamanho mínimo de usuário, força de senha, nem confirma senha — tudo isso é só no client-side (e no client-side de `cadastroUsuario.jsp`, que é a única versão realmente conectada a um evento).

## `OperacaoDetalharUsuario` (`cmd=detalharUsuario`)

- **Campos usados:** `idUsuario` (query string).
- **Valida:** nada. `idUsuario` é usado como **índice de posição** na lista `listaUsuario` da sessão (`listaGenero.indexOf(bean)` foi usado para montar o link), não como ID do banco.
- **Falha:** não há tratamento — se `idUsuario` não for um número válido ou estiver fora do intervalo da lista, lança `NumberFormatException`/`IndexOutOfBoundsException` **não capturada**, que não vira `OperacaoException` e derruba a request com erro 500 (consistente com o achado do Bloco 1).
- **Regra não óbvia:** como é índice de lista (não ID), se a lista de usuários em sessão for recarregada entre a pesquisa e o clique em "Alterar" (outra aba, por exemplo), o link pode abrir o usuário errado.

## `OperacaoPesquisarUsuario` (`cmd=pesquisarUsuario`)

- **Campos usados:** qualquer parâmetro do request cujo nome comece com `"r"`.
- **Valida:** nada — monta um `Map` de filtro removendo o prefixo `"r"` de cada nome de parâmetro (ex.: `rlcname` → `lcname`) e passa direto para `daoTrim.listaUsuario(mCamposPesquisa)`.
- **Falha:** só exceção técnica → `OperacaoException` → `erro.jsp`.
- **Regra não óbvia:** qualquer parâmetro que **não comece com `"r"`** é silenciosamente ignorado no filtro (sem erro, sem aviso) — convenção de nomenclatura de campo que não é documentada em lugar nenhum do formulário.

## `OperacaoPesquisarDocumento` (`cmd=pesquisarDocumento`)

- **Campos usados:** mesmo padrão de prefixo `"r"` do item acima, aplicado aos ~18 campos de `pesquisarDocumento.jsp`.
- **Valida:** nada nos campos em si.
- **Falha:** se a busca não retornar nada e não houver nenhum parâmetro preenchido, `listaDocumento` fica `null` (tela mostra "não há documentos"). Exceção técnica → `OperacaoException` → `erro.jsp`.
- **Regra não óbvia (controle de acesso por perfil):** se o `grupo` do usuário logado na sessão for `"SASM"`, a classe **força** `mCamposPesquisa.put("perfil", "PAPEM41")` — restringe a pesquisa a documentos do PAPEM-41 automaticamente, **independente do que o usuário preencheu no formulário**. Essa regra não aparece em nenhum campo da tela; é um filtro de segurança injetado no servidor.

## `OperacaoDetalharDocumento` (`cmd=detalharDocumento`)

- **Campos usados:** `idArquivo`.
- **Valida:** nada de formato. Busca o documento e, se necessário, agrega anexos (`daoTrim.addAnexo`).
- **Falha:** se `daoTrim.listaDocumentoporId(idArquivo)` retornar `null`, seta mensagem **"Não foi possível realizar a operação"**, mas **mesmo assim** segue para `detalhaDocumento2.jsp` com `documento = null` salvo na sessão. **Achado:** `detalhaDocumento2.jsp` chama `documento.getPasta()` etc. sem checar null — isso deve gerar `NullPointerException` na renderização do JSP, não a mensagem de erro esperada.
- **Regra não óbvia:** nenhuma além da acima.

## `OperacaoIncluirDocumento` (`cmd=incluirDocumento`)

- **Campos usados:** todos os campos de `incluirDocumento.jsp` (via multipart), mais o `documento` já presente na sessão (se houver).
- **Valida:** só se a requisição é multipart (`ServletFileUpload.isMultipartContent`). Não valida nenhum campo de negócio (data, NIP, processo etc.) — tudo isso fica só para o client-side.
- **Falha (não é multipart):** mensagem **"Desculpe este Servlet só lida com pedido de upload de arquivos"**, `return` sem persistir — mas como `proxPagina` (`content.jsp`) já foi setado **antes** da checagem, a tela de sucesso aparece mesmo assim.
- **Falha (erro no parse do upload):** mensagem **"Falha no Recebimento do Documento"**, `return` sem persistir — mesmo mascaramento acima (já visto no Bloco 1).
- **Falha técnica no insert:** `OperacaoException` → `erro.jsp`. Há ainda um `catch (Throwable e)` externo que só faz `printStackTrace()` sem repassar erro ao usuário.
- **Regra não óbvia:** nenhuma validação de obrigatoriedade real no servidor — mesmo os campos marcados com `*` na tela (Processo, Data do Documento, Documento) **podem chegar vazios ao banco** se o usuário burlar o JS client-side (ex.: enviando o POST diretamente).

## `OperacaoIncluirDocumentoPapem42` (`cmd=incluirDocumentoPapem42`)

- **Campos usados:** nenhum vindo do request — monta um novo `Documento` filho a partir do `documento` pai já salvo na sessão.
- **Valida:** nada.
- **Falha:** **sem try/catch algum.** Se `documento` (pai) não estiver na sessão, `documento.getIdArquivo()` lança `NullPointerException` não tratada → erro 500, não vira `OperacaoException`.
- **Regra não óbvia:** fixa automaticamente `recordType = "5"` e `classificacao = "PAPEM - PAPEM40 - PAPEM42"` — essa operação **não grava** o documento; ela só prepara um novo `Documento` filho na sessão e redireciona de volta para `incluirDocumento.jsp`, onde a gravação real acontece depois via `cmd=incluirDocumento`. Ou seja, o nome do comando sugere "incluir", mas o que ele faz é só "preparar formulário pré-preenchido".

## `OperacaoIncluirAnexoPapem41` (`cmd=incluirAnexoPapem41`)

- **Campos usados:** `nomeArquivoOriginal` (arquivo), documento pai da sessão.
- **Valida:** só se é multipart (implícito ao tentar o parse — não há checagem explícita).
- **Falha (erro no upload):** mensagem **"Falha no Recebimento do Anexo"**, `return` sem persistir — mas `proxPagina` (`content.jsp`) já setado antes, então a tela de sucesso aparece mesmo assim (erro mascarado, já visto no Bloco 1).
- **Regra não óbvia:** **sem checar se `documento` é `null`** antes de chamar `documento.getIdArquivo()` logo no início do método (antes até do try) — se o usuário chegar nessa tela sem ter passado por um documento carregado na sessão, `NullPointerException` não tratada. Fixa `recordType = "15"` e `classificacao = "PAPEM - PAPEM40 - PAPEM41"` automaticamente.

## `OperacaoIncluirDocumentoRespostaPapem42` (`cmd=incluirDocumentoRespostaPapem42`)

- **Campos usados:** `titulo`, `nomeArquivoOriginal` (arquivo), documento pai da sessão.
- **Valida:** mesmo padrão do item acima (mesma falta de checagem de `documento null`).
- **Falha (erro no upload):** mensagem **"Falha no Recebimento do Anexo"**, mesmo mascaramento (segue para `content.jsp`).
- **Regra não óbvia:** fixa `recordType = "8"` e `classificacao = "PAPEM - PAPEM40 - PAPEM42"`. O campo `titulo` só é gravado se vier como campo de formulário (`item.isFormField() && item.getFieldName().equals("titulo")`) — se o multipart não trouxer esse campo (por exemplo, um POST manual malformado), `anexo.setTitle(null)` silenciosamente, sem erro.

## `OperacaoBuscarRecordId` (`cmd=buscaProximoRecordId`)

- **Campos usados:** todos os campos de `incluirDocumento.jsp` (parse multipart, populados via `Utilitaria.setDocumento` — reflexão por nome de campo).
- **Valida:** só o parse do multipart está dentro de try/catch.
- **Falha (erro no upload):** mensagem **"Falha no Recebimento do Documento"**, `return` — mas `proxPagina` (`incluirDocumento.jsp`) já setado antes, mascarando o erro (já visto no Bloco 1).
- **Regra não óbvia:** `daoTrim.buscaProximoRecordId(Integer.parseInt(documento.getRecordType()))` fica **fora** do try/catch do parse. Se `recordType` não for um número válido (ou vier nulo), `NumberFormatException` — que é `RuntimeException`, não `OperacaoException` — propaga sem tratamento, apesar da assinatura do método declarar `throws OperacaoException` (essa cláusula não cobre exceções não verificadas).

## `OperacaoLocalizarDocumento` (`cmd=localizarDocumento`)

- **Campos usados:** nenhum do request — abre um `JFileChooser` (Swing) no **processo do servidor**.
- **Valida:** nada.
- **Regra não óbvia:** essa tela não funciona em ambiente servidor real/headless (não há display gráfico) — é herança de uma versão desktop do sistema. Sem try/catch: qualquer exceção do Swing/AWT (bem provável em headless) propaga sem virar `OperacaoException`.

## `OperacaoEncerraSessao` (`cmd=encerraSessao`)

- **Campos usados:** nenhum.
- **Valida:** nada — só `request.getSession().invalidate()`.
- **Regra não óbvia:** nenhuma (o único try/catch existente está comentado, então hoje a operação nunca lança erro).

## `OperacaoScanearDocumento` / `OperacaoAbrirDocumento` — não são `Operacao`

Ambas estendem `HttpServlet`, não `Operacao` — não têm `executar()`/validação de negócio no
sentido pedido aqui (já documentado no Bloco 1: `OperacaoScanearDocumento` quebra com
`ClassCastException` quando chamada via `cmd`). Vale registrar como achado de segurança à
parte: **`OperacaoAbrirDocumento.doPost()` recebe o parâmetro `nomeDocumento` e abre esse
caminho diretamente no disco (`new File(nomeDocumento)` + `FileInputStream`), sem validar
que o caminho pertence à pasta de documentos configurada (`path` no `web.xml`).** Como é um
parâmetro de POST comum, um usuário autenticado pode alterar esse valor no client e ler
qualquer arquivo acessível ao processo do servidor (path traversal / leitura arbitrária de
arquivo) — não é uma validação de campo de formulário, mas é a falha mais grave encontrada
nas classes deste pacote.

## Classes órfãs (não registradas em `mapOperacoes`, inacessíveis via `cmd`)

- **`OperacaoCadastroUsuario`**: só lista usuários e redireciona para `cadastroUsuario.jsp` — sem validação. Sobreposta pela combinação `cadastro.jsp` (JSP) + `OperacaoPesquisarUsuario`/`OperacaoIncluirUsuario`, que é o que o sistema realmente usa.
- **`OperacaoIncluirAnexo`**: versão genérica (sem sufixo Papem41) de anexar arquivo. **Bug:** lê `documento` de `request.getAttribute("documento")`, mas em todo o resto do sistema o documento fica em **sessão** (`session.getAttribute`), não em atributo de request — na prática esse valor é sempre `null` e a primeira linha do `try` (`documento.getIdArquivo()`) lançaria `NullPointerException`. Consistente com ser código morto/nunca finalizado.
- **`OperacaoTeste`**: manipula `documento.classificacao` a partir de um parâmetro de teste e redireciona para `../teste.jsp` (arquivo que não existe no projeto). Sem validação; claramente um stub de teste esquecido no código.
