<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('path_section_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('path_section_id')->constrained('path_sections')->onDelete('cascade');
            $table->timestamp('completed_at')->useCurrent();

            $table->unique(['user_id', 'path_section_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('path_section_user'); }
};
