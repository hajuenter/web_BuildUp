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
        Schema::create('data_cpb', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('alamat');
            $table->string('nik', 16)->unique()->index();
            $table->string('no_kk', 16)->unique();
            $table->string('pekerjaan');
            $table->string('email')->unique();
            $table->string('foto_rumah');
            $table->string('koordinat');
            $table->enum('status', ['Terverifikasi', 'Tidak Terverifikasi'])->default('Tidak Terverifikasi');
            $table->enum('pengecekan', ['Sudah Dicek', 'Belum Dicek'])->default('Belum Dicek');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_cpb');
    }
};
