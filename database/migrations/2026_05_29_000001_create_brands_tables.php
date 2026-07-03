<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_pages', function (Blueprint $table) {
            $table->id();
            $table->string('image_banner')->nullable();
            $table->timestamps();
        });

        Schema::create('brand_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('orden', 10)->nullable()->unique();
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_category_id')->nullable()->constrained('brand_categories')->nullOnDelete();
            $table->string('image');
            $table->string('orden', 10)->nullable()->unique();
            $table->boolean('visible')->default(true);
            $table->boolean('destacado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
        Schema::dropIfExists('brand_categories');
        Schema::dropIfExists('brand_pages');
    }
};
