<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('nov_categories')) {
            Schema::create('nov_categories', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('orden')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('nov_categories', function (Blueprint $table) {
                if (! Schema::hasColumn('nov_categories', 'title')) {
                    $table->string('title');
                }

                if (! Schema::hasColumn('nov_categories', 'orden')) {
                    $table->string('orden')->nullable();
                }

                if (! Schema::hasColumn('nov_categories', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        if (! Schema::hasTable('nov_pivote')) {
            Schema::create('nov_pivote', function (Blueprint $table) {
                $table->id();
                $table->foreignId('novedades_id')->constrained('novedades')->cascadeOnDelete();
                $table->foreignId('category_id')->constrained('nov_categories')->cascadeOnDelete();
                $table->unique(['novedades_id', 'category_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('nov_pivote');
        Schema::dropIfExists('nov_categories');
    }
};
