# DALBIT Laravel Migration: Handover to Claude

Hello Claude! This document outlines exactly what has happened so far in migrating the **DALBIT Artisan Bites & Moonlight** application from its original Next.js + Supabase architecture to **Laravel (PHP) + MySQL**.

You are stepping into a project that is in the **middle of its migration phase**.

## 1. What Has Been Completed So Far

### The Audit & Backup
- **Next.js Backup**: The entire original Next.js codebase was audited and then fully backed up to `d:\backup-nextjs-original-2026-08-12` locally, and also completely pushed to the original `AFK420/Dalbit` GitHub repository.
- **Business Logic Reconciliation**: We analyzed the new Ground Truth Business Logic against the old Next.js code. The new logic heavily overrules the old logic in specific ways (detailed below).

### Database Schema Generation
We have generated the exact MySQL migration files and Eloquent models for the new database structure, strictly following the provided schema. These files are located in `database/migrations/` and `app/Models/`.

1. **`admins` & `admin_login_logs`**: Designed for multi-admin support. Replaces the old single-password system. Tracks exactly who logged in and when.
2. **`products`**: Normalized into its own table (it used to be a JSON blob in site_settings). It has bilingual fields (`name_ar`, `full_description_ar`), and JSON fields for `flavor_profile`, `allergens`, and `highlights` (extracted directly from the original `config/products.ts`).
3. **`orders`**: 
   - **Status**: Uses a PHP-backed Enum (`app/Enums/OrderStatus.php`) with the values: `pending_confirmation`, `new`, `in_progress`, `completed`, `cancelled`. *Note that `pending_confirmation` is a new starting state where an admin must manually verify the order via a phone call.*
   - **Delivery Scheduling**: Removed the old immediate ordering. Enforces a strict 24-hour advance notice, assigning one of four fixed slots (`day`, `noon`, `afternoon`, `night`).
   - **Fraud Prevention**: Added `ip_address` to support rate limiting.
   - **Attribution**: Added `handled_by_admin_id` to track which admin took the order on the Kanban board.
4. **`qr_scans`**: Tracks silent, hidden mobile QR scans from delivery boxes.
5. **`customer_feedback`**: A new table for the review funnel. Constrained to 1-5 stars. Stores private text complaints linked to `order_id`.
6. **`site_settings`**: Global toggles like `show_marquee`. 

### The New GitHub Repository
- A brand new repository, `dalbit-laravel`, was initialized in `d:\laravel-app`.
- We committed and pushed the drafted Migrations, Models, Enum, and a highly detailed `README.md` to GitHub.

---

## 2. What HAS NOT Been Done Yet (The Blocker)

**CRITICAL NOTE**: The actual Laravel framework has **not** been scaffolded yet! 

When we attempted to run `composer create-project laravel/laravel`, we discovered that **PHP, Composer, and MySQL are not installed on the user's host machine.** 

As a result, the `d:\laravel-app` directory currently *only* contains the files we manually wrote (`app/`, `database/`, `README.md`, `CLAUDE.md`). The core Laravel framework is missing, and the migrations have never been executed against a real local database.

---

## 3. Ground Truth Rules for Next Steps

When the environment is ready (PHP/Composer installed), here are the strict rules for continuing the build:

1. **Tech Stack**: Laravel 12 (PHP 8.3), MySQL, Blade, Alpine.js, Tailwind CSS. Do NOT use Inertia (for SEO and server-side rendering reasons).
2. **No Real-Time Reverb**: Because Hostinger Cloud Hosting kills persistent background processes, you **must use Pusher** for real-time WebSocket syncing (not Laravel Reverb).
   - Order creation broadcasts on a **Private** Pusher channel.
   - Site settings broadcast on a **Public** Pusher channel.
3. **Image Storage**: Use the local `public` disk (`php artisan storage:link`). Do not use S3.
4. **Theme Toggling**: The Arabic/English language toggle must also swap the brand color CSS variables simultaneously (Off-white/Lavender/Yellow <-> Deep Lavender/Off-white/Yellow).
5. **Fraud/Spam Prevention Implementation**: You will need to build the honeypot fields, IP/Phone rate limiters, and the Jordanian phone Regex validation (`07 7/8/9`).
6. **Telegram Integration**: The Telegram bot order alerts must be carried over exactly as they worked in Next.js.
7. **Proceed Feature by Feature**: Do not build the whole app at once. The user has a specific playbook ("Prompt 4") that asks to migrate one feature at a time, starting with the Product Catalog, then the Language swap, then the Checkout flow, etc. Verify each step.
8. **Pre-Deployment Review**: Before deploying, there is a checklist ("Prompt 5") to ensure security (no raw SQL, admin routes protected, Pusher config production-ready, no secrets exposed).

Good luck, Claude! The database is meticulously planned, but the actual Laravel application needs to be built around it!
