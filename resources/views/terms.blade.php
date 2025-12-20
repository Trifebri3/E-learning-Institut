@extends('layouts.app')

@section('title', 'Syarat & Ketentuan Layanan')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <!-- Header dengan ikon dan gradien -->
    <div class="text-center mb-8 md:mb-12">
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-3">
            Syarat & Ketentuan Layanan
        </h1>
        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-green-500 mx-auto rounded-full mb-3"></div>
        <p class="text-sm md:text-base text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Penyangkalan Tanggung Jawab (Disclaimer) & Persetujuan Pengguna
        </p>
    </div>

    <!-- Isi Dokumen (Scrollable) dengan desain lebih baik -->
    <div class="h-80 md:h-96 overflow-y-auto p-4 md:p-6 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 mb-6 md:mb-8 text-sm md:text-base text-gray-700 dark:text-gray-300 leading-relaxed shadow-lg">
        <!-- Progress indicator -->
        <div class="sticky top-0 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm py-2 mb-4 -mt-2 border-b border-gray-200 dark:border-gray-700 z-10">
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                <span>Dokumen Syarat & Ketentuan</span>
                <span id="scroll-progress">0%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1">
                <div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-green-500 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
        </div>

        <!-- Content dengan numbering yang lebih jelas -->
        <div class="space-y-6">
            <div class="term-section">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <span class="text-blue-700 dark:text-blue-300 font-bold text-sm">1</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">Pendahuluan</h3>
                        <p>
                            Selamat datang di Platform E-Learning Nasional ("Layanan"). Dengan mengakses atau menggunakan Layanan ini, Anda setuju untuk terikat oleh syarat dan ketentuan berikut. Jika Anda tidak setuju dengan bagian mana pun dari syarat ini, Anda dilarang menggunakan Layanan ini.
                        </p>
                    </div>
                </div>
            </div>

            <div class="term-section">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <span class="text-blue-700 dark:text-blue-300 font-bold text-sm">2</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">Batasan Tanggung Jawab Tim Pengembang (Developer Disclaimer)</h3>
                        <p class="mb-2">
                            <strong class="text-gray-900 dark:text-white">A. Integritas Data:</strong> Tim Pengembang (Developer) telah berupaya sebaik mungkin untuk memastikan keamanan dan keandalan sistem. Namun, Tim Pengembang <strong class="text-red-600 dark:text-red-400">TIDAK BERTANGGUNG JAWAB</strong> atas kehilangan data, kebocoran informasi, atau kerusakan sistem yang disebabkan oleh:
                        </p>
                        <ul class="list-disc ml-6 mt-1 mb-2 space-y-1">
                            <li>Kelalaian pengguna (seperti membagikan password atau kode OTP).</li>
                            <li>Kegagalan perangkat keras atau jaringan di sisi pengguna.</li>
                            <li>Serangan siber (cyber-attack) yang berada di luar kendali wajar sistem keamanan kami.</li>
                            <li>Force majeure (bencana alam, gangguan listrik massal, dll).</li>
                        </ul>
                        <p>
                            <strong class="text-gray-900 dark:text-white">B. Konten Pembelajaran:</strong> Tim Pengembang hanya menyediakan <em>platform</em> teknologi. Seluruh materi, video, modul, dan konten pembelajaran adalah tanggung jawab penuh dari <strong class="text-green-600 dark:text-green-400">Penyedia Konten (Instruktur/Admin Program)</strong>. Tim Pengembang tidak bertanggung jawab atas keakuratan, legalitas, atau kualitas materi yang diunggah.
                        </p>
                    </div>
                </div>
            </div>

            <div class="term-section">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <span class="text-blue-700 dark:text-blue-300 font-bold text-sm">3</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">Kewajiban Pengguna</h3>
                        <p class="mb-2">
                            Sebagai pengguna, Anda setuju untuk:
                        </p>
                        <ul class="list-disc ml-6 mt-1 space-y-1">
                            <li>Menggunakan data identitas asli dan valid (Nomor Induk, KTP).</li>
                            <li>Tidak menyebarluaskan materi pembelajaran yang memiliki hak cipta keluar dari platform ini.</li>
                            <li>Menjaga kerahasiaan akun Anda. Segala aktivitas yang terjadi di bawah akun Anda adalah tanggung jawab Anda sepenuhnya.</li>
                            <li>Berperilaku sopan dan profesional dalam fitur Diskusi, Forum, dan Chat.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="term-section">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <span class="text-blue-700 dark:text-blue-300 font-bold text-sm">4</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">Sanksi & Penangguhan</h3>
                        <p>
                            Penyelenggara berhak untuk menangguhkan (suspend) atau menghapus akun Anda secara permanen tanpa pemberitahuan sebelumnya jika ditemukan indikasi pelanggaran terhadap syarat dan ketentuan ini, termasuk namun tidak terbatas pada kecurangan presensi, manipulasi nilai, atau tindakan perundungan (bullying).
                        </p>
                    </div>
                </div>
            </div>

            <div class="term-section">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <span class="text-blue-700 dark:text-blue-300 font-bold text-sm">5</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg mb-2 text-gray-900 dark:text-white">Perubahan Ketentuan</h3>
                        <p>
                            Kami berhak untuk mengubah atau mengganti Syarat ini kapan saja. Merupakan tanggung jawab Anda untuk meninjau Syarat ini secara berkala.
                        </p>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-gray-300 dark:border-gray-600">

            <div class="text-center py-4">
                <div class="inline-flex items-center px-4 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                    <i class="fas fa-calendar-check text-blue-500 dark:text-blue-400 mr-2"></i>
                    <p class="text-xs text-gray-600 dark:text-gray-400">
                        Dokumen ini dibuat dan disahkan pada <span class="font-semibold">{{ now()->format('d F Y') }}</span>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Persetujuan dengan desain lebih baik -->
    <form method="POST" action="{{ route('terms.accept') }}" class="mt-6 md:mt-8">
        @csrf

        <!-- Checkbox dengan desain lebih menarik -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 md:p-6 mb-6 transition-all duration-300 hover:shadow-md">
            <div class="flex items-start">
                <div class="flex items-center h-5 mt-1">
                    <input id="agreement" name="agreement" type="checkbox" required
                           class="w-5 h-5 border border-gray-300 rounded bg-white dark:bg-gray-800 focus:ring-3 focus:ring-blue-300 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 transition duration-150">
                </div>
                <label for="agreement" class="ml-3 text-sm md:text-base text-gray-900 dark:text-gray-300 cursor-pointer">
                    <span class="font-semibold">Saya telah membaca, memahami, dan menyetujui seluruh</span>
                    <span class="text-blue-600 dark:text-blue-400 font-bold">Syarat & Ketentuan Layanan</span>
                    <span class="font-semibold">serta melepaskan Tim Pengembang dari tuntutan di luar batas tanggung jawab yang disebutkan.</span>
                </label>
            </div>
            @error('agreement')
                <p class="text-red-500 text-sm mt-2 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Tombol aksi dengan layout responsif -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 md:gap-4">
            <!-- Tombol Logout (jika tidak setuju) -->
            <button type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="order-2 sm:order-1 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:bg-gray-600 transition-all duration-300 flex items-center justify-center">
                <i class="fas fa-sign-out-alt mr-2"></i>
                Tolak & Logout
            </button>

            <!-- Tombol Setuju -->
            <button type="submit" class="order-1 sm:order-2 px-6 md:px-8 py-3 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-green-600 rounded-lg hover:from-blue-700 hover:to-green-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center">
                <i class="fas fa-check-circle mr-2"></i>
                Saya Setuju & Lanjutkan
            </button>
        </div>
    </form>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
</div>

<style>
    .term-section {
        border-left: 3px solid #3b82f6;
        padding-left: 1rem;
        margin-left: 0.5rem;
    }

    .dark .term-section {
        border-left-color: #60a5fa;
    }

    /* Custom scrollbar */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .dark .overflow-y-auto::-webkit-scrollbar-track {
        background: #374151;
    }

    .dark .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #6b7280;
    }

    /* Animasi untuk section terms */
    .term-section {
        opacity: 0;
        transform: translateY(10px);
        animation: fadeInUp 0.5s ease forwards;
    }

    .term-section:nth-child(1) { animation-delay: 0.1s; }
    .term-section:nth-child(2) { animation-delay: 0.2s; }
    .term-section:nth-child(3) { animation-delay: 0.3s; }
    .term-section:nth-child(4) { animation-delay: 0.4s; }
    .term-section:nth-child(5) { animation-delay: 0.5s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scrollContainer = document.querySelector('.overflow-y-auto');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('scroll-progress');

        // Progress indicator
        scrollContainer.addEventListener('scroll', function() {
            const scrollHeight = scrollContainer.scrollHeight - scrollContainer.clientHeight;
            const scrolled = (scrollContainer.scrollTop / scrollHeight) * 100;
            progressBar.style.width = scrolled + '%';
            progressText.textContent = Math.round(scrolled) + '%';
        });

        // Highlight current section while scrolling
        const sections = document.querySelectorAll('.term-section');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        sections.forEach(section => {
            observer.observe(section);
        });

        // Add pulse animation to checkbox when page loads
        const checkbox = document.getElementById('agreement');
        setTimeout(() => {
            checkbox.parentElement.classList.add('animate-pulse');
            setTimeout(() => {
                checkbox.parentElement.classList.remove('animate-pulse');
            }, 2000);
        }, 1000);
    });
</script>
@endsection
