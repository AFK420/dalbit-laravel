# DALBIT Laravel Migration: Master Handover Document

Hello Claude! You are picking up a conversation in a new chat because the previous one ran out of context tokens. 

This document summarizes **everything** that has been accomplished from the very beginning of the project up to this exact moment. You are jumping in right as we are about to start **Prompt 4** of the migration playbook.

---

## 1. Project Context & Architecture
We are migrating the **DALBIT Artisan Bites & Moonlight** web app from Next.js + Supabase to a traditional server-side stack.
- **Tech Stack:** Laravel 13 (PHP 8.3), MySQL 8.4, Blade Templates, Alpine.js, and Tailwind CSS.
- **No Inertia:** We are strictly using Blade + Alpine to ensure 100% server-side HTML rendering for SEO purposes.
- **Real-Time Sync:** Because Hostinger Cloud Hosting kills persistent processes (meaning Laravel Reverb won't work reliably), we are using **Pusher** for WebSockets.

---

## 2. What Has Been Completed (Prompts 1, 2, and 3)

We have successfully scaffolded the environment, generated the database schema, and mathematically proven that the database constraints and auth guards work.

### ✅ Framework & Packages Scaffolded
- Ran `composer create-project laravel/laravel` (It pulled Laravel 13.25.0).
- Required `laravel/breeze` and `pusher/pusher-php-server`.
- Ran `php artisan breeze:install blade` to set up the authentication scaffolding.
- Ran `php artisan storage:link` to prepare local image storage.

### ✅ Configuration Updates
- **Timezone:** `config/app.php` is strictly set to `'timezone' => 'Asia/Amman'`. All delivery math must use this.
- **Environment:** `.env` is configured for MySQL. It also has `BROADCAST_CONNECTION=pusher` and contains empty placeholders for Pusher and Telegram API keys.
- **Authentication Wiring:** We are using an `Admin` model, not `User`. 
  - The `config/auth.php` file was carefully modified to use `App\Models\Admin::class` for the `web` guard and the `admins` provider.
  - The `Admin` model correctly extends `Illuminate\Foundation\Auth\User as Authenticatable`.
  - We ran a manual test script that successfully created an Admin and passed `Auth::attempt()`.

### ✅ Database Schema & Migrations
The database schema was meticulously designed and tested against MySQL. We removed the default Laravel `users` table and split the `sessions` / `password_reset_tokens` migrations properly.

The following tables exist and have been migrated successfully:
1. **`admins`**: Uses `CHAR(36)` UUIDs.
2. **`admin_login_logs`**: Tracks IP and login times.
3. **`products`**: Contains bilingual fields (`name_ar`, `full_description_ar`) and JSON arrays (`flavor_profile`, `allergens`, `highlights`).
4. **`orders`**: 
   - `status` uses a PHP Enum (not a MySQL ENUM) for flexibility.
   - Requires a strict 24-hour advance notice.
   - `delivery_slot` is enforced by a MySQL `CHECK` constraint restricting it to exactly four values: `'day'`, `'noon'`, `'afternoon'`, `'night'`. *(We mathematically proved this constraint works by trying to insert an invalid slot).*
5. **`qr_scans`**: Hidden analytics for box QR scans.
6. **`customer_feedback`**: Ratings are enforced by a `CHECK` constraint (1-5). *(We proved this works by trying to insert a 6).*
7. **`site_settings`**: Global toggles (e.g., marquee visibility).

---

## 3. What Needs to Happen Now (Your Job)

The environment is 100% ready. You are now starting **Prompt 4** of the playbook, which requires migrating the actual application features **one by one**, validating each before moving on.

**The Feature Implementation Order:**
1. **Product Catalog:** Fetching from the MySQL database and rendering the Blade storefront.
2. **Language/Theme Swap:** Toggling Arabic/English (RTL/LTR) must also automatically swap the CSS brand color variables (Off-white/Lavender vs Deep Lavender/Off-white).
3. **Checkout Flow:** Enforcing the 24-hour scheduling math, the spam prevention (honeypots, Jordanian phone regex `07 7/8/9`, IP rate limits), and Telegram bot notifications. *(Note: All orders start as `pending_confirmation` and require manual admin phone verification).*
4. **Feedback Funnel:** 1-3 stars goes to a private DB complaint; 4-5 stars redirects to Google Maps.
5. **QR Tracking:** A hidden `/links` page that silently logs a scan before redirecting.
6. **Admin Auth & Kanban:** The multi-admin dashboard.
7. **Order Attribution:** Tracking which admin moves a Kanban card.
8. **Pusher Integration:** Wiring the Kanban board to listen to the Private channel for new orders, and the Storefront to listen to the Public channel for `site_settings` changes.
9. **WhatsApp Button:** A "Request Feedback" button that formats a `wa.me` link.

Please begin with **Feature 1: The Product Catalog**. Write the necessary Controllers and Blade views!
