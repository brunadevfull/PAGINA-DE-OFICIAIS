# Mensagens de erro e sucesso — texto completo

Levantamento por busca (`grep`) em todo `src/main/java` e `src/main/webapp` por
`request.setAttribute("mensagem", ...)`, redirecionamento com `mensagem=`/`msg=` na
URL, e texto estático em JSP que funciona como mensagem ao usuário.
`System.out.println`/`log.debug` não entram — não chegam à tela.

## 1. Mensagens via `request.setAttribute("mensagem", ...)` (servidor → JSP via `<%=request.getAttribute("mensagem")%>`)

Organizado por arquivo Java de origem.

| Arquivo | Mensagem exata |
|---|---|
| `utilitaria/LoginFilter.java:49` | "Realize o login para ter acesso ao sistema." |
| `controller/OperacaoLogin.java:63` | "Usuário Bloqueado" |
| `controller/OperacaoLogin.java:67` | "Usuário e/ou senha inválida." |
| `controller/OperacaoLogin.java:76` | "Falha na execução da operação" |
| `controller/OperacaoIncluirUsuario.java:25` | "Usuário `<nome>` já cadastrado no sistema" |
| `controller/OperacaoIncluirUsuario.java:34-35` | "Usuário `<nome>` incluído no sistema com sucesso." |
| `controller/OperacaoIncluirUsuario.java:37` | "Falha na execução da operação" |
| `controller/OperacaoIncluirUsuario.java:44` | "Falha na execução da operação" (exceção) |
| `controller/OperacaoAlterarUsuario.java:41` | "Usuário `<nome>` alterado no sistema" |
| `controller/OperacaoAlterarUsuario.java:43` | "Falha na execução da operação" |
| `controller/OperacaoAlterarUsuario.java:50` | "Falha na execução da operação" (exceção) |
| `controller/OperacaoAlterarSenha.java:32` | "Senha alterada com sucesso!" |
| `controller/OperacaoAlterarSenha.java:34` | "Falha na execucao da operacao" |
| `controller/OperacaoAlterarSenha.java:41` | "Falha na execucao da operacao" (exceção) |
| `controller/OperacaoExcluirUsuario.java:21` | "Usuário excluído do sistema" |
| `controller/OperacaoExcluirUsuario.java:23` | "Falha na execução da operação" |
| `controller/OperacaoExcluirUsuario.java:30` | "Falha na execução da operação" (exceção) |
| `controller/OperacaoPesquisarUsuario.java:56` | "Falha na execução da operação" (exceção) |
| `controller/OperacaoPesquisarDocumento.java:85` | "Falha na execução da operação" (exceção) |
| `controller/OperacaoDetalharDocumento.java:35` | "Não foi possível realizar a operação" |
| `controller/OperacaoDetalharDocumento.java:41` | "Falha na execução da operação" (exceção) |
| `controller/OperacaoIncluirDocumento.java:79` | "Falha no Recebimento do Documento" |
| `controller/OperacaoIncluirDocumento.java:86` | "Desculpe este Servlet só lida com pedido de upload de arquivos" |
| `controller/OperacaoIncluirDocumento.java:98` (via var. `mensagem`) | "Documento `<recordNumber>` inserido no sistema!" |
| `controller/OperacaoIncluirDocumento.java:102` (via var. `mensagem`) | "Falha na execução da operação" |
| `controller/OperacaoIncluirDocumento.java:109` | "Falha na execução da operação" (exceção) |
| `controller/OperacaoBuscarRecordId.java:46` | "Falha no Recebimento do Documento" |
| `controller/OperacaoIncluirAnexoPapem41.java:56` | "Falha no Recebimento do Anexo" |
| `controller/OperacaoIncluirAnexoPapem41.java:73` (via var. `mensagem`) | "Anexo `<recordNumber>` inserido no sistema!" |
| `controller/OperacaoIncluirAnexoPapem41.java:76` (via var. `mensagem`) | "Falha na execução da operação" |
| `controller/OperacaoIncluirDocumentoRespostaPapem42.java:64` | "Falha no Recebimento do Anexo" |
| `controller/OperacaoIncluirDocumentoRespostaPapem42.java:78` (via var. `mensagem`) | "Anexo `<recordNumber>` inserido no sistema!" |
| `controller/OperacaoIncluirDocumentoRespostaPapem42.java:81` (via var. `mensagem`) | "Falha na execução da operação" |
| `controller/OperacaoCadastroUsuario.java:27` *(classe órfã, não registrada em `mapOperacoes`)* | "Falha na execução da operação" (exceção) |
| `controller/OperacaoIncluirAnexo.java:66` *(classe órfã)* | "Falha no Recebimento do Documento" |
| `controller/OperacaoIncluirAnexo.java:72` *(classe órfã)* | "Desculpe este Servlet só lida com pedido de upload de arquivos" |
| `controller/OperacaoIncluirAnexo.java:84` *(classe órfã, via var. `mensagem`)* | "Anexo `<recordNumber>` inserido no sistema!" |
| `controller/OperacaoIncluirAnexo.java:87` *(classe órfã, via var. `mensagem`)* | "Falha na execução da operação" |
| `controller/OperacaoIncluirAnexo.java:93` *(classe órfã)* | "Falha na execução da operação" (exceção) |

Todas as classes acima **sem** mensagem própria de exceção (`OperacaoDetalharUsuario`,
`OperacaoLocalizarDocumento`, `OperacaoEncerraSessao`, `OperacaoIncluirDocumentoPapem42`,
`OperacaoTeste`) não chamam `setAttribute("mensagem", ...)` em lugar nenhum — se
falharem, a exceção não tratada não chega como texto amigável (vira erro 500, já
documentado nos blocos 1 e 4).

## 2. Mensagens passadas direto na URL de redirecionamento (sem `setAttribute`)

`controller/ServletControlador.java`, dentro de `service()`:

| Linha | Mensagem exata | Quando |
|---|---|---|
| `:81` | "Atividade não encontrada" (parâmetro `msg=`) | `cmd` ausente na requisição |
| `:94` | "Usuário não Logado na aplicação" (parâmetro `mensagem=`) | comando diferente de `login` sem sessão ativa |
| `:106` | `e.getMessage()` da `OperacaoException` capturada (parâmetro `mensagem=`) | qualquer `Operacao*` que lance `OperacaoException` — o texto varia conforme a exceção original (ex.: mensagem de `SQLException`, ou uma das mensagens da seção 1 quando a classe também chamou `setAttribute` antes de lançar) |

## 3. Texto estático em JSP (hardcoded no HTML, não vem de `request.setAttribute`)

| Arquivo | Mensagem exata | Contexto |
|---|---|---|
| `views/exibiDocumento.jsp:63` | "Não há documentos que atendam a estes critérios de pesquisa" | fallback quando a lista de resultados de `OperacaoPesquisarDocumento` vem vazia |
| `views/cadastroUsuario.jsp` | "Não há usuários" | fallback quando `listaUsuario` da sessão vem vazia/nula |
| `views/pesquisaUsuario.jsp` *(tela órfã, sem link ativo)* | "Não há usuários" | mesmo fallback, cópia da tela acima |
| `views/erro.jsp` | título fixo "Erro!" (comentado) / "SisImagem - Login" (título ativo, herdado por copiar/colar — não foi atualizado); corpo: `<h1><%=request.getAttribute("mensagem")%></h1>` | tela genérica de erro — o texto real vem sempre da seção 1 ou 2, o JSP só imprime o que recebeu |

## 4. Mensagens de validação client-side (`alert()`) — já catalogadas no Bloco 3

Para não duplicar o levantamento completo (regra + mensagem exata por campo, e qual
delas de fato dispara), ver `docs/01-analise-sistema-atual/validacoes-client-side.md`.
Resumo dos textos literais que aparecem em algum `alert()` do projeto, por arquivo:

- **`login.jsp` (raiz e `views/`)**, **`views/incluirUsuario.jsp`** *(código morto — nunca chamado, ver Bloco 3)*: "Campo 'Usuário' é obrigatório", "Campo 'Senha' é obrigatório", "Campo 'Grupo' é obrigatório" *(só em `incluirUsuario.jsp`)*, "Senha fraca".
- **`views/cadastroUsuario.jsp`** *(ativa)*: as mesmas acima + "Campo 'Confirmar Senha' é obrigatório" + "Campo 'Senha' e 'Confirmar Senha' estão divergentes".
- **`views/detalharUsuario.jsp`**, **`views/alterarSenha.jsp`** *(ativas)*: "Campo 'Senha' e 'Confirmar Senha' estão divergentes", "Senha fraca", "Campo 'Grupo' é obrigatório" *(só em `detalharUsuario.jsp`)*.
- **`views/incluirDocumento.jsp`**, **`views/incluirDocumentoPapem42.jsp`**, **`views/incluirAnexoPapem41.jsp`**, **`views/incluirDocumentoRespostaPapem42.jsp`** *(as funções que contêm estes textos são código morto — nunca chamadas por `onclick`, ver Bloco 3; a validação real dessas telas é via jQuery Validate, listada abaixo)*: "Campo 'Data do documento' é obrigatório", "Campo 'Data de inclusão' é obrigatório", "Campo 'Pasta' é obrigatório", "Campo 'Documento' é obrigatório", "Campo 'Processo' é obrigatório", "Preencha um dos campos opcionais", "Campo 'Anexo' é obrigatório", "Campo 'Título' é obrigatório".
- **`js/validacao.js`** (jQuery Validate, ativo em `incluirDocumento.jsp`, `incluirDocumentoPapem42.jsp`, `incluirAnexoPapem41.jsp`, `incluirDocumentoRespostaPapem42.jsp`): "Digite a data do documento", "Digite a data de inclusão", "Selecione um Documento", "Digite um número de Processo", "Digite o Título do documento" — mais as mensagens padrão da lib em PT-BR ("Este campo é obrigatório.", "Digite apenas números.", "Máximo {n} caracteres.", "Digite pelo menos {n} caracteres.") aplicadas a `nipMatricula`.
