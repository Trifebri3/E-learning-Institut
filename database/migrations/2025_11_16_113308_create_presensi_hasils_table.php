<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi_hasils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

            // Menyimpan Nomor Induk yang dipakai saat itu
            $table->foreignId('nomor_induk_id')->constrained('nomor_induks')->onDelete('cascade');

            // Data Presensi Awal
            $table->text('refleksi_awal')->nullable();
            $table->dateTime('waktu_presensi_awal')->nullable();

            // Data Presensi Akhir
            $table->text('refleksi_akhir')->nullable();
            $table->dateTime('waktu_presensi_akhir')->nullable();

            // Status Kehadiran (Full, Sebagian, Alpha)
            $table->enum('status_kehadiran', ['alpha', 'hadir_awal', 'hadir_akhir', 'hadir_full'])
                  ->default('alpha');

            $table->timestamps();

            // 1 user hanya bisa 1x entry per kelas
            $table->unique(['user_id', 'kelas_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('presensi_hasils'); }
};
