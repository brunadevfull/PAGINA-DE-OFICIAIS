# Gaps de Documentação para Migração do SisImagem

## 1. Visão Geral

Este documento identifica **lacunas críticas** na documentação atual do SisImagem que precisam ser preenchidas para viabilizar uma migração completa ou reimplementação do sistema com tecnologias modernas.

---

## 2. Gaps Críticos Identificados

### 2.1 Modelo de Dados Completo

> [!CAUTION]
> **CRÍTICO**: Falta documentação completa do schema do banco Oracle/TRIM

#### O que está faltando:
- **Schema completo das tabelas TRIM**:
  - `TSRECORD`: estrutura completa, constraints, índices
  - `TSEXFIELD`: definição de todos os campos customizados
  - `TSEXFIELDV`: valores e relacionamentos
  - `TSLOCATION`: estrutura de usuários e permissões
  - `TSJURGROUP`: grupos e hierarquias
  - `TSRECLOC`: relacionamento entre registros e localizações
  - `TSRECELEC`: registros eletrônicos
  - Outras tabelas referenciadas no código mas não documentadas

- **Diagrama ER completo** com:
  - Todos os relacionamentos (1:1, 1:N, N:N)
  - Cardinalidades
  - Chaves primárias e estrangeiras
  - Constraints e validações
  - Índices para performance

- **Dicionário de dados**:
  - Descrição de cada coluna
  - Tipos de dados e tamanhos
  - Valores permitidos/enumerações
  - Regras de negócio aplicadas em nível de banco

- **Scripts DDL**:
  - CREATE TABLE de todas as tabelas
  - Sequences utilizadas (ex: geração de URIs)
  - Views, se existirem
  - Stored procedures/functions, se existirem

#### Como obter:
```sql
-- Executar no banco Oracle/TRIM para extrair schema
SELECT * FROM USER_TABLES;
SELECT * FROM USER_TAB_COLUMNS WHERE TABLE_NAME LIKE 'TS%';
SELECT * FROM USER_CONSTRAINTS WHERE TABLE_NAME LIKE 'TS%';
SELECT * FROM USER_INDEXES WHERE TABLE_NAME LIKE 'TS%';
```

---

### 2.2 Mapeamento Completo de Comandos e Fluxos

> [!IMPORTANT]
> Falta mapeamento detalhado de todos os comandos (`cmd`) e seus fluxos

#### O que está faltando:

**Matriz de Comandos Completa**:

| Comando (cmd) | Classe Operação | JSP Origem | JSP Destino | Parâmetros | Validações | Permissões |
|---------------|----------------|------------|-------------|------------|------------|------------|
| login | OperacaoLogin | login.jsp | index.jsp | usuario, senha | força senha | público |
| alterarUsuario | OperacaoAlterarUsuario | ? | ? | ? | ? | admin? |
| alterarSenha | OperacaoAlterarSenha | ? | ? | ? | ? | ? |
| excluirUsuario | OperacaoExcluirUsuario | ? | ? | ? | ? | admin? |
| incluirUsuario | OperacaoIncluirUsuario | ? | ? | ? | ? | admin? |
| detalharUsuario | OperacaoDetalharUsuario | ? | ? | ? | ? | ? |
| pesquisarUsuario | OperacaoPesquisarUsuario | ? | ? | ? | ? | ? |
| pesquisarDocumento | OperacaoPesquisarDocumento | pesquisarDocumento.jsp | exibiDocumento.jsp | ? | ? | ? |
| detalharDocumento | OperacaoDetalharDocumento | exibiDocumento.jsp | detalhaDocumento2.jsp | ? | ? | ? |
| incluirDocumento | OperacaoIncluirDocumento | incluirDocumento.jsp | content.jsp | multipart | ? | ? |
| scanearDocumento | OperacaoScanearDocumento | ? | ? | ? | ? | ? |
| localizarDocumento | OperacaoLocalizarDocumento | ? | ? | ? | ? | ? |
| encerraSessao | OperacaoEncerraSessao | ? | sair.jsp | - | - | ? |
| buscaProximoRecordId | OperacaoBuscarRecordId | ? | ? | idTipoRegistro | ? | ? |
| incluirAnexoPapem41 | OperacaoIncluirAnexoPapem41 | incluirAnexoPapem41.jsp | ? | ? | ? | ? |
| incluirDocumentoRespostaPapem42 | OperacaoIncluirDocumentoRespostaPapem42 | incluirDocumentoRespostaPapem42.jsp | ? | ? | ? | ? |
| incluirDocumentoPapem42 | OperacaoIncluirDocumentoPapem42 | incluirDocumentoPapem42.jsp | ? | ? | ? | ? |

**Diagramas de Sequência Detalhados** para cada comando principal (faltam pelo menos 10 diagramas)

**Diagramas de Atividade** mostrando:
- Fluxos condicionais (if/else)
- Loops de processamento
- Tratamento de exceções
- Validações em cada etapa

---

### 2.3 Regras de Negócio Documentadas

> [!WARNING]
> Regras de negócio estão espalhadas no código sem documentação centralizada

#### O que está faltando:

**Glossário de Termos de Negócio**:
- **PAPEM-41**: O que é? Quando usar? Regras específicas?
- **PAPEM-42**: O que é? Diferença para PAPEM-41?
- **Resposta PAPEM-42**: Conceito e fluxo
- **Consignado**: Significado no contexto do sistema
- **Urgente**: Critérios para marcar como urgente
- **NIP**: O que significa? Formato?
- **RecordId vs RecordNumber**: Diferenças e usos
- **FullRecordId**: Composição e formato
- **URI**: Como é gerado e usado no TRIM?

**Regras de Validação**:
- Política de senhas (força, caracteres, tamanho)
- Validação de CPF (apenas formato ou também verificação de dígitos?)
- Regras de datas (períodos permitidos, formatos)
- Tamanhos máximos de arquivos
- Tipos de arquivo permitidos (MIME types)
- Campos obrigatórios vs opcionais por tipo de documento

**Regras de Autorização**:
- Quais perfis/grupos existem?
- Matriz de permissões (quem pode fazer o quê?)
- Regras de bloqueio de usuário (5 tentativas, quanto tempo bloqueado?)
- Senha padrão e política de troca forçada

**Regras de Geração de IDs**:
- Como é calculado o próximo RecordId?
- Lógica de sequencial anual por tipo
- Formato final do identificador

---

### 2.4 Configurações e Ambientes

> [!IMPORTANT]
> Falta documentação de configuração para diferentes ambientes

#### O que está faltando:

**Variáveis de Ambiente**:
```properties
# Quais variáveis são necessárias?
# Como são definidas (JVM args, arquivo .properties, JNDI)?

# Banco de Dados
DB_HOST_PROD=?
DB_PORT_PROD=?
DB_SID_PROD=?
DB_USER_PROD=?
DB_PASS_PROD=?

DB_HOST_HOMOLOG=?
DB_PORT_HOMOLOG=?
DB_SID_HOMOLOG=?
DB_USER_HOMOLOG=?
DB_PASS_HOMOLOG=?

# Diretório de Arquivos
FILE_REPOSITORY_PATH=/repositorio/

# Logging
LOG_LEVEL=?
LOG_PATH=?
```

**Configuração do Servidor de Aplicação**:
- Versão específica do WildFly/JBoss requerida
- Configurações de datasource (JNDI?)
- Configurações de memória (heap size)
- Timeout de sessão
- Configurações de segurança (HTTPS obrigatório?)

**Dependências Externas**:
- Versão exata do Oracle Database
- Driver JDBC (ojdbc8 19.3.0.0 - confirmar se é a versão correta)
- MSPSCAN.EXE: onde obter? Como instalar? Versão?
- Requisitos de sistema operacional do cliente

---

### 2.5 Interface do Usuário e Navegação

> [!NOTE]
> Falta mapa completo de navegação e screenshots das telas

#### O que está faltando:

**Mapa de Navegação (Sitemap)**:
```
login.jsp
  └─> index.jsp (após autenticação)
      ├─> Pesquisar
      │   ├─> pesquisarDocumento.jsp
      │   │   └─> exibiDocumento.jsp
      │   │       └─> detalhaDocumento2.jsp
      │   │           ├─> incluirAnexoPapem41.jsp
      │   │           └─> incluirDocumentoRespostaPapem42.jsp
      │   └─> pesquisar.jsp (?)
      │
      ├─> Incluir
      │   ├─> incluirDocumento.jsp
      │   ├─> incluirDocumentoPapem42.jsp
      │   └─> incluir.jsp (?)
      │
      ├─> Cadastros (admin)
      │   ├─> cadastro.jsp
      │   ├─> cadastroUsuario.jsp
      │   ├─> incluirUsuario.jsp
      │   ├─> pesquisaUsuario.jsp
      │   └─> detalharUsuario.jsp
      │
      └─> Outras
          ├─> alterarSenha.jsp
          ├─> localizarArquivo.jsp
          └─> sair.jsp
```

**Screenshots de Todas as Telas**:
- Capturar tela de cada JSP em funcionamento
- Anotar campos, botões, validações visuais
- Documentar mensagens de erro/sucesso

**Wireframes/Mockups**:
- Para facilitar redesign em nova tecnologia
- Identificar componentes reutilizáveis

**Catálogo de Componentes UI**:
- Formulários e campos
- Tabelas e grids
- Modais/popups
- Menus e navegação
- Mensagens e alertas

---

### 2.6 Integrações e APIs

> [!WARNING]
> Integrações externas não estão completamente documentadas

#### O que está faltando:

**Integração com Scanner (MSPSCAN.EXE)**:
- Protocolo de comunicação (como o browser aciona o executável?)
- Formato de dados retornados
- Tratamento de erros
- Alternativas modernas (WebRTC, TWAIN, etc.)

**Integração com Sistema de Arquivos**:
- Estrutura de diretórios no `/repositorio/`
- Convenção de nomes de arquivos
- Permissões necessárias
- Estratégia de backup
- Política de retenção

**Possíveis Integrações Futuras**:
- API REST para acesso externo?
- Integração com outros sistemas?
- Single Sign-On (SSO)?

---

### 2.7 Testes e Qualidade

> [!CAUTION]
> Não há documentação de testes ou casos de teste

#### O que está faltando:

**Casos de Teste Funcionais**:
- Cenários de teste para cada funcionalidade
- Dados de teste (usuários, documentos, etc.)
- Resultados esperados

**Casos de Teste de Integração**:
- Testes de conexão com banco
- Testes de upload/download de arquivos
- Testes de scanner

**Testes de Segurança**:
- Testes de SQL Injection
- Testes de autenticação/autorização
- Testes de upload de arquivos maliciosos

**Testes de Performance**:
- Carga esperada (usuários simultâneos)
- Tempo de resposta aceitável
- Volume de dados (documentos, tamanho do repositório)

**Critérios de Aceitação**:
- Definir o que significa "sistema funcionando corretamente"
- Métricas de qualidade

---

### 2.8 Deployment e Operações

> [!IMPORTANT]
> Falta documentação operacional completa

#### O que está faltando:

**Guia de Instalação**:
1. Pré-requisitos (SO, Java, servidor, banco)
2. Instalação do banco de dados
3. Criação do schema e carga inicial
4. Configuração do servidor de aplicação
5. Deploy do WAR
6. Configuração de variáveis de ambiente
7. Testes pós-instalação
8. Troubleshooting comum

**Guia de Atualização**:
- Como fazer upgrade de versão
- Scripts de migração de dados
- Rollback em caso de falha

**Guia de Backup e Restore**:
- O que fazer backup (banco + arquivos)
- Frequência recomendada
- Procedimento de restore
- Testes de restore

**Monitoramento**:
- Logs importantes e onde encontrá-los
- Métricas a monitorar
- Alertas recomendados
- Dashboard de saúde do sistema

**Troubleshooting**:
- Problemas comuns e soluções
- Códigos de erro e significados
- Procedimentos de diagnóstico

---

### 2.9 Código e Arquitetura

> [!NOTE]
> Alguns aspectos técnicos precisam ser melhor documentados

#### O que está faltando:

**Diagrama de Classes Completo**:
- Todas as classes com atributos e métodos
- Relacionamentos e dependências
- Padrões de design utilizados

**Diagrama de Componentes**:
- Separação lógica dos módulos
- Interfaces entre componentes
- Dependências externas

**Diagrama de Implantação**:
- Topologia de rede
- Servidores e suas responsabilidades
- Firewalls e segurança de rede

**Documentação de APIs Internas**:
- Javadoc completo de todas as classes públicas
- Exemplos de uso
- Contratos de métodos (pré/pós-condições)

**Padrões e Convenções**:
- Padrões de nomenclatura
- Estrutura de pacotes
- Convenções de código
- Boas práticas adotadas

---

### 2.10 Dados de Referência

> [!IMPORTANT]
> Falta documentação de dados mestres e de referência

#### O que está faltando:

**Tipos de Registro**:
- Lista completa de tipos de registro
- IDs e descrições
- Quando usar cada tipo
- Campos específicos por tipo

**Classificações**:
- Taxonomia de classificação de documentos
- Hierarquia
- Regras de classificação

**Grupos e Perfis**:
- Lista de grupos existentes
- Permissões de cada grupo
- Como atribuir usuários a grupos

**Dados de Configuração**:
- Parâmetros do sistema
- Valores padrão
- Como alterar configurações

**Dados Iniciais (Seed Data)**:
- Usuário administrador padrão
- Grupos iniciais
- Tipos de registro pré-cadastrados
- Classificações padrão

---

## 3. Documentação Adicional Necessária

### 3.1 Documentação de Migração

**Estratégia de Migração**:
- Abordagem: Big Bang vs Incremental
- Tecnologias alvo sugeridas
- Cronograma estimado
- Riscos e mitigações

**Mapeamento Tecnológico**:

| Tecnologia Atual | Tecnologia Alvo Sugerida | Justificativa |
|------------------|--------------------------|---------------|
| Java 8 + Servlets/JSP | Java 17+ Spring Boot | Modernização, produtividade |
| jQuery 1.4.2 | React/Vue/Angular | UI moderna, componentização |
| JDBC manual | Spring Data JPA / MyBatis | Produtividade, manutenibilidade |
| Oracle TRIM | PostgreSQL / Oracle moderno | Custo, flexibilidade |
| Upload Apache Commons | Spring MultipartFile | Integração framework |
| Log4j | SLF4J + Logback | Segurança, performance |
| WAR em WildFly | JAR standalone / Container | Simplicidade deployment |

**Plano de Migração de Dados**:
- Scripts de extração do Oracle TRIM
- Transformação de dados
- Carga no novo sistema
- Validação de integridade
- Migração de arquivos do repositório

### 3.2 Manual do Usuário Completo

**Para cada funcionalidade**:
- Objetivo
- Pré-requisitos
- Passo a passo com screenshots
- Dicas e boas práticas
- Solução de problemas comuns

**Tutoriais**:
- Primeiro acesso
- Fluxo completo de inclusão de documento
- Fluxo completo de pesquisa
- Administração de usuários

### 3.3 Documentação de Segurança

**Análise de Vulnerabilidades**:
- SQL Injection (CRÍTICO - já identificado)
- XSS (Cross-Site Scripting)
- CSRF (Cross-Site Request Forgery)
- Upload de arquivos maliciosos
- Exposição de informações sensíveis
- Autenticação e sessão

**Plano de Remediação**:
- Priorização de vulnerabilidades
- Soluções recomendadas
- Testes de segurança

**Políticas de Segurança**:
- Política de senhas
- Política de acesso
- Política de auditoria
- Conformidade (LGPD, etc.)

---

## 4. Artefatos UML Faltantes

### 4.1 Diagramas Comportamentais

- [ ] **Diagrama de Casos de Uso Completo**
  - Todos os atores
  - Todos os casos de uso
  - Relacionamentos (include, extend)
  - Descrição textual de cada caso de uso

- [ ] **Diagramas de Sequência** (pelo menos 15):
  - Login e autenticação
  - Pesquisa de documento
  - Inclusão de documento
  - Inclusão de anexo PAPEM-41
  - Inclusão de documento PAPEM-42
  - Inclusão de resposta PAPEM-42
  - Download de documento
  - Escaneamento de documento
  - Cadastro de usuário
  - Alteração de usuário
  - Exclusão de usuário
  - Alteração de senha
  - Bloqueio de usuário
  - Geração de RecordId
  - Encerramento de sessão

- [ ] **Diagramas de Atividade** (pelo menos 10):
  - Fluxo de autenticação completo
  - Fluxo de inclusão de documento
  - Fluxo de pesquisa
  - Fluxo de upload de arquivo
  - Fluxo de validação de formulário
  - Fluxo de geração de ID
  - Fluxo de tratamento de erro
  - Fluxo de bloqueio de usuário
  - Fluxo de troca de senha
  - Fluxo de anexação de documento

- [ ] **Diagramas de Estado**:
  - Estados de um documento
  - Estados de um usuário
  - Estados de uma sessão

### 4.2 Diagramas Estruturais

- [ ] **Diagrama de Classes Completo**
  - Todos os pacotes
  - Todas as classes
  - Atributos e métodos
  - Relacionamentos e multiplicidades
  - Interfaces

- [ ] **Diagrama de Pacotes**
  - Organização lógica
  - Dependências entre pacotes

- [ ] **Diagrama de Componentes**
  - Componentes de software
  - Interfaces providas/requeridas
  - Dependências

- [ ] **Diagrama de Implantação**
  - Nós de hardware/software
  - Artefatos deployados
  - Protocolos de comunicação

### 4.3 Diagramas de Dados

- [ ] **Diagrama ER Completo**
  - Todas as entidades
  - Todos os relacionamentos
  - Atributos e tipos
  - Chaves e constraints

- [ ] **Modelo Lógico de Dados**
  - Normalização
  - Índices
  - Particionamento (se houver)

- [ ] **Modelo Físico de Dados**
  - DDL completo
  - Tablespaces
  - Partições
  - Estatísticas

---

## 5. Checklist de Documentação para Migração

### 5.1 Documentação de Requisitos
- [ ] Requisitos funcionais completos
- [ ] Requisitos não-funcionais (performance, segurança, etc.)
- [ ] Casos de uso detalhados
- [ ] Regras de negócio centralizadas
- [ ] Glossário de termos

### 5.2 Documentação de Arquitetura
- [ ] Visão geral da arquitetura
- [ ] Decisões arquiteturais e justificativas
- [ ] Padrões e estilos arquiteturais
- [ ] Diagramas UML completos
- [ ] Documentação de APIs

### 5.3 Documentação de Dados
- [ ] Modelo de dados completo (ER)
- [ ] Dicionário de dados
- [ ] Scripts DDL
- [ ] Dados de referência
- [ ] Regras de integridade

### 5.4 Documentação de Código
- [ ] Javadoc de todas as classes públicas
- [ ] Comentários em código complexo
- [ ] README de cada módulo
- [ ] Guia de contribuição

### 5.5 Documentação de Testes
- [ ] Plano de testes
- [ ] Casos de teste
- [ ] Dados de teste
- [ ] Resultados de testes
- [ ] Cobertura de código

### 5.6 Documentação de Deployment
- [ ] Guia de instalação
- [ ] Guia de configuração
- [ ] Guia de atualização
- [ ] Guia de backup/restore
- [ ] Troubleshooting

### 5.7 Documentação de Usuário
- [ ] Manual do usuário
- [ ] Tutoriais
- [ ] FAQs
- [ ] Vídeos de treinamento

### 5.8 Documentação de Migração
- [ ] Estratégia de migração
- [ ] Plano de migração de dados
- [ ] Mapeamento tecnológico
- [ ] Cronograma
- [ ] Plano de rollback

---

## 6. Próximos Passos Recomendados

### Fase 1: Documentação Crítica (Prioridade Alta)
1. **Extrair schema completo do banco Oracle/TRIM**
   - Conectar ao banco e executar queries de metadados
   - Gerar DDL de todas as tabelas
   - Criar diagrama ER

2. **Mapear todos os comandos e fluxos**
   - Analisar cada classe `Operacao*`
   - Documentar parâmetros e validações
   - Criar matriz de comandos

3. **Documentar regras de negócio**
   - Entrevistar usuários/stakeholders
   - Criar glossário de termos
   - Documentar validações e cálculos

4. **Capturar screenshots de todas as telas**
   - Executar sistema em ambiente de desenvolvimento
   - Navegar por todos os fluxos
   - Documentar campos e comportamentos

### Fase 2: Documentação Técnica (Prioridade Média)
5. **Criar diagramas UML faltantes**
   - Casos de uso
   - Sequência (principais fluxos)
   - Atividade (processos complexos)
   - Classes completo

6. **Documentar configurações e ambientes**
   - Variáveis de ambiente
   - Configuração de servidores
   - Dependências externas

7. **Documentar integrações**
   - Scanner (MSPSCAN.EXE)
   - Sistema de arquivos
   - Banco de dados

### Fase 3: Documentação de Suporte (Prioridade Baixa)
8. **Criar manual do usuário**
   - Passo a passo de cada funcionalidade
   - Screenshots e exemplos
   - Troubleshooting

9. **Documentar testes**
   - Casos de teste
   - Dados de teste
   - Procedimentos de teste

10. **Criar documentação de deployment**
    - Guia de instalação
    - Guia de configuração
    - Procedimentos operacionais

---

## 7. Ferramentas Recomendadas

### Para Engenharia Reversa:
- **SchemaSpy**: Gerar documentação do banco de dados
- **PlantUML**: Criar diagramas UML a partir de texto
- **JDepend**: Analisar dependências entre pacotes
- **Javadoc**: Gerar documentação de código
- **SonarQube**: Análise de qualidade de código

### Para Documentação:
- **Confluence/Notion**: Wiki colaborativo
- **Draw.io**: Diagramas
- **Markdown**: Documentação versionada
- **Swagger/OpenAPI**: Documentação de APIs (futuro)

### Para Migração:
- **Liquibase/Flyway**: Migração de schema de banco
- **Apache Camel**: Integração e migração de dados
- **Testcontainers**: Testes de integração

---

## 8. Estimativa de Esforço

| Atividade | Esforço Estimado | Prioridade |
|-----------|------------------|------------|
| Extração schema banco | 2-3 dias | Alta |
| Mapeamento de comandos | 3-5 dias | Alta |
| Documentação regras de negócio | 5-7 dias | Alta |
| Screenshots e navegação | 2-3 dias | Alta |
| Diagramas UML | 5-10 dias | Média |
| Documentação técnica | 3-5 dias | Média |
| Manual do usuário | 5-7 dias | Média |
| Documentação de testes | 2-3 dias | Baixa |
| Documentação deployment | 2-3 dias | Baixa |
| **TOTAL** | **29-46 dias** | - |

> [!NOTE]
> Esta estimativa considera uma pessoa trabalhando em tempo integral. O esforço pode ser reduzido com paralelização e envolvimento de múltiplas pessoas.

---

## 9. Conclusão

A documentação atual do SisImagem cobre bem a **arquitetura geral** e os **principais componentes**, mas faltam detalhes críticos para uma migração completa:

### Gaps Mais Críticos:
1. ✅ **Schema completo do banco de dados**
2. ✅ **Mapeamento detalhado de todos os comandos**
3. ✅ **Regras de negócio documentadas**
4. ✅ **Configurações de ambiente**
5. ✅ **Diagramas UML completos**

### Recomendação:
Priorize a **Fase 1** (documentação crítica) antes de iniciar qualquer migração. Sem o schema completo do banco e o mapeamento de regras de negócio, há alto risco de perda de funcionalidades ou dados na migração.

---

## 10. Referências

- [reverse-engineering-report.md](./reverse-engineering-report.md)
- [guia-documentacao-sisimagem.md](./guia-documentacao-sisimagem.md)
- [SYSTEM_OVERVIEW.md](../../SYSTEM_OVERVIEW.md)
- Código-fonte em [src/main/java](../../src/main/java)
- Views em [src/main/webapp/views](../../src/main/webapp/views)
