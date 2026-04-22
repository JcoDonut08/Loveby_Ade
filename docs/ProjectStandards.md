# Project Coding Rules

Hard Boundaries — Never Break These

-Never rewrite or destabilize working, existing code. Apply targeted, surgical changes only.
-Never bundle fixes. One problem = one fix. Do not combine unrelated changes in a single commit or edit.
-Never leave partial fixes. If a bug is addressed, it must be fully resolved — not patched over.
-Never create giant files. Target 100–300 lines per file. Split anything larger into focused modules.
-Never skip validation. Every form input, API parameter, and database write must be validated.
-Never expose sensitive data. No credentials, tokens, or keys in code, comments, or commits.
-The result must always be production-ready. Aim for 10/10 quality on every task.


# Project Structure Standards

app/
├── Http/
│   ├── Controllers/        # One controller per resource. Thin controllers only.
│   ├── Requests/           # FormRequest classes for all validation
│   └── Middleware/         # Custom middleware here
├── Models/                 # Eloquent models — no business logic
├── Services/               # Business logic lives here
├── Repositories/           # Database query abstraction (optional but preferred)
└── Helpers/                # Pure utility functions only

resources/
├── views/
│   ├── layouts/            # Base layout files (app.blade.php, guest.blade.php)
│   ├── components/         # Reusable Blade components
│   └── pages/              # Page-level views organized by feature
├── css/
│   └── app.css             # Tailwind entry — @import 'tailwindcss' only
└── js/
    └── app.js              # JS entry point


# Laravel Rules

# Controllers

-Keep controllers thin — no business logic inside them.
-Each controller handles one resource only.
-Always use FormRequest classes for validation — never validate inside the controller method.
-Return consistent responses: views for web, JSON for API.

# Models

-Models are data definitions only — relationships, casts, fillable/guarded, scopes.
-No business logic in models. Move it to a Service class.
-Always define $fillable or $guarded. Never leave both empty.
-Always define casts for booleans, dates, and JSON columns.

# Services

-All business logic lives in app/Services/.
-One service per feature domain (e.g. PostService, AuthService).
-Services are injected via the constructor — never instantiated with new inside controllers.

# Routes

-Group routes by feature using Route::prefix() and Route::middleware().
-Use named routes everywhere. Never hardcode URLs in Blade or controllers.
-API routes go in routes/api.php. Web routes go in routes/web.php.

# Blade Views

-No PHP logic in Blade. Pass all data from the controller.
-Use @component / <x-component> for anything used more than once.
-All layouts go in resources/views/layouts/.
-All reusable UI pieces go in resources/views/components/.
-Every page extends a layout using @extends or <x-layout>.

# Tailwind CSS Rules

# General

-Utility-first always. No custom CSS unless Tailwind cannot do it.
-Never write inline style="" attributes. Use Tailwind classes.
-Do not use !important — ever.
-Keep app.css minimal. It should only contain @import 'tailwindcss' and custom theme tokens.

# Class Organization (order matters for readability) Follow this order when writing Tailwind classes on an element:

Layout → Flexbox/Grid → Spacing → Sizing → Typography → Colors → Borders → Effects → State

# Components

-Any UI pattern used more than once becomes a Blade component.
-Components live in resources/views/components/.
-Keep component files under 100 lines. Split complex components.

# Security Rules

-Always use Laravel's built-in auth never roll a custom auth system.
-Always sanitize and validate all input using FormRequest or $request->validate().
-Always use Eloquent or query builder never raw SQL with user input.
-Never store passwords in plain text. Always use bcrypt() or Hash::make().
-Protect all sensitive routes with the auth middleware.
-Never commit .env. It is gitignored by default keep it that way.

# Code Quality Rules

# Naming Conventions

-Controllers — PascalCase + Controller → PostController
-Models — PascalCase singular → Post, UserProfile
-Migrations — snake_case with timestamp → create_posts_table
-Routes (named) — dot.notation → posts.index, posts.store
-Blade views — snake_case → edit_profile.blade.php
-Components — kebab-case → <x-user-card>
-Variables — camelCase → $userPost, $isActive
-CSS classes — Tailwind only → text-gray-700 font-bold


# File Size Limits

-Controllers — 80–150 lines (max 200)
-Models — 50–100 lines (max 150)
-Services — 100–200 lines (max 300)
-Blade views — 50–150 lines (max 250)
-Components — 20–80 lines (max 120)

# Local Dev Commands Reference

# Start development
php artisan serve          # Run Laravel app at localhost:8000
npm run dev                # Run Vite dev server (live reload)
npm install                # For every pull

# Before pushing
npm run build              # Compile assets for production
php artisan config:clear   # Clear config cache
php artisan cache:clear    # Clear app cache
php artisan route:clear    # Clear route cache

# Database
php artisan migrate                      # Run new migrations
php artisan migrate:rollback             # Undo last migration
php artisan make:migration <name>        # Create new migration
php artisan make:model <name> -mrc       # Model + migration + controller + request

# Code generation
php artisan make:controller <name>Controller --resource
php artisan make:request Store<name>Request