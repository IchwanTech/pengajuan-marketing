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
        Schema::create('penjamin_nasabah_luars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah_luars')->onDelete('cascade');
            $table->enum('hubungan_penjamin', ['Orang Tua', 'Suami', 'Istri', 'Anak', 'Keluarga Kandung', 'Lainnya']);
            $table->string('nama_penjamin');
            $table->string('pekerjaan_penjamin');
            $table->decimal('penghasilan_penjamin', 15, 2);
            $table->string('no_hp_penjamin');
            $table->enum('persetujuan_penjamin', ['Ya', 'Tidak']);
            $table->string('foto_ktp_penjamin');
            $table->boolean('validasi_penjamin')->nullable();
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
        Schema::dropIfExists('penjamin_nasabah_luars');
    }
};
