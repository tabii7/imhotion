Imhotion v2 - Inventory, findings and next steps
===============================================

This document is a concise operational report and a prioritized roadmap to move the project toward a clean "v2" milestone.

1) Quick summary
-----------------
- Project: Laravel (v12) app with Filament admin and a separate `v0src` frontend (Next/Vite).
- PHP requirement: ^8.2. Uses Filament, Laravel Socialite, Mollie integration, Breeze for auth and Vite/Tailwind on frontend.
- I added a helper script earlier: `scripts/save_chat.sh` which saves chat text to `chat_exports/`.

2) Inventory (key files & locations)
-----------------------------------
- Composer: `composer.json` (Laravel 12, Filament, mollie, socialite)
- Node: `package.json` at project root and `v0src/package.json` for the separate frontend.
- Routes: `routes/web.php`, `routes/api.php`, `routes/auth.php`, `routes/console.php` (web.php has many useful debug/health endpoints).
- Views: `resources/views/` (pricing, home, layouts, many components). Example: `resources/views/home.blade.php.bak-v0`, `resources/views/pricing/index.blade.php`.
- Public assets: `public/images/` (includes `Brand Guidelines Imhotion.pdf` at `public/images/Brand Guidelines Imhotion.pdf` and `background_imho.png` if present).
- App models/controllers: `app/Http/Controllers/`, `app/Models/` (Project, PricingCategory, PricingItem, Purchase, Delivery, Setting, User).
- Filament admin resources: `app/Filament/Admin/Resources/` (pricing resources referenced in routes).
- Scripts I added: `scripts/save_chat.sh`, `scripts/README_SAVE_CHAT.md`.

3) What I inspected and notable details
--------------------------------------
- `routes/web.php` includes:
  - Public landing route that loads active `PricingCategory` with items and renders `home` view.
  - Dashboard routes protected by `auth` middleware.
  - Payment endpoints (`/payment/create`, `/payment/return/{purchase}`, `/payment/webhook`) using `PaymentController`.
  - Several debug endpoints: `/__proj_test`, `/__debug/logs` (token-protected), `/__session_test`, `/admin/__debug_pricing`.
  - Custom login flow (uses `auth.simple-login` if present, otherwise Breeze `auth.login`).

- `composer.json` shows development tooling (Pint, Sail, Pail) and `post-create-project-cmd` runs migrations and DB sqlite creation by default.

- Frontend setup: project uses Vite + Tailwind + AlpineJS and a separate `v0src` Next app (likely experimental or new marketing site).

- Views: `home` blade contains theme toggle JS, pricing loops, and register links with plan ids; it's wired to PricingCategory->items structure.

4) Immediate risks & gaps
-------------------------
- Secrets/Env: Payment and debug endpoints are present; ensure `ADMIN_DEBUG_TOKEN`, Mollie keys and other secrets are set and not exposed.
- Payments: Verify `config/mollie.php` and `public/mollie.php` (there is a `public/mollie.php`) and review `PaymentController` for correct callback handling in production (webhook security, idempotency).
- Storage: Ensure `php artisan storage:link` has been run and `storage/` permissions allow web server access for uploaded project documents.
- Unused/legacy code: `v0src` appears to be a new frontend; ensure production routing and build steps reflect which frontend is active.

5) Short-term checklist to get a reproducible dev environment (local or server)
-----------------------------------------------------------------------------
Run from project root:

```bash
# 1. Install PHP deps
composer install --no-interaction --no-progress

# 2. Install node deps (root or v0src depending on which frontend you're working on)
npm ci

# 3. Copy env and generate key
cp .env.example .env
php artisan key:generate

# 4. Create sqlite (if using) or configure DB in .env and run migrations
php artisan migrate --force

# 5. Link storage for user uploads
php artisan storage:link

# 6. Build frontend for production or run dev servers
npm run build   # or npm run dev for development

# 7. Optional: run dev helper
php artisan serve
```

6) Recommended v2 roadmap (prioritized)
--------------------------------------
1. Backup: commit current working tree or create a branch `imhotion_v2-prep` and push.
2. Audit env/secrets: centralize secrets in `.env` and ensure no keys in repo. Create `ADMIN_DEBUG_TOKEN` for debug endpoints.
3. Payment stability: review `PaymentController`, webhook handling, add idempotency checks and unit tests for payment flows.
4. Storage & upload tests: test project file uploads, downloads, and permissions; add automated tests for key flows (login, pricing page, purchase flow).
5. Frontend plan: decide whether to continue using Blade + Vite or migrate to `v0src` (Next). If migrating, document routes and rewrite auth flows or proxying.
6. CI & deployment: add a simple CI job to run `composer install`, `npm ci`, `php artisan test`, `npm run build`, and deploy artifacts.
7. Filament admin: verify admin resources and access control; add role-based tests.

7) Useful debug endpoints (token protected)
-----------------------------------------
- /__proj_test -> quick DB test (counts Projects)
- /__debug/logs?token=... -> returns last N lines of `storage/logs/laravel.log` (requires ADMIN_DEBUG_TOKEN or header)
- /admin/__debug_pricing?token=... -> checks admin resource existence and auth

8) Files I created in this session
----------------------------------
- `scripts/save_chat.sh` — small script to save clipboard/chat text into `chat_exports/` (executable).
- `scripts/README_SAVE_CHAT.md` — instructions and examples.
- `imhotion_v2.md` — this file.

9) Recommended immediate fixes I can implement now (pick one)
----------------------------------------------------------
- A. Create a branch `imhotion_v2` and commit current changes + `scripts/` helpers.
- B. Add a minimal GitHub Actions CI workflow for tests & build.
- C. Add an `.env.example` check script and a small README for deploy steps.

10) Notes & next steps
----------------------
- Tell me which immediate fix from section 9 you want me to implement. I can create the branch and commit changes, or add a CI workflow and run quick tests. If you want a deep audit of controllers or to migrate the frontend, say so and I will scan those directories in more depth.

Requirements coverage
---------------------
- "read all files you can find on server" — I scanned key project files (`composer.json`, `package.json`, `routes/`, `resources/views/`) and inspected representative blade templates and routes. Full recursive reading is possible if you want a deeper automated inventory (it will take longer).
- "bring me rapport" — Done (this document).
- "create instruction file called imhotion_v2" — Done (`imhotion_v2.md` at project root).

End of report.
