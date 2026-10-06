# Perfis e permissões

Levantamento feito por leitura de `src/main/java/utilitaria/LoginFilter.java`,
`bean/Usuario.java`, `bean/Grupo.java` e `model/DAOTrim.java`, cruzado com
`grep` de toda checagem de `grupo`/permissão no restante do código (Java e JSP).

## 1. Não existe um "nível de permissão" modelado no código

`Grupo` (`bean/Grupo.java`) é só um `nome` livre (`String`) — não há enum, constante
nem tabela de níveis. Os grupos existentes vêm inteiramente do banco: **qualquer
string pode ser cadastrada como grupo**, via `DAOTrim.listaGrupo()`:

```java
// DAOTrim.java:1636-1665
public List<Grupo> listaGrupo() {
    sql = "SELECT trim(Jgname) grupo from Tsjurgroup WHERE TRIM(JGNAME) IS NOT NULL";
    ...
}
```

`Usuario` (`bean/Usuario.java`) carrega o grupo como atributo simples (`grupo`), associado
via `Tslocation.LCJURGROUP → Tsjurgroup.URI` no banco (`DAOTrim.validaUsuarioSenha`,
linhas 1496-1531). Não há coluna de "nível" — o grupo em si já é o único dado de perfil.

## 2. Mas o código trata 3 valores de grupo com lógica hardcoded

Apesar de o banco aceitar qualquer nome de grupo, **só 3 valores literais são checados em
algum lugar do código**: `"ADM"`, `"PAPEM40"`, `"SASM"`. Qualquer outro grupo cadastrado
cai no comportamento residual ("nem isso, nem aquilo") descrito na tabela abaixo — na
prática um "usuário comum" sem nenhum menu de gestão.

| Grupo | O que pode | O que não pode | Onde é checado (arquivo:linha / método) |
|---|---|---|---|
| **ADM** | Ver o link "Cadastrar Usuário" no menu (leva a `cadastro.jsp` → `cadastroUsuario.jsp`, que lista/inclui/altera/exclui usuários). Ver os links "Incluir Documento" e "Scanear" (compartilhado com PAPEM40). Ver os botões de incluir anexo/documento-resposta PAPEM41/42 na tela de detalhe (por não ser SASM). | Não tem restrição própria — é o único grupo com acesso total pela UI. | `views/topo.jsp:35` (`if grupo.equals("ADM")` → mostra link cadastro) e `views/topo.jsp:50-51` (`if grupo.equals("ADM") \|\| grupo.equals("PAPEM40")` → mostra incluir/scanear) |
| **PAPEM40** | Mesmo acesso de ADM a "Incluir Documento"/"Scanear". Ver botões de incluir anexo/resposta na tela de detalhe (por não ser SASM). | Não vê o link "Cadastrar Usuário" (cai no `else` do topo.jsp, que mostra "Alterar Senha" no lugar). | `views/topo.jsp:50-51` (incluído na mesma condição do ADM) |
| **SASM** | Pesquisar documentos — mas a pesquisa é **restrita automaticamente** aos documentos da pasta PAPEM41, não importa o que o usuário digite no formulário. | Não vê "Incluir Documento"/"Scanear" (cai fora da condição `ADM \|\| PAPEM40`). Não vê os botões "Incluir Anexo PAPEM41" / "Incluir Documento de Resposta PAPEM-42" / "Incluir Documento PAPEM42" na tela de detalhamento. | Filtro de pesquisa: `controller/OperacaoPesquisarDocumento.java:72-73` (`if grupo.equals("SASM") → mCamposPesquisa.put("perfil","PAPEM41")`). Ocultar botões: `views/detalhaDocumento2.jsp:165` (`if (!grupo.equals("SASM"))` → só então mostra os botões) |
| **Qualquer outro grupo** (cadastrado livremente, nem ADM/PAPEM40/SASM) | Só pesquisar documentos sem restrição de pasta (não cai na regra do SASM) e alterar a própria senha. | Não vê "Cadastrar Usuário" nem "Incluir Documento"/"Scanear". Vê os botões de incluir anexo/resposta na tela de detalhe (só SASM é excluído dessa checagem — qualquer outro grupo passa). | Mesma lógica acima, por exclusão das duas condições |

Nenhum outro arquivo Java ou JSP checa `grupo`/`getGrupo()`/`getAttribute("grupo")` além
dos 3 pontos listados acima (`views/topo.jsp` ×2, `OperacaoPesquisarDocumento.java`,
`views/detalhaDocumento2.jsp`) — confirmado por busca no projeto inteiro.

## 3. `LoginFilter.java` — não checa perfil, só autenticação

`utilitaria/LoginFilter.java` (`doFilter`, linhas 35-61) verifica **apenas se existe sessão
com atributo `usuario`** — não olha `grupo` nem nenhum outro dado de permissão:

```java
if ((((HttpServletRequest) request).getSession(false).getAttribute("usuario") == null)) {
    // ... redireciona para erro.jsp
}
```

Está mapeado em `web.xml` só para o `url-pattern` `/views/*` — ou seja, protege acesso
direto a arquivos `.jsp`, mas **não** ao endpoint `/controller/controlador` (onde ficam
todos os `cmd=...`), que tem sua própria checagem equivalente (só "está logado?", também
sem checar grupo) em `ServletControlador.service()` (ver
`docs/01-analise-sistema-atual/formularios-jsp.md`, seção "Mecanismo de dispatch").

## 4. Achado de segurança: controle de acesso por perfil é só de interface

**Nenhuma classe `Operacao*.java` valida o `grupo` do usuário antes de executar uma ação**,
com a única exceção do filtro de pesquisa do SASM (item 2). Isso inclui as operações mais
sensíveis:

- `OperacaoIncluirUsuario`, `OperacaoAlterarUsuario`, `OperacaoExcluirUsuario` — qualquer
  usuário autenticado, de qualquer grupo, pode chamar `cmd=incluirUsuario`,
  `cmd=alterarUsuario` ou `cmd=excluirUsuario` diretamente (via URL/POST manual), mesmo
  não sendo ADM. O menu só *esconde* o link — não impede a chamada.
- `OperacaoIncluirDocumento`, `OperacaoIncluirDocumentoPapem42`,
  `OperacaoIncluirAnexoPapem41`, `OperacaoIncluirDocumentoRespostaPapem42` — mesma coisa:
  um usuário SASM (que na UI só deveria pesquisar) pode incluir documentos e anexos
  chamando o `cmd` certo diretamente, já que o servidor não verifica `grupo` nessas
  classes.

Ou seja, o controle de acesso por perfil (ADM / PAPEM40 / SASM) é **inteiramente
cosmético em quase todo o sistema** — implementado mostrando/escondendo links na tela
(`topo.jsp`, `detalhaDocumento2.jsp`) — com uma única exceção real no backend (filtro de
pesquisa do SASM). Isso é uma falha de controle de acesso (categoria OWASP "Broken Access
Control"): a autorização deveria ser reforçada nas classes `Operacao*` do lado servidor,
não apenas na visibilidade dos botões.

## 5. Bloqueio de conta (não é nível de permissão, mas é estado de acesso)

`Usuario.bloqueio` mapeia para a coluna `LCVALIDTO` de `Tslocation` (reaproveitada do
sistema legado TRIM — mesma coluna seria usada para "validade" em outro contexto).
`'B'` = bloqueado. Pontos relevantes:

- `DAOTrim.validaUsuarioSenha` (linha 1500-1502) já filtra `lcvalidto <> 'B' or lcvalidto
  is null` na query — usuário bloqueado nunca é retornado, então o login falha como se
  fosse senha inválida (não há mensagem distinta de "conta bloqueada" no momento do
  login).
- Bloqueio automático: `OperacaoLogin.java` incrementa um contador `senhaInvalida` na
  sessão a cada tentativa malsucedida; na 5ª, chama `DAOTrim.bloquearUsuario` (seta
  `LCVALIDTO='B'`) e mostra "Usuário Bloqueado" (ver `validacoes-server-side.md`).
- Desbloqueio manual: um ADM desmarca o checkbox "Bloqueio" em `detalharUsuario.jsp`, que
  volta `LCVALIDTO` ao valor enviado pelo formulário (vazio, se desmarcado) via
  `OperacaoAlterarUsuario` → `DAOTrim.alterarUsuario`.

**Achado extra:** `DAOTrim.validaUsuarioSenha` monta o SQL sem comparar a senha no banco —
o trecho que compararia `lcidnumber` (senha) está comentado
(`model/DAOTrim.java:1503-1504`). A query retorna o usuário só pelo nome (e filtro de
bloqueio); a comparação de senha (hash Whirlpool) acontece depois, em Java, em
`OperacaoLogin.executar()`. Funcionalmente não quebra a autenticação (a comparação ainda
ocorre), mas é um padrão frágil: o hash da senha trafega para fora da camada de dados
antes de ser comparado.
