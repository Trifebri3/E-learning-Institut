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
    Schema::create('announcements', function (Blueprint $table) {
        $table->id();

        // Pembuat Pengumuman (Super Admin, Instructor, dll)
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

        // Tipe: 'global' (Semua orang) atau 'program' (Spesifik)
        $table->enum('type', ['global', 'program'])->default('program');

        // Jika tipe 'program', kolom ini terisi. Jika 'global', ini NULL.
        $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('cascade');

        $table->string('title');
        $table->text('content');
        $table->string('attachment_path')->nullable(); // Opsional: Lampiran PDF/Gambar

        // Tingkat urgensi (untuk warna tampilan)
        $table->enum('priority', ['normal', 'important', 'critical'])->default('normal');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
