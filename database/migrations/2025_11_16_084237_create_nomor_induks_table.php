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
        Schema::create('nomor_induks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pemilik Nomor Induk
            $table->string('nomor_induk')->unique(); // Nomornya kita buat unik

            // Ini "kunci" nya. Null berarti BEBAS, terisi berarti SUDAH DIPAKAI.
            $table->foreignId('program_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nomor_induks');
    }
};
