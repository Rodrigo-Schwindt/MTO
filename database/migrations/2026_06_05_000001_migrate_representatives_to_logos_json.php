<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_representatives', function (Blueprint $table) {
            $table->json('logos')->nullable()->after('title');
        });

        DB::table('home_representatives')->get()->each(function ($row) {
            $logos = array_values(array_filter([
                $row->logo_1 ?? null,
                $row->logo_2 ?? null,
            ]));
            DB::table('home_representatives')
                ->where('id', $row->id)
                ->update(['logos' => json_encode($logos)]);
        });

        Schema::table('home_representatives', function (Blueprint $table) {
            $table->dropColumn(['logo_1', 'logo_2']);
        });
    }

    public function down(): void
    {
        Schema::table('home_representatives', function (Blueprint $table) {
            $table->string('logo_1')->nullable();
            $table->string('logo_2')->nullable();
            $table->dropColumn('logos');
        });
    }
};
