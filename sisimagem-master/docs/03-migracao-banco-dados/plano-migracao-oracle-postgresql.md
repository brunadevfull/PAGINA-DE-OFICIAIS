# Plano Detalhado: Migração Oracle → PostgreSQL

## 🎯 Objetivo

Migrar o banco de dados do SisImagem de **Oracle SQL (TRIM)** para **PostgreSQL 15** com **zero perda de dados** e **mínimo downtime**. Este plano foi escrito por mim para servir como roteiro de execução e checklist.

---

## 📋 Sumário Executivo

| Item | Detalhes |
|------|----------|
| **Banco Origem** | Oracle Database (versão TRIM) |
| **Banco Destino** | PostgreSQL 15 |
| **Volume Estimado** | A definir após análise |
| **Downtime Máximo** | 4 horas (janela de manutenção) |
| **Duração Total** | 6 semanas |
| **Risco Geral** | 🟡 Médio-Alto |

---

## 🔍 FASE 1: Análise e Inventário (Semana 1-2)

### 1.1 Extração do Schema Oracle

#### Passo 1: Conectar ao Banco Oracle

```bash
# Instalar Oracle Instant Client
wget https://download.oracle.com/otn_software/linux/instantclient/instantclient-basic-linux.x64-21.1.0.0.0.zip
unzip instantclient-basic-linux.x64-21.1.0.0.0.zip
export LD_LIBRARY_PATH=/path/to/instantclient_21_1:$LD_LIBRARY_PATH

# Testar conexão
sqlplus usuario/senha@host:porta/sid
```

#### Passo 2: Inventário de Objetos

```sql
-- 1. Listar todas as tabelas
SELECT table_name, num_rows, tablespace_name
FROM user_tables
ORDER BY table_name;

-- 2. Listar todas as colunas
SELECT table_name, column_name, data_type, data_length, nullable
FROM user_tab_columns
ORDER BY table_name, column_id;

-- 3. Listar constraints
SELECT constraint_name, constraint_type, table_name, search_condition
FROM user_constraints
ORDER BY table_name;

-- 4. Listar índices
SELECT index_name, table_name, uniqueness, column_name
FROM user_indexes i
JOIN user_ind_columns ic ON i.index_name = ic.index_name
ORDER BY table_name, index_name;

-- 5. Listar sequences
SELECT sequence_name, min_value, max_value, increment_by, last_number
FROM user_sequences;

-- 6. Listar triggers
SELECT trigger_name, table_name, triggering_event, trigger_type
FROM user_triggers;

-- 7. Listar stored procedures e functions
SELECT object_name, object_type, status
FROM user_objects
WHERE object_type IN ('PROCEDURE', 'FUNCTION', 'PACKAGE');

-- 8. Listar views
SELECT view_name, text
FROM user_views;
```

#### Passo 3: Análise de Tamanho

```sql
-- Tamanho total do banco
SELECT 
    SUM(bytes)/1024/1024/1024 AS size_gb
FROM user_segments;

-- Tamanho por tabela
SELECT 
    segment_name AS table_name,
    SUM(bytes)/1024/1024 AS size_mb,
    COUNT(*) AS num_extents
FROM user_segments
WHERE segment_type = 'TABLE'
GROUP BY segment_name
ORDER BY SUM(bytes) DESC;

-- Contagem de registros por tabela
SELECT 
    table_name,
    num_rows
FROM user_tables
WHERE table_name LIKE 'TS%'
ORDER BY num_rows DESC;
```

**Entregáveis:**
- [ ] `oracle-inventory.xlsx` - Inventário completo
- [ ] `oracle-schema.sql` - DDL de todas as tabelas
- [ ] `oracle-size-analysis.txt` - Análise de tamanho
- [ ] `oracle-dependencies.md` - Mapa de dependências

---

### 1.2 Análise de Compatibilidade

#### Usar ora2pg para Relatório Automático

```bash
# Instalar ora2pg
sudo apt-get install ora2pg

# Criar arquivo de configuração
cat > ora2pg.conf << 'EOF'
ORACLE_DSN      dbi:Oracle:host=localhost;sid=TRIM;port=1521
ORACLE_USER     usuario
ORACLE_PWD      senha
SCHEMA          SCHEMA_NAME
TYPE            TABLE,VIEW,SEQUENCE,TRIGGER,FUNCTION,PROCEDURE
OUTPUT          output.sql
OUTPUT_DIR      /tmp/ora2pg
EOF

# Gerar relatório de compatibilidade
ora2pg -t SHOW_REPORT -c ora2pg.conf > compatibility_report.html

# Abrir no navegador para análise
firefox compatibility_report.html
```

#### Análise Manual de Incompatibilidades

**Checklist de Incompatibilidades:**

- [ ] **Tipos de dados não suportados**
  - `VARCHAR2` → `VARCHAR`
  - `NUMBER` → `NUMERIC` ou `INTEGER`
  - `CLOB` → `TEXT`
  - `BLOB` → `BYTEA`

- [ ] **Funções Oracle-specific**
  - `SYSDATE` → `CURRENT_TIMESTAMP`
  - `NVL()` → `COALESCE()`
  - `DECODE()` → `CASE WHEN`
  - `ROWNUM` → `LIMIT/OFFSET`

- [ ] **Sintaxe de Outer Join**
  - `(+)` → `LEFT/RIGHT JOIN`

- [ ] **Sequences**
  - Converter para `SERIAL` ou manter `SEQUENCE`

- [ ] **Stored Procedures**
  - PL/SQL → PL/pgSQL (reescrita necessária)

- [ ] **Triggers**
  - Sintaxe diferente (reescrita necessária)

- [ ] **Packages**
  - Não existem no PostgreSQL (reorganizar em schemas)

**Entregáveis:**
- [ ] `compatibility_report.html` - Relatório ora2pg
- [ ] `incompatibilities.md` - Lista de incompatibilidades
- [ ] `conversion_plan.md` - Plano de conversão

---

## 🛠️ FASE 2: Preparação do PostgreSQL (Semana 2)

### 2.1 Instalação e Configuração

```bash
# Instalar PostgreSQL 15
sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
sudo apt-get update
sudo apt-get install postgresql-15 postgresql-contrib-15

# Verificar instalação
sudo systemctl status postgresql
psql --version
```

### 2.2 Criação do Banco e Usuário

```sql
-- Conectar como postgres
sudo -u postgres psql

-- Criar banco de dados
CREATE DATABASE sisimagem
  WITH ENCODING 'UTF8'
       LC_COLLATE = 'pt_BR.UTF-8'
       LC_CTYPE = 'pt_BR.UTF-8'
       TEMPLATE template0;

-- Criar usuário
CREATE USER sisimagem_user WITH PASSWORD 'senha_forte_aqui';

-- Conceder permissões
GRANT ALL PRIVILEGES ON DATABASE sisimagem TO sisimagem_user;

-- Conectar ao banco sisimagem
\c sisimagem

-- Conceder permissões no schema public
GRANT ALL ON SCHEMA public TO sisimagem_user;
GRANT ALL ON ALL TABLES IN SCHEMA public TO sisimagem_user;
GRANT ALL ON ALL SEQUENCES IN SCHEMA public TO sisimagem_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO sisimagem_user;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO sisimagem_user;
```

### 2.3 Instalação de Extensões

```sql
-- Conectar ao banco sisimagem
\c sisimagem

-- Extensões úteis
CREATE EXTENSION IF NOT EXISTS pg_trgm;        -- Busca fuzzy
CREATE EXTENSION IF NOT EXISTS unaccent;       -- Remove acentos
CREATE EXTENSION IF NOT EXISTS btree_gin;      -- Índices GIN para B-tree
CREATE EXTENSION IF NOT EXISTS pg_stat_statements; -- Estatísticas de queries
CREATE EXTENSION IF NOT EXISTS pgcrypto;       -- Funções de criptografia

-- Verificar extensões instaladas
\dx
```

### 2.4 Configuração de Performance

```bash
# Editar postgresql.conf
sudo nano /etc/postgresql/15/main/postgresql.conf
```

**Configurações Recomendadas (para servidor com 16GB RAM):**

```conf
# CONNECTIONS
max_connections = 200

# MEMORY
shared_buffers = 4GB                    # 25% da RAM
effective_cache_size = 12GB             # 75% da RAM
maintenance_work_mem = 1GB
work_mem = 64MB

# CHECKPOINT
checkpoint_completion_target = 0.9
wal_buffers = 16MB
min_wal_size = 1GB
max_wal_size = 4GB

# QUERY PLANNER
default_statistics_target = 100
random_page_cost = 1.1                  # Para SSD
effective_io_concurrency = 200          # Para SSD

# PARALLEL QUERIES
max_worker_processes = 8
max_parallel_workers_per_gather = 4
max_parallel_workers = 8
max_parallel_maintenance_workers = 4

# LOGGING
logging_collector = on
log_directory = 'log'
log_filename = 'postgresql-%Y-%m-%d_%H%M%S.log'
log_rotation_age = 1d
log_rotation_size = 100MB
log_line_prefix = '%t [%p]: [%l-1] user=%u,db=%d,app=%a,client=%h '
log_min_duration_statement = 1000       # Log queries > 1s

# AUTOVACUUM
autovacuum = on
autovacuum_max_workers = 3
autovacuum_naptime = 10s
```

**Reiniciar PostgreSQL:**
```bash
sudo systemctl restart postgresql
```

**Entregáveis:**
- [ ] PostgreSQL 15 instalado e configurado
- [ ] Banco `sisimagem` criado
- [ ] Extensões instaladas
- [ ] Performance tuning aplicado

---

## 🔄 FASE 3: Conversão de Schema (Semana 3-4)

### 3.1 Conversão Automática com ora2pg

```bash
# Exportar schema
ora2pg -t TABLE -c ora2pg.conf -o tables.sql
ora2pg -t CONSTRAINT -c ora2pg.conf -o constraints.sql
ora2pg -t INDEX -c ora2pg.conf -o indexes.sql
ora2pg -t SEQUENCE -c ora2pg.conf -o sequences.sql
ora2pg -t TRIGGER -c ora2pg.conf -o triggers.sql
ora2pg -t FUNCTION -c ora2pg.conf -o functions.sql
ora2pg -t PROCEDURE -c ora2pg.conf -o procedures.sql
ora2pg -t VIEW -c ora2pg.conf -o views.sql
```

### 3.2 Revisão e Ajustes Manuais

#### Exemplo: Conversão da Tabela TSRECORD

**Oracle (Original):**
```sql
CREATE TABLE TSRECORD (
  URI NUMBER PRIMARY KEY,
  RECORDNUMBER VARCHAR2(50) NOT NULL,
  RECORDTYPE NUMBER,
  CREATEDATE DATE DEFAULT SYSDATE,
  MODIFYDATE TIMESTAMP,
  CONTAINERURI NUMBER,
  RCCONTAINERURI VARCHAR2(255),
  FULLRECORDID VARCHAR2(100)
);

CREATE INDEX IDX_RECORDNUMBER ON TSRECORD(RECORDNUMBER);
CREATE INDEX IDX_RECORDTYPE ON TSRECORD(RECORDTYPE);
```

**PostgreSQL (Convertido e Otimizado):**
```sql
-- Tabela principal
CREATE TABLE ts_record (
  uri BIGSERIAL PRIMARY KEY,
  record_number VARCHAR(50) NOT NULL,
  record_type INTEGER,
  create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  modify_date TIMESTAMP,
  container_uri BIGINT,
  rc_container_uri VARCHAR(255),
  full_record_id VARCHAR(100),
  
  -- Metadata para auditoria
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices
CREATE INDEX idx_ts_record_number ON ts_record(record_number);
CREATE INDEX idx_ts_record_type ON ts_record(record_type);
CREATE INDEX idx_ts_record_create_date ON ts_record(create_date);
CREATE INDEX idx_ts_record_container ON ts_record(container_uri);

-- Índice para busca full-text (se necessário)
CREATE INDEX idx_ts_record_full_text ON ts_record 
  USING GIN(to_tsvector('portuguese', record_number || ' ' || COALESCE(full_record_id, '')));

-- Comentários para documentação
COMMENT ON TABLE ts_record IS 'Tabela principal de registros/documentos';
COMMENT ON COLUMN ts_record.uri IS 'Identificador único do registro';
COMMENT ON COLUMN ts_record.record_number IS 'Número do registro (formato: YYYY-NNNNN)';
COMMENT ON COLUMN ts_record.record_type IS 'Tipo do registro (1=PAPEM-41, 2=PAPEM-42, etc.)';
```

#### Exemplo: Conversão de Sequence

**Oracle:**
```sql
CREATE SEQUENCE TSRECORD_URI_SEQ
  START WITH 1
  INCREMENT BY 1
  NOCACHE;
```

**PostgreSQL (usando SERIAL):**
```sql
-- Já incluído na definição da tabela com BIGSERIAL
-- Mas se precisar criar manualmente:
CREATE SEQUENCE ts_record_uri_seq
  START WITH 1
  INCREMENT BY 1
  NO MAXVALUE
  CACHE 10;

-- Associar à coluna
ALTER TABLE ts_record ALTER COLUMN uri SET DEFAULT nextval('ts_record_uri_seq');
ALTER SEQUENCE ts_record_uri_seq OWNED BY ts_record.uri;
```

### 3.3 Conversão de Stored Procedures

#### Exemplo: Procedure de Validação de Usuário

**Oracle (PL/SQL):**
```sql
CREATE OR REPLACE PROCEDURE validar_usuario(
  p_login IN VARCHAR2,
  p_senha IN VARCHAR2,
  p_resultado OUT NUMBER
) AS
  v_count NUMBER;
BEGIN
  SELECT COUNT(*) INTO v_count
  FROM TSLOCATION
  WHERE login = p_login AND senha = p_senha;
  
  IF v_count > 0 THEN
    p_resultado := 1;
  ELSE
    p_resultado := 0;
  END IF;
END;
```

**PostgreSQL (PL/pgSQL):**
```sql
CREATE OR REPLACE FUNCTION validar_usuario(
  p_login VARCHAR,
  p_senha VARCHAR
) RETURNS INTEGER AS $$
DECLARE
  v_count INTEGER;
BEGIN
  SELECT COUNT(*) INTO v_count
  FROM ts_location
  WHERE login = p_login AND senha = p_senha;
  
  IF v_count > 0 THEN
    RETURN 1;
  ELSE
    RETURN 0;
  END IF;
END;
$$ LANGUAGE plpgsql;
```

**Melhor Abordagem (mover para aplicação):**
```typescript
// No Node.js com Prisma
async function validarUsuario(login: string, senhaHash: string): Promise<boolean> {
  const user = await prisma.tsLocation.findFirst({
    where: {
      login: login,
      senha: senhaHash
    }
  });
  
  return user !== null;
}
```

### 3.4 Importação do Schema

```bash
# Importar schema no PostgreSQL
psql -U sisimagem_user -d sisimagem -f tables.sql
psql -U sisimagem_user -d sisimagem -f sequences.sql
psql -U sisimagem_user -d sisimagem -f constraints.sql
psql -U sisimagem_user -d sisimagem -f indexes.sql
psql -U sisimagem_user -d sisimagem -f triggers.sql
psql -U sisimagem_user -d sisimagem -f functions.sql
psql -U sisimagem_user -d sisimagem -f views.sql

# Verificar objetos criados
psql -U sisimagem_user -d sisimagem -c "\dt"  # Tabelas
psql -U sisimagem_user -d sisimagem -c "\di"  # Índices
psql -U sisimagem_user -d sisimagem -c "\ds"  # Sequences
psql -U sisimagem_user -d sisimagem -c "\df"  # Functions
```

**Entregáveis:**
- [ ] Schema PostgreSQL completo
- [ ] Todas as tabelas criadas
- [ ] Índices criados
- [ ] Constraints aplicadas
- [ ] Functions/procedures convertidas

---

## 📦 FASE 4: Migração de Dados (Semana 4-5)

### 4.1 Estratégia de Migração

#### Opção A: Migração Completa (Downtime de 4h)

**Vantagens:**
- ✅ Mais simples
- ✅ Mais rápido
- ✅ Menos complexo

**Desvantagens:**
- ❌ Requer downtime
- ❌ Sem rollback fácil

**Processo:**
```bash
# 1. Parar aplicação
sudo systemctl stop sisimagem

# 2. Backup do Oracle
expdp usuario/senha@oracle DIRECTORY=backup_dir DUMPFILE=sisimagem_full.dmp FULL=Y

# 3. Migração com ora2pg
ora2pg -t COPY -c ora2pg.conf -o data.sql

# 4. Importação no PostgreSQL
psql -U sisimagem_user -d sisimagem -f data.sql

# 5. Validação
# (ver seção 4.3)

# 6. Iniciar aplicação com novo banco
sudo systemctl start sisimagem
```

---

#### Opção B: Migração Incremental (Zero Downtime) - RECOMENDADA

**Vantagens:**
- ✅ Zero downtime
- ✅ Rollback fácil
- ✅ Validação gradual

**Desvantagens:**
- ❌ Mais complexo
- ❌ Requer dual-write temporário

**Processo:**

**Fase 4.1.1: Snapshot Inicial**
```bash
# Criar snapshot dos dados Oracle
ora2pg -t COPY -c ora2pg.conf -o initial_snapshot.sql

# Importar no PostgreSQL
psql -U sisimagem_user -d sisimagem -f initial_snapshot.sql
```

**Fase 4.1.2: Dual-Write (1-2 semanas)**
```typescript
// Aplicação escreve em ambos os bancos
async function inserirDocumento(documento: Documento) {
  // 1. Escrever no Oracle (banco atual)
  await oracleDB.insert(documento);
  
  // 2. Escrever no PostgreSQL (banco novo)
  try {
    await postgresDB.insert(documento);
  } catch (error) {
    // Log erro mas não falha a operação
    logger.error('Erro ao escrever no PostgreSQL', error);
  }
}
```

**Fase 4.1.3: Sincronização de Deltas**
```bash
# Script para sincronizar mudanças
# Executar a cada hora durante dual-write

#!/bin/bash
# sync_deltas.sh

# Buscar registros modificados no Oracle desde última sync
LAST_SYNC=$(cat /tmp/last_sync_timestamp.txt)

ora2pg -t COPY -c ora2pg.conf \
  -w "modify_date > TO_TIMESTAMP('$LAST_SYNC', 'YYYY-MM-DD HH24:MI:SS')" \
  -o delta.sql

# Importar deltas no PostgreSQL
psql -U sisimagem_user -d sisimagem -f delta.sql

# Atualizar timestamp
date '+%Y-%m-%d %H:%M:%S' > /tmp/last_sync_timestamp.txt
```

**Fase 4.1.4: Cutover**
```bash
# 1. Última sincronização
./sync_deltas.sh

# 2. Parar aplicação (downtime mínimo: ~5 minutos)
sudo systemctl stop sisimagem

# 3. Sincronização final
./sync_deltas.sh

# 4. Validação rápida
psql -U sisimagem_user -d sisimagem -c "SELECT COUNT(*) FROM ts_record;"

# 5. Apontar aplicação para PostgreSQL
# (alterar connection string)

# 6. Iniciar aplicação
sudo systemctl start sisimagem

# 7. Monitorar logs
tail -f /var/log/sisimagem/app.log
```

---

### 4.2 Migração de Dados com ora2pg

```bash
# Configurar ora2pg para migração de dados
cat >> ora2pg.conf << 'EOF'
# Configurações de migração de dados
DATA_LIMIT          10000      # Registros por transação
PARALLEL_TABLES     4          # Tabelas em paralelo
JOBS                8          # Workers paralelos
DISABLE_SEQUENCE    0          # Manter sequences
DISABLE_COMMENT     0          # Manter comentários
FILE_PER_TABLE      1          # Um arquivo por tabela
EOF

# Migrar dados
ora2pg -t COPY -c ora2pg.conf

# Importar no PostgreSQL (tabela por tabela para controle)
for file in output/*.sql; do
  echo "Importando $file..."
  psql -U sisimagem_user -d sisimagem -f "$file"
  if [ $? -eq 0 ]; then
    echo "✓ $file importado com sucesso"
  else
    echo "✗ Erro ao importar $file"
    exit 1
  fi
done
```

### 4.3 Validação de Dados

```sql
-- Script de validação
-- validacao_migracao.sql

-- 1. Comparar contagens de registros
SELECT 'ts_record' AS tabela, COUNT(*) AS registros FROM ts_record
UNION ALL
SELECT 'ts_exfield', COUNT(*) FROM ts_exfield
UNION ALL
SELECT 'ts_exfieldv', COUNT(*) FROM ts_exfieldv
UNION ALL
SELECT 'ts_location', COUNT(*) FROM ts_location
UNION ALL
SELECT 'ts_jurgroup', COUNT(*) FROM ts_jurgroup;

-- 2. Verificar integridade referencial
SELECT 
  conname AS constraint_name,
  conrelid::regclass AS table_name,
  confrelid::regclass AS referenced_table
FROM pg_constraint
WHERE contype = 'f'
  AND connamespace = 'public'::regnamespace;

-- 3. Verificar sequences
SELECT 
  sequence_name,
  last_value,
  is_called
FROM information_schema.sequences
WHERE sequence_schema = 'public';

-- 4. Verificar índices
SELECT 
  schemaname,
  tablename,
  indexname,
  indexdef
FROM pg_indexes
WHERE schemaname = 'public'
ORDER BY tablename, indexname;

-- 5. Verificar dados nulos em campos NOT NULL
SELECT 
  table_name,
  column_name
FROM information_schema.columns
WHERE table_schema = 'public'
  AND is_nullable = 'NO'
  AND column_default IS NULL;
```

**Executar Validação:**
```bash
psql -U sisimagem_user -d sisimagem -f validacao_migracao.sql > validacao_resultado.txt

# Revisar resultados
cat validacao_resultado.txt
```

**Entregáveis:**
- [ ] Todos os dados migrados
- [ ] Validação de contagens OK
- [ ] Integridade referencial OK
- [ ] Sequences configuradas
- [ ] Relatório de validação

---

## ✅ FASE 5: Testes e Validação (Semana 5-6)

### 5.1 Testes Funcionais

```bash
# Checklist de testes funcionais
```

- [ ] **Login e Autenticação**
  - [ ] Login com usuário válido
  - [ ] Login com senha incorreta
  - [ ] Bloqueio após 5 tentativas
  - [ ] Troca de senha forçada

- [ ] **CRUD de Documentos**
  - [ ] Incluir documento
  - [ ] Pesquisar documento
  - [ ] Detalhar documento
  - [ ] Anexar PAPEM-41
  - [ ] Incluir resposta PAPEM-42

- [ ] **CRUD de Usuários**
  - [ ] Incluir usuário
  - [ ] Pesquisar usuário
  - [ ] Alterar usuário
  - [ ] Excluir usuário

- [ ] **Geração de IDs**
  - [ ] Buscar próximo RecordId
  - [ ] Formato correto (YYYY-NNNNN)
  - [ ] Sequencial anual

- [ ] **Upload de Arquivos**
  - [ ] Upload de PDF
  - [ ] Upload de imagem
  - [ ] Download de arquivo

### 5.2 Testes de Performance

```sql
-- Benchmark de queries críticas

-- Query 1: Busca de documentos
EXPLAIN ANALYZE
SELECT * FROM ts_record
WHERE record_number LIKE '2024-%'
  AND record_type = 1
ORDER BY create_date DESC
LIMIT 100;

-- Query 2: Busca com joins
EXPLAIN ANALYZE
SELECT 
  r.*,
  f.field_name,
  fv.field_value
FROM ts_record r
LEFT JOIN ts_exfieldv fv ON r.uri = fv.evobjecturi
LEFT JOIN ts_exfield f ON fv.evfielduri = f.uri
WHERE r.record_type = 1
LIMIT 100;

-- Query 3: Agregação
EXPLAIN ANALYZE
SELECT 
  record_type,
  COUNT(*) as total,
  MIN(create_date) as primeiro,
  MAX(create_date) as ultimo
FROM ts_record
GROUP BY record_type;
```

**Métricas Esperadas:**
- Queries simples: < 50ms
- Queries com joins: < 200ms
- Queries de agregação: < 500ms

### 5.3 Testes de Carga

```bash
# Instalar pgbench
sudo apt-get install postgresql-contrib

# Criar script de teste
cat > test_load.sql << 'EOF'
\set record_num random(1, 100000)
SELECT * FROM ts_record WHERE uri = :record_num;
EOF

# Executar teste de carga
pgbench -c 50 -j 4 -t 1000 -f test_load.sql sisimagem

# Métricas esperadas:
# - TPS (transactions per second): > 500
# - Latência média: < 50ms
# - Latência p95: < 200ms
```

### 5.4 Testes de Segurança

- [ ] **SQL Injection**
  - [ ] Testar inputs maliciosos
  - [ ] Verificar uso de PreparedStatements

- [ ] **Autenticação**
  - [ ] Testar força de senha
  - [ ] Testar hash de senha (bcrypt)

- [ ] **Autorização**
  - [ ] Testar acesso sem permissão
  - [ ] Testar RBAC

**Entregáveis:**
- [ ] Relatório de testes funcionais
- [ ] Relatório de performance
- [ ] Relatório de carga
- [ ] Relatório de segurança

---

## 🚀 FASE 6: Deploy e Go-Live (Semana 6)

### 6.1 Preparação de Produção

```bash
# 1. Backup completo do Oracle
expdp usuario/senha@oracle DIRECTORY=backup_dir DUMPFILE=sisimagem_final.dmp FULL=Y

# 2. Backup do PostgreSQL (vazio, apenas schema)
pg_dump -U sisimagem_user -d sisimagem -s > schema_backup.sql

# 3. Configurar backup automático do PostgreSQL
sudo nano /etc/cron.daily/pg_backup.sh
```

**Script de Backup:**
```bash
#!/bin/bash
# /etc/cron.daily/pg_backup.sh

BACKUP_DIR="/var/backups/postgresql"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup completo
pg_dump -U sisimagem_user -d sisimagem -F c -f "$BACKUP_DIR/sisimagem_$DATE.backup"

# Manter apenas últimos 7 dias
find $BACKUP_DIR -name "sisimagem_*.backup" -mtime +7 -delete

# Log
echo "[$DATE] Backup realizado com sucesso" >> /var/log/pg_backup.log
```

### 6.2 Checklist de Go-Live

**Pré-Go-Live:**
- [ ] Backup do Oracle realizado
- [ ] Backup do PostgreSQL realizado
- [ ] Validação de dados OK
- [ ] Testes funcionais OK
- [ ] Testes de performance OK
- [ ] Plano de rollback documentado

**Durante Go-Live:**
- [ ] Parar aplicação
- [ ] Sincronização final de dados
- [ ] Validação rápida
- [ ] Alterar connection string
- [ ] Iniciar aplicação
- [ ] Testes de smoke

**Pós-Go-Live:**
- [ ] Monitorar logs (primeiras 4 horas)
- [ ] Monitorar performance
- [ ] Validar operações críticas
- [ ] Registrar status da migração

### 6.3 Plano de Rollback

**Se algo der errado:**

```bash
# 1. Parar aplicação
sudo systemctl stop sisimagem

# 2. Reverter connection string para Oracle
# (editar arquivo de configuração)

# 3. Iniciar aplicação
sudo systemctl start sisimagem

# 4. Investigar problema
tail -f /var/log/sisimagem/app.log

# 5. Corrigir e tentar novamente
```

---

## 📊 Monitoramento Pós-Migração

### 6.4 Métricas a Monitorar

```sql
-- 1. Queries lentas
SELECT 
  query,
  calls,
  total_time,
  mean_time,
  max_time
FROM pg_stat_statements
ORDER BY mean_time DESC
LIMIT 20;

-- 2. Uso de índices
SELECT 
  schemaname,
  tablename,
  indexname,
  idx_scan,
  idx_tup_read,
  idx_tup_fetch
FROM pg_stat_user_indexes
ORDER BY idx_scan ASC;

-- 3. Tamanho das tabelas
SELECT 
  schemaname,
  tablename,
  pg_size_pretty(pg_total_relation_size(schemaname||'.'||tablename)) AS size
FROM pg_tables
WHERE schemaname = 'public'
ORDER BY pg_total_relation_size(schemaname||'.'||tablename) DESC;

-- 4. Conexões ativas
SELECT 
  datname,
  count(*) as connections
FROM pg_stat_activity
GROUP BY datname;
```

### 6.5 Alertas Recomendados

- 🔴 **Crítico**: Queries > 5s
- 🟡 **Warning**: Queries > 1s
- 🔴 **Crítico**: Conexões > 180 (de 200 max)
- 🟡 **Warning**: Disk usage > 80%
- 🔴 **Crítico**: Replication lag > 1min (se houver)

---

## ⚠️ Riscos e Mitigações

| Risco | Probabilidade | Impacto | Mitigação |
|-------|---------------|---------|-----------|
| Perda de dados | Baixa | Crítico | Backup completo + validação rigorosa |
| Downtime prolongado | Média | Alto | Migração incremental + rollback plan |
| Performance inferior | Média | Alto | Tuning + índices + cache |
| Incompatibilidades | Alta | Médio | Testes extensivos + reescrita de queries |
| Bugs em produção | Média | Alto | Testes E2E + período de monitoramento |

---

## 📅 Cronograma Detalhado

| Semana | Atividades |
|--------|-----------|
| 1 | Análise e inventário Oracle |
| 2 | Setup PostgreSQL + conversão schema |
| 3-4 | Migração de dados + validação |
| 5 | Testes funcionais e performance |
| 6 | Deploy e go-live |

---

## ✅ Conclusão

Esta migração Oracle → PostgreSQL é **viável e recomendada**, com os seguintes benefícios:

1. **🚀 Performance igual ou superior**
2. **🔒 Segurança moderna**
3. **🌐 Sem vendor lock-in**
4. **📈 Escalabilidade**

**Próximo Passo**: Iniciar Fase 1.
