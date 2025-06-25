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
        Schema::create('jaminans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade');
            $table->string('jaminan_hrd');
            $table->string('jaminan_cg');
            $table->enum('penjamin', ['ada', 'tidak']);
            $table->string('nama_penjamin')->nullable();
            $table->string('lama_kerja_penjamin')->nullable();
            $table->string('bagian')->nullable();
            $table->string('absensi')->nullable();
            $table->enum('riwayat_pinjam_penjamin', ['ada', 'tidak'])->nullable();
            $table->decimal('riwayat_nominal_penjamin', 15, 2)->nullable();
            $table->integer('riwayat_tenor_penjamin')->nullable();
            $table->decimal('sisa_pinjaman_penjamin', 15, 2)->nullable();
            $table->string('jaminan_cg_penjamin')->nullable();
            $table->string('status_hubungan_penjamin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jaminans');
    }
};
