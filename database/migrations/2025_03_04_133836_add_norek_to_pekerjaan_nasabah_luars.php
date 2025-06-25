<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pekerjaan_nasabah_luars', function (Blueprint $table) {
            $table->string('norek')->after('slip_gaji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pekerjaan_nasabah_luars', function (Blueprint $table) {
            $table->dropColumn('norek');
        });
    }
};
