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
        Schema::table('juegos', function (Blueprint $table) {
            $table->string('img_id')->default('sample_img')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('juegos', function (Blueprint $table) {
            $table->string('img_id', 20)->default('sample_img')->change();
        });
    }
};
