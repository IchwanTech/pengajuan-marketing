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
        Schema::table('notifaction_marketings', function (Blueprint $table) {
            $table->unsignedBigInteger('pengajuan_id')->nullable()->change();
            $table->foreignId('pengajuan_luar_id')
                ->nullable()
                ->after('pengajuan_id')
                ->constrained('pengajuan_nasabah_luars')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifaction_marketings', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_luar_id']);
            $table->dropColumn('pengajuan_luar_id');
        });
    }
};
