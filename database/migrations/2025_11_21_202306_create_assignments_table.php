<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); // Terikat ke Kelas

            $table->string('title');
            $table->text('description'); // Deskripsi dan instruksi tugas
            $table->dateTime('due_date'); // Batas waktu pengumpulan
            $table->integer('max_points')->default(100); // Bobot nilai maksimum

            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assignments'); }
};
