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
    Schema::table('users', function (Blueprint $table) {
        // Tambahkan kolom google_id
        $table->string('google_id')->nullable()->after('id');

        // Ubah agar password bisa null
        $table->string('password')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('google_id');
        $table->string('password')->nullable(false)->change(); // Kembalikan
    });
}
};
