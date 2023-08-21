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
        Schema::create('amal_yaumis', function (Blueprint $table) {
            $table->id();
            $table->boolean('subuh');
            $table->boolean('zuhur');
            $table->boolean('ashar');
            $table->boolean('maghrib');
            $table->boolean('isya');
            $table->boolean('tahajud');
            $table->boolean('dhuha');
            $table->boolean('witir');
            $table->boolean('puasa');
            $table->boolean('dzikir_pagi');
            $table->boolean('dzikir_petang');
            $table->boolean('kajian_subuh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amal_yaumis');
    }
};
