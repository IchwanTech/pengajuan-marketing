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
        Schema::create('notification_surveyors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_luar_id')->constrained('pengajuan_nasabah_luars')->onDelete('cascade');
            $table->string('pesan');
            $table->boolean('read')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_surveyors');
    }
};
