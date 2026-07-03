<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact', function (Blueprint $table) {
            if (!Schema::hasColumn('contact', 'link_externo')) {
                $table->string('link_externo')->nullable()->after('youtube');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact', function (Blueprint $table) {
            if (Schema::hasColumn('contact', 'link_externo')) {
                $table->dropColumn('link_externo');
            }
        });
    }
};
