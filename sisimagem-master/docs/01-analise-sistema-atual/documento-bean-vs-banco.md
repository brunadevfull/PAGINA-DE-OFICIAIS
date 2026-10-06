# `bean/Documento.java` vs. colunas reais de `TSRECORD`/`TSEXFIELDV`

Comparação entre os 24 atributos de `bean/Documento.java` e o que `model/DAOTrim.java`
efetivamente grava, olhando `inserirRecord` (grava em `TSRECORD`), `inserirField`
(grava um `TSEXFIELDV` por chamada) e as leituras (`listaDocumentoporId`,
`listaDocumento`) que populam o bean de volta a partir do banco — já documentado em
detalhe no fluxo de inclusão (`fluxo-inclusao-documento.md`).

## Resposta direta às duas perguntas

**Campo que existe na tela mas não é salvo:** sim — **`title` / "Título do Documento"**
(campo `<input name="title" id="title">` em `incluirDocumento.jsp` e
`incluirDocumentoPapem42.jsp`). `TSRECORD.TITLE` é uma coluna real e gravada
normalmente, mas o valor que o usuário digita nunca chega ao bean: `Utilitaria.
setDocumento(campo, valor, documento)` (o método que popula o `Documento` a partir dos
campos do multipart) **não tem nenhum `case` para `"title"`** — o `if/else if` cobre
todos os outros ~19 campos de texto da tela, mas pula esse. Resultado: `documento.
getTitle()` chega vazio em `inserirRecord`, que então aplica o fallback "gera nome do
título se não for preenchido na tela" (`DAOTrim.java:1236-1238`) e grava o **ID
numérico do registro** como título — nunca o texto que o usuário escreveu. Esse
achado já estava registrado no Bloco 6; aqui fica confirmado como a resposta ao
"campo na tela que não é salvo".

**Campo salvo que a tela não mostra:** não, entre os 14 nomes de `TSEXFIELDV` gravados
por `inserirField` (Beneficiário, Consignado, CPF, Data de Protocolo, Entidade
Consignatária, OBSERVAÇÕES, Número do Documento, Origem, Protocolo, Tipo de Documento,
NIP/Matrícula, Ofício Judicial Anexo, Urgente, Data de Criação (Legado), Data de
Protocolo (Legado)) — todos têm campo correspondente tanto no bean quanto na tela de
`incluirDocumento.jsp`/`incluirDocumentoPapem42.jsp`, confirmado no Bloco 2. Nenhum
`TSEXFIELDV` é gravado "às escuras".

## Tabela completa de correspondência

| Atributo do bean | Onde é gravado (tabela.coluna) | Observação |
|---|---|---|
| `idArquivo` | `TSRECORD.URI` | gerado por `proximoURI`, nunca vem da tela |
| `beneficiario` | `TSEXFIELDV` ("Beneficiário") | ok, 1:1 com a tela |
| `consignado` | `TSEXFIELDV` ("Consignado") | ok |
| `cpf` | `TSEXFIELDV` ("CPF") | ok (dígitos limpos de `.`/`-` em `Utilitaria.setDocumento`) |
| `dataProtocolo` | `TSEXFIELDV` ("Data de Protocolo") | ok |
| `dataCriacaoLegado` | `TSEXFIELDV` ("Data de Criação (Legado)") | ok — mas **não existe campo nessa tela para preenchê-lo**; só chega a ter valor em documentos migrados do sistema legado, nunca via `incluirDocumento.jsp` |
| `dataProtocoloLegado` | `TSEXFIELDV` ("Data de Protocolo (Legado)") | mesma observação acima |
| `entidadeConsignataria` | `TSEXFIELDV` ("Entidade Consignatária") | ok |
| `nipMatricula` | `TSEXFIELDV` ("NIP/Matrícula") | ok |
| `numeroDocumento` | `TSEXFIELDV` ("Número do Documento") | ok |
| `observacoes` | `TSEXFIELDV` ("OBSERVAÇÕES") | ok |
| `oficioJudicialAnexo` | `TSEXFIELDV` ("Ofício Judicial Anexo") | ok |
| `origem` | `TSEXFIELDV` ("Origem") | ok |
| `protocolo` | `TSEXFIELDV` ("Protocolo") | ok |
| `tipoDocumento` | `TSEXFIELDV` ("Tipo de Documento") | ok |
| `urgente` | `TSEXFIELDV` ("Urgente") | ok |
| `recordNumber` | `TSRECORD.RECORDID` | gravado, mas **recalculado dentro de `inserirRecord`** (chama `buscaProximoRecordId` de novo) — o que aparece na tela pode não ser o valor final gravado (achado do Bloco 6) |
| `title` | `TSRECORD.TITLE` | **nunca recebe o valor digitado** — ver acima |
| `classificacao` | `TSRECORD.RCSTRUCTUREDTITLE` | não é digitação livre: campo `readonly`, preenchido por JS/constante fixa por tipo de documento (`OperacaoIncluirAnexoPapem41`/`Papem42` fixam esse valor no servidor) |
| `dataDocumento` | `TSRECORD.REGDATETIME` | ok (parse `dd/MM/yyyy HH:mm:ss`) |
| `dataInclusao` | `TSRECORD.CREATIONDATETIME` | ok |
| `recordType` | `TSRECORD.RCRECTYPEURI` | ok (também usado para decidir `RCCONTAINERURI`) |
| `rccontaineruri` | `TSRECORD.RCCONTAINERURI` | só usado quando setado (fluxo de anexo/documento filho); senão cai no fallback `1`/`0` conforme `recordType` seja `"4"`/`"12"` |
| `fullrecordid` | `TSRECORD.FULLRECORDID` | **o setter existe e é usado em outros fluxos (ex.: exibição), mas o valor gravado no insert é `lRecordId[1]`, recalculado por `buscaProximoRecordId` dentro do próprio `inserirRecord` — o valor que o bean carregava antes é descartado** |
| `responsavel` | `TSRECLOC.RLLOCURI` (via `inserirRecLoc`) e `TSRECELEC` (via `inserirRecElec`, usado só para resolver o `uri` do responsável na subquery) | gravado, mas em tabela fora de `TSRECORD`/`TSEXFIELDV` — não vem de digitação, vem da sessão (usuário logado) |
| `pasta` | `TSRECLOC.RLDESCRIPTION` (via `inserirRecLoc`) | gravado, fora de `TSRECORD`/`TSEXFIELDV`; campo `readonly` na tela, preenchido por JS conforme o tipo de registro |
| `nomeArquivoCompleto` | `TSRECELEC.RESID` (via `inserirRecElec`) | gravado, fora de `TSRECORD`/`TSEXFIELDV` — é o nome do arquivo no sistema de arquivos |
| `nomeArquivoOriginal` | `TSRECELEC.REFILENAME` (+ `REEXTENSION` derivado) | gravado, fora de `TSRECORD`/`TSEXFIELDV` |
| `recordNumberPai` | **não é gravado em nenhum insert.** Só é populado na *leitura*, via `left join tsrecord pai on (pai.uri = rec.RCCONTAINERURI)` dentro de `listaDocumentoporId` (`DAOTrim.java:255,301`) — é um valor **derivado** (o `RECORDID` do documento-pai), não uma coluna própria. E mesmo nessa forma, a mesma linha de código está **comentada** nos outros dois métodos de listagem (`listaDocumento`, linhas 511 e 698) — então em telas de listagem esse campo fica sempre vazio, só aparece corretamente na tela de detalhamento de um documento específico |
| `listaAnexo` | não é coluna — populado à parte via `addAnexo` (consulta separada em `TSRECORD`/`TSRECELEC` filtrando `RCCONTAINERURI`) | relação, não campo persistido diretamente |

## Achados relevantes (além da resposta direta)

1. **"Título do Documento" é o único campo com `<input>` na tela de inclusão que se
   perde silenciosamente** — nem erro, nem aviso; o usuário digita, o sistema descarta
   e grava o ID numérico no lugar.
2. **Duas colunas "Legado" (`dataCriacaoLegado`, `dataProtocoloLegado`) existem no bean
   e são gravadas normalmente se tiverem valor, mas não há nenhum campo na tela de
   inclusão de documento para preenchê-las** — só podem ter chegado ao registro por
   importação/migração do sistema TRIM legado, nunca pelo fluxo web atual. Na tela de
   pesquisa (`pesquisarDocumento.jsp`) elas aparecem como filtro de busca, mas não como
   campo de cadastro.
3. **`recordNumberPai` é a peça mais frágil:** não é uma coluna própria (é derivada por
   `JOIN` no momento da leitura), e essa derivação só está ativa em um dos três métodos
   de consulta que povoam `Documento` a partir do banco — nas outras duas está comentada
   no código-fonte. Então "Processo/Pasta" (o rótulo que exibe esse campo em
   `detalhaDocumento2.jsp`) só aparece corretamente quando o documento é aberto
   individualmente (`cmd=detalharDocumento`); em qualquer fluxo que reaproveitasse
   `recordNumberPai` a partir de uma listagem ficaria vazio.
4. **`fullrecordid` do bean é ignorado no momento de gravar** — mesmo que algum fluxo
   tenha setado esse valor antes (ex.: `OperacaoBuscarRecordId`), `inserirRecord`
   recalcula por conta própria com `buscaProximoRecordId`, o mesmo padrão de
   recomputação silenciosa já visto em `recordNumber` (Bloco 6).
