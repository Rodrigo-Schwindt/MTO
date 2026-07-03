<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact', function (Blueprint $table) {
            if (!Schema::hasColumn('contact', 'image_banner')) {
                $table->string('image_banner')->nullable()->after('id');
            }

            if (!Schema::hasColumn('contact', 'request_text')) {
                $table->text('request_text')->nullable()->after('frame_adm');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact', function (Blueprint $table) {
            if (Schema::hasColumn('contact', 'request_text')) {
                $table->dropColumn('request_text');
            }

            if (Schema::hasColumn('contact', 'image_banner')) {
                $table->dropColumn('image_banner');
            }
        });
    }
};
