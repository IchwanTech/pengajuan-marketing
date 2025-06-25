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
            $table->enum('status_pengajuan', ['pending', 'aproved ca', 'rejected ca', 'aproved hm', 'rejected hm', 'revisi', 'banding', 'approved banding ca', 'rejected banding ca', 'approved banding hm', 'rejected banding hm', 'perlu survey', 'tidak perlu survey', 'verifikasi', 'survey selesai'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_nasabah_luars', function (Blueprint $table) {
            $table->enum('status_pengajuan', ['pending', 'aproved ca', 'rejected ca', 'revisi', 'banding', 'approved banding', 'rejected banding', 'perlu survey', 'tidak perlu survey', 'verifikasi'])->default('pending')->change();
        });
    }
};
