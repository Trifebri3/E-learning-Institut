<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pengaturan Bobot Nilai per Kelas
        Schema::create('grade_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

            // Bobot dalam Persen (Total harusnya 100%)
            // Default diset 0 agar admin wajib setting dulu
            $table->integer('weight_presensi')->default(0);
            $table->integer('weight_tugas')->default(0);
            $table->integer('weight_quiz')->default(0);
            $table->integer('weight_essay')->default(0);
            $table->integer('weight_progress')->default(0); // Bobot untuk kelengkapan materi (video/modul)
            $table->integer('weight_custom')->default(0); // Bobot total untuk komponen manual

            $table->timestamps();
        });

        // 2. Definisi Kolom Nilai Manual (Custom)
        Schema::create('custom_grade_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('name'); // Contoh: "Sikap", "Keaktifan", "Ujian Lisan"
            $table->timestamps();
        });

        // 3. Isi Nilai Manual per User
        Schema::create('custom_grade_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_grade_column_id')->constrained('custom_grade_columns')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('score', 5, 2)->default(0);
            $table->timestamps();
        });

        // 4. Rapor Akhir (Final Report) per Kelas
        Schema::create('class_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Rincian Nilai Murni (Disimpan agar history jelas)
            $table->decimal('score_presensi', 5, 2)->default(0);
            $table->decimal('score_tugas', 5, 2)->default(0);
            $table->decimal('score_quiz', 5, 2)->default(0);
            $table->decimal('score_essay', 5, 2)->default(0);
            $table->decimal('score_progress', 5, 2)->default(0);
            $table->decimal('score_custom', 5, 2)->default(0);

            $table->decimal('final_score', 5, 2)->default(0); // Nilai Akhir Terhitung
            $table->string('letter_grade')->nullable(); // A, B, C, D, E
            $table->text('feedback')->nullable(); // Umpan Balik Admin

            $table->boolean('is_passed')->default(false); // Status Lulus (Keputusan Admin)
            $table->boolean('is_published')->default(false); // Status Terbit

            $table->timestamp('generated_at')->useCurrent();
            $table->timestamps();

            $table->unique(['kelas_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_reports');
        Schema::dropIfExists('custom_grade_values');
        Schema::dropIfExists('custom_grade_columns');
        Schema::dropIfExists('grade_settings');
    }
};
