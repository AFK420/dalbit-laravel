# 🌙 DALBIT Laravel Migration (In Progress)

This repository is the new **Laravel (PHP) + MySQL** backend for DALBIT Artisan Bites & Moonlight, migrating from the original Next.js + Supabase architecture.

## 🚀 Current Status
- ✅ **Database Schema Drafted**: All core database migrations (Orders, Products, Admins, QR Scans, Customer Feedback, Site Settings) have been mapped out according to the new strict business logic.
- ✅ **Eloquent Models Created**: UUIDs and relationships have been configured.
- 🚧 **Pending Scaffolding**: Awaiting PHP 8.3 and Composer installation on the local environment to run `composer create-project` and fully initialize the Laravel application structure.

## 🗄️ New Business Logic Additions
1. **Strict 24-Hour Delivery Math**: All orders now strictly enforce a 24-hour advance notice, mapped to 4 fixed daily slots (Day, Noon, Afternoon, Night).
2. **Multi-Admin Dashboard**: Replaces the single hardcoded admin account with a secure, multi-user system tracking login logs and order handling attribution.
3. **Spam & Fraud Prevention**: 
   - New `pending_confirmation` status requiring manual admin verification.
   - Hidden honeypot fields.
   - IP and phone number rate-limiting.
   - Strict Jordanian mobile number format validation.
4. **Pusher Real-Time Sync**: Replaces Supabase WebSockets with a resilient Pusher integration, utilizing private authenticated channels for admin order data.

*(Full codebase scaffolding will appear here once Composer is installed and the base Laravel 12.x framework is generated!)*
