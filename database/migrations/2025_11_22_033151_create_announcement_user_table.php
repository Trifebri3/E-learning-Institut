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
    Schema::create('announcement_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('announcement_id')->constrained('announcements')->onDelete('cascade');

        $table->timestamp('read_at')->useCurrent(); // Waktu konfirmasi dibaca

        $table->unique(['user_id', 'announcement_id']); // 1 user 1x konfirmasi per pengumuman
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcement_user');
    }
};
