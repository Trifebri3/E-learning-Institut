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
    Schema::create('custom_grade_user', function (Blueprint $table) {
        $table->id();
        $table->foreignId('custom_grade_id')->constrained('custom_grades')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        $table->decimal('score', 5, 2)->default(0); // Nilai manual
        $table->text('feedback')->nullable();

        $table->timestamps();
        $table->unique(['custom_grade_id', 'user_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_grade_user');
    }
};
