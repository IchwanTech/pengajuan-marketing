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
        Schema::create('data_cicilans', function (Blueprint $table) {
            $table->id();
            $table->string('id_pinjam');
            $table->string('nama_konsumen');
            $table->date('tgl_pencairan');
            $table->decimal('pokok_pinjaman', 15, 2);
            $table->string('jumlah_tenor_seharusnya');
            $table->decimal('cicilan_perbulan', 15, 2);
            $table->integer('pinjaman_ke');
            $table->string('sisa_tenor');
            $table->decimal('sisa_pinjaman', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_cicilans');
    }
};
