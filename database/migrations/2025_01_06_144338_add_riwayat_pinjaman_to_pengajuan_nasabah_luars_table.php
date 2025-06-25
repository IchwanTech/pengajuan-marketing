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
        Schema::table('pengajuan_nasabah_luars', function (Blueprint $table) {
            $table->enum('status_pinjaman', ['baru', 'lama'])->default('baru')->after('tenor');
            $table->integer('pinjaman_ke')->nullable()->after('status_pinjaman');
            $table->decimal('pinjaman_terakhir', 15, 2)->nullable()->after('pinjaman_ke');
            $table->decimal('sisa_pinjaman', 15, 2)->nullable()->after('pinjaman_terakhir');
            $table->string('realisasi_pinjaman')->nullable()->after('sisa_pinjaman');
            $table->decimal('cicilan_perbulan', 15, 2)->nullable()->after('realisasi_pinjaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_nasabah_luars', function (Blueprint $table) {
            $table->dropColumn('status_pinjaman');
            $table->dropColumn('pinjaman_ke');
            $table->dropColumn('pinjaman_terakhir');
            $table->dropColumn('sisa_pinjaman');
            $table->dropColumn('realisasi_pinjaman');
            $table->dropColumn('cicilan_perbulan');
        });
    }
};
