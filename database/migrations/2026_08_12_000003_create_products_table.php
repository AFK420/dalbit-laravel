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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('category');
            $table->string('category_ar')->nullable();
            $table->string('type'); // package or sweet
            $table->decimal('price', 8, 2);
            $table->string('currency')->default('JOD');
            $table->string('image_path')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->text('fallback_placeholder')->nullable();
            $table->text('short_description')->nullable();
            $table->text('short_description_ar')->nullable();
            $table->text('full_description')->nullable();
            $table->text('full_description_ar')->nullable();
            $table->json('flavor_profile')->nullable();
            $table->json('flavor_profile_ar')->nullable();
            $table->json('allergens')->nullable();
            $table->json('allergens_ar')->nullable();
            $table->json('highlights')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
