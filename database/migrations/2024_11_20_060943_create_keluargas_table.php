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
        Schema::create('keluargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade');
            $table->enum('hubungan', ['orang tua', 'suami', 'istri', 'anak']);
            $table->string('nama');
            $table->enum('bekerja', ['ya', 'tidak']);
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->decimal('penghasilan', 15, 2)->nullable();
            $table->string('alamat_kerja')->nullable();
            $table->string('no_hp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
