# 🌙 DALBIT Laravel Migration: Artisan Bites & Moonlight

An artisanal, high-performance web application and order management system built for **DALBIT Artisan Bites & Moonlight** in Amman, Jordan. 

Currently migrating from Next.js to **Laravel 12 (PHP 8.3) + MySQL**, utilizing Laravel Breeze, Blade templates, Alpine.js, Tailwind CSS, and Pusher for real-time WebSocket syncing.

---

## 🎨 Brand Design & Aesthetic System
The website is designed around a curated, luxurious 2-tone nocturnal color palette. **Toggling to English (LTR) automatically swaps the primary brand colors** to create a distinct visual theme:

- **Arabic (RTL - Default)**: Off-white Background / Lavender Accents / Yellow Highlights
- **English (LTR)**: Deep Lavender Background / Off-white Accents / Yellow Highlights

---

## ✨ Core Features & Business Logic

### 🛒 1. Storefront & Product Experience
- **Blade + Alpine.js Rendering**: Full server-side HTML rendering (SSR) via Laravel Blade ensures immediate SEO visibility for search crawlers, while Alpine.js provides lightweight client-side interactivity without the overhead of a heavy JS framework like Inertia.
- **Local Image Storage**: All product images are served directly via Hostinger's local disk/CDN for maximum speed, bypassing external cloud latency.
- **Dynamic Product Catalog**: Products are fetched from the MySQL `products` table, ensuring a synchronized, database-backed catalog that prevents multi-admin race conditions.

### 🌙 2. Strict 24-Hour Delivery Scheduling
- **No Same-Day Delivery**: *Every* order strictly requires a 24-hour advance notice for preparation and baking. 
- **Fixed Delivery Slots**: Customers are assigned the earliest available 3-hour slot occurring at least 24 hours after their order timestamp (Day: 9AM-12PM, Noon: 12PM-3PM, Afternoon: 3PM-6PM, Night: 6PM-9PM).
- **Timezone Native**: All scheduling math is handled server-side using the `Asia/Amman` timezone; the customer's local device clock is completely ignored to prevent spoofing.

### 🛡️ 3. Spam & Fraud Prevention (No-Payment Flow)
Because the store operates exclusively on **Cash on Delivery (COD)**, the checkout flow incorporates 4 layers of spam defense:
1. **Honeypot Fields**: Hidden checkout fields silently reject automated bot submissions.
2. **Rate Limiting**: Orders are limited to a maximum of 2-3 per phone number and IP address every 24 hours.
3. **Jordanian Phone Validation**: Strict Regex enforces the `07 7/8/9` mobile prefix.
4. **Pending Confirmation Flow**: All new orders land in a `pending_confirmation` state. An admin must manually call the customer to verify the order before it can be moved to the `new` status.

### 📡 4. Real-Time Sync (Laravel Broadcasting + Pusher)
- **Admin Orders (Private Channel)**: When a customer places an order, Laravel broadcasts a secure, authenticated event via Pusher. The Admin Kanban board listens to this private channel and updates instantly without a page refresh.
- **Site Settings (Public Channel)**: Toggling store visibility or marquee settings broadcasts over a public Pusher channel, syncing all active visitor storefronts instantly.

### 📱 5. Admin Command Center & Analytics
- **Multi-Admin Breeze Auth**: Replaces the old single-password system. Every admin has a unique login, and the `admin_login_logs` table creates an audit trail of access.
- **Order Attribution**: When an admin moves a Kanban card, the system records *which* admin handled the order and *when*.
- **WhatsApp Integration**: A dedicated "Request Feedback" button formats the customer's phone number and opens a pre-filled `wa.me` WhatsApp link.
- **Hidden QR Scans**: A hidden, mobile-only `/links` page silently inserts scan analytics into the database whenever a customer scans the box QR code, requiring zero user interaction.

### ⭐️ 6. Customer Feedback Funnel
A dual-branch review system for completed orders:
- **1 to 3 Stars**: Reveals a private text area that submits complaints directly to the database (never shown publicly).
- **4 to 5 Stars**: Immediately redirects the user to the public Google Maps review page.

---

## 🗄️ Database Architecture (MySQL)

The database utilizes `CHAR(36)` UUIDs, JSON columns, and strictly UTC `DATETIME` timestamps.

* **`admins`**: Multi-user login credentials.
* **`admin_login_logs`**: Security audit trail for admin access.
* **`products`**: Centralized product definitions, pricing, and bilingual descriptions.
* **`orders`**: Customer details, JSON cart items, and delivery slot tracking.
* **`qr_scans`**: Analytics for box QR scans.
* **`customer_feedback`**: Private ratings and text complaints linked to orders.
* **`site_settings`**: Global toggles (e.g., marquee visibility) synced to the frontend.

---

## 🚀 Local Development (Awaiting Environment Setup)

*Note: The following instructions will apply once PHP 8.3 and Composer are installed on the host machine.*

1. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```
2. **Environment Setup**:
   Copy `.env.example` to `.env`, configure the MySQL credentials, and set up the Pusher keys.
3. **Run Migrations**:
   ```bash
   php artisan migrate
   ```
4. **Link Storage (For Images)**:
   ```bash
   php artisan storage:link
   ```
5. **Start Development Servers**:
   ```bash
   php artisan serve
   npm run dev
   ```

---

## 📜 License & Copyright
&copy; 2026 **DALBIT Artisan Bites & Moonlight**. All rights reserved.  
Handcrafted with ♡ in Amman, Hashemite Kingdom of Jordan.
