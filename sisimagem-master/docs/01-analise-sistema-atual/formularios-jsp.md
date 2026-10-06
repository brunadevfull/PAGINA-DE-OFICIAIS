# Análise de comandos do ServletControlador e campos de formulário dos JSPs

Levantamento feito por leitura direta do código-fonte (`web.xml`, `ServletControlador.java`,
classes `Operacao*` e os JSPs em `src/main/webapp/`). Nenhum dado foi inferido sem checagem
no código — divergências entre nome de arquivo e conteúdo real estão sinalizadas como achado.

## 1. Comandos (`cmd`) aceitos pelo `ServletControlador`

Mecanismo de dispatch (`ServletControlador.service()`, linhas 71-110): lê `cmd`; se `cmd == null`
→ `erro.jsp?msg=Atividade não encontrada`; se `cmd != "login"` sem sessão autenticada →
`erro.jsp?mensagem=Usuário não Logado na aplicação`; executa `operacao.executar(request)` e
redireciona para `operacao.getProxPagina()`; `catch (OperacaoException e)` → `erro.jsp?mensagem=<msg>`
(erro-padrão da maioria dos comandos).

| cmd | Classe | JSP sucesso | JSP erro | Observação |
|---|---|---|---|---|
| `login` | `OperacaoLogin` | `index.jsp` (ou `alterarSenha.jsp` se senha = "marinha") | `login.jsp` (credencial inválida) / `erro.jsp` (exceção) | Trata tudo internamente, nunca lança `OperacaoException`. |
| `alterarUsuario` | `OperacaoAlterarUsuario` | `content.jsp` | `erro.jsp` | |
| `alterarSenha` | `OperacaoAlterarSenha` | `index.jsp` | `erro.jsp` | |
| `excluirUsuario` | `OperacaoExcluirUsuario` | `topo.jsp` | `erro.jsp` | |
| `incluirUsuario` | `OperacaoIncluirUsuario` | `content.jsp` (ou `cadastroUsuario.jsp` se usuário já existe) | `erro.jsp` | |
| `detalharUsuario` | `OperacaoDetalharUsuario` | `detalharUsuario.jsp` | — | Sem try/catch: exceção de runtime não vira `OperacaoException`, propaga como erro 500. |
| `pesquisarUsuario` | `OperacaoPesquisarUsuario` | `cadastroUsuario.jsp` | `erro.jsp` | |
| `pesquisarDocumento` | `OperacaoPesquisarDocumento` | `exibiDocumento.jsp` | `erro.jsp` | |
| `detalharDocumento` | `OperacaoDetalharDocumento` | `detalhaDocumento2.jsp` | `erro.jsp` | |
| `incluirDocumento` | `OperacaoIncluirDocumento` | `content.jsp` | `erro.jsp` (parcial) | `proxPagina` fixado em `content.jsp` antes da lógica; `catch (Throwable e)` externo só faz `printStackTrace()` sem relançar. |
| `scanearDocumento` | `OperacaoScanearDocumento` | — | — | **Bug de tipo:** a classe estende `HttpServlet`, não `Operacao`. O cast `(Operacao)` em `getOperacao()` lança `ClassCastException` não tratada → erro 500. |
| `localizarDocumento` | `OperacaoLocalizarDocumento` | `incluirDocumento.jsp` | — | Sem try/catch; usa `JFileChooser` (Swing, não funcional em servidor headless). |
| `encerraSessao` | `OperacaoEncerraSessao` | `sair.jsp` | `erro.jsp` (inativo) | Único try/catch está comentado; hoje não lança nada. |
| `buscaProximoRecordId` | `OperacaoBuscarRecordId` | `incluirDocumento.jsp` | `incluirDocumento.jsp` (erro mascarado) | `setProxPagina` ocorre antes do try; catch só seta mensagem e dá `return` sem relançar. |
| `incluirAnexoPapem41` | `OperacaoIncluirAnexoPapem41` | `content.jsp` | `content.jsp` (erro mascarado) | Mesmo padrão. |
| `incluirDocumentoRespostaPapem42` | `OperacaoIncluirDocumentoRespostaPapem42` | `content.jsp` | `content.jsp` (erro mascarado) | Mesmo padrão. |
| `incluirDocumentoPapem42` | `OperacaoIncluirDocumentoPapem42` | `incluirDocumento.jsp` | — | Sem try/catch; NPE se `documento` não estiver na sessão. |

### Fora do dispatch por `cmd` (servlets próprios no `web.xml`)

| servlet-name | url-pattern | classe |
|---|---|---|
| `AbrirDoc` | `/controller/AbrirDoc` | `controller.OperacaoAbrirDocumento` (extends `HttpServlet`) |
| `ScanearDoc` | `/controller/scanearDocumento` | `controller.OperacaoScanearDocumento` (extends `HttpServlet`) |
| `ChamaScaner` | `/ChamaScaner` | `controller.ChamaScaner` |

### Classes `Operacao*` órfãs (existem mas não estão em `mapOperacoes`)

`OperacaoCadastroUsuario`, `OperacaoIncluirAnexo` (genérica, sem sufixo Papem41), `OperacaoTeste`.

---

## 2. Campos de formulário por JSP

HTML antigo (XHTML 1.0 / HTML4), sem atributo `placeholder` em nenhum lugar — a única "ajuda"
visível é o asterisco vermelho de campo obrigatório e, em telas de senha, um parágrafo fixo de
requisitos.

### `login.jsp` (raiz — welcome-file) e `views/login.jsp` (mesma tela, dentro do iframe)

Praticamente idênticas; diferem só em caminho relativo e no botão Voltar.

| name | id | tipo | valor padrão | label | ajuda |
|---|---|---|---|---|---|
| usuario | usuario | text | — | "Usuário *" | — |
| senha | — | password | — | "Senha *" | — |

Botões: submit "Login", reset "Limpar", botão "Voltar" (raiz: chama `encerraSessao()`→`sair.jsp`; views: `history.back(-1)`).

### `views/cadastroUsuario.jsp` — Incluir Usuário (tela real, carregada via `cadastro.jsp`)

| name | id | tipo | valor padrão | label | ajuda |
|---|---|---|---|---|---|
| rlcname | lcname | text (maxlength 20) | — | "Usuário *" | — |
| rjgname | rjgname | select (opções da lista `listaGrupo` da sessão) | opção vazia inicial | "Grupo *" | — |
| senha | — | password (maxlength 15) | — | "Senha *" | parágrafo fixo: "Senha deve ter no mínimo 7 e no máximo 15 caracteres... maiúscula, minúscula, caracter especial e número" + aviso LGPD (Lei 12.527/2011, Decreto 7.724/2012) |
| confSenha | — | password (maxlength 15) | — | "Confirmar Senha *" | idem |

Botões: `button` "Incluir Usuário" (JS valida e envia a `cmd=incluirUsuario`), `submit` "Pesquisar Usuário" (`cmd=pesquisarUsuario`), link Voltar. Abaixo, tabela de usuários com links Alterar/Deletar (não é campo de formulário).

### `views/detalharUsuario.jsp` — Alterar Usuário (admin)

| name | id | tipo | valor padrão | label | ajuda |
|---|---|---|---|---|---|
| recordNumber | recordNumber | text readonly | `usuarioDetalhe.usuario` | "Usuário" | — |
| grupo | grupo | select (opções `listaGrupo`) | grupo atual pré-selecionado | "Grupo *" | — |
| bloqueio | bloqueio | checkbox, value="B" | marcado se já bloqueado | "Bloqueio" | — |
| senha | senha | password (maxlength 15) | — | "Senha *" | mesmo parágrafo de requisitos + LGPD |
| confsenha | — | password (maxlength 15) | — | "Confirmar Senha *" | idem |

### `views/alterarSenha.jsp` — Alterar Senha (usuário comum; forçada no 1º login com senha "marinha")

| name | id | tipo | valor padrão | label | ajuda |
|---|---|---|---|---|---|
| recordNumber | recordNumber | text readonly | `usuario.usuario` | "Usuário" | — |
| grupo | grupo | text readonly | `usuario.grupo` | "Grupo" | — |
| senha | senha | password (maxlength 15) | — | "Senha *" | mesmo parágrafo de requisitos + LGPD |
| confsenha | — | password (maxlength 15) | — | "Confirmar Senha *" | idem |

### `views/pesquisarDocumento.jsp` — Pesquisar Documento

Todos `type="text"` salvo indicado; **nomes de campo usam espaço e acentuação literal** (ex.:
`name="NIP/Matr�cula"`), o que funciona em HTML mas quebra referência por JS
(`document.form.campo`) e é frágil para `getParameter`:

| name | id | maxlength/size | label |
|---|---|---|---|
| NIP/Matrícula | — | 9 / 13 | "NIP/Matrícula" |
| CPF | CPF | 11 / 25 | "CPF" |
| rrecordid | — | — / 21 | "Processo" |
| Consignado | — | — / 95 | "Consignado" |
| Beneficiário | — | — / 95 | "Beneficiário" |
| rtitle | — | — / 87 | "Título do Documento" |
| Tipo de Documento | — | — / 31 | "Tipo de Documento" |
| Número do Documento | — | — / 25 | "Número do Documento" |
| Protocolo | — | — / 40 | "Protocolo" |
| Data de Protocolo | — | — / 30 | "Data de Protocolo" |
| Data de Criação (Legado) | — | — / 25 | "Data de Criação (Legado)" |
| Data de Protocolo (Legado) | — | — / 21 | "Data de Protocolo (Legado)" |
| rrecordid *(2ª ocorrência — name duplicado)* | — | — / 30 | "Número do Registro" |
| Origem | — | — / 40 | "Origem" |
| Entidade Consignatária | — | — / 27 | "Entidade Consignatária" |
| OBSERVAÇÕES | — | — / 35 | "Observações" |
| rloc.lcname | — | — / 37 | "Responsável" |
| Ofício Judicial Anexo | — | select: `""`(Não)/`"SIM"`(Sim) | "Ofício Judicial Anexo" |
| Urgente | Urgente | checkbox, value="1" | "Urgente" |

Botão "Pesquisar" é um link estilizado (não `<input>`), aciona `validaCamposPesquisar()` via JS.
**Achado:** `rrecordid` é usado duas vezes como `name` (Processo e Número do Registro) — o
segundo sobrescreve o primeiro no submit.

### `views/incluirDocumento.jsp` — Incluir Documento

| name | id | tipo | valor padrão | label |
|---|---|---|---|---|
| recordType | recordType | select (`Registro.lTipoRegistro`), `onchange="validaPasta()"` | opção vazia / pré-selecionada se já houver | "Tipo Registro" |
| classificacao | classificacao | text readonly | `documento.classificacao` | "Classificação" |
| pasta | pasta | text readonly | `documento.pasta` | "Pasta" |
| recordNumber | recordNumber | text | `documento.recordNumber` | "Processo *" |
| dataDocumento | dataDocumento | text | `documento.dataDocumento` (também preenchido por JS `time()` ao carregar) | "Data do Documento *" |
| dataInclusao | dataInclusao | text readonly | idem, via `time()` | "Data de Inclusão *" |
| nipMatricula | nipMatricula | text (maxlength 9, minlength 8) | `documento.nipMatricula` | "NIP/Matrícula" |
| cpf | cpf | text | `documento.cpf` | "CPF" |
| consignado | — | text | `documento.consignado` | "Consignado" |
| beneficiario | — | text | `documento.beneficiario` | "Beneficiário" |
| title | title | text | — | "Título do Documento" |
| tipoDocumento | — | text | `documento.tipoDocumento` | "Tipo de Documento" |
| numeroDocumento | — | text | `documento.numeroDocumento` | "Número do Documento" |
| protocolo | — | text | `documento.protocolo` | "Protocolo" |
| dataProtocolo | dataProtocolo | text | `documento.dataProtocolo` | "Data de Protocolo" |
| recordNumber *(2ª ocorrência — name/id duplicados)* | recordNumber | text | — | "Número do Registro" |
| origem | — | text | `documento.origem` | "Origem" |
| entidadeConsignataria | — | text | `documento.entidadeConsignataria` | "Entidade Consignatária" |
| observacoes | — | text | `documento.observacoes` | "Observações" |
| responsavel | — | text readonly | usuário logado (scriptlet) | "Responsável" |
| oficioJudicialAnexo | — | select: `""`(Não)/`"SIM"`(Sim) | — | "Ofício Judicial Anexo" |
| urgente | urgente | checkbox | — | "Urgente" |
| nomeArquivoOriginal | — | file | — | "Documento *" |

Sem placeholders; asteriscos vermelhos marcam obrigatório; alertas JS no submit cobram
preenchimento. **Achado:** `id="recordNumber"` aparece duas vezes no DOM (`getElementById`
pega sempre a 1ª).

### `views/incluirDocumentoPapem42.jsp` — quase clone do anterior

Mesma estrutura de campos, com estas diferenças: `pasta` e `recordNumber` (principal) ambos
`readonly`; sem `minlength` em `nipMatricula`; e **bug**: `responsavel` usa
`value="<%=session.getAttribute("usuario")%>"` (o bean inteiro, não `.getUsuario()`) —
provavelmente renderiza o `toString()` do objeto em vez do nome do usuário.

### `views/incluirAnexoPapem41.jsp` — Incluir Anexo PAPEM-41

| name | tipo | size | label |
|---|---|---|---|
| nomeArquivoOriginal | file | 67 | "Anexar ao Documento *" |

Único campo. Botões: submit "Incluir", reset "Limpar", Voltar.

### `views/incluirDocumentoRespostaPapem42.jsp` — Incluir Documento Resposta PAPEM-42

| name | id | tipo | valor padrão | label |
|---|---|---|---|---|
| recordNumber | recordNumber | text readonly | `documento.recordNumber` | "Processo" |
| titulo | titulo | text (size 40) | — | "Título *" |
| nomeArquivoOriginal | — | file (size 79) | — | "Documento *" |

### Formulários sem campo de entrada real (têm `<form>`, mas só links/botões-imagem/hidden)

- **`views/topo.jsp`** — forms `scanearDocumento` e `encerraSessao` só disparam submit via clique em imagem, sem inputs visíveis.
- **`views/exibiDocumento.jsp`** — form só envolve uma tabela de resultados com links (`cmd=detalharDocumento`), sem campo.
- **`views/detalhaDocumento2.jsp`** — só `input type="hidden" name="nomeDocumento"` (e `nomeDocumento<i>` por anexo), usados para abrir arquivo; tela é só leitura.

### Achados: arquivos órfãos/mortos (sem link algum apontando para eles no fluxo ativo)

- **`views/incluirUsuario.jsp`**: apesar do nome, é uma tela de **login** (`form id="login"`,
  `action=cmd=login`) com campos `usuario`(text), `senha`(**text, não password**), `grupo`(text) —
  não tem relação com inclusão de usuário.
- **`views/pesquisaUsuario.jsp`**: apesar do nome, é uma versão antiga/duplicada de "Cadastro de
  Usuário" (`form name="cadastroUsuario"`, `action=cmd=incluirUsuario` fixo no HTML) com `usuario`,
  `senha`(**text, não password**), `confSenha`(text), `grupo`(**text livre, não select** — diferente
  da versão em uso em `cadastroUsuario.jsp`). O botão chama `incluirUsuario()`, função que não existe
  neste arquivo.
- **`views/criteria.jsp`**: form morto com `action="regdoc.jsp"` (página inexistente no projeto),
  dois `<input id="file-input">` com **id duplicado**. Só referenciado (comentado, inativo) em
  `incluir.jsp`.
