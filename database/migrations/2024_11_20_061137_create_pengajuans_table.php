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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade');
            $table->enum('status_pinjaman', ['baru', 'lama'])->default('baru');
            $table->decimal('nominal_pinjaman', 15, 2);
            $table->integer('tenor');
            $table->string('keperluan');
            $table->enum('status', ['pending', 'aproved spv', 'rejected spv', 'aproved ca', 'rejected ca', 'aproved head', 'rejected head'])->default('pending');
            $table->decimal('riwayat_nominal', 15, 2)->nullable();
            $table->integer('riwayat_tenor')->nullable();
            $table->decimal('sisa_pinjaman', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
