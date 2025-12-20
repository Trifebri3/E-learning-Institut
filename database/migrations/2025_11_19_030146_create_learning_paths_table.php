<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->id();
            // Setiap kelas HANYA memiliki SATU Learning Path
            $table->foreignId('kelas_id')->unique()->constrained('kelas')->onDelete('cascade');
            $table->string('title')->default('Kurikulum Utama');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('learning_paths'); }
};
