<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badge_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_template_id')->constrained()->onDelete('cascade');
            $table->timestamp('earned_at'); // Kapan badge ini didapat

            // Mencegah user dapat badge yang sama 2x
            $table->unique(['user_id', 'badge_template_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badge_user');
    }
};
