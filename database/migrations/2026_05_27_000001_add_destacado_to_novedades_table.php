<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('novedades', 'destacado')) {
            Schema::table('novedades', function (Blueprint $table) {
                $table->boolean('destacado')->default(false)->after('orden');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('novedades', 'destacado')) {
            Schema::table('novedades', function (Blueprint $table) {
                $table->dropColumn('destacado');
            });
        }
    }
};
