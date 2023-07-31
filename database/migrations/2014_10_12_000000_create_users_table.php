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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('tgl_lahir');
            $table->string('tempat_lahir');
            $table->string('jenkel');
            $table->string('alamat');
            $table->string('no_telp');
            $table->string('email')->unique();
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->string('range_gaji');
            $table->string('status');
            $table->string('jumlah_anak');
            $table->string('img');
            $table->string('role');
            $table->integer('otp_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
