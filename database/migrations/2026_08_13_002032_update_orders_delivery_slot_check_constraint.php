<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop the existing constraint
        DB::statement('ALTER TABLE orders DROP CHECK check_delivery_slot');

        // 2. Convert existing values
        DB::statement("UPDATE orders SET delivery_slot = '9-12' WHERE delivery_slot = 'day'");
        DB::statement("UPDATE orders SET delivery_slot = '12-15' WHERE delivery_slot = 'noon'");
        DB::statement("UPDATE orders SET delivery_slot = '15-18' WHERE delivery_slot = 'afternoon'");
        DB::statement("UPDATE orders SET delivery_slot = '18-21' WHERE delivery_slot = 'night'");

        // 3. Add the new constraint
        DB::statement("ALTER TABLE orders ADD CONSTRAINT check_delivery_slot CHECK (delivery_slot IN ('9-12', '12-15', '15-18', '18-21'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE orders DROP CHECK check_delivery_slot');

        DB::statement("UPDATE orders SET delivery_slot = 'day' WHERE delivery_slot = '9-12'");
        DB::statement("UPDATE orders SET delivery_slot = 'noon' WHERE delivery_slot = '12-15'");
        DB::statement("UPDATE orders SET delivery_slot = 'afternoon' WHERE delivery_slot = '15-18'");
        DB::statement("UPDATE orders SET delivery_slot = 'night' WHERE delivery_slot = '18-21'");

        DB::statement("ALTER TABLE orders ADD CONSTRAINT check_delivery_slot CHECK (delivery_slot IN ('day', 'noon', 'afternoon', 'night'))");
    }
};
