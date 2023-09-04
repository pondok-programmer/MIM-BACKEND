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
        Schema::create('bacaan_sholats', function (Blueprint $table) {
            $table->id();
            $table->string('arab');
            $table->string('latin');
            $table->string('terjemahan');
            $table->string('voice_arab');
            $table->string('voice_terjemahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bacaan_sholats');
    }
};
