# HRM System (Laravel + MySQL + Blade)

Production-ready HRM foundation with RBAC, multi-branch support, attendance (GPS/QR-ready), leave, payroll, admin dashboard, and employee panel.

## Stack

- Backend: Laravel 12 (MVC)
- DB: MySQL
- Frontend: Blade
- Auth: Laravel built-in auth (Breeze-based)
- RBAC: spatie/laravel-permission
- PDF: barryvdh/laravel-dompdf
- QR generator: simplesoftwareio/simple-qrcode

## Roles

- Super Admin
- Admin / HR
- Employee

All role routes are protected by `role` middleware.

## Core Modules Included

- Employee Management
- Multi-Branch Management
- Attendance (4 scans/day, GPS radius validation, anti-duplicate scans)
- Leave Management (approve/reject + balance deduction)
- Payroll Generation + Payslip PDF
- Admin Dashboard summaries
- Employee Mobile-first panel + PWA-ready manifest/service worker placeholders
- Corporate Admin panel (sidebar + top header + data-first dashboard)
- Attendance management table with detail modal
- Leave reject flow with required admin comment
- Payroll detail page + paid/pending workflow
- SaaS subscription dashboard (plans + companies + revenue summary)
- Company settings (primary color, plan label, payroll visibility toggle)
- API-ready structure at `/api/v1/*` (token-auth ready)

## Install

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Set `.env` MySQL credentials, then run:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Default Accounts

- Super Admin: `superadmin@hrm.local` / ` `
- SaaS Admin: `saas@hrm.local` / `password123`
- Admin HR: `hr@hrm.local` / `password123`
- Employee: `employee@hrm.local` / `password123`

## How to Create a New Plan

1. Log in as **SaaS Admin** (`saas@hrm.local` / `password123`).
2. Go to the sidebar and click **Subscription Plans**.
3. Click the **Create Plan** button at the top right.
4. Fill in the plan details (name, price, employee/branch limits, etc).
5. Click **Create Plan** to save.

Plans will now be available for companies to subscribe.

## Shared Hosting (cPanel)

1. Upload project files to your app folder.
2. Point domain document root to `/public`.
3. Set `.env` and run `php artisan key:generate`.
4. Run `php artisan migrate --seed` once.
5. Run `php artisan storage:link`.
6. Ensure `storage/` and `bootstrap/cache/` are writable.

### Quick Repair (500 error)

If you get `500 Internal Server Error` on cPanel, run this from project root:

```bash
chmod +x scripts/cpanel_repair.sh
bash scripts/cpanel_repair.sh
```

The script automatically:
- fixes `public/storage` symlink issues,
- clears/rebuilds Laravel caches,
- reapplies required permissions,
- creates a safe root `index.php` + `.htaccess` fallback for hosts that cannot point document root to `/public`.

No WebSocket server is required.

## Security + Performance Notes

- CSRF enabled by default.
- Password hashing via Laravel hashed cast/bcrypt.
- FormRequest validation on write actions.
- Activity logging middleware stores request metadata.
- Duplicate attendance scan prevented by DB unique index and service checks.
- QR scanner requires a secure (HTTPS) connection and valid camera permissions; most browsers will block camera access over plain HTTP.
- Eager loading used in dashboards/list pages.
- Dashboard summary cached for 5 minutes.

## Next Extensions

- Strong anti-fake-GPS heuristics (speed/device attestation)
- Dynamic QR token issuing/expiration scheduler
- Multi-level leave approvals workflow rules
- API auth tokens using Sanctum/Passport
