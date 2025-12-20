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
    Schema::create('resources', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

        $table->string('title'); // Judul E-book/Dokumen
        $table->text('description')->nullable();

        // File dan Link (Setidaknya salah satu harus ada)
        $table->string('file_path')->nullable(); // Dokumen (Opsional)
        $table->string('link_url')->nullable(); // Link (Wajib jika file kosong)

        $table->boolean('is_published')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
