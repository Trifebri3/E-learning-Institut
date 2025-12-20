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
    Schema::create('narasumbers', function (Blueprint $table) {
        $table->id();
        // Terikat ke Program
        $table->foreignId('program_id')->constrained()->onDelete('cascade');

        $table->string('nama');
        $table->string('foto_path')->nullable(); // Foto Profil
        $table->text('deskripsi'); // Bio / Profile
        $table->string('jabatan')->nullable(); // Tambahan: Misal "CEO at Google"
        $table->string('kontak')->nullable(); // Email/Linkedin/IG (Opsional)

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('narasumbers');
    }
};
