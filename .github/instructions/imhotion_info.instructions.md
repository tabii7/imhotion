# Imhotion — Design & Implementation Instructions

This file collects the brand assets, fonts, colors, UI rules and step-by-step guidance for implementers and AI agents working on the Imhotion codebase.

## Assets (paths)
- Logo: `public/images/imhotion.jpg` (use `asset('images/imhotion.jpg')` in Blade)
- Brand PDF: `public/images/Brand Guidelines Imhotion.pdf`
- Fonts (BrittiSans family): place under `public/fonts/`
  - `BrittiSansRegular.ttf` (400)
  - `BrittiSansMedium.ttf` (500)
  - `BrittiSansSemibold.ttf` (600)
  - `BrittiSansBold.ttf` (700)

Store images at `public/images/` and reference with Laravel's `asset()` helper. Keep images optimized (webp or compressed JPG) and under ~300 KB when possible.

## Colors & Typography
- Primary: #0066ff
- Primary light: #99c2ff
- Background light: #f2f7ff
- Midnight / Dark: #001f4c

Typography
- Primary brand font: `BrittiSans` (loaded via @font-face in Blade or app CSS)
- Fallbacks: system-ui, -apple-system, sans-serif

Define CSS variables in a global stylesheet (recommended `resources/css/app.css` or Filament theme):

:root {
  --brand-primary: #0066ff;
  --brand-primary-200: #99c2ff;
  --brand-bg: #f2f7ff;
  --brand-dark: #001f4c;
  --font-sans: 'BrittiSans', system-ui, -apple-system, sans-serif;
}

## UI Principles (high level)
- Minimalistic, centered layouts for public/customer pages.
- Filament admin uses compact, functional interfaces. Prefer Filament form components and tables for CRUD.
- Use subtle gradients and soft rounded corners for cards; keep contrast high for accessibility.
- Avoid heavy decorative elements in admin views; keep focus on content and quick actions.

## Accessibility
- All images must have `alt` attributes.
- Use semantic HTML for forms and headings.
- Maintain WCAG contrast ratios for text over background.

## Filament-specific guidelines
- Admin panel path: `/admin` (registered in `app/Providers/Filament/AdminPanelProvider.php`).
- Place admin-only resources under `app/Filament/Admin/Resources` and use resource discovery to auto-discover.
- Use `expandableColumn()` on main table rows if you want inline details; use `modalContentView('filament.pricing.row-details')` to display custom item lists.
- When creating relation managers for items, use Filament `Checkbox` components for boolean options (gift box, project files, weekends included) so the admin UI reflects yes/no clearly.

## Frontend patterns (Alpine.js + Blade)
- Keep interactive behaviours lightweight with Alpine.js and small fetch/ajax operations for inline edits.
- For inline rename/hide operations use POST with `X-CSRF-TOKEN` and update UI on success.

## Database & Models
- Models: `PricingCategory` (hasMany `PricingItem`), `PricingItem` (belongsTo `PricingCategory`).
- Migrations: store boolean flags for features: `has_gift_box`, `has_project_files`, `has_weekends_included`.

## Implementation steps — login/public pages
1. Place brand assets in `public/images/` and `public/fonts/` as specified above.
2. Load fonts: add `@font-face` rules in `resources/css/app.css` or inline in critical blade (login) using `url(asset('fonts/..'))`.
3. Add CSS variables and use them across components.
4. Update `resources/views/auth/simple-login.blade.php` to use `asset()` for logo and system font stack — this file follows the brand layout.

## Implementation steps — Filament admin
1. Add Filament Resource under `app/Filament/Admin/Resources/PricingCategoryResource.php` and a Relation Manager for `PricingItem` under `.../RelationManagers`.
2. Add a lightweight Filament `TextColumn` named `items_preview` if you want quick inline price summaries in the list table (keeps table compact).
3. For details use `->expandableColumn()->modalContentView('filament.pricing.row-details')` and create `resources/views/filament/pricing/row-details.blade.php` to render a small items table.

## Small UI contracts (components)
- Price card (frontend): accepts title, price, unit, features (booleans), discount, duration; used on `/pricing` public page.
- Admin Items table: title, price, unit, gift (checkbox), files (checkbox), weekends (checkbox), discount, active.

## Performance & caching
- Use eager-loading in admin table queries when showing items (avoid N+1). Example: load items in `getTableQuery()` or use `formatStateUsing` carefully.
- Clear views after updates: `php artisan view:clear` and `php artisan optimize:clear` when changing Blade discovery or Filament providers.

## Testing & QA
- Add a minimal seeder `PricingSeeder` to populate categories and items for QA.
- Quick smoke checks:
  - Visit `/pricing` (public) — verify card layout and images
  - Visit `/admin/pricing-categories` — verify categories list, expand rows for items

## Notes for AI agents / contributors
- When editing views mention the file path and keep changes minimal. Use `asset()` for static references and `e()`/`{{ }}` to escape output.
- Prefer Filament components for admin UIs. Avoid manual form markup in admin panels unless necessary for the public-facing site.

---

If you'd like, I can also generate a small reference `resources/css/brand.css` with the font-face rules and variables, and wire it into Vite for you.
