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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade'); // Terikat ke 1 program

            $table->string('title'); // Judul Kelas (Penting)
            $table->enum('tipe', ['materi', 'interaktif']); // Tipe kelas

            $table->string('link_zoom')->nullable(); // Khusus untuk 'interaktif'
            $table->text('deskripsi');
            $table->string('banner_path')->nullable();

            $table->date('tanggal'); // Waktu (Tanggal)
            $table->time('jam_mulai'); // Waktu (Jam Mulai)
            $table->time('jam_selesai')->nullable(); // (Pengembangan)

            $table->string('tempat'); // Misal: "Online", "Zoom", "Aula"

            // (Pengembangan) Untuk Admin bisa publish/draft
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
