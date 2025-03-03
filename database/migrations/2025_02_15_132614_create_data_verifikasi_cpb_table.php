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
        Schema::create('data_verifikasi_cpb', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->foreign('nik')->references('nik')->on('data_cpb')->onDelete('cascade');
            $table->decimal('penutup_atap', 3, 2)->nullable();
            $table->decimal('rangka_atap', 3, 2)->nullable();
            $table->decimal('kolom', 3, 2)->nullable();
            $table->decimal('ring_balok', 3, 2)->nullable();
            $table->decimal('dinding_pengisi', 3, 2)->nullable();
            $table->decimal('kusen', 3, 2)->nullable();
            $table->decimal('pintu', 3, 2)->nullable();
            $table->decimal('jendela', 3, 2)->nullable();
            $table->decimal('struktur_bawah', 3, 2)->nullable();
            $table->decimal('penutup_lantai', 3, 2)->nullable();
            $table->decimal('pondasi', 3, 2)->nullable();
            $table->decimal('sloof', 3, 2)->nullable();
            $table->decimal('mck', 3, 2)->nullable();
            $table->decimal('air_kotor', 3, 2)->nullable();
            $table->boolean('kesanggupan_berswadaya')->default(0);
            $table->enum('tipe', ['T', 'K'])->nullable();
            $table->decimal('penilaian_kerusakan', 5, 2)->nullable();
            $table->unsignedBigInteger('nilai_bantuan')->nullable();
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_verifikasi_cpb');
    }
};
