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
        Schema::create('pengajuan_nasabah_luars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah_luars')->onDelete('cascade');
            $table->enum('jenis_pembiayaan', ['BPJS', 'SHM', 'BPKB', 'Kedinasan']);
            $table->decimal('nominal_pinjaman', 15, 2);
            $table->integer('tenor');
            $table->enum('status_pengajuan', ['pending', 'aproved ca', 'rejected ca', 'revisi', 'banding', 'approved banding', 'rejected banding', 'perlu survey', 'tidak perlu survey', 'verifikasi'])->default('pending');
            $table->boolean('validasi_pengajuan')->nullable();
            $table->string('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_nasabah_luars');
    }
};
