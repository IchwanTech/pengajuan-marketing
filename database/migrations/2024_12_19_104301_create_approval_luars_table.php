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
        Schema::create('approval_luars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_nasabah_luars')->onDelete('cascade');
            $table->text('analisa')->nullable();
            $table->decimal('nominal_pinjaman', 15, 2)->nullable();
            $table->integer('tenor')->nullable();
            $table->enum('status', ['approved', 'rejected'])->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_luars');
    }
};
