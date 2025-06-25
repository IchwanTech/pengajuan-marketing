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
            $table->string('inisial_marketing')->nullable()->after('type');
            $table->string('golongan')->nullable()->after('inisial_marketing');
            $table->string('inisial_ca')->nullable()->after('golongan');
            $table->string('id_card')->nullable()->after('inisial_ca');
            $table->string('kedinasan')->nullable()->after('id_card');
            $table->string('pinjaman_ke')->nullable()->after('kedinasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_kontraks', function (Blueprint $table) {
            $table->dropColumn('inisial_marketing');
            $table->dropColumn('golongan');
            $table->dropColumn('inisial_ca');
            $table->dropColumn('id_card');
            $table->dropColumn('kedinasan');
            $table->dropColumn('pinjaman_ke');
        });
    }
};
