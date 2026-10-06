# Visão Geral do Sistema

## Propósito do sistema
- Aplicação web de gestão de documentos que permite autenticar usuários, pesquisar registros, incluir documentos/anexos (incluindo fluxos específicos PAPEM-41/PAPEM-42) e acompanhar metadados e arquivos armazenados no repositório TRIM/Oracle e no diretório de arquivos configurado no servidor.【F:src/main/java/controller/ServletControlador.java†L33-L113】【F:src/main/java/model/DAOTrim.java†L92-L153】【F:src/main/webapp/WEB-INF/web.xml†L5-L28】【F:docs/01-analise-sistema-atual/guia-documentacao-sisimagem.md†L9-L75】

## Stack tecnológica
- **Linguagem/plataforma:** Java 8 com Java EE (Servlet/JSP) empacotado como WAR para servidores compatíveis com WildFly/JBoss (BOM Java EE 7).【F:pom.xml†L15-L83】
- **Camada web:** Servlets (`ServletControlador`, `Operacao*`) e JSPs em `src/main/webapp/views`, com jQuery 1.4.2 e scripts de validação na pasta `js`.【F:src/main/webapp/WEB-INF/web.xml†L11-L61】【F:src/main/webapp/js/jquery-1.4.2.js†L1-L14】【F:src/main/webapp/views/login.jsp†L1-L110】
- **Persistência:** JDBC manual via `DAOTrim`, conectando ao banco Oracle/TRIM com strings parametrizadas por ambiente e uso do driver `oracle.jdbc.driver.OracleDriver`.【F:src/main/java/model/DAOTrim.java†L24-L90】
- **Logging:** SLF4J no código e configuração Log4j para gravação em arquivo de log no servidor de aplicação.【F:src/main/java/controller/ServletControlador.java†L14-L20】【F:src/main/resources/log4j.properties†L10-L20】
- **Outros utilitários:** Filtro de autenticação (`LoginFilter`), funções utilitárias (hash Whirlpool, manipulação de upload/campos) e scripts front-end de validação de formulário.【F:src/main/java/utilitaria/LoginFilter.java†L1-L59】【F:src/main/java/utilitaria/Whirlpool.java†L1-L140】【F:src/main/webapp/js/validar.js†L1-L60】

## Principais módulos/pastas
- `src/main/java/controller`: controladores e comandos de negócio (`Operacao*`) que processam requisições HTTP, acionam DAO/beans e definem a próxima página JSP; inclui servlet frontal `ServletControlador` e servlets auxiliares (abertura e escaneamento de documentos).【F:src/main/java/controller/ServletControlador.java†L33-L122】【F:src/main/java/controller/OperacaoIncluirDocumento.java†L26-L107】【F:src/main/java/controller/OperacaoLogin.java†L20-L70】
- `src/main/java/model`: `DAOTrim` centraliza o acesso ao banco TRIM/Oracle para autenticação, busca e gravação de documentos, usuários e anexos, além de gerar `recordId` sequenciais.【F:src/main/java/model/DAOTrim.java†L24-L153】
- `src/main/java/bean`: objetos de domínio simples (`Documento`, `Anexo`, `Usuario`, `Grupo`, etc.) que carregam metadados e são usados pelas operações e DAO.【F:src/main/java/bean/Documento.java†L8-L167】【F:src/main/java/bean/Anexo.java†L1-L86】【F:src/main/java/bean/Usuario.java†L1-L56】
- `src/main/java/utilitaria`: infraestrutura e utilidades, como filtro de login, hashing Whirlpool e funções auxiliares para operações de arquivo e formatação.【F:src/main/java/utilitaria/LoginFilter.java†L1-L59】【F:src/main/java/utilitaria/Whirlpool.java†L1-L140】【F:src/main/java/controller/Utilitaria.java†L1-L160】
- `src/main/webapp`: recursos web (JSPs, CSS, JS, imagens) e configurações de deployment em `WEB-INF`. Páginas em `views/` compõem o front-end principal para login, pesquisa, inclusão e cadastros de usuários/documentos.【F:src/main/webapp/views/index.jsp†L1-L35】【F:src/main/webapp/views/pesquisarDocumento.jsp†L1-L109】【F:src/main/webapp/WEB-INF/web.xml†L5-L61】
- `docs`: documentação de engenharia e guia funcional já existente sobre o SisImagem.【F:docs/01-analise-sistema-atual/guia-documentacao-sisimagem.md†L1-L120】

## Principais pontos de entrada
- **Servlet frontal:** `ServletControlador` mapeado para `/controller/controlador`; recebe parâmetro `cmd` e despacha para a operação correspondente via mapa interno de `Operacao`. Comandos incluem login, pesquisa/inclusão de documentos, geração de `recordId`, gerenciamento de usuários e encerramento de sessão.【F:src/main/java/controller/ServletControlador.java†L33-L113】【F:src/main/webapp/WEB-INF/web.xml†L15-L35】
- **Serviços específicos:** `OperacaoAbrirDocumento` em `/controller/AbrirDoc` para download/visualização e `OperacaoScanearDocumento` em `/controller/scanearDocumento` para fluxo de escaneamento; `ChamaScaner` em `/ChamaScaner` envia cabeçalho para acionar aplicativo externo de scanner.【F:src/main/webapp/WEB-INF/web.xml†L22-L61】【F:src/main/java/controller/ChamaScaner.java†L10-L37】
- **Filtro de autenticação:** `LoginFilter` aplicado a `/views/*`, bloqueando acesso a páginas JSP sem sessão de usuário válida.【F:src/main/webapp/WEB-INF/web.xml†L42-L50】【F:src/main/java/utilitaria/LoginFilter.java†L29-L59】
- **Ponto inicial de navegação:** `login.jsp` definido como welcome file; demais JSPs são acionadas via redirecionamento após as operações ou acessadas sob `/views`.【F:src/main/webapp/WEB-INF/web.xml†L9-L14】【F:src/main/webapp/login.jsp†L1-L110】

## Principais integrações externas
- **Banco Oracle/TRIM:** conexão JDBC direta com driver Oracle; strings de conexão possuem placeholders para ambientes (homologação/produção) e são usadas para autenticação, leitura e gravação de registros e anexos no repositório TRIM.【F:src/main/java/model/DAOTrim.java†L24-L153】
- **Sistema de arquivos do servidor:** diretório base configurado via `context-param path` em `web.xml` define onde os uploads são salvos e recuperados (ex.: `/repositorio/`).【F:src/main/webapp/WEB-INF/web.xml†L5-L13】【F:src/main/java/controller/OperacaoIncluirDocumento.java†L52-L75】
- **Aplicativo de scanner local:** servlet `ChamaScaner` responde com header apontando para executável Microsoft Office Document Imaging (`MSPSCAN.EXE`), sugerindo integração com ferramenta de digitalização cliente-side.【F:src/main/java/controller/ChamaScaner.java†L27-L36】
- **Bibliotecas de frontend:** jQuery e plugins de validação/máscara de entrada usados nos formulários JSP.【F:src/main/webapp/js/jquery-1.4.2.js†L1-L14】【F:src/main/webapp/js/jquery.validate.js†L1-L20】【F:src/main/webapp/js/jquery.maskedinput-1.3.js†L1-L20】

## Dúvidas / hipóteses
- As strings de conexão do `DAOTrim` incluem comentários e placeholders para diferentes ambientes; não há configuração externa versionada indicando qual ambiente está ativo ou onde credenciais são mantidas.
- O servlet de escaneamento apenas define um header apontando para executável local; não há evidências de como o cliente aciona ou instala essa dependência, sugerindo forte dependência do ambiente desktop do usuário.
