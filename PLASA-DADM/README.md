# PAPEM Digital — Backend Laravel

Backend PHP/Laravel 11 para o sistema de painel digital da PAPEM (Marinha do Brasil).
Substitui o backend Node.js/Express mantendo compatibilidade total com o frontend React.

## Stack

- **PHP 8.2+** + **Laravel 11**
- **PostgreSQL 16** (banco de dados existente, sem alterações de esquema)
- **Apache HTTPD** + **PHP-FPM** (substitui Nginx + Node.js)
- **SSE** via `response()->stream()` + `pg_get_notify()` para atualizações em tempo real
- **React 18** + **TypeScript** + **Vite** (SPA em `resources/react/`)
- **SunCalc** — cálculo astronômico de pôr do sol (sem tabela hardcoded, funciona para qualquer ano)

## Requisitos

- PHP 8.2+ (testado até 8.3) com extensões: `pdo_pgsql`, `pgsql`, `mbstring`, `xml`, `curl`, `zip`, `intl`, `bcmath`, `gd`
- Composer 2.x
- PostgreSQL 16
- Apache 2.4 com módulos: `proxy`, `proxy_fcgi`, `headers`, `rewrite`

## Instalação em produção

### Pré-requisito: PostgreSQL

O script de instalação **não instala o PostgreSQL**. Garanta que ele esteja rodando e acessível antes de continuar.

- **Ubuntu/Debian:** `apt install postgresql-16`
- **Oracle Linux 8/9:** `dnf install postgresql16-server && postgresql-16-setup initdb && systemctl enable --now postgresql-16`

Crie o banco e o usuário no psql:

```sql
CREATE USER papem WITH PASSWORD 'senha_aqui';
CREATE DATABASE marinha_papem OWNER papem;
```

---

### Ubuntu 22.04 / 24.04 e Oracle Linux 8/9

O script `deploy/setup.sh` detecta automaticamente o sistema operacional e funciona nos dois:

```bash
# 1. Clonar o repositório
git clone <repo> /opt/papem
cd /opt/papem

# 2. Rodar o setup (instala PHP 8.2, Apache e Composer se ausentes)
sudo bash deploy/setup.sh
```

O script detecta automaticamente o que já está instalado:
- PHP, Apache e Composer já presentes → pula instalação, só valida extensões
- PHP ou Apache ausentes → instala via repositório Remi (Oracle Linux) ou apt (Ubuntu)

O script faz pausa para você editar o `.env` com as credenciais do banco e **valida a conexão ao PostgreSQL antes de rodar as migrations** — se as credenciais estiverem erradas, o script aborta com mensagem de erro.

Arquivos de configuração gerados:
- `/etc/httpd/conf.d/papem.conf` ou `/etc/apache2/sites-enabled/papem.conf` — VirtualHost Apache
- `/etc/php-fpm.d/papem.conf` — pool PHP-FPM dedicada

---

### Após a instalação

O painel fica disponível em `http://<IP-do-servidor>/` assim que o script terminar.  
Login inicial: usuário `admin`, senha definida em `ADMIN_BOOTSTRAP_PASSWORD` no `.env`.

## Configuração mínima (.env)

O script cria `.env` a partir de `.env.example` automaticamente. Edite os valores antes de pressionar ENTER na pausa do script.

```ini
APP_KEY=                          # gerado automaticamente pelo script
APP_ENV=production
APP_DEBUG=false                   # NUNCA true em produção
APP_URL=http://papem.marinha.local

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=marinha_papem
DB_USERNAME=postgres
DB_PASSWORD=                      # obrigatório

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_TABLE=sessions
SESSION_SECURE_COOKIE=false       # mudar para true se habilitar HTTPS

# Senha do admin criada na primeira instalação — altere após o primeiro login
ADMIN_BOOTSTRAP_PASSWORD=

OPENWEATHER_API_KEY=              # chave da API OpenWeatherMap
SUNSET_LATITUDE=-22.9068
SUNSET_LONGITUDE=-43.1729
```

## Estrutura de diretórios de upload

Uploads ficam em `/opt/papem/uploads/` (fora do DocumentRoot):

```
uploads/
├── plasa/          # PDFs da PLASA
├── escala/         # PDFs de escalas
├── cardapio/       # PDFs de cardápio
├── bono/           # PDF do BONO
├── outros/         # Outros documentos
├── plasa-pages/    # Imagens das páginas da PLASA (por documento)
├── escala-cache/   # Cache de imagens de escala
└── cardapio-cache/ # Cache de imagens de cardápio
```

## Rotas da API

### Públicas (sem autenticação)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/health` | Status do sistema e banco |
| GET | `/api/temperature` | Temperatura atual (OpenWeatherMap) |
| GET | `/api/system-info` | Informações do servidor |
| GET | `/api/status` | Status público (usado pelas TVs) |
| GET | `/api/documents` | Lista documentos |
| GET | `/api/documents/stream` | SSE: atualizações de documentos |
| GET | `/api/documents/view-state` | Estado de visualização dos documentos |
| GET | `/api/notices` | Lista avisos |
| GET | `/api/duty-officers` | Oficial de serviço |
| GET | `/api/duty-officers/stream` | SSE: atualizações de escalas |
| GET | `/api/military-personnel` | Lista militares |
| POST | `/api/admin/login` | Autenticação (throttle: 5/min) |
| GET | `/api/admin/session` | Dados da sessão atual |
| GET | `/api/admin/check` | Verifica se sessão está ativa |
| POST | `/api/check-plasa-pages` | Verifica cache de páginas da PLASA |
| GET | `/api/check-escala-cache/{id}` | Verifica cache de escala |
| POST | `/api/save-escala-cache` | Salva cache de escala |
| GET | `/api/proxy-pdf` | Proxy para PDFs externos |
| POST | `/api/cache-plasa-page` | Cacheia página da PLASA (throttle: 30/min) |

### Protegidas (requer autenticação)

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/admin/logout` | Encerrar sessão |
| GET | `/api/admin/users` | Lista usuários admin |
| POST | `/api/admin/users` | Criar usuário admin |
| PUT | `/api/admin/users/{id}` | Atualizar usuário admin |
| POST | `/api/documents` | Criar documento |
| PUT | `/api/documents/{id}` | Atualizar documento |
| DELETE | `/api/documents/{id}` | Remover documento |
| POST | `/api/documents/view-state` | Salvar estado de visualização |
| POST | `/api/notices` | Criar aviso |
| PUT | `/api/notices/{id}` | Atualizar aviso |
| DELETE | `/api/notices/{id}` | Remover aviso |
| POST | `/api/military-personnel` | Criar militar |
| PUT | `/api/military-personnel/{id}` | Atualizar militar |
| DELETE | `/api/military-personnel/{id}` | Remover militar |
| PUT | `/api/duty-officers` | Atualizar oficial de serviço |
| POST | `/api/upload-pdf` | Upload de PDF |
| POST | `/api/upload-plasa-page` | Upload de página da PLASA |
| DELETE | `/api/upload/{type}/{file}` | Remover arquivo |
| GET | `/api/upload/list/{type}` | Listar arquivos por tipo |
| GET | `/api/upload/config` | Configuração de uploads |
| POST | `/api/upload/cleanup` | Limpar arquivos órfãos |
| PUT | `/api/upload/{type}/{file}/meta` | Atualizar metadados de arquivo |
| GET | `/api/system/status` | Status detalhado do sistema |
| POST | `/api/system/cache/clear` | Limpar cache |
| GET | `/api/system/logs` | Logs da aplicação |

## Segurança

- Senhas armazenadas com `bcrypt` via `Hash::make()`
- Hashes legados do Node.js (`scrypt`) não são verificados — apenas o admin padrão pode fazer login pela primeira vez (força upgrade para bcrypt)
- `APP_DEBUG=false` em produção — stack traces nunca expostos ao cliente
- Login limitado a 5 tentativas por minuto (`throttle:5,1`) contra brute-force
- Uploads validados por tipo MIME real (`mimetypes:application/pdf`), máximo 100 MB, nome sanitizado com `Str::slug()`
- Tipos de upload validados contra lista fechada
- `ServerTokens Prod` e `expose_php = Off` para ocultar versões

## SSE (Server-Sent Events)

SSE é implementado via `response()->stream()` com conexão direta ao PostgreSQL (`pg_connect`) e `pg_get_notify()` para receber eventos `LISTEN/NOTIFY`. Usa `stream_select()` com timeout de 1s para detectar notificações rapidamente sem busy-loop.

O PHP-FPM pool dedicado usa `request_terminate_timeout = 3600` — streams SSE são encerrados após 1 hora, evitando acúmulo de processos.

## Desenvolvimento local

```bash
# Dependências PHP
composer install

# Dependências JavaScript (React + SunCalc + Vite)
npm install

# Compile o frontend React
npm run build:react

# Suba o servidor
php artisan serve --port=8080
# Aplicação disponível em http://localhost:8080/
# API disponível em http://localhost:8080/api/
```

> Para recompilar o frontend após alterações em `resources/react/src/`, execute `npm run build:react` novamente.
