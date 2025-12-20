<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('path_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_path_id')->constrained('learning_paths')->onDelete('cascade');

            $table->string('title'); // Cth: Bagian 1: Pengenalan Arsitektur Jaringan
            $table->text('content');
            $table->string('image_path')->nullable(); // Dukungan Gambar
            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('path_sections'); }
};
