#!/bin/bash

# Nome do banco e do novo usuário com boas práticas
DB_NAME="paginadeoficiais"
DB_USER="pagoficial_rw"
DB_PASSWORD="Papem_RW@2024"

# Diretório onde salvar os backups
BACKUP_DIR="/backup/db"
mkdir -p "$BACKUP_DIR"

# Nome do arquivo com data e hora
DATA=$(date +%Y%m%d_%H%M%S)
ARQUIVO_BACKUP="$BACKUP_DIR/backup_${DB_NAME}_$DATA.sql"

# Executar o backup com pg_dump usando a senha correta
PGPASSWORD="$DB_PASSWORD" pg_dump -U "$DB_USER" -h localhost "$DB_NAME" > "$ARQUIVO_BACKUP"

# Verificar se deu certo
if [ $? -eq 0 ]; then
  echo "✅ Backup realizado com sucesso: $ARQUIVO_BACKUP"
else
  echo "❌ Erro ao realizar o backup!"
fi
