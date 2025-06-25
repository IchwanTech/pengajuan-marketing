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
        Schema::create('alamat_nasabah_luars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah_luars')->onDelete('cascade');
            $table->string('alamat_ktp');
            $table->string('rt_rw');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('provinsi');
            $table->enum('status_rumah', ['pribadi', 'kontrak bulanan', 'kontrak tahunan', 'milik orang tua']);
            $table->decimal('biaya_perbulan', 15, 2)->nullable();
            $table->decimal('biaya_pertahun', 15, 2)->nullable();
            $table->enum('domisili', ['sesuai ktp', 'tidak sesuai ktp']);
            $table->string('alamat_domisili')->nullable();
            $table->enum('rumah_domisili', ['pribadi', 'kontrak bulanan', 'kontrak tahunan', 'milik orang tua']);
            $table->decimal('biaya_perbulan_domisili', 15, 2)->nullable();
            $table->decimal('biaya_pertahun_domisili', 15, 2)->nullable();
            $table->string('atas_nama_listrik');
            $table->string('hubungan');
            $table->string('foto_meteran_listrik');
            $table->boolean('validasi_alamat')->nullable();
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
        Schema::dropIfExists('alamat_nasabah_luars');
    }
};
