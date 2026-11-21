# Loveby Ade

Loveby Ade is an academic team project: a dessert storefront and administration workspace built with Laravel, Blade, JavaScript, and MySQL. Customers can browse products, save favorites, manage a cart, place orders, and follow their order history. Administrators manage the catalog, orders, customers, promotions, reports, and audit activity.

**Status:** source available for local development. No public application demo has been verified. The features below describe the repository implementation; they are not a claim of production readiness.

## Features

### Customer storefront

- Product browsing, category filtering, product details, and search suggestions.
- Session-aware cart and favorites.
- Registration with email OTP, login, password recovery, and optional Google sign-in.
- Authenticated checkout with promotion-code validation.
- Order history, order confirmation, delivery confirmation, and printable receipts.
- Product reviews, replies, likes, profile updates, and in-app notifications.

### Administration

- Role-restricted dashboard and administrator account management.
- Product and stock management, including product images.
- Order processing and walk-in order entry.
- Customer lists, promotions, notification reads, analytics, and report exports.
- User audit logs.

## Technical structure

```text
Browser / Blade + JavaScript
          |
Laravel routes and middleware
          |
Controllers, form requests, and services
          |
Eloquent models / MySQL
```

Laravel renders the Blade interface and handles validation, authentication, authorization, and persistence. Checkout uses a database transaction, checks current product stock under a row lock, and stores the order and its item details.

| Layer | Technology |
| --- | --- |
| Server | PHP 8.2+, Laravel 12 |
| Interface | Blade, JavaScript, Tailwind CSS 4 |
| Assets | Vite 7 |
| Database | MySQL, as configured in `.env.example` |
| Optional identity provider | Laravel Socialite / Google OAuth |
| PDF output | Laravel Dompdf |
| Automated checks | Pest / PHPUnit; Laravel Pint |

## Local setup

Requirements: PHP 8.2 or newer with the extensions required by Laravel and MySQL, Composer, Node.js/npm compatible with the Vite 7 toolchain, and a local MySQL server.

### 1. Install dependencies

```powershell
git clone https://github.com/JcoDonut08/Loveby_Ade.git
cd Loveby_Ade
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
```

The copy command is for a fresh checkout. Keep an existing local `.env` when updating the project.

### 2. Configure the local database

Create a local MySQL database named `loveby_ade`, or choose another name and update `DB_DATABASE`. Set the `DB_HOST`, `DB_PORT`, `DB_USERNAME`, and `DB_PASSWORD` entries in `.env` for your own local server. The committed example uses the MySQL connection.

Run the repository migrations:

```powershell
php artisan migrate
```

The migrations create users, sessions, cache storage, OTP records, cart/favorite records, products, orders and order items, reviews, notifications, promotions, and audit logs.

To add the sample product catalog to a local database:

```powershell
php artisan db:seed --class=ProductSeeder
```

The full `DatabaseSeeder` additionally creates or updates a development administrator fixture. Review that file before using it; it is not a production account-provisioning workflow.

### 3. Start the application

Run the Laravel server:

```powershell
php artisan serve
```

In a second terminal, start the asset server:

```powershell
npm run dev
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

### Mail and optional Google sign-in

The example environment uses `MAIL_MAILER=log`, which writes local OTP mail to application logs rather than delivering email. Configure a supported mail transport to test actual delivery.

Google sign-in additionally needs `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, and a matching `GOOGLE_REDIRECT_URI`. The supplied local callback is `http://127.0.0.1:8000/auth/google/callback`.

Keep local credentials in `.env`; never commit the configured file.

## Checks

```powershell
composer test
npm run build
vendor\bin\pint --test
```

`composer test` clears configuration and runs the Laravel test suite. `phpunit.xml` configures an isolated in-memory SQLite database, array mail/session/cache drivers, and synchronous test queues. Enable the PHP SQLite extension before running those tests.

The repository includes feature tests for authentication, cart and favorites, checkout, reviews, orders, administrator access, catalog management, promotions, reports, and related interfaces. No current passing test count is claimed in this README.

## Project boundaries

- This is an academic application, with no verified public demo linked here.
- Recording a selected payment method on an order does not establish a payment-gateway integration.
- The source contains development fixtures and sample catalog data.
- Setup instructions were checked against the repository configuration and scripts; a clean installation and the checks above should be verified in the target development environment.

## Team

| Member | Reported project role |
| --- | --- |
| Jericho Paul Salvador | Team leader, lead full-stack developer, UI/UX designer, quality assurance |
| Ron Alexander Inocencio | Full-stack development, quality assurance, security support |
| Carl Vincent Ventura | Full-stack development, quality assurance, business flow |

These roles are recorded in the team's academic report. Module-by-module ownership is not inferred from them. The original report is preserved in [docs/contribution-report.md](docs/contribution-report.md).

## Source

[GitHub repository](https://github.com/JcoDonut08/Loveby_Ade)

