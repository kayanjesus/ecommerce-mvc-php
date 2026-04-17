======================================================================
🇺🇸 ENGLISH VERSION: KIDS' CLOTHING E-COMMERCE – FINAL YEAR PROJECT
======================================================================

📌 PROJECT OVERVIEW
----------------------------------------------------------------------
This project consists of a Kids' Clothing E-Commerce System developed 
as a Final Year Project (TCC). The system enables product, user, and 
order management, along with payment simulation through PagSeguro 
integration in a development environment.

The project aimed to apply web development concepts, MVC architecture, 
database modeling, and payment API integration in a real-world 
e-commerce scenario.

🚀 SYSTEM FEATURES
----------------------------------------------------------------------
👥 USER FEATURES:
- User registration and authentication
- Product browsing and search
- Shopping cart management
- Checkout and order placement
- Payment simulation via PagSeguro

🔧 ADMIN FEATURES:
- Admin dashboard
- Product CRUD (Create, Read, Update, Delete)
- Product image upload
- Order management and tracking
- User management

🛠️ TECHNOLOGY STACK
----------------------------------------------------------------------
[BACKEND]
Technology        | Purpose
------------------|---------------------------------------------------
PHP 8.x           | Server-side programming language
Laravel 10.x      | MVC framework for application structure
MySQL             | Relational database management

[FRONTEND]
Technology        | Purpose
------------------|---------------------------------------------------
HTML5             | Page structure
CSS3              | Styling and responsive design
JavaScript        | Client-side interactivity
Bootstrap 5       | Responsive UI framework

[DEVELOPMENT TOOLS]
Tool              | Purpose
------------------|---------------------------------------------------
Node.js & NPM     | Frontend dependency management
Composer          | PHP dependency management
Git & GitHub      | Version control and collaboration
Ngrok             | Exposing local server for webhooks
PagSeguro API     | Payment gateway integration (Sandbox)

🏗️ SYSTEM ARCHITECTURE
----------------------------------------------------------------------
The system follows Laravel's MVC (Model-View-Controller) architecture:

┌─────────────────────────────────────────────────────┐
│                    Client Browser                    │
└─────────────────────┬───────────────────────────────┘
                      │ HTTP Request
                      ▼
┌─────────────────────────────────────────────────────┐
│                   Routes (web.php)                   │
│         Define URLs and map to Controllers           │
└─────────────────────┬───────────────────────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────┐
│                    Controller                        │
│    Handles requests, business logic validation      │
└──────────────┬──────────────────┬───────────────────┘
               │                  │
               ▼                  ▼
┌──────────────────────┐  ┌──────────────────────┐
│       Model          │  │        View          │
│  Database queries    │  │  UI Templates        │
│  Business rules      │  │  (Blade files)       │
└──────────────────────┘  └──────────────────────┘

[DIRECTORY STRUCTURE]
app/
 |-- Http/Controllers/     # Application logic
 |-- Http/Middleware/      # Request filters
 |-- Models/               # Database models and relationships
 |-- Services/             # External API integrations (PagSeguro)
routes/
 |-- web.php               # Web routes
resources/
 |-- views/                # Blade templates
 |-- css/js/               # Frontend assets
database/
 |-- migrations/           # Database schema version control

🗄️ DATABASE DESIGN
----------------------------------------------------------------------
CORE TABLES:
- users: Customer and admin accounts
- products: Product catalog
- categories: Product categorization
- orders: Order headers
- order_items: Individual items per order
- payments: Payment transaction records

ENTITY-RELATIONSHIP DIAGRAM:
users (1) ------- (n) orders
orders (1) ------ (n) order_items
order_items (n) - (1) products
products (n) ---- (1) categories
orders (1) ------ (1) payments

💳 PAGSEGURO INTEGRATION
----------------------------------------------------------------------
The system integrates with PagSeguro's API in Sandbox mode.

PAYMENT FLOW:
User -> System -> PagSeguro API -> Ngrok Tunnel -> Webhook

WHY NGROK?
Since PagSeguro requires a public URL to send payment notifications 
(webhooks), Ngrok was used during development to expose the local 
Laravel server (port 8000) to the internet.

⚙️ INSTALLATION & SETUP
----------------------------------------------------------------------
1. Clone Repository: git clone [URL]
2. Install PHP Deps: composer install
3. Install JS Deps: npm install
4. Environment: cp .env.example .env && php artisan key:generate

[ENVIRONMENT CONFIG (.env)]
DB_CONNECTION=mysql
DB_DATABASE=your_database
PAGSEGURO_EMAIL=your_sandbox_email
PAGSEGURO_TOKEN=your_sandbox_token

5. Run Migrations: php artisan migrate
6. Compile Assets: npm run dev
7. Start Server: php artisan serve
8. Start Ngrok: ngrok http 8000

📄 ADDITIONAL DOCUMENTATION (/docs) Note: Some of the documentation was prepared before the system was fully finalized, so some implementation details, structures, or functionality may differ from the final version of the project. Documentation should be considered as a basis for analysis, modeling and planning of the system, and there may be small discrepancies in relation to the current code.

🎯 ACADEMIC OBJECTIVES
----------------------------------------------------------------------
- Web Development (Complete e-commerce building)
- Laravel Framework (MVC and best practices)
- Database Modeling (Relational schemas)
- API Integration (External payment gateway)
- E-commerce Logic (Cart, order flow)
- Software Documentation (Technical writing)

🧪 TESTING THE APPLICATION
----------------------------------------------------------------------
ADMIN ACCESS:
Set access_level = admin in the database or run:
php artisan db:seed --class=AdminUserSeeder

PAGSEGURO SANDBOX CARDS:
- Visa: 4111111111111111
- Mastercard: 5555555555554444

👨‍💻 AUTHOR
----------------------------------------------------------------------
KAYAN DA SILVA JESUS 
ISABELLA MURAKAMI ROCHA
RAÍSSA NASCIMENTO MORAES
LEONARDO PEREIRA BRAGA 
LUCAS VINÍCIUS SANTOS ROCHA 

Institution: Etec Jardim Ângela
Course: DESENVOLVIMENTO DE SISTEMA

📌 IMPORTANT NOTES
----------------------------------------------------------------------
⚠️ ACADEMIC PROJECT ONLY: Not recommended for production without 
security audits and proper infrastructure.
======================================================================





















