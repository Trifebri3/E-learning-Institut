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
    Schema::create('video_embed_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('video_embed_id')->constrained('video_embeds')->onDelete('cascade');
        $table->timestamp('watched_at')->useCurrent(); // Kapan selesai ditonton
    $table->timestamps(); 
        $table->unique(['user_id', 'video_embed_id']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_embed_user');
    }
};
