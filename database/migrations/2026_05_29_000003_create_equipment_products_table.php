<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_pages', function (Blueprint $table) {
            $table->id();
            $table->string('image_banner')->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('technical_sheet')->nullable();
            $table->string('orden', 10)->nullable()->unique();
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        Schema::create('equipment_product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_product_id')->constrained('equipment_products')->cascadeOnDelete();
            $table->string('image');
            $table->boolean('is_main')->default(false);
            $table->string('orden', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('equipment_product_related', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_product_id')->constrained('equipment_products')->cascadeOnDelete();
            $table->foreignId('related_equipment_product_id')->constrained('equipment_products')->cascadeOnDelete();
            $table->unique(['equipment_product_id', 'related_equipment_product_id'], 'equipment_related_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_product_related');
        Schema::dropIfExists('equipment_product_images');
        Schema::dropIfExists('equipment_products');
        Schema::dropIfExists('equipment_pages');
    }
};
