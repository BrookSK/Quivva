# Quivva CRM + Chatbot (PHP MVC)

## Requisitos
- PHP >= 8.1
- MySQL 5.7+
- Composer

## Instalação
1. Clone o repositório e entre na pasta do projeto
2. Copie `.env.example` para `.env` e ajuste as variáveis (dev/prod)
3. Copie `config/config.php` se quiser personalizar mais algo (normalmente não precisa)
4. Crie o banco de dados e importe `database.sql`
5. Instale o autoload do Composer:
```bash
composer install
```
6. Aponte seu VirtualHost/Servidor para `public/` como DocumentRoot
7. Acesse `http://localhost/` (ou a URL configurada)

### Variáveis de ambiente (.env)
Arquivo: `.env` (ignorado pelo git via `.gitignore`). Exemplo de conteúdo (baseado em `.env.example`):

```
APP_ENV=development
QUIVVA_BASE_URL_DEV=/

QUIVVA_DB_HOST_DEV=127.0.0.1
QUIVVA_DB_PORT_DEV=3306
QUIVVA_DB_NAME_DEV=quivva
QUIVVA_DB_USER_DEV=root
QUIVVA_DB_PASS_DEV=

# Produção (definir no servidor)
# APP_ENV=production
# QUIVVA_BASE_URL_PROD=https://app.seudominio.com
# QUIVVA_DB_HOST_PROD=...
# QUIVVA_DB_PORT_PROD=3306
# QUIVVA_DB_NAME_PROD=...
# QUIVVA_DB_USER_PROD=...
# QUIVVA_DB_PASS_PROD=...

QUIVVA_CSRF_KEY=change_this_csrf_key
```

O carregamento do `.env` ocorre em `public/index.php` via `App\core\Env::load()`.

## Login inicial
- Email: `admin@acme.test`
- Senha padrão: `Admin@2025!`

Obs.: A senha é aplicada no banco via ferramenta one-time em `public/tools/set_admin_password.php`. Acesse com um token, por exemplo:

```
/tools/set_admin_password.php?password=Admin@2025!&token=SET_A_SECRET
```

Após o uso, exclua este arquivo por segurança.

## Estrutura MVC
- `app/core/` núcleo (roteamento, controller, model, auth)
- `app/controllers/` controladores
- `app/models/` modelos
- `app/views/` views com Bootstrap 5
- `public/` index e assets

## Webhook do Chatbot
- Endpoint: `POST /chat/webhook`
- Body JSON: `{ "lead_id": 1, "message": "oi" }`
- Regras:
  - contém "oi" -> "Olá! Como posso ajudar?"
  - contém "preço" -> "Você pode informar o produto ou serviço desejado?"

## Conectar WhatsApp (simulado)
- Menu: `WhatsApp`
- Página: `GET /whatsapp/connect`
- A página exibe um QR code (simulado). Clique em "Simular leitura do QR" para marcar o status como conectado.
- Endpoints auxiliares:
  - `GET /whatsapp/status` → retorna `{ connected: true|false }`
  - `GET /whatsapp/simulate` → marca conectado (simula o scan)
  - `GET /whatsapp/disconnect` → desconecta

## Construtor de Fluxos (drag & drop)
- Menu: `Fluxos`
- Listagem: `GET /flows/index`
- Criar: `GET /flows/create`
  - Adicione blocos de `Texto` e `Pergunta`, edite os conteúdos, arraste para reordenar e salve.
- Editar: `GET /flows/edit/{id}`
- Rodar no contato: `GET /flows/run/{id}`
  - Escolha um lead e execute. As mensagens do fluxo são registradas em `messages` para o lead selecionado.

## Rotas principais
- Auth: `/auth/login`, `/auth/register`, `/auth/logout`
- Dashboard: `/dashboard/index`
- Leads: `/leads/index|create|store|edit/{id}|update/{id}|delete/{id}`
- Pipelines: `/pipelines/index|create|store|edit/{id}|update/{id}|delete/{id}|reorder`
- Chat: `/chat/index/{lead_id}`, `/chat/send`, `/chat/poll/{lead_id}`, `/chat/webhook`, `/chat/sendMessage`
- WhatsApp: `/whatsapp/connect|status|simulate|disconnect`
- Fluxos: `/flows/index|create|store|edit/{id}|update/{id}|delete/{id}|run/{id}|execute`
## Segurança
- Senhas com `password_hash`
- SQL com PDO e prepared statements
- CSRF token em formulários (`Auth::csrfToken()`)

## Próximos passos
- JWT para API mobile
- Integração WhatsApp Cloud API
- Multi-tenant avançado e billing
