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

 This document is a concise operational report and a prioritized roadmap to move the project toward a clean "v2" milestone.
npm ci


 - 2) Inventory (key files & locations)
npm run build   # or npm run dev for development

# 7. Optional: run dev helper

 Detailed scan (source directories scanned):

 - `app/` - models, controllers, providers, Filament admin provider and middleware. Key controllers: `PaymentController`, `DashboardController`, `ClientController`, `PricingController`, and `Auth/SocialAuthController`.
 - `routes/` - `web.php` (public landing, payment and debug endpoints), `api.php`, `auth.php`, `console.php`.
 - `resources/views/` - full Blade site including `home` (pricing loops), `pricing/index.blade.php`, layout templates and component partials.
 - `config/` - `mollie.php`, `filesystems.php`, `app.php`, `auth.php`, `database.php`, etc.
 - `database/` - migrations for users, purchases, pricing categories/items, projects, project_documents and several 2025-dated migrations.
 - `public/` - fonts, images, `v0` prebuilt Next static files and `v0` assets; `public/images/Brand Guidelines Imhotion.pdf` is present.
 - `v0src/` - a Next/React (TypeScript) frontend with components, hooks, and a `page.tsx` and `layout.tsx` we inspected.
 - `tests/` - Laravel PHPUnit tests for auth flows and basic features.
php artisan serve
```
--------------------------------------
1. Backup: commit current working tree or create a branch `imhotion_v2-prep` and push.
2. Audit env/secrets: centralize secrets in `.env` and ensure no keys in repo. Create `ADMIN_DEBUG_TOKEN` for debug endpoints.
3. Payment stability: review `PaymentController`, webhook handling, add idempotency checks and unit tests for payment flows.
4. Storage & upload tests: test project file uploads, downloads, and permissions; add automated tests for key flows (login, pricing page, purchase flow).
5. Frontend plan: decide whether to continue using Blade + Vite or migrate to `v0src` (Next). If migrating, document routes and rewrite auth flows or proxying.
7. Filament admin: verify admin resources and access control; add role-based tests.
7) Useful debug endpoints (token protected)

 Additional notes from controllers & models scanned:

 - `PaymentController` implements Mollie payment creation, webhook handling and return flow. It creates `Purchase` records, stores Mollie payment id, and credits users on successful payments. Webhook and return have idempotency protections (checks `credited` flag and uses DB transactions + row locking).
 - `ProjectDocumentController` handles uploads, renames and hiding of project documents storing files under `storage/app/public/project-docs/{project_id}` and creating `ProjectDocument` records.
 - `DashboardController` composes dashboard data (purchased days vs used days) and has a `downloadFiles` helper that can create ZIP archives under `storage/app/temp/`.
 - `SocialAuthController` supports Google OAuth and creates users with random passwords when needed.
 - Models `Purchase`, `Project`, `ProjectDocument`, `PricingCategory`, `PricingItem`, and `User` contain sensible casts and relations; `User` integrates with Filament and has a `canAccessPanel` method.
- /__proj_test -> quick DB test (counts Projects)
- /__debug/logs?token=... -> returns last N lines of `storage/logs/laravel.log` (requires ADMIN_DEBUG_TOKEN or header)

 Additional risks discovered:

 - Webhook security: Mollie webhooks use the Mollie library but the webhook endpoint accepts any Mollie id; consider validating Mollie signatures or using additional verification from headers.
 - Zip generation storage: `storage/app/temp/` is used for zip creation but not guaranteed to be cleaned up; could grow disk usage.
 - Debug endpoints: `/__debug/logs` and `/admin/__debug_pricing` rely on `ADMIN_DEBUG_TOKEN` — ensure this token is set and not guessable.
 - File name handling: uploaded files use the original filename in stored name; consider sanitizing filenames to prevent path traversal or encoding issues.
- `scripts/README_SAVE_CHAT.md` — instructions and examples.
- `imhotion_v2.md` — this file.

 Additions to roadmap based on scan:

 - Add webhook signature validation and stricter Mollie verification.
 - Implement cleanup for `storage/app/temp/` and a retention policy for uploaded packages.
 - Harden file upload sanitization and storage paths.
 - Add a small monitoring script to ensure debug token is set in production and log growth is tracked.

10) Notes & next steps

 I already created and switched to branch `imhotion_v2` and committed the artifacts. If you want, I can now implement B or C.
---------------------
- "read all files you can find on server" — I scanned key project files (`composer.json`, `package.json`, `routes/`, `resources/views/`) and inspected representative blade templates and routes. Full recursive reading is possible if you want a deeper automated inventory (it will take longer).
- "bring me rapport" — Done (this document).
- "create instruction file called imhotion_v2" — Done (`imhotion_v2.md` at project root).

End of report.
