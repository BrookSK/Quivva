# Quivva CRM + Chatbot (PHP MVC)

## Requisitos
- PHP >= 8.1
- MySQL 5.7+
- Composer

## Instalação
1. Clone o repositório e entre na pasta do projeto
2. Copie `config/config.php` e ajuste as credenciais de banco
3. Crie o banco de dados e importe `database.sql`
4. Instale o autoload do Composer:
```bash
composer install
```
5. Aponte seu VirtualHost/Servidor para `public/` como DocumentRoot
6. Acesse `http://localhost/` (ou a URL configurada)

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
