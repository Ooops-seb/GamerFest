<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade la columna reglamentos_pdf si aún no existe.
     */
    public function up(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            if (!Schema::hasColumn('juegos', 'reglamentos_pdf')) {
                $table->string('reglamentos_pdf', 500)
                      ->nullable()
                      ->after('img_id');
            }
        });
    }

    /**
     * Reversa la operación de up().
     */
    public function down(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            if (Schema::hasColumn('juegos', 'reglamentos_pdf')) {
                $table->dropColumn('reglamentos_pdf');
            }
        });
    }
};
