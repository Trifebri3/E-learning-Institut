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
    Schema::create('quizzes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

        $table->string('title'); // Nama: "Pre-Test", "Ujian Akhir", dll
        $table->text('description')->nullable();
        $table->integer('duration_minutes')->default(60); // Durasi

        // [FITUR BATAS PENGERJAAN]
        // Isi 1 jika hanya boleh 1x. Isi 0 atau null jika tidak terbatas.
        $table->integer('max_attempts')->default(1);

        $table->boolean('is_published')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
