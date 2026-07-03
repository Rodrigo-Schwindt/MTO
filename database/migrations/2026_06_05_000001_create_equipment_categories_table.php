<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('imagen')->nullable();
            $table->string('orden', 10)->nullable()->unique();
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        Schema::table('equipment_products', function (Blueprint $table) {
            $table->foreignId('equipment_category_id')
                ->nullable()
                ->after('id')
                ->constrained('equipment_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('equipment_products', function (Blueprint $table) {
            $table->dropForeign(['equipment_category_id']);
            $table->dropColumn('equipment_category_id');
        });

        Schema::dropIfExists('equipment_categories');
    }
};
