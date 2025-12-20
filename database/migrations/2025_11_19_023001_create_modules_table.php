<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

            $table->string('title');
            $table->text('content'); // Isi materi modul (HTML/Markdown)

            // KUNCI: Menentukan apakah ini wajib dibaca sebelum lanjut
            $table->boolean('is_mandatory')->default(true);

            $table->integer('order')->default(1); // Urutan modul
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('modules'); }
};
