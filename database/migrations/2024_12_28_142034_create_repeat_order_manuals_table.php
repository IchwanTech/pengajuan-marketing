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
        Schema::create('repeat_order_manuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('nik');
            $table->string('no_hp');
            $table->decimal('nominal_pinjaman', 15, 2);
            $table->integer('tenor');
            $table->integer('pinjaman_ke');
            $table->string('nama_marketing');
            $table->enum('status_konsumen', ['baru', 'lama']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repeat_order_manuals');
    }
};
