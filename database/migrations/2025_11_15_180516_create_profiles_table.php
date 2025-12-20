<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Terhubung ke user

            // Flag untuk middleware
            $table->boolean('is_complete')->default(false);

            // 1. Data Pribadi Dasar
            $table->string('nama_panggilan')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kewarganegaraan')->default('WNI')->nullable();
            $table->string('agama')->nullable();
            $table->string('golongan_darah', 3)->nullable();
            $table->text('deskripsi_singkat')->nullable();

            // 2. Identitas Kependudukan
            $table->enum('jenis_identitas', ['KTP', 'Paspor', 'SIM', 'Kartu Pelajar'])->nullable();
            $table->string('nomor_identitas')->nullable(); // Untuk NIK, Paspor, dll.

            // 3. Data Kontak
            $table->string('nomor_hp')->nullable();
            $table->string('email_cadangan')->nullable();
            $table->string('kontak_darurat_nama')->nullable();
            $table->string('kontak_darurat_hubungan')->nullable();
            $table->string('kontak_darurat_nomor')->nullable();

            // 4. Alamat
            $table->integer('provinsi_id')->nullable(); // Sesuai permintaan Anda
            $table->string('kabupaten_kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan_desa')->nullable();
            $table->string('rt_rw', 7)->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->text('alamat_lengkap')->nullable();

            // 5. Pendidikan / Pekerjaan
            $table->string('status_peserta', 50)->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('nama_sekolah_kampus')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('nisn_nim')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('instansi_perusahaan')->nullable();
            $table->string('jabatan')->nullable();

            // 8. Dokumen (Simpan path filenya)
            $table->string('scan_ktp_path')->nullable();
            $table->string('pas_foto_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
