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
        Schema::table('hasil_survey_pengajuans', function (Blueprint $table) {
            $table->longText('kesimpulan')->change();
            $table->longText('rekomendasi')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_survey_pengajuans', function (Blueprint $table) {
            $table->string('kesimpulan')->change();
            $table->string('rekomendasi')->change();
        });
    }
};
