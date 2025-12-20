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
    Schema::create('forums', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();

        // Jika null = Forum Global. Jika terisi = Forum khusus Program itu.
        $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('cascade');

        // Siapa pembuatnya (Admin/Instruktur)
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
