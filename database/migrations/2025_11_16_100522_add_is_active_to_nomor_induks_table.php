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
        Schema::table('nomor_induks', function (Blueprint $table) {
            // Tambahkan kolom 'is_active'
            // Kita set default 'true' agar semua nomor baru otomatis aktif
            $table->boolean('is_active')->default(true)->after('nomor_induk');
        });
    }

    public function down(): void
    {
        Schema::table('nomor_induks', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
