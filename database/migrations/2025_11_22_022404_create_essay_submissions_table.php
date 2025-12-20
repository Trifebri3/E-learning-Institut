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
    Schema::create('essay_submissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('essay_exam_id')->constrained('essay_exams')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        $table->dateTime('started_at');
        $table->dateTime('submitted_at')->nullable();

        // Status: ongoing, submitted, graded
        $table->string('status')->default('ongoing');
        $table->decimal('final_score', 5, 2)->nullable(); // Nilai Akhir
        $table->text('admin_feedback')->nullable(); // Feedback global dari admin

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('essay_submissions');
    }
};
