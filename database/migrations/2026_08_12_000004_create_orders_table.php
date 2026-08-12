<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('customer_name');
            $table->string('phone');
            $table->string('location')->nullable();
            $table->text('gift_note')->nullable();
            $table->text('special_instructions')->nullable();
            $table->text('deletion_reason')->nullable();
            $table->json('items');
            $table->decimal('total_amount', 8, 2);
            $table->string('status')->default('pending_confirmation'); // pending_confirmation, new, in_progress, completed, cancelled
            $table->string('ip_address')->nullable();
            $table->date('delivery_date');
            $table->string('delivery_slot');
            $table->uuid('handled_by_admin_id')->nullable();
            $table->foreign('handled_by_admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->dateTime('handled_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });

        // Add check constraint for delivery_slot
        DB::statement("ALTER TABLE orders ADD CONSTRAINT check_delivery_slot CHECK (delivery_slot IN ('day', 'noon', 'afternoon', 'night'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
