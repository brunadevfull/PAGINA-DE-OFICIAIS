#!/usr/bin/env bash
# migrate-papem-to-dadm.sh — Migração zero-downtime PAPEM → DADM
# Atualiza um servidor em produção já configurado sem interromper o serviço.
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
APP_DIR="/var/www/html/dadm/dadm-laravel"

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
info()  { echo -e "${GREEN}[INFO]${NC} $*"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $*"; }
error() { echo -e "${RED}[ERRO]${NC} $*"; exit 1; }

echo "======================================================"
echo "       Migração PAPEM → DADM (zero-downtime)"
echo "======================================================"

# ── Detectar OS ────────────────────────────────────────────
if [ -f /etc/debian_version ]; then
  OS=debian
  FPM_POOL_DIR="/etc/php/8.2/fpm/pool.d"
  SOCKET_OLD="/run/php/php8.2-fpm-papem.sock"
  SOCKET_NEW="/run/php/php8.2-fpm-dadm.sock"
  FPM_CONF_SRC="$SCRIPT_DIR/php-fpm-dadm.conf"
  APACHE_CONF_DIR="/etc/apache2/sites-available"
  APACHE_CONF="$APACHE_CONF_DIR/papem.conf"
  APACHE_CONF_NEW="$APACHE_CONF_DIR/dadm.conf"
  FPM_SERVICE="php8.2-fpm"
  APACHE_SERVICE="apache2"
elif [ -f /etc/os-release ] && grep -qiE "^ID=(ol|rhel|centos|rocky|almalinux)" /etc/os-release; then
  OS=oracle
  FPM_POOL_DIR="/etc/php-fpm.d"
  SOCKET_OLD="/run/php-fpm/papem.sock"
  SOCKET_NEW="/run/php-fpm/dadm.sock"
  FPM_CONF_SRC="$SCRIPT_DIR/oracle-linux/php-fpm-dadm.conf"
  APACHE_CONF_DIR="/etc/httpd/conf.d"
  APACHE_CONF="$APACHE_CONF_DIR/papem.conf"
  APACHE_CONF_NEW="$APACHE_CONF_DIR/dadm.conf"
  FPM_SERVICE="php-fpm"
  APACHE_SERVICE="httpd"
else
  error "Sistema operacional não reconhecido. Suporte: Debian/Ubuntu ou Oracle Linux/RHEL."
fi

info "OS detectado: $OS"

# ── Verificar pré-requisitos ──────────────────────────────
[ -f "$FPM_POOL_DIR/papem.conf" ] || error "Pool papem não encontrado em $FPM_POOL_DIR/papem.conf"
[ -f "$APACHE_CONF" ]             || error "VirtualHost papem não encontrado em $APACHE_CONF"
[ -f "$FPM_CONF_SRC" ]            || error "Arquivo de pool dadm não encontrado em $FPM_CONF_SRC"
[ -d "$APP_DIR" ]                 || error "Diretório da aplicação não encontrado: $APP_DIR"

# ── Passo 1: Criar novo pool dadm ─────────────────────────
info "Criando pool PHP-FPM dadm..."
cp "$FPM_CONF_SRC" "$FPM_POOL_DIR/dadm.conf"
systemctl reload "$FPM_SERVICE"
info "PHP-FPM recarregado — ambos os sockets (papem e dadm) estão ativos."

# Aguarda o novo socket aparecer
WAIT=0
until [ -S "$SOCKET_NEW" ] || [ $WAIT -ge 10 ]; do
  sleep 1
  WAIT=$((WAIT+1))
done
[ -S "$SOCKET_NEW" ] || error "Socket $SOCKET_NEW não foi criado após reload do PHP-FPM."

# ── Passo 2: Atualizar VirtualHost Apache ────────────────
info "Atualizando VirtualHost Apache para apontar para socket dadm e novo caminho da app..."
sed \
  -e "s|php8.2-fpm-papem\.sock|php8.2-fpm-dadm.sock|g" \
  -e "s|php-fpm/papem\.sock|php-fpm/dadm.sock|g" \
  -e "s|papem\.marinha\.local|dadm.marinha.local|g" \
  -e "s|/opt/papem/papem-laravel|$APP_DIR|g" \
  -e "s|/opt/dadm/dadm-laravel|$APP_DIR|g" \
  -e "s|papem-error\.log|dadm-error.log|g" \
  -e "s|papem-access\.log|dadm-access.log|g" \
  "$APACHE_CONF" > "$APACHE_CONF_NEW"

if [ "$OS" = "debian" ]; then
  a2ensite dadm.conf
  a2dissite papem.conf 2>/dev/null || true
fi

systemctl reload "$APACHE_SERVICE"
info "Apache recarregado — tráfego agora vai para o pool dadm."

# ── Passo 3: Remover pool papem ──────────────────────────
info "Removendo pool papem antigo..."
rm "$FPM_POOL_DIR/papem.conf"
systemctl reload "$FPM_SERVICE"
info "Pool papem encerrado."

# ── Resultado ─────────────────────────────────────────────
echo ""
echo "======================================================"
echo -e "${GREEN}  Migração PAPEM → DADM concluída com sucesso!${NC}"
echo "======================================================"
echo ""
echo "  App dir       : $APP_DIR"
echo "  Socket ativo  : $SOCKET_NEW"
echo "  VirtualHost   : $APACHE_CONF_NEW"
echo "  Pool FPM      : $FPM_POOL_DIR/dadm.conf"
echo ""
warn "Atualize o DNS/hosts para apontar dadm.marinha.local se necessário."
echo ""
