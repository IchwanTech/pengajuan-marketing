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
        Schema::create('hasil_survey_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_nasabah_luars')->onDelete('cascade');
            $table->string('berjumpa_siapa');
            $table->string('hubungan');
            $table->string('status_rumah');
            $table->string('hasil_cekling1');
            $table->string('hasil_cekling2');
            $table->string('kesimpulan');
            $table->string('rekomendasi');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_survey_pengajuans');
    }
};
