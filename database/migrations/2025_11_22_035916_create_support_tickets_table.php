<?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

                // Jika laporan terkait program (Izin/Akademik), isi ini.
                // Jika laporan umum/IT, ini bisa null.
                $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('cascade');

                // Kategori Laporan
                $table->enum('category', [
                    'general',      // Laporan Umum (Perundungan dll)
                    'academic',     // Kendala Kelas/Program
                    'permission',   // Pengajuan Izin
                    'system'        // Gangguan Sistem (IT)
                ]);

                $table->string('subject'); // Judul Laporan
                $table->text('description'); // Detail Laporan
                $table->string('attachment_path')->nullable(); // Bukti Foto/PDF

                // Status Tiket
                $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');

                // Balasan Admin (Sederhana)
                $table->text('admin_reply')->nullable();

                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('support_tickets');
        }
    };
