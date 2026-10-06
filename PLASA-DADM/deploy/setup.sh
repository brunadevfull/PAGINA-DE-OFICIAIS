#!/usr/bin/env bash
# Script de instalação do PAPEM Digital — Laravel + Apache
# Suporte: Ubuntu 22.04/24.04 e Oracle Linux 8/9 (RHEL compatível)
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
LARAVEL_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
UPLOADS_DIR="/opt/papem/uploads"

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
info()  { echo -e "${GREEN}[INFO]${NC} $*"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $*"; }
error() { echo -e "${RED}[ERRO]${NC} $*"; exit 1; }

echo "======================================================"
echo "       PAPEM Digital — Instalação Automática"
echo "======================================================"

# ── Detectar OS ────────────────────────────────────────────
[ -f /etc/os-release ] && . /etc/os-release || error "Não foi possível detectar o sistema operacional."
OS_ID="${ID:-unknown}"
info "Sistema detectado: $OS_ID ${VERSION_ID:-}"

# ── Instalar dependências ──────────────────────────────────
if [[ "$OS_ID" =~ ^(ubuntu|debian)$ ]]; then
    info "Instalando PHP + Apache (Ubuntu/Debian)..."
    apt-get update -qq
    apt-get install -y software-properties-common curl
    add-apt-repository ppa:ondrej/php -y 2>/dev/null || true
    apt-get update -qq
    apt-get install -y \
        php8.2 php8.2-cli php8.2-pgsql php8.2-mbstring \
        php8.2-xml php8.2-curl php8.2-zip php8.2-intl php8.2-bcmath \
        php8.2-gd apache2 composer
    a2enmod rewrite headers
    APACHE_SERVICE="apache2"
    APACHE_CONF_DIR="/etc/apache2/sites-available"
    WEB_USER="www-data"

elif [[ "$OS_ID" =~ ^(ol|rhel|centos|rocky|almalinux)$ ]]; then
    info "Instalando PHP + Apache (Oracle Linux/RHEL)..."
    dnf install -y epel-release 2>/dev/null || true
    dnf install -y "https://rpms.remirepo.net/enterprise/remi-release-$(rpm -E %rhel).rpm" 2>/dev/null || true
    dnf module reset php -y 2>/dev/null || true
    dnf module enable php:remi-8.2 -y 2>/dev/null || true
    dnf install -y \
        php php-cli php-pgsql php-mbstring \
        php-xml php-curl php-zip php-intl php-bcmath \
        php-gd httpd
    EXPECTED_CHECKSUM="$(curl -fsSL https://composer.github.io/installer.sig)"
    curl -fsSL https://getcomposer.org/installer -o composer-setup.php
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"
    [ -n "$EXPECTED_CHECKSUM" ] && [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ] && { error "Checksum do Composer inválido — download corrompido."; }
    php composer-setup.php --quiet
    rm composer-setup.php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
    APACHE_SERVICE="httpd"
    APACHE_CONF_DIR="/etc/httpd/conf.d"
    WEB_USER="apache"
else
    error "Sistema não suportado: $OS_ID. Use Ubuntu, Debian ou Oracle Linux/RHEL."
fi

info "PHP e Apache instalados."

# ── Instalar dependências PHP ──────────────────────────────
info "Instalando dependências PHP via Composer..."
cd "$LARAVEL_DIR"
composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction

# ── Configurar .env ────────────────────────────────────────
if [ ! -f "$LARAVEL_DIR/.env" ]; then
    cp "$LARAVEL_DIR/.env.example" "$LARAVEL_DIR/.env"
    info "Arquivo .env criado."
fi

echo ""
warn "Configure o banco de dados no .env antes de continuar:"
echo "  Arquivo  : $LARAVEL_DIR/.env"
echo "  Variáveis: DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD"
echo ""
echo "  Credenciais admin já configuradas no .env (ADMIN_BOOTSTRAP_PASSWORD)."
echo "  Altere a senha padrão no .env se desejar outra antes de continuar."
echo ""
read -rp "Pressione ENTER após configurar o banco de dados no .env..." _

# ── Gerar chave e migrations ───────────────────────────────
info "Gerando chave da aplicação..."
php artisan key:generate --force

info "Criando tabelas no banco de dados..."
php artisan migrate --force --seed

# ── Permissões ─────────────────────────────────────────────
info "Configurando permissões..."
chown -R "$WEB_USER:$WEB_USER" "$LARAVEL_DIR/storage" "$LARAVEL_DIR/bootstrap/cache"
chmod -R 775 "$LARAVEL_DIR/storage" "$LARAVEL_DIR/bootstrap/cache"

# ── Diretórios de upload ───────────────────────────────────
info "Criando diretórios de upload em $UPLOADS_DIR..."
mkdir -p "$UPLOADS_DIR"/{plasa,escala,cardapio,outros,bono,plasa-pages,escala-cache,cardapio-cache}
chown -R "$WEB_USER:$WEB_USER" "$UPLOADS_DIR"

# ── Otimizações Laravel ────────────────────────────────────
info "Otimizando para produção..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── Configurar VirtualHost Apache ─────────────────────────
info "Configurando Apache..."
cat > "$APACHE_CONF_DIR/papem.conf" <<VHOST
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot $LARAVEL_DIR/public

    <Directory $LARAVEL_DIR/public>
        AllowOverride All
        Require all granted
        Options -Indexes
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/papem-error.log
    CustomLog \${APACHE_LOG_DIR}/papem-access.log combined
</VirtualHost>
VHOST

if [[ "$OS_ID" =~ ^(ubuntu|debian)$ ]]; then
    a2ensite papem.conf
    a2dissite 000-default.conf 2>/dev/null || true
fi

# ── SELinux (Oracle Linux/RHEL) ────────────────────────────
if command -v getenforce &>/dev/null && [ "$(getenforce 2>/dev/null)" = "Enforcing" ]; then
    info "Configurando SELinux..."
    chcon -R -t httpd_sys_rw_content_t "$LARAVEL_DIR/storage" "$LARAVEL_DIR/bootstrap/cache" "$UPLOADS_DIR"
    setsebool -P httpd_can_network_connect_db 1
fi

# ── Firewall (Oracle Linux/RHEL) ──────────────────────────
if command -v firewall-cmd &>/dev/null; then
    info "Abrindo porta 80 no firewall..."
    firewall-cmd --permanent --add-service=http &>/dev/null || true
    firewall-cmd --reload &>/dev/null || true
fi

# ── Iniciar Apache ─────────────────────────────────────────
info "Iniciando Apache..."
systemctl enable "$APACHE_SERVICE"
systemctl restart "$APACHE_SERVICE"

# ── Resultado ──────────────────────────────────────────────
SERVER_IP=$(hostname -I 2>/dev/null | awk '{print $1}' || echo "localhost")
echo ""
echo "======================================================"
echo -e "${GREEN}  PAPEM Digital instalado com sucesso!${NC}"
echo "======================================================"
echo ""
echo "  Acesso principal : http://$SERVER_IP/"
echo "  Painel admin     : http://$SERVER_IP/admin"
echo "  Usuário padrão   : admin"
echo "  Senha padrão     : (a que você definiu em ADMIN_BOOTSTRAP_PASSWORD no .env)"
echo ""
warn "Altere a senha padrão após o primeiro login!"
echo ""
