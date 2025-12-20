<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_setups', function (Blueprint $table) {
            $table->id();
            // Terikat ke 1 kelas
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

            // Token unik untuk Awal dan Akhir
            $table->string('token_awal');
            $table->string('token_akhir');

            // Jadwal presensi Awal
            $table->dateTime('buka_awal'); // Waktu & durasi buka (mulai)
            $table->dateTime('tutup_awal'); // Waktu & durasi buka (selesai)

            // Jadwal presensi Akhir
            $table->dateTime('buka_akhir');
            $table->dateTime('tutup_akhir');

            $table->boolean('is_active')->default(false); // Admin bisa nyalakan/matikan
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('presensi_setups'); }
};
