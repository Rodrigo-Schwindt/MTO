<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nosotros', function (Blueprint $table) {
            $table->string('title_5')->nullable()->after('image_4');
            $table->text('description_5')->nullable()->after('title_5');
            $table->string('image_5')->nullable()->after('description_5');
        });
    }

    public function down(): void
    {
        Schema::table('nosotros', function (Blueprint $table) {
            $table->dropColumn([
                'title_5',
                'description_5',
                'image_5',
            ]);
        });
    }
};
