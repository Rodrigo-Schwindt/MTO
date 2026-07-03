<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quality_pages', function (Blueprint $table) {
            $table->id();
            $table->string('image_banner')->nullable();
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        Schema::create('quality_downloads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file');
            $table->string('original_name')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('orden', 10)->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_downloads');
        Schema::dropIfExists('quality_pages');
    }
};
