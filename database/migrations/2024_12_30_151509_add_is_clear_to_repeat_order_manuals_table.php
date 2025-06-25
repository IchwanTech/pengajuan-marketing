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
        Schema::table('repeat_order_manuals', function (Blueprint $table) {
            $table->boolean('is_clear')->default('0')->after('status_konsumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repeat_order_manuals', function (Blueprint $table) {
            $table->dropColumn('is_clear');
        });
    }
};
