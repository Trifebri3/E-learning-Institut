<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::table('users', function (Blueprint $table) {
                // Menyimpan waktu persetujuan. Null = belum setuju.
                $table->timestamp('agreed_to_tos_at')->nullable()->after('email_verified_at');
            });
        }

        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('agreed_to_tos_at');
            });
        }
    };
