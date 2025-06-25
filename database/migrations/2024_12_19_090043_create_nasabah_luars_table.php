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
        Schema::create('nasabah_luars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('nik');
            $table->string('no_kk');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('no_hp');
            $table->string('email')->nullable();
            $table->enum('status_nikah', ['Belum Menikah', 'Menikah', 'Janda/Duda']);
            $table->string('foto_ktp')->nullable();
            $table->string('foto_kk')->nullable();
            $table->boolean('validasi_nasabah')->nullable();
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
        Schema::dropIfExists('nasabah_luars');
    }
};
