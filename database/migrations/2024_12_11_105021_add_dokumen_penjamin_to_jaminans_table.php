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
        Schema::table('jaminans', function (Blueprint $table) {
            $table->string('foto_ktp_penjamin')->after('status_hubungan_penjamin')->nullable();
            $table->string('foto_id_card_penjamin')->after('foto_ktp_penjamin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jaminans', function (Blueprint $table) {
            $table->dropColumn('foto_ktp_penjamin');
            $table->dropColumn('foto_kk_penjamin');
        });
    }
};
