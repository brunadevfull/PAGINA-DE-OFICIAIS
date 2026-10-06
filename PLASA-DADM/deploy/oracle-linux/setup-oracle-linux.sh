#!/usr/bin/env bash
# Script de instalação do PAPEM Digital — Oracle Linux 8/9
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
LARAVEL_DIR="$(cd "$SCRIPT_DIR/../.." && pwd)"
UPLOADS_DIR="/opt/papem/uploads"
WEB_USER="apache"
APACHE_SERVICE="httpd"
APACHE_CONF_DIR="/etc/httpd/conf.d"
FPM_POOL_DIR="/etc/php-fpm.d"
FPM_SERVICE="php-fpm"

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
info()  { echo -e "${GREEN}[INFO]${NC} $*"; }
warn()  { echo -e "${YELLOW}[WARN]${NC} $*"; }
error() { echo -e "${RED}[ERRO]${NC} $*"; exit 1; }

echo "======================================================"
echo "  PAPEM Digital — Instalação Oracle Linux 8/9"
echo "======================================================"

# ── Verificar OS ───────────────────────────────────────────
[ -f /etc/os-release ] && . /etc/os-release || error "Não foi possível detectar o sistema operacional."
[[ "${ID:-}" =~ ^(ol|rhel|centos|rocky|almalinux)$ ]] || error "Este script é para Oracle Linux/RHEL. OS detectado: ${ID:-unknown}"
info "Sistema detectado: $ID ${VERSION_ID:-}"

# ── PHP: instalar apenas se ausente ───────────────────────
PHP_REQUIRED_EXTS=(pdo_pgsql pgsql mbstring xml curl zip intl bcmath gd)

if command -v php &>/dev/null; then
    info "PHP já instalado: $(php -r 'echo PHP_VERSION;'). Pulando instalação."
    MISSING_EXTS=()
    for ext in "${PHP_REQUIRED_EXTS[@]}"; do
        php -m 2>/dev/null | grep -qi "^${ext}$" || MISSING_EXTS+=("$ext")
    done
    if [ ${#MISSING_EXTS[@]} -gt 0 ]; then
        error "Extensões PHP ausentes: ${MISSING_EXTS[*]}\nInstale-as primeiro: dnf install $(printf 'php-%s ' "${MISSING_EXTS[@]}")"
    else
        info "Todas as extensões PHP necessárias estão presentes."
    fi
else
    info "Instalando PHP via repositório Remi..."
    dnf install -y oracle-epel-release-el"$(rpm -E %rhel)" 2>/dev/null \
        || dnf install -y epel-release 2>/dev/null \
        || true
    RHEL_VER="$(rpm -E %rhel)"
    dnf install -y "https://rpms.remirepo.net/enterprise/remi-release-${RHEL_VER}.rpm" 2>/dev/null || true
    dnf module reset php -y 2>/dev/null || true
    dnf module enable php:remi-8.2 -y 2>/dev/null || true
    dnf install -y \
        php php-cli php-fpm php-pgsql php-mbstring \
        php-xml php-curl php-zip php-intl php-bcmath \
        php-gd php-opcache
fi

# ── Apache: instalar apenas se ausente ────────────────────
if command -v httpd &>/dev/null; then
    info "Apache (httpd) já instalado: $(httpd -v 2>&1 | head -1). Pulando instalação."
    if [ ! -f /etc/httpd/modules/mod_proxy_fcgi.so ]; then
        error "mod_proxy_fcgi.so não encontrado em /etc/httpd/modules/. Instale com: dnf install mod_proxy_fcgi"
    fi
else
    info "Instalando Apache (httpd)..."
    dnf install -y httpd mod_proxy_fcgi
fi

# ── Instalar Composer ──────────────────────────────────────
if ! command -v composer &>/dev/null; then
    info "Instalando Composer..."
    EXPECTED_CHECKSUM="$(curl -fsSL https://composer.github.io/installer.sig)"
    curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"
    [ -n "$EXPECTED_CHECKSUM" ] && [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ] \
        && error "Checksum do Composer inválido — download corrompido."
    php /tmp/composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer
    rm /tmp/composer-setup.php
fi

# ── PHP-FPM: pool dedicada ─────────────────────────────────
info "Configurando pool PHP-FPM para PAPEM..."
# Desabilita pool www padrão para não desperdiçar recursos
[ -f "$FPM_POOL_DIR/www.conf" ] && mv "$FPM_POOL_DIR/www.conf" "$FPM_POOL_DIR/www.conf.disabled"
cp "$SCRIPT_DIR/php-fpm-papem.conf" "$FPM_POOL_DIR/papem.conf"

# ── Apache: VirtualHost ────────────────────────────────────
info "Configurando Apache (httpd)..."
cp "$SCRIPT_DIR/apache-vhost.conf" "$APACHE_CONF_DIR/papem.conf"

# Habilitar mod_proxy_fcgi e mod_headers (já incluídos no httpd no OL, mas confirma)
grep -qr "LoadModule proxy_module" /etc/httpd/ || \
    echo "LoadModule proxy_module modules/mod_proxy.so" >> /etc/httpd/conf/httpd.conf
grep -qr "LoadModule proxy_fcgi_module" /etc/httpd/ || \
    echo "LoadModule proxy_fcgi_module modules/mod_proxy_fcgi.so" >> /etc/httpd/conf/httpd.conf

# ── Dependências PHP (Composer) ────────────────────────────
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
echo ""
read -rp "Pressione ENTER após configurar o banco de dados no .env..." _

# ── Validar conexão com PostgreSQL ────────────────────────
info "Validando conexão com PostgreSQL..."
php -r "
\$env = parse_ini_file('$LARAVEL_DIR/.env');
try {
    new PDO(
        'pgsql:host='.(\$env['DB_HOST']??'127.0.0.1').';port='.(\$env['DB_PORT']??'5432').';dbname='.(\$env['DB_DATABASE']??''),
        \$env['DB_USERNAME']??'',
        \$env['DB_PASSWORD']??''
    );
} catch (Exception \$e) {
    fwrite(STDERR, \$e->getMessage().PHP_EOL);
    exit(1);
}
" || error "Não foi possível conectar ao PostgreSQL. Verifique DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME e DB_PASSWORD no .env."

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

# ── SELinux ────────────────────────────────────────────────
if command -v getenforce &>/dev/null && [ "$(getenforce 2>/dev/null)" = "Enforcing" ]; then
    info "Configurando SELinux..."
    chcon -R -t httpd_sys_rw_content_t \
        "$LARAVEL_DIR/storage" \
        "$LARAVEL_DIR/bootstrap/cache" \
        "$UPLOADS_DIR"
    # Permite PHP-FPM/httpd conectar ao banco de dados via rede
    setsebool -P httpd_can_network_connect_db 1
    # Permite httpd se comunicar com socket PHP-FPM
    setsebool -P httpd_can_network_connect 1
fi

# ── Firewall ───────────────────────────────────────────────
if command -v firewall-cmd &>/dev/null; then
    info "Abrindo portas HTTP e HTTPS no firewalld..."
    firewall-cmd --permanent --add-service=http
    firewall-cmd --permanent --add-service=https
    firewall-cmd --reload
fi

# ── Iniciar serviços ───────────────────────────────────────
info "Iniciando PHP-FPM e Apache..."
systemctl enable --now "$FPM_SERVICE"
systemctl enable --now "$APACHE_SERVICE"
systemctl restart "$FPM_SERVICE"
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
echo "  Senha padrão     : (definida em ADMIN_BOOTSTRAP_PASSWORD no .env)"
echo ""
warn "Altere a senha padrão após o primeiro login!"
echo ""
echo "  Logs Apache : /var/log/httpd/papem-{error,access}.log"
echo "  Logs FPM    : /var/log/php-fpm/papem-slow.log"
echo ""
