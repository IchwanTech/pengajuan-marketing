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
        Schema::create('pengajuan_bpkbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_nasabah_luars')->onDelete('cascade');
            $table->string('atas_nama_bpkb')->nullable();
            $table->string('no_stnk')->nullable();
            $table->string('alamat_pemilik_bpkb')->nullable();
            $table->string('type_kendaraan')->nullable();
            $table->string('tahun_perakitan')->nullable();
            $table->string('warna_kendaraan')->nullable();
            $table->string('stransmisi')->nullable();
            $table->string('no_rangka')->nullable();
            $table->string('no_mesin')->nullable();
            $table->string('no_bpkb')->nullable();
            $table->string('foto_stnk')->nullable();
            $table->string('foto_bpkb')->nullable();
            $table->string('foto_motor')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_bpkbs');
    }
};
