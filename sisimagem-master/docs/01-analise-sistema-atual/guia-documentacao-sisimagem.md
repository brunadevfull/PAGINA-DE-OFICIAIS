# Guia de Documentação e Reengenharia do SisImagem

## 1. Propósito do documento
Este guia consolida requisitos funcionais, arquitetura técnica, orientações de documentação UML e instruções operacionais do SisImagem. Ele foi pensado para meu uso no dia a dia, com apoio de duas pessoas que ajudam na operação, mantendo o sistema atual ou reimplementando-o em outra linguagem quando necessário, sem perder comportamento e integrações originais.

## 2. Panorama geral do sistema
- **Papel do front controller:** todo fluxo HTTP passa por `ServletControlador`, que registra comandos na inicialização e delega cada requisição a uma operação concreta após validar a sessão.【F:src/main/java/controller/ServletControlador.java†L3-L132】  
- **Operações de negócio:** as classes derivadas de `Operacao` encapsulam regras para autenticação, pesquisa, inclusão de documentos e administração de usuários. Cada operação define a próxima página JSP exibida para o usuário.【F:src/main/java/controller/Operacao.java†L1-L34】【F:src/main/java/controller/OperacaoLogin.java†L16-L81】【F:src/main/java/controller/OperacaoIncluirDocumento.java†L1-L115】  
- **Camada de dados:** `DAOTrim` concentra o acesso JDBC ao TRIM/Oracle, incluindo geração de identificadores, persistência de documentos e autenticação de usuários.【F:src/main/java/model/DAOTrim.java†L1-L130】  
- **Segurança de acesso:** `LoginFilter` protege as páginas sob `/views`, garantindo que apenas sessões autenticadas prossigam.【F:src/main/java/utilitaria/LoginFilter.java†L1-L70】  
- **Modelo de domínio:** `Documento`, `Anexo` e `Usuario` guardam os metadados manipulados na aplicação, refletindo os campos apresentados nas telas e nas operações de persistência.【F:src/main/java/bean/Documento.java†L1-L167】【F:src/main/java/bean/Anexo.java†L1-L86】【F:src/main/java/bean/Usuario.java†L1-L56】

## 3. Funcionalidades detalhadas
### 3.1 Autenticação e sessão
1. Formulário de login: valida força de senha e campos obrigatórios antes de submeter para `cmd=login`.【F:src/main/webapp/views/login.jsp†L1-L108】  
2. `OperacaoLogin` valida as credenciais, controla tentativas inválidas, bloqueia o usuário após cinco erros e redireciona para troca de senha ou página inicial conforme necessário.【F:src/main/java/controller/OperacaoLogin.java†L20-L70】  
3. `LoginFilter` impede acesso direto às views sem sessão, exibindo mensagem orientativa.【F:src/main/java/utilitaria/LoginFilter.java†L35-L59】

### 3.2 Pesquisa e consulta de documentos
1. Formulário `pesquisarDocumento.jsp` reúne filtros (CPF, NIP, consignado, datas, urgência etc.) e envia a busca para `cmd=pesquisarDocumento`.【F:src/main/webapp/views/pesquisarDocumento.jsp†L1-L109】  
2. Os resultados são renderizados em `exibiDocumento.jsp`, com links que levam ao detalhamento por `cmd=detalharDocumento`.【F:src/main/webapp/views/exibiDocumento.jsp†L29-L70】  
3. `detalhaDocumento2.jsp` exibe metadados completos, lista de anexos e oferece ações contextuais para anexar ou responder documentos.【F:src/main/webapp/views/detalhaDocumento2.jsp†L33-L190】

### 3.3 Inclusão de documentos e anexos
1. `OperacaoIncluirDocumento` processa uploads multipart, normaliza campos via `Utilitaria`, grava arquivos no diretório configurado e persiste os metadados por meio do `DAOTrim`.【F:src/main/java/controller/OperacaoIncluirDocumento.java†L26-L107】  
2. `DAOTrim.buscaProximoRecordId` calcula sequenciais anuais para cada tipo de registro antes da gravação.【F:src/main/java/model/DAOTrim.java†L92-L153】  
3. As classes `Documento` e `Anexo` expõem campos alinhados aos registros TRIM, permitindo mapear 1:1 os metadados necessários.【F:src/main/java/bean/Documento.java†L8-L167】【F:src/main/java/bean/Anexo.java†L1-L86】

### 3.4 Administração de usuários
1. `cadastroUsuario.jsp` permite incluir, pesquisar, alterar e excluir usuários, com validação de força de senha e política de segurança exibida na própria tela.【F:src/main/webapp/views/cadastroUsuario.jsp†L16-L170】  
2. `DAOTrim` fornece operações correlatas (validação, bloqueio, atualização de último acesso) reutilizadas pelas classes `Operacao*Usuario`.

## 4. Considerações técnicas essenciais
- **Integração Oracle/TRIM:** o driver é carregado manualmente, e as strings de conexão possuem marcadores para substituição conforme ambiente (homologação/produção). Qualquer reimplementação deve prever configuração externa para credenciais e hosts.【F:src/main/java/model/DAOTrim.java†L32-L90】  
- **Upload e armazenamento:** os arquivos são salvos em um diretório definido em `web.xml` (`context-param "path"`); o mesmo valor é reutilizado para montar links de download e anexos.【F:src/main/java/controller/OperacaoIncluirDocumento.java†L52-L75】【F:src/main/webapp/views/detalhaDocumento2.jsp†L38-L156】  
- **Tratamento de segurança:** política de senhas fortes é aplicada via JavaScript nas telas de login e cadastro. Reimplementações devem reforçar essas regras no backend para garantir consistência.【F:src/main/webapp/views/login.jsp†L14-L55】【F:src/main/webapp/views/cadastroUsuario.jsp†L16-L131】

## 5. Diretrizes para documentação UML
### 5.1 Diagrama de casos de uso
- **Atores:** Usuário comum (consulta e inclusão), Administrador (operações de cadastro), Sistema TRIM (provedor de persistência).  
- **Casos principais:** autenticar, pesquisar documento, detalhar documento, incluir documento, incluir anexo PAPEM-41, incluir resposta PAPEM-42, administrar usuários.  
- **Sugestão:** agrupar casos conforme menus da interface (Pesquisar, Incluir, Cadastros) para refletir a navegação observada nas JSPs.【F:src/main/webapp/views/pesquisarDocumento.jsp†L29-L88】【F:src/main/webapp/views/detalhaDocumento2.jsp†L165-L175】【F:src/main/webapp/views/cadastroUsuario.jsp†L80-L170】

### 5.2 Diagrama de classes
- **Pacotes recomendados:** `controller` (operações e front controller), `model` (DAOTrim), `bean` (entidades), `utilitaria` (infraestrutura).  
- **Relacionamentos:** `ServletControlador` mantém um `HashMap<String, Operacao>`; subclasses de `Operacao` dependem de `DAOTrim` e dos beans; `DAOTrim` instancia e popula `Documento`, `Anexo` e `Usuario`.  
- **Notas:** documente atributos críticos (`recordNumber`, `fullrecordid`, `nomeArquivoCompleto`) e métodos chave (`executar`, `obterConexaoDesenv`, `buscaProximoRecordId`) para orientar implementações futuras.【F:src/main/java/controller/ServletControlador.java†L20-L129】【F:src/main/java/controller/Operacao.java†L1-L34】【F:src/main/java/model/DAOTrim.java†L32-L153】【F:src/main/java/bean/Documento.java†L8-L167】

### 5.3 Diagramas de sequência
Crie pelo menos três sequências:
1. **Login:** `login.jsp` → `ServletControlador` (`cmd=login`) → `OperacaoLogin` → `DAOTrim.validaUsuarioSenha` → retorno para JSP de destino.【F:src/main/webapp/views/login.jsp†L75-L107】【F:src/main/java/controller/ServletControlador.java†L70-L110】【F:src/main/java/controller/OperacaoLogin.java†L20-L70】  
2. **Pesquisa de documento:** `pesquisarDocumento.jsp` → `ServletControlador` (`cmd=pesquisarDocumento`) → `OperacaoPesquisarDocumento` → `DAOTrim.listaDocumento` → `exibiDocumento.jsp`. (A classe `OperacaoPesquisarDocumento` segue o mesmo padrão de dependência do DAO).  
3. **Inclusão de documento:** `incluirDocumento.jsp` → `ServletControlador` (`cmd=incluirDocumento`) → `OperacaoIncluirDocumento` → `Utilitaria`/upload → `DAOTrim.inserirDocumento` → retorno para `content.jsp` com mensagem.【F:src/main/java/controller/OperacaoIncluirDocumento.java†L26-L107】

### 5.4 Diagramas de atividade
- **Fluxo de autenticação:** incluir nós para validação de senha, incremento de contador de tentativas, bloqueio de usuário e redirecionamento condicional.【F:src/main/java/controller/OperacaoLogin.java†L24-L70】  
- **Fluxo de inclusão:** representar paralelismo entre leitura de campos e escrita do arquivo, seguido de persistência transacional no TRIM.【F:src/main/java/controller/OperacaoIncluirDocumento.java†L52-L107】

### 5.5 Diagrama de componentes
- **Componentes sugeridos:** Portal web (JSP/Servlets), Módulo de Negócio (operações), Módulo de Persistência (DAOTrim), Banco TRIM (Oracle), Diretório de Arquivos, Sistema de Autenticação (embutido). Indique as dependências e interfaces (por exemplo, `DAOTrim` expondo métodos de CRUD de documentos).【F:src/main/java/controller/ServletControlador.java†L20-L129】【F:src/main/java/model/DAOTrim.java†L32-L153】

### 5.6 Diagrama de implantação
- **Nós mínimos:** Servidor de Aplicação Java EE (hospeda WAR), Banco Oracle TRIM, Repositório de Arquivos de documentos. Inclua parâmetros configuráveis (`context-param path`, URLs JDBC) como propriedades do nó para orientar deployments futuros.【F:src/main/java/controller/OperacaoIncluirDocumento.java†L67-L75】【F:src/main/java/model/DAOTrim.java†L32-L90】

### 5.7 Diagrama ER / Modelo de dados
- Mapeie as classes `Documento`, `Anexo` e `Usuario` para tabelas TRIM (`TSRECORD`, `TSEXFIELDV`, `TSLOCATION` etc.). Use atributos presentes nos beans para definir colunas e relacionamentos (por exemplo, `Documento.listaAnexo` → associação 1:N).【F:src/main/java/bean/Documento.java†L8-L167】【F:src/main/java/bean/Anexo.java†L1-L86】【F:src/main/java/bean/Usuario.java†L1-L56】

## 6. Manual do usuário consolidado
1. **Login:** acesse `/views/login.jsp`, informe usuário/senha seguindo as regras de complexidade, e envie para autenticação.【F:src/main/webapp/views/login.jsp†L75-L107】  
2. **Tela principal:** `index.jsp` carrega `topo.jsp` e `content.jsp`, oferecendo menus para Pesquisa, Inclusão e Cadastros (estrutura herdada do controlador).  
3. **Pesquisar documentos:** utilize filtros em `pesquisarDocumento.jsp` e visualize resultados em `exibiDocumento.jsp` com links para detalhamento.【F:src/main/webapp/views/pesquisarDocumento.jsp†L29-L88】【F:src/main/webapp/views/exibiDocumento.jsp†L29-L70】  
4. **Detalhar documento:** confira dados e anexos em `detalhaDocumento2.jsp`; utilize botões para anexar PAPEM-41 ou respostas PAPEM-42 conforme o tipo.【F:src/main/webapp/views/detalhaDocumento2.jsp†L35-L175】  
5. **Incluir documento:** acione o menu “Incluir”, preencha o formulário (tipicamente `incluirDocumento.jsp`), gere o próximo `recordNumber` via `cmd=buscaProximoRecordId` e finalize em `cmd=incluirDocumento`.  
6. **Gerenciar usuários:** perfis administrativos acessam `cadastro.jsp`/`cadastroUsuario.jsp` para criar, pesquisar, alterar e excluir logins, seguindo as orientações exibidas na página.【F:src/main/webapp/views/cadastroUsuario.jsp†L80-L170】  
7. **Encerrar sessão:** utilize `cmd=encerraSessao` a partir do menu, que remove atributos de sessão no controlador e redireciona para `sair.jsp`.

## 7. Diretrizes para reimplementação em outra linguagem
1. **Preserve o front controller:** mesmo em frameworks modernos, mantenha uma camada de roteamento que centralize a resolução de comandos para ações equivalentes ao `HashMap` original.【F:src/main/java/controller/ServletControlador.java†L33-L129】  
2. **Mantenha separação de responsabilidades:** adapte as classes `Operacao*` para handlers ou services que recebam o contexto da requisição, manipulem beans (DTOs) e devolvam páginas ou respostas JSON conforme necessário.【F:src/main/java/controller/Operacao.java†L1-L34】  
3. **Reimplemente o DAO como gateway TRIM:** converta `DAOTrim` em um módulo dedicado, parametrizável por ambiente, responsável por construir consultas equivalentes e por serializar/deserializar os metadados (com atenção para os formatos de datas e geração de IDs).【F:src/main/java/model/DAOTrim.java†L32-L153】  
4. **Repita a política de segurança:** implemente controle de tentativas inválidas, bloqueio temporário e mensagens amigáveis conforme os comportamentos observados em `OperacaoLogin` e nas views.【F:src/main/java/controller/OperacaoLogin.java†L20-L70】【F:src/main/webapp/views/login.jsp†L14-L55】  
5. **Repense as views:** ao migrar para SPAs ou templates modernos, traduza cada JSP em componentes equivalentes, mantendo campos, validações e mensagens. Use este guia como checklist para garantir que nenhum campo do domínio seja omitido.

## 8. Plano de documentação complementar
- **Glossário:** derive termos (PAPEM-41, PAPEM-42, TRIM, Urgente, Consignado) a partir das labels presentes nas JSPs para reduzir ambiguidade de negócio.【F:src/main/webapp/views/pesquisarDocumento.jsp†L29-L109】  
- **Matriz de rastreabilidade:** relacione cada caso de uso com operações (`cmd`) e com as tabelas TRIM tocadas pelo `DAOTrim`.  
- **Catálogo de mensagens:** documente textos exibidos nas telas e mensagens de erro configuradas nos atributos `mensagem` para manter consistência na reimplementação.【F:src/main/webapp/views/login.jsp†L62-L107】【F:src/main/webapp/views/exibiDocumento.jsp†L18-L70】

## 9. Referências rápidas
- **Controle principal:** `src/main/java/controller/ServletControlador.java`  
- **Operações de negócio:** `src/main/java/controller/Operacao*.java`  
- **Persistência TRIM:** `src/main/java/model/DAOTrim.java`  
- **Entidades:** `src/main/java/bean/*.java`  
- **Filtros e utilitários:** `src/main/java/utilitaria/*.java`  
- **Interface JSP:** `src/main/webapp/views/*.jsp`

Este documento deve ser mantido juntamente com diagramas UML versionados (por exemplo, em `docs/uml/`) para garantir que evolução de código e artefatos de engenharia sigam sincronizados.
