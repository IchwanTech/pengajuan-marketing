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
        Schema::create('pinjaman_lain_nasabah_luars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah_luars')->onDelete('cascade');
            $table->enum('cicilan_lain', ['Ada', 'Tidak']);
            $table->string('nama_pembiayaan');
            $table->decimal('cicilan_perbulan', 15, 2);
            $table->integer('sisa_tenor');
            $table->boolean('validasi_pinjaman_lain')->nullable();
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
        Schema::dropIfExists('pinjaman_lain_nasabah_luars');
    }
};
