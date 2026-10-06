# O botão de scanner

Levantamento feito por leitura de `controller/ChamaScaner.java` e de todas as classes e
JSPs que fazem parte do mesmo assunto (achadas por `grep` de "scanear"/"Scaner"/"Paint"
no projeto inteiro): `controller/OperacaoScanearDocumento.java`,
`controller/CopyOfOperacaoScanearDocumento.java`, `controller/Chooser.java`,
`views/topo.jsp`, `views/incluirDocumento.jsp`, `views/incluirDocumentoPapem42.jsp` e
`js/validar.js`.

**Resumo antes do detalhe:** não existe *um* fluxo de scanner — existem **quatro
implementações diferentes**, de épocas e abordagens distintas, nenhuma funcional hoje.
Isso é o padrão mais forte de "código arqueológico" encontrado no projeto até agora:
tentativas sucessivas de resolver o mesmo problema (digitalizar um documento e anexá-lo),
cada uma abandonada pela metade antes de a anterior ser removida.

---

## 1. `controller/ChamaScaner.java` — endpoint `/ChamaScaner`

Mapeado em `web.xml` (`servlet-name="ChamaScaner"`, `url-pattern="/ChamaScaner"`).

```java
protected void doPost(HttpServletRequest request, HttpServletResponse response) {
    response.setHeader("Content-Disposition",
        "attachment;C:\\Program Files (x86)\\Common Files\\microsoft shared\\MODI\\11.0\\MSPSCAN.EXE");
}
```

- `doGet` está vazio (stub gerado pela IDE, nunca implementado — o comentário
  `// TODO Auto-generated method stub` continua lá).
- `doPost` **não envia nenhum corpo de resposta** — só seta um cabeçalho HTTP
  `Content-Disposition` cujo valor é o caminho local, no Windows, do executável
  `MSPSCAN.EXE`. Isso não tem efeito nenhum no navegador: `Content-Disposition` serve
  para dizer como tratar o *corpo* de uma resposta (anexo + nome de arquivo), não para
  abrir um programa — e mesmo que fosse interpretado, o caminho aponta para uma máquina
  Windows específica (a que originalmente rodou/testou o código), nunca para a do
  usuário atual.
- **Dependência externa:** `MSPSCAN.EXE`, parte do **Microsoft Office Document Imaging
  (MODI)** — componente do Office 2003/2007, mantido até o Office 2010 e **removido
  totalmente a partir do Office 2013**. É a pista mais forte de época no projeto: este
  trecho só fazia sentido rodando em uma máquina com Office 2003–2010 instalado.
- **É chamado por alguma tela?** Não. Busquei `"ChamaScaner"` (o nome do endpoint) em
  todo o projeto — a única ocorrência fora da própria classe e do `web.xml` é o registro
  do servlet. **Nenhum JSP, link, formulário ou script referencia `/ChamaScaner`.**
  É um endpoint morto: existe e está registrado, mas nada no sistema o chama.

## 2. `controller/OperacaoScanearDocumento.java` — endpoint `/controller/scanearDocumento`

Este é o único botão do sistema que, hoje, ainda dispara uma requisição de rede real
(clicável em `views/topo.jsp`, ver seção 4). Estende `HttpServlet` diretamente (não
`Operacao` — já documentado como bug no Bloco 1, pois também está registrado em
`mapOperacoes` para `cmd=scanearDocumento`, o que quebra com `ClassCastException` se
chamado por esse caminho).

```java
protected void doPost(...) {
    response.setContentType("/image/tiff");
    response.setHeader("Content-Disposition", "attachment; filename=0000000001.tif");
    /* todo o restante — abrir o arquivo "001.tif" do disco e copiar os bytes
       para a resposta — está comentado */
}
```

Ou seja: quando chamado pela URL direta (`/controller/scanearDocumento`, forma como
`topo.jsp` de fato o aciona), ele **responde com os cabeçalhos de um arquivo TIFF
anexo, mas sem nenhum byte de conteúdo** — o código que leria o arquivo do disco e o
enviaria está todo dentro de um bloco `/* ... */` nunca reativado. Na prática, clicar
nesse botão baixa um arquivo vazio chamado `0000000001.tif`.

## 3. `controller/CopyOfOperacaoScanearDocumento.java` — cópia esquecida, código morto

Nome do arquivo já denuncia a origem: uma cópia ("Copy of...", padrão do Eclipse ao
duplicar uma classe) que ficou no repositório. **Não está registrada em
`mapOperacoes`** (não aparece em `ServletControlador.init()`) — não existe nenhum `cmd`
que a acione; é inacessível por qualquer caminho do sistema.

O que ela faz, caso fosse chamada: cria um arquivo `.tif` vazio em `c:\trim\temp\`
(caminho hardcoded do **disco do servidor**, não do cliente) e chama
`new Chooser().abrirPaint(caminho)`:

```java
// Chooser.java:11-20
public void abrirPaint(String nomeArquivo) {
    String comando = "mspaint.exe " + nomeArquivo;
    Process processo = Runtime.getRuntime().exec(comando);
}
```

**Dependência externa:** `mspaint.exe` (Paint do Windows), executado via
`Runtime.exec()` **no processo do servidor de aplicação** — só funcionaria com o
servidor rodando em Windows, com uma sessão de desktop interativa disponível (o Paint
precisa de GUI; não roda como serviço headless nem em Linux). Mesmo se funcionasse,
abrir o Paint no servidor não ajuda um usuário remoto a digitalizar nada — é
comportamento de uma aplicação desktop local, não de uma aplicação web.

`Chooser.java` também tem um `abrirDigitalizador()` (não referenciado em lugar nenhum),
que executa `esfiledl.exe` da mesma forma — provável ligação a algum SDK/driver de
scanner específico do equipamento usado originalmente; não há mais nenhuma pista no
código sobre qual dispositivo era esse.

## 4. Quem realmente tenta chamar "scanear" hoje, e o que acontece

| Onde | O que faz | Funciona? |
|---|---|---|
| `views/topo.jsp:61-63` — `<form name="scanearDocumento" action="../controller/scanearDocumento">` + botão-imagem `onclick="chamaScanear()"` | `topo.jsp` define `chamaScanear()` inline (`function chamaScanear(){ document.scanearDocumento.submit(); }`) — **essa função existe e é chamada** | Dispara o POST, mas cai no endpoint da seção 2 — baixa um `.tif` vazio, sem imagem real |
| `views/incluirDocumento.jsp:308` e `incluirDocumentoPapem42.jsp:299` — botão-imagem `onclick="chamaScanear();"` | **Nenhuma função `chamaScanear` está definida no escopo dessas páginas.** A única definição ativa é a de `topo.jsp` (acima); a outra definição existente, em `js/validar.js`, nunca é carregada (confirmado no Bloco 3 — as 3 referências a `validar.js` no projeto estão comentadas, incluindo a destas duas telas) | **Não.** Clicar nesse botão gera um erro JavaScript no console ("chamaScanear is not defined") e não faz nada — nem por `validar.js` estar morto, nem porque, mesmo que estivesse vivo, `topo.jsp` e `incluirDocumento.jsp` rodam em **iframes irmãos diferentes** dentro de `views/index.jsp` (`iframe name="Topo"` e `iframe name="Principal"`), então uma função JS definida em um não é visível no escopo do outro sem qualificação explícita (`parent.Topo.chamaScanear()`), que também não é usada em lugar nenhum |
| `js/validar.js:8-12` — `function chamaScanear(){ document.incluirDocumento.action="../controller/scanearArquivo.jsp"; document.incluirDocumento.submit(); }` | Aponta para `../controller/scanearArquivo.jsp`, arquivo que **não existe em nenhum lugar do projeto** (não há `scanearArquivo.jsp` em `src/main/webapp/`) | Mesmo se `validar.js` fosse carregado, o submit cairia num 404 |

## 5. Conclusão

Não há fluxo de scanner funcional no sistema hoje. Há três gerações de tentativas
visíveis no código, sem indicação explícita de data (nenhum comentário com data ou
changelog), mas com uma pista temporal forte:

1. **Mais antiga, provavelmente a original:** `Chooser.abrirPaint`/`abrirDigitalizador`
   via `Runtime.exec()` — típica de aplicação desktop Java/Swing rodando localmente
   (`CopyOfOperacaoScanearDocumento`, nunca conectada a nenhum `cmd`).
2. **Intermediária:** `ChamaScaner.java`, dependente de `MSPSCAN.EXE` (MODI, Office
   2003–2010) — sugere a época em que essa tentativa foi escrita, já descontinuada há
   mais de uma década; nunca chegou a ser referenciada por nenhuma tela.
3. **Mais recente, a única "quase conectada":** `OperacaoScanearDocumento` +
   `topo.jsp`/`js/validar.js` — tem um botão que de fato dispara uma requisição, mas o
   servlet nunca teve o corpo da resposta implementado (código comentado) e a segunda
   cópia do botão (dentro da tela de incluir documento, onde faria sentido de verdade)
   nunca funcionou por causa do isolamento de `iframe`.

Nenhuma dessas telas ou classes deveria ser considerada uma funcionalidade ativa — são
três tentativas paralelas de um recurso que nunca chegou a funcionar de ponta a ponta
em ambiente web real.
