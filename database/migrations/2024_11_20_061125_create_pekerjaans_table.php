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
        Schema::create('pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade');
            $table->enum('perusahaan', ['FOTS', 'ILW', 'TSII', 'ANI', 'Lainnya']);
            $table->string('divisi');
            $table->integer('lama_kerja_bulan')->nullable();
            $table->integer('lama_kerja_tahun')->nullable();
            $table->enum('golongan', ['HO', 'HT', 'HR', 'HS', 'HG', 'HL', 'Borongan', 'Lainnya']);
            $table->string('yayasan')->nullable();
            $table->string('nama_atasan');
            $table->string('nama_hrd');
            $table->string('absensi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pekerjaans');
    }
};
