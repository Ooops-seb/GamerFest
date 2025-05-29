<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        Schema::table('juegos', function (Blueprint $table) {
            $table->boolean('active')->default(true);
            $table->integer('max_participantes')->nullable();
        });
        Schema::table('ad_models', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        Schema::table('juegos', function (Blueprint $table) {
            $table->dropColumn(['active', 'max_participantes']);
        });
        Schema::table('ad_models', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
