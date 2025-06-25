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
        Schema::create('surat_kontraks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kontrak');
            $table->string('nama');
            $table->text('alamat');
            $table->string('no_ktp');
            $table->string('type');
            $table->decimal('pokok_pinjaman', 15, 2);
            $table->integer('tenor');
            $table->decimal('biaya_admin', 15, 2);
            $table->decimal('cicilan', 15, 2);
            $table->decimal('biaya_layanan', 15, 2);
            $table->decimal('bunga', 15, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_kontraks');
    }
};
