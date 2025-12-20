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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Program (Penting)
            $table->string('redeem_code')->unique(); // Kode untuk redeem, unik

            $table->string('logo_path')->nullable();
            $table->string('banner_path')->nullable();

            $table->text('deskripsi_singkat')->nullable();
            $table->text('deskripsi_lengkap')->nullable();

            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai');

            $table->integer('kuota')->default(0); // Total kuota
            $table->string('lokasi'); // Bisa "Online", "Jakarta", dll.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
