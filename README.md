======================================================================
🇺🇸 ENGLISH VERSION: KIDS' CLOTHING E-COMMERCE – FINAL YEAR PROJECT
======================================================================
*(🇧🇷 Versão em português: [Leia-me.md](./Leia-me.md))*

📌 PROJECT OVERVIEW
----------------------------------------------------------------------
This project is a Kids' Clothing E-Commerce System developed as a
Final Year Project (TCC). It covers product, user, and order
management, Google social login, and payment processing through
PagSeguro's API (Sandbox environment).

The project aimed to apply web development concepts, MVC architecture,
database modeling, authentication (traditional + OAuth), and payment
API integration in a real-world e-commerce scenario.

📸 SCREENSHOTS
----------------------------------------------------------------------
| Home | Product page |
|---|---|
| ![Home page](./docs/screenshots/home.png) | ![Product page](./docs/screenshots/produto.png) |

| Cart / Checkout | Product registration (admin) |
|---|---|
| ![Cart](./docs/screenshots/carrinho.png) | ![Admin product form](./docs/screenshots/cdt-adm.png) |

| Admin dashboard | Sales panel |
|---|---|
| ![Admin dashboard](./docs/screenshots/adm-dashboard.png) | ![Sales panel](./docs/screenshots/adm-vendas.png) |

🚀 SYSTEM FEATURES
----------------------------------------------------------------------
👥 USER FEATURES:
- User registration and authentication (email/password)
- Social login with Google (OAuth via Laravel Socialite)
- Email verification
- Product browsing and search
- Shopping cart management
- Checkout and order placement
- Payment via PagSeguro (PIX, card, boleto)

🔧 ADMIN FEATURES:
- Admin dashboard with sales stats and recent orders
- Product CRUD (Create, Read, Update, Delete)
- Product image upload (auto-converted to WebP)
- Order management and tracking
- User management
- Sales panel with monthly revenue chart

🛠️ TECHNOLOGY STACK
----------------------------------------------------------------------
[BACKEND]
Technology              | Purpose
------------------------|-----------------------------------------------
PHP 8.1+                | Server-side programming language
Laravel 10.x            | MVC framework for application structure
MySQL                   | Relational database management
Laravel Breeze          | Authentication scaffolding
Laravel Socialite       | Google OAuth login
darryldecode/cart       | Shopping cart management

[FRONTEND]
Technology              | Purpose
------------------------|-----------------------------------------------
HTML5 / CSS3            | Page structure and styling
JavaScript              | Client-side interactivity
Blade                   | Server-side templating

[DEVELOPMENT TOOLS]
Tool                    | Purpose
------------------------|-----------------------------------------------
Node.js & NPM           | Frontend dependency management
Composer                | PHP dependency management
Git & GitHub            | Version control
Ngrok                   | Exposing local server for PagSeguro webhooks
PagSeguro API (V4)      | Payment gateway integration (Sandbox)
Brevo (SMTP)            | Transactional email delivery

🏗️ SYSTEM ARCHITECTURE
----------------------------------------------------------------------
The system follows Laravel's MVC (Model-View-Controller) architecture:

```
Client Browser
      │ HTTP Request
      ▼
Routes (web.php / auth.php)
      │
      ▼
Controller ──► Model (Eloquent / MySQL)
      │
      ▼
   View (Blade)
```

[DIRECTORY STRUCTURE]
```
app/
 |-- Http/Controllers/     # Application logic
 |-- Http/Controllers/Auth # Login, register, Google OAuth
 |-- Http/Middleware/      # Request filters
 |-- Models/               # Database models and relationships
 |-- Services/             # External API integrations (PagSeguro)
 |-- Support/              # Small shared helpers (PagSeguro URL resolver)
routes/
 |-- web.php               # Web routes
 |-- auth.php              # Authentication routes
resources/
 |-- views/                # Blade templates
 |-- css/js/               # Frontend assets
database/
 |-- migrations/           # Database schema version control
 |-- seeders/              # Sample data (categories, sizes, colors)
```

🗄️ DATABASE DESIGN
----------------------------------------------------------------------
CORE TABLES (Portuguese names, as used in the actual schema):
- `users`: Customer and admin accounts (includes Google OAuth fields)
- `produtos`: Product catalog
- `categorias`: Product categories
- `pedidos`: Order headers
- `pedidos_itens`: Individual items per order
- `pagamentos`: Payment transaction records
- `reembolsos`: Refund records

ENTITY-RELATIONSHIP DIAGRAM:
```
users (1) ------- (n) pedidos
pedidos (1) ------ (n) pedidos_itens
pedidos_itens (n) - (1) produtos
produtos (n) ---- (n) categorias
pedidos (1) ------ (1) pagamentos
```

💳 PAGSEGURO INTEGRATION
----------------------------------------------------------------------
The system integrates with PagSeguro's **Orders API (V4)** in Sandbox
mode, supporting PIX payments with automatic QR code generation.

PAYMENT FLOW:
```
User → System → PagSeguro Orders API → Webhook (via Ngrok) → Order status update
```

WHY NGROK?
Since PagSeguro needs a public URL to send payment notifications
(webhooks) about status changes, Ngrok is used during local development
to expose the Laravel server (port 8000) to the internet.

⚙️ INSTALLATION & SETUP
----------------------------------------------------------------------
1. Clone the repository: `git clone [URL]`
2. Install PHP dependencies: `composer install`
3. Install JS dependencies: `npm install`
4. Set up the environment: `cp .env.example .env && php artisan key:generate`
5. Fill in the `.env` file (see comments in `.env.example` for what each
   variable is for — database, SMTP, Google OAuth, PagSeguro)
6. Run migrations: `php artisan migrate`
7. (Optional) Seed sample data: `php artisan db:seed`
8. Compile assets: `npm run dev`
9. Start the server: `php artisan serve`
10. Start Ngrok (only needed to test PagSeguro webhooks locally):
    `ngrok http 8000`, then set `WEBHOOK_URL` in `.env` to the Ngrok URL

🧪 TESTING THE APPLICATION
----------------------------------------------------------------------
ADMIN ACCESS:
Set `access_level = admin` on a user row in the database.

PAGSEGURO SANDBOX TEST CARDS:
- Visa: 4111111111111111
- Mastercard: 5555555555554444

📄 ADDITIONAL DOCUMENTATION
----------------------------------------------------------------------
Some documentation under `/docs` was written before the system was
fully finalized, so a few implementation details may differ slightly
from the current code. Treat it as a reference for the overall design,
not as an exact spec of the final version.

🎯 ACADEMIC OBJECTIVES
----------------------------------------------------------------------
- Web Development (full e-commerce build)
- Laravel Framework (MVC and best practices)
- Database Modeling (relational schemas)
- Authentication (traditional + OAuth 2.0)
- API Integration (external payment gateway)
- E-commerce Logic (cart, checkout, order flow)
- Software Documentation (technical writing)

👨‍💻 AUTHORS
----------------------------------------------------------------------
KAYAN DA SILVA JESUS;
ISABELLA MURAKAMI ROCHA;
RAÍSSA NASCIMENTO MORAES;
LEONARDO PEREIRA BRAGA;
LUCAS VINÍCIUS SANTOS ROCHA;

Institution: Etec Jardim Ângela
Course: Systems Development

📌 IMPORTANT NOTES
----------------------------------------------------------------------
⚠️ ACADEMIC PROJECT ONLY: Not recommended for production without a
proper security audit and infrastructure hardening.
======================================================================
