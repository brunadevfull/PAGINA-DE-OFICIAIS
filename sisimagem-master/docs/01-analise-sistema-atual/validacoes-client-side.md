# Validações client-side (JavaScript)

Levantamento feito por leitura de `src/main/webapp/js/validar.js`, `validacao.js` e dos
`<script>` inline em cada JSP de formulário, cruzado com `grep` de `onclick=`/`onsubmit=`
para confirmar se cada função de validação é de fato **acionada** por algum evento do HTML.
Isso importa: várias funções de validação existem no código mas nunca são chamadas —
estão sinalizadas como **código morto** e não bloqueiam nada no navegador.

## 0. Visão geral dos arquivos `.js`

| arquivo | papel | carregado por |
|---|---|---|
| `js/validar.js` | Funções de validação de NIP (dígito verificador), data (mês/ano), período, CPF, e-mail — via classe `Validator` (depende de Prototype.js, que **não está presente no projeto**) | **Nenhum JSP carrega este arquivo de forma ativa.** As 3 referências existentes estão comentadas (`incluirDocumento.jsp`, `incluirDocumentoPapem42.jsp`, `incluirDocumentoRespostaPapem42.jsp`). **Arquivo inteiro é código morto** — nunca chega a ser interpretado pelo navegador. |
| `js/validacao.js` | Máscaras de input (jQuery Maskedinput) + regras do plugin jQuery Validate | `pesquisarDocumento.jsp`, `incluirDocumento.jsp`, `incluirDocumentoPapem42.jsp`, `incluirAnexoPapem41.jsp`, `incluirDocumentoRespostaPapem42.jsp` |
| `js/jquery.validate.js` | Plugin jQuery Validate (biblioteca de terceiros); mensagens padrão traduzidas para PT-BR embutidas no próprio arquivo | mesmas 5 telas acima |
| `js/jquery.maskedinput-1.3.js` | Plugin de máscara de input (biblioteca de terceiros) | mesmas 5 telas acima |
| `js/tablecloth.js` | Estilização de tabela — não faz validação | `pesquisarDocumento.jsp`, `exibiDocumento.jsp`, `pesquisaUsuario.jsp`, `detalhaDocumento2.jsp` |

Nenhuma tela carrega `validar.js` de fato, então as funções `validaNIP()`, `validaData()`,
`validaPeriodo()`, `caracterEspecial()`, `verificaDatas()` e a classe `Validator` (CPF, e-mail,
data) **nunca executam em produção**.

---

## 1. Máscaras de formato (`validacao.js`, via jQuery Maskedinput)

Aplicadas por `id` do campo — restringem o que pode ser **digitado**, sem exibir mensagem de
erro (não é validação de submit, é máscara de digitação):

| campo (id) | máscara | efeito | telas onde o id existe e a máscara funciona |
|---|---|---|---|
| `cpf` | `999.999.999-99` | só dígitos, formatados como CPF | `incluirDocumento.jsp`, `incluirDocumentoPapem42.jsp` |
| `dataDocumento` | `49/19/2039 69:59:59` | formato `dd/mm/aaaa hh:mm:ss` | `incluirDocumento.jsp`, `incluirDocumentoPapem42.jsp` |
| `dataInclusao` | `49/19/2039 69:59:59` | idem | `incluirDocumento.jsp`, `incluirDocumentoPapem42.jsp` |
| `dataProtocolo` | `49/19/2039` | formato `dd/mm/aaaa` | `incluirDocumento.jsp`, `incluirDocumentoPapem42.jsp` |

**Achado:** `pesquisarDocumento.jsp` também carrega `validacao.js`, mas o campo de CPF nessa
tela tem `id="CPF"` (maiúsculo) — o seletor `$("#cpf")` é *case-sensitive* e não bate, então
**a máscara de CPF não é aplicada nessa tela**. Os demais campos de data em
`pesquisarDocumento.jsp` não têm `id`, então nenhuma máscara se aplica a eles também.

---

## 2. Regras de validação de submit — jQuery Validate (`validacao.js`)

Mensagens padrão ativas na biblioteca (PT-BR, usadas quando não há mensagem customizada —
a versão em inglês está comentada dentro do próprio `jquery.validate.js`):
`required` → **"Este campo é obrigatório."**; `digits` → **"Digite apenas números."**;
`maxlength` → **"Máximo {n} caracteres."**; `minlength` → **"Digite pelo menos {n} caracteres."**

### `#incluirDocumento` (form `incluirDocumento.jsp` **e** `incluirDocumentoPapem42.jsp`)

**Achado:** o `<form>` de `incluirDocumentoPapem42.jsp` também usa `id="incluirDocumento"`
(reaproveitado do outro arquivo), então esta mesma regra do jQuery Validate se aplica às
duas telas.

| campo | regra | mensagem exata |
|---|---|---|
| `dataDocumento` | obrigatório | "Digite a data do documento" |
| `dataInclusao` | obrigatório | "Digite a data de inclusão" |
| `nipMatricula` | `digits` (só números), `maxlength: 9`, `minlength: 8` — **não obrigatório** | sem mensagem customizada → usa a padrão da lib: "Digite apenas números." / "Máximo 9 caracteres." / "Digite pelo menos 8 caracteres." (só dispara se o campo for preenchido; vazio passa) |
| `nomeArquivoOriginal` | obrigatório | "Selecione um Documento" |
| `recordNumber` | obrigatório | "Digite um número de Processo" |

### `#incluirDocumentoRespostaPapem42`

| campo | regra | mensagem exata |
|---|---|---|
| `titulo` | obrigatório | "Digite o Título do documento" |
| `nomeArquivoOriginal` | obrigatório | "Selecione um Documento" |

### `#incluirAnexoPapem41`

| campo | regra | mensagem exata |
|---|---|---|
| `nomeArquivoOriginal` | obrigatório | "Selecione um Documento" |

### `pesquisarDocumento.jsp`

Carrega `validacao.js`, mas **não existe nenhuma regra `.validate()` para este formulário**
no arquivo (só há regras para `#incluirDocumento`, `#incluirDocumentoRespostaPapem42` e
`#incluirAnexoPapem41`). O botão "Pesquisar" chama `validaCamposPesquisar()`, que só faz
`document.pesquisarDocumento.submit()` — **não valida nada**. Resultado: nenhum campo desta
tela tem validação client-side.

---

## 3. Validações inline nos JSPs (`<script>` dentro do próprio arquivo)

### 3.1 Ativas (a função é de fato chamada por `onclick`/`onsubmit`)

**`cadastroUsuario.jsp`** — botão "Incluir Usuário" chama `incluirUsuario()`:

| campo | regra | mensagem exata |
|---|---|---|
| `rlcname` (Usuário) | obrigatório | "Campo 'Usuário' é obrigatório" |
| `rlcname` (Usuário) | checagem de tamanho — **bug**: testa `document.cadastroUsuario.rlcname.length` (tamanho da coleção de elementos chamados `rlcname`, sempre 1 elemento → `undefined`), não `.value.length`; a condição `undefined < 5` é sempre `false`, então **nunca dispara** | "Campo 'Usuário' é obrigatório" (código morto) |
| `senha` | obrigatório | "Campo 'Senha' é obrigatório" |
| `confSenha` | obrigatório | "Campo 'Confirmar Senha' é obrigatório" |
| `senha` = `confSenha` | devem ser iguais | "Campo 'Senha' e 'Confirmar Senha' estão divergentes" |
| `senha` | força mínima (ver 3.3), exceto se o valor for literalmente `"marinha"` | "Senha fraca" |
| `rjgname` (Grupo) | obrigatório | "Campo 'Grupo' é obrigatório" |

Botão "Pesquisar Usuário" chama `pesquisarUsuario()` — sem validação, só muda a `action` para
`cmd=pesquisarUsuario` e submete.

**`detalharUsuario.jsp`** (admin altera outro usuário) — botão "Alterar" chama `alterarUsuario()`:

| campo | regra | mensagem exata |
|---|---|---|
| `senha` = `confsenha` | devem ser iguais | "Campo 'Senha' e 'Confirmar Senha' estão divergentes" |
| `senha` | se preenchida e diferente de `"marinha"`, força mínima (ver 3.3) — campo vazio é permitido (não altera a senha) | "Senha fraca" |
| `grupo` | obrigatório | "Campo 'Grupo' é obrigatório" |

**`alterarSenha.jsp`** (usuário comum troca a própria senha) — botão "Alterar" chama
`alterarUsuario()` (mesmo nome de função, corpo diferente — não há exceção para `"marinha"`
aqui, e não valida `grupo` pois o campo é somente leitura):

| campo | regra | mensagem exata |
|---|---|---|
| `senha` = `confsenha` | devem ser iguais | "Campo 'Senha' e 'Confirmar Senha' estão divergentes" |
| `senha` | se preenchida, força mínima (ver 3.3) — campo vazio é permitido | "Senha fraca" |

### 3.2 Código morto (a função existe mas nenhum elemento a chama)

| tela | função definida | por que não executa |
|---|---|---|
| `login.jsp` (raiz) e `views/login.jsp` | `validacamposPesquisar()` — checa `usuario` e `senha` obrigatórios (+ força de senha) | botão submit não tem `onclick`/`onsubmit`; formulário vai direto ao servidor sem checagem alguma |
| `views/incluirUsuario.jsp` (tela órfã) | mesma função, com checagem extra de `grupo` obrigatório | idem — sem `onclick` no submit |
| `views/incluirDocumento.jsp` / `incluirDocumentoPapem42.jsp` | `validacamposPesquisar()` — checa `dataDocumento`, `dataInclusao`, `pasta`, `nomeArquivoOriginal`, `recordNumber` (só em `incluirDocumento.jsp`) obrigatórios, e "preencha ao menos um campo opcional" | botão "Incluir" (`id="btnIncluir"`) não tem `onclick`; quem realmente valida esse submit é o jQuery Validate (seção 2) |
| `views/incluirAnexoPapem41.jsp` | `validacamposPesquisar()` — checa `nomeArquivoOriginal` obrigatório | botão "Incluir" sem `onclick`; validação real é a do jQuery Validate (seção 2) |
| `views/incluirDocumentoRespostaPapem42.jsp` | `validacamposPesquisar()` — checa `nomeArquivoOriginal` e `titulo` obrigatórios | idem — validação real é a do jQuery Validate (seção 2) |
| `views/pesquisaUsuario.jsp` (tela órfã) | botão tem `onclick="incluirUsuario()"`, mas essa função **não existe neste arquivo** (só em `cadastroUsuario.jsp`) | dispara erro JS "incluirUsuario is not defined" no console; o clique não faz nada |

**Mensagens que nunca aparecem ao usuário** (por estarem em código morto acima), para
referência caso alguém reative essas funções:

- "Campo 'Usuário' é obrigatório" / "Campo 'Senha' é obrigatório" / "Campo 'Grupo' é obrigatório" (login)
- "Campo 'Data do documento' é obrigatório", "Campo 'Data de inclusão' é obrigatório", "Campo 'Pasta' é obrigatório", "Campo 'Documento' é obrigatório", "Campo 'Processo' é obrigatório", "Preencha um dos campos opcionais" (incluir documento)
- "Campo 'Anexo' é obrigatório" (incluir anexo Papem41)
- "Campo 'Documento' é obrigatório", "Campo 'Título' é obrigatório" (incluir documento resposta Papem42)

### 3.3 Regra de força de senha — `verificaForcaSenha()`

Usada (com pequenas variações do texto do gatilho) em `login.jsp`, `cadastroUsuario.jsp`,
`detalharUsuario.jsp` e `alterarSenha.jsp`. Soma 1 ponto para cada critério atendido e exige
pontuação ≥ 5 (ou seja, **todos** os critérios simultaneamente):

| critério | regex/condição |
|---|---|
| tamanho | `length >= 7` (login.jsp usa `> 7`, uma unidade a mais — inconsistência entre telas) |
| tem minúscula | `/[a-z]+/` |
| tem maiúscula | `/[A-Z]+/` |
| tem número | `/[0-9]+/` |
| tem caractere não alfanumérico | `/\W+/` |

Mensagem em todas as telas: **"Senha fraca"**. Não há mensagem detalhando qual critério
faltou — só o `alert` genérico. O texto de ajuda fixo exibido perto do campo (não é erro, é
texto estático sempre visível) é: *"Senha deve ter no mínimo 7 e no máximo 15 caracteres e
deve ser composta obrigatoriamente de uma letra maiúscula, uma letra minúscula, um caracter
especial (!@#$%&*) e um número."*
