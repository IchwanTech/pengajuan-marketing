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
        Schema::create('kontak_darurat_nasabah_luars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah_luars')->onDelete('cascade');
            $table->string('nama_kontak_darurat');
            $table->string('hubungan_kontak_darurat');
            $table->string('no_hp_kontak_darurat');
            $table->boolean('validasi_kontak_darurat')->nullable();
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
        Schema::dropIfExists('kontak_darurat_nasabah_luars');
    }
};
