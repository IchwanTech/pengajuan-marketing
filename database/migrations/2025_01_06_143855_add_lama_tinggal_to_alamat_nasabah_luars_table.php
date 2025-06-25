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
        Schema::table('alamat_nasabah_luars', function (Blueprint $table) {
            $table->string('lama_tinggal')->nullable()->after('biaya_pertahun_domisili');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alamat_nasabah_luars', function (Blueprint $table) {
            $table->dropColumn('lama_tinggal');
        });
    }
};
