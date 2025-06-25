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
        Schema::create('pekerjaan_nasabah_luars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah_luars')->onDelete('cascade');
            $table->string('perusahaan');
            $table->string('alamat_perusahaan');
            $table->string('kontak_perusahaan');
            $table->string('jabatan');
            $table->string('lama_kerja');
            $table->enum('status_karyawan', ['tetap', 'kontrak']);
            $table->string('lama_kontrak')->nullable();
            $table->decimal('pendapatan_perbulan', 15, 2);
            $table->string('slip_gaji');
            $table->string('id_card');
            $table->boolean('validasi_pekerjaan')->nullable();
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
        Schema::dropIfExists('pekerjaan_nasabah_luars');
    }
};
