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
        Schema::table('pengajuan_nasabah_luars', function (Blueprint $table) {
            $table->boolean('is_banding')->default('0')->after('catatan_marketing');
            $table->string('alasan_banding')->nullable()->after('is_banding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_nasabah_luars', function (Blueprint $table) {
            $table->dropColumn('is_banding');
            $table->dropColumn('alasan_banding');
        });
    }
};
