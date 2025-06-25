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
        Schema::table('surat_kontraks', function (Blueprint $table) {
            $table->integer('nomor_urut')->nullable()->after('nomor_kontrak');
            $table->string('kelompok')->nullable()->after('type');
            $table->string('perusahaan')->nullable()->after('kelompok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_kontraks', function (Blueprint $table) {
            $table->dropColumn('nomor_urut');
            $table->dropColumn('kelompok');
            $table->dropColumn('perusahaan');
        });
    }
};
