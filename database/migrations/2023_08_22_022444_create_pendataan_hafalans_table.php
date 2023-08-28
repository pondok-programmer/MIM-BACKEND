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
        Schema::create('pendataan_hafalans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('juz');
            $table->string('surah');
            $table->string('ayat_awal');
            $table->string('ayat_akhir');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendataan_hafalans');
    }
};
