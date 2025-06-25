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
        Schema::table('nasabahs', function (Blueprint $table) {
            $table->string('foto_ktp')->after('status_nikah')->nullable();
            $table->string('foto_kk')->after('foto_ktp')->nullable();
            $table->string('foto_id_card')->after('foto_kk')->nullable();
            $table->string('foto_rekening')->after('foto_id_card')->nullable();
            $table->string('no_rekening')->after('foto_rekening')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nasabahs', function (Blueprint $table) {
            $table->dropColumn('foto_ktp');
            $table->dropColumn('foto_kk');
            $table->dropColumn('foto_id_card');
            $table->dropColumn('foto_rekening');
            $table->dropColumn('no_rekening');
        });
    }
};
