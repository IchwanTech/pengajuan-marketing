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
        Schema::table('pinjaman_lain_nasabah_luars', function (Blueprint $table) {
            $table->string('total_pinjaman')->nullable()->after('nama_pembiayaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjaman_lain_nasabah_luars', function (Blueprint $table) {
            $table->dropColumn('total_pinjaman');
        });
    }
};
