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
        Schema::table('approval_luars', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('pengajuan_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->enum('role', ['ca', 'hm'])->after('user_id');
            $table->boolean('is_banding')->default(false)->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approval_luars', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'role', 'is_banding']);
        });
    }
};
