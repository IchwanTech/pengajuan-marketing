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
            Schema::table('tanggungan_nasabah_luars', function (Blueprint $table) {
                $table->longText('kondisi_tanggungan')->nullable()->change();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('tanggungan_nasabah_luars', function (Blueprint $table) {
                $table->string('kondisi_tanggungan')->nullable()->change();
            });
        }
    };
