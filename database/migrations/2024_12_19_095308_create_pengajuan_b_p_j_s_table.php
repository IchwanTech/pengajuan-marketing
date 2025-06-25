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
        Schema::create('pengajuan_b_p_j_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_nasabah_luars')->onDelete('cascade');
            $table->decimal('saldo_bpjs', 15, 2)->nullable();
            $table->date('tanggal_bayar_terakhir')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('foto_bpjs')->nullable();
            $table->string('foto_jaminan_tambahan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_b_p_j_s');
    }
};
