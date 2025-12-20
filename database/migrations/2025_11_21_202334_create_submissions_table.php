<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('submission_link'); // Link hasil tugas (Wajib)
            $table->text('notes')->nullable(); // Catatan dari peserta

            $table->dateTime('submitted_at')->useCurrent();
            $table->boolean('is_late')->default(false);

            // Status Penilaian
            $table->boolean('is_graded')->default(false);
            $table->integer('score')->nullable();

            // 1 user hanya bisa 1x submit per tugas (untuk versi ini)
            $table->unique(['assignment_id', 'user_id']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('submissions'); }
};
