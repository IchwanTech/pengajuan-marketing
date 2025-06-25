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
            $table->enum('role', ['spv', 'ca', 'hm'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approval_luars', function (Blueprint $table) {
            $table->enum('role', ['ca', 'hm']);
        });
    }
};
