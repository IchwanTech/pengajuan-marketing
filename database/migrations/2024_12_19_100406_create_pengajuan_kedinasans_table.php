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
        Schema::create('pengajuan_kedinasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_nasabah_luars')->onDelete('cascade');
            $table->string('instansi')->nullable();
            $table->string('surat_permohonan_kredit')->nullable();
            $table->string('surat_pernyataan_penjamin')->nullable();
            $table->string('surat_persetujuan_pimpinan')->nullable();
            $table->string('surat_keterangan_gaji')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kedinasans');
    }
};
