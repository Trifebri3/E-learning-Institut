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
    Schema::create('essay_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('essay_submission_id')->constrained('essay_submissions')->onDelete('cascade');
        $table->foreignId('essay_question_id')->constrained('essay_questions')->onDelete('cascade');

        $table->longText('answer_text')->nullable(); // Jawaban user
        $table->decimal('score', 5, 2)->nullable(); // Nilai per soal (diisi admin)
        $table->text('notes')->nullable(); // Catatan koreksi per soal

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('essay_answers');
    }
};
