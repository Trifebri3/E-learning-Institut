<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badge_templates', function (Blueprint $table) {
            $table->id();

            // [INI KUNCINYA] 1 Badge Template hanya milik 1 Program
            $table->foreignId('program_id')->constrained()->onDelete('cascade');

            $table->string('title'); // Misal: "Penyelesaian IoT Dasar"
            $table->text('description')->nullable();
            $table->string('image_path'); // Path ke gambar badge

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badge_templates');
    }
};
