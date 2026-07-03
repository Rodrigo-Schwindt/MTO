<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (! Schema::hasColumn('sliders', 'button_text')) {
                $table->string('button_text')->nullable()->after('url');
            }

            if (! Schema::hasColumn('sliders', 'button_target')) {
                $table->string('button_target')->default('_self')->after('button_text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            if (Schema::hasColumn('sliders', 'button_target')) {
                $table->dropColumn('button_target');
            }

            if (Schema::hasColumn('sliders', 'button_text')) {
                $table->dropColumn('button_text');
            }
        });
    }
};
