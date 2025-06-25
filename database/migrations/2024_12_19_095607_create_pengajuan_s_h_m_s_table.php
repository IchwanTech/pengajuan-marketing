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
        Schema::create('pengajuan_s_h_m_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_nasabah_luars')->onDelete('cascade');
            $table->string('atas_nama_shm')->nullable();
            $table->string('hubungan_shm')->nullable();
            $table->string('alamat_shm')->nullable();
            $table->string('luas_shm')->nullable();
            $table->string('njop_shm')->nullable();
            $table->string('foto_shm')->nullable();
            $table->string('foto_kk_pemilik_shm')->nullable();
            $table->string('foto_pbb')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_s_h_m_s');
    }
};
