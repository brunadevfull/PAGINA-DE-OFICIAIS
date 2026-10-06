#!/bin/bash

# Nome do banco de dados (existente ou novo)
DB_NAME="paginadeoficiais"
DB_USER="pagoficial_rw"
DB_PASSWORD="Papem_RW@2024"

# Caminho do arquivo de backup
ARQUIVO_BACKUP="/backup/db/backup_paginadeoficiais_20250326_212058.sql"

# Restaurar o backup
PGPASSWORD="$DB_PASSWORD" psql -U "$DB_USER" -h localhost -d "$DB_NAME" < "$ARQUIVO_BACKUP"

# Verificar se deu certo
if [ $? -eq 0 ]; then
  echo "✅ Restore concluído com sucesso!"
else
  echo "❌ Erro ao restaurar o backup!"
fi
