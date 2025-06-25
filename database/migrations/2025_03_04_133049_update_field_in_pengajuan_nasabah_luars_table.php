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
            $table->enum('jenis_pembiayaan', ['BPJS', 'SHM', 'BPKB', 'Kedinasan', 'UMKM', 'SF', 'Kecamatan'])->change();
            $table->string('berkas_jaminan')->after('tenor');
            $table->enum('status_pengajuan', ['pending', 'aproved ca', 'rejected ca', 'aproved hm', 'rejected hm', 'revisi', 'revisi spv', 'checked by spv', 'banding', 'approved banding ca', 'rejected banding ca', 'approved banding hm', 'rejected banding hm', 'perlu survey', 'tidak perlu survey', 'verifikasi', 'survey selesai'])->default('pending')->change();
            $table->text('catatan_spv')->nullable()->after('catatan');
            $table->text('catatan_marketing')->nullable()->after('catatan_spv');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_nasabah_luars', function (Blueprint $table) {
            $table->enum('jenis_pembiayaan', ['BPJS', 'SHM', 'BPKB', 'Kedinasan']);
            $table->enum('status_pengajuan', ['pending', 'aproved ca', 'rejected ca', 'aproved hm', 'rejected hm', 'revisi', 'banding', 'approved banding ca', 'rejected banding ca', 'approved banding hm', 'rejected banding hm', 'perlu survey', 'tidak perlu survey', 'verifikasi', 'survey selesai'])->default('pending');
            $table->dropColumn('berkas_jaminan');
            $table->dropColumn('catatan_spv');
            $table->dropColumn('catatan_marketing');
        });
    }
};
