<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuesto_pages', function (Blueprint $table) {
            $table->id();
            $table->string('image_banner')->nullable();
            $table->timestamps();
        });

        Schema::create('presupuesto_system_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('orden', 10)->nullable()->unique();
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        Schema::create('presupuesto_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('mobile');
            $table->string('province');
            $table->string('locality');
            $table->string('service')->nullable();
            $table->string('equipment')->nullable();
            $table->string('system_type')->nullable();
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->unsignedBigInteger('attachment_size')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuesto_requests');
        Schema::dropIfExists('presupuesto_system_types');
        Schema::dropIfExists('presupuesto_pages');
    }
};
