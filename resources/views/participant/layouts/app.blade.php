<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Institut Hijau Indonesia</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="{{ asset('css/kelas.css') }}" rel="stylesheet">
    <link href="{{ asset('css/kelas-utilities.css') }}" rel="stylesheet">

    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'id',
                includedLanguages: 'id,en,ar,ja,zh-CN', // Bahasa penting saja agar list tidak kepanjangan
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>

    <script>
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.classList.toggle('dark', theme === 'dark');

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        // PALET WARNA HIJAU TUA PASTEL (Sesuai Permintaan)
                        primary: {
                            50: '#f4f9f6',
                            100: '#e1efeb',
                            200: '#c2dcd4',
                            300: '#99c2b3',
                            400: '#70a290',
                            500: '#5e8775', // WARNA UTAMA
                            600: '#4a6f5f',
                            700: '#3d594d',
                            800: '#334940',
                            900: '#2a3d35',
                        }
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 0 15px rgba(94, 135, 117, 0.4)', // Glow hijau pastel
                        'up': '0 -4px 20px -2px rgba(0, 0, 0, 0.1)',
                    }
                }
            }
        }
    </script>

    <style>
        /* Base */
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }

        /* --- PERBAIKAN GOOGLE TRANSLATE (CLEAN STYLE) --- */
        #google_translate_element {
            display: inline-block;
            vertical-align: middle;
        }
        .goog-te-gadget {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 0 !important; /* Hide default text */
            color: transparent !important;
        }
        .goog-te-gadget-simple {
            background-color: #f3f4f6 !important; /* Gray-100 */
            border: 1px solid #e5e7eb !important;
            border-radius: 99px !important; /* Pill Shape */
            padding: 6px 12px !important;
            font-size: 13px !important;
            line-height: 1.5 !important;
            display: inline-flex !important;
            align-items: center !important;
            transition: all 0.2s ease;
        }
        .dark .goog-te-gadget-simple {
            background-color: #1f2937 !important; /* Gray-800 */
            border-color: #374151 !important;
        }
        .goog-te-gadget-simple a {
            color: #4b5563 !important; /* Gray-600 */
            font-weight: 600 !important;
            text-decoration: none !important;
        }
        .dark .goog-te-gadget-simple a {
            color: #d1d5db !important;
        }
        .goog-te-gadget-simple img {
            display: none !important; /* Hide Google Icon */
        }
        /* Custom Icon before text */
        .goog-te-gadget-simple:before {
            content: "\f1ab"; /* FontAwesome Globe */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            color: #5e8775; /* Primary Color */
            margin-right: 8px;
            font-size: 14px;
        }
        .goog-te-banner-frame { display: none !important; }
        body { top: 0 !important; }
        /* ----------------------------------------------- */

        /* Custom Scrollbar */
        .scrollbar-thin::-webkit-scrollbar { width: 5px; height: 5px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark .scrollbar-thin::-webkit-scrollbar-thumb { background: #4b5563; }

        /* Layout Architecture */
        .content-wrapper { display: flex; width: 100%; height: 100vh; overflow: hidden; }

        /* Sidebar Transitions */
        .sidebar-container { width: 17rem; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); border-right: 1px solid rgba(229, 231, 235, 0.5); }
        .dark .sidebar-container { border-right: 1px solid rgba(55, 65, 81, 0.5); }
        .sidebar-collapsed { width: 5rem !important; }
        .sidebar-collapsed .sidebar-text, .sidebar-collapsed .logo-full, .sidebar-collapsed .sidebar-badge { display: none; }
        .sidebar-collapsed .logo-icon { display: flex !important; }
        .sidebar-collapsed .sidebar-toggle-icon { transform: rotate(180deg); }

        /* Main Content */
        .main-content-area { flex: 1; display: flex; flex-direction: column; overflow: hidden; background-color: #f8fafc; transition: margin-left 0.4s; }
        .dark .main-content-area { background-color: #0f172a; }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .sidebar-desktop { display: none !important; }
            .main-content-area { margin-left: 0 !important; }
            .mobile-header { backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
            /* Padding bawah extra untuk Bottom Nav */
            .content-pad-mobile { padding-top: 4.5rem; padding-bottom: 6rem; }
        }
        @media (min-width: 769px) {
            .bottom-nav { display: none !important; }
            .content-pad-desktop { padding-top: 5rem; }
        }

        /* Menu Item Styling */
        .menu-item { position: relative; transition: all 0.2s ease; }
        .menu-item:hover { transform: translateX(3px); background-color: rgba(94, 135, 117, 0.08); }
        .dark .menu-item:hover { background-color: rgba(94, 135, 117, 0.2); }

        .menu-active {
            background: linear-gradient(90deg, rgba(94, 135, 117, 0.15) 0%, transparent 100%);
            border-left: 4px solid #5e8775;
            color: #3d594d;
        }
        .dark .menu-active {
            background: linear-gradient(90deg, rgba(94, 135, 117, 0.25) 0%, transparent 100%);
            border-left: 4px solid #70a290;
            color: #c2dcd4;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-200"
      x-data="{
          sidebarOpen: false,
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
          activeBottomNav: 'dashboard',
          mobileMenuOpen: false
      }">

<header class="hidden md:flex fixed top-0 right-0 z-40 h-16 items-center transition-all duration-300"
        :class="sidebarCollapsed ? 'w-[calc(100%-5rem)]' : 'w-[calc(100%-17rem)]'">
    <div class="w-full h-full flex items-center justify-between px-6 backdrop-blur-md bg-white/80 dark:bg-gray-900/80 border-b border-gray-200/50 dark:border-gray-800/50">
        <div class="flex-1 max-w-lg">
            <div x-data="{ focused: false }" class="relative group">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 group-focus-within:text-primary-600 transition-colors"></i>
                <input type="text" placeholder="Cari..."
                       class="w-full pl-10 pr-4 py-2 rounded-full bg-gray-100/70 dark:bg-gray-800/70 border-transparent
                              focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-primary-500/30 transition-all text-sm">
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <div class="hidden lg:block text-right">
                <span class="block text-xs text-gray-500" x-text="(new Date().getHours() < 12 ? 'Selamat Pagi' : new Date().getHours() < 18 ? 'Selamat Sore' : 'Selamat Malam') + ','"></span>
                <span class="block text-sm font-bold text-primary-700 dark:text-primary-400">{{ Auth::user()->name }}</span>
            </div>

            <div id="google_translate_element" class="hidden xl:block"></div>

            <button onclick="toggleTheme()" class="w-9 h-9 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center hover:bg-primary-50 dark:hover:bg-gray-700 text-gray-500 transition-colors">
                <i class="fas fa-moon dark:hidden"></i><i class="fas fa-sun hidden dark:block text-yellow-400"></i>
            </button>

            <div class="relative">
                 @php
                    $user = Auth::user();
                    $profile = $user->profile;
                    $fotoPath = $profile?->pas_foto_path;
                    $profileUrl = ($fotoPath && Storage::disk('public')->exists($fotoPath)) ? asset('storage/' . $fotoPath) : asset('images/defaultprofil.svg');
                @endphp
                <img src="{{ $profileUrl }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 dark:border-gray-700">
            </div>
        </div>
    </div>
</header>

<header class="md:hidden fixed top-0 w-full z-40 h-16 mobile-header border-b border-gray-200/50 dark:border-gray-800/50 bg-white/80 dark:bg-gray-900/80">
    <div class="flex items-center justify-between px-4 h-full">
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo-light.png') }}" alt="Logo" class="h-8 w-auto dark:hidden">
            <img src="{{ asset('images/logo-dark.png') }}" alt="Logo" class="h-8 w-auto hidden dark:block">
        </div>

        <div class="flex items-center space-x-3">
             <div id="google_translate_element" class="scale-75 origin-right"></div> <button onclick="toggleTheme()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500">
                <i class="fas fa-moon dark:hidden text-xs"></i><i class="fas fa-sun hidden dark:block text-xs"></i>
            </button>
            <div class="relative">
                 <img src="{{ $profileUrl }}" class="w-8 h-8 rounded-full object-cover border border-gray-200">
            </div>
        </div>
    </div>
</header>

<div class="content-wrapper">
    <aside class="sidebar-desktop sidebar-container bg-white dark:bg-gray-900 flex flex-col z-50 relative shadow-soft"
           :class="{'sidebar-collapsed': sidebarCollapsed}">

        <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
                class="absolute -right-3 top-20 z-50 w-7 h-7 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full flex items-center justify-center shadow-md text-gray-500 hover:text-primary-600 transition-all">
            <i class="fas fa-chevron-left text-[10px] sidebar-toggle-icon"></i>
        </button>

        <div class="h-28 flex items-center justify-center px-4 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-b from-primary-50/50 to-transparent">
            <div class="logo-full transition-opacity duration-300" x-show="!sidebarCollapsed">
                <img src="{{ asset('images/logo-light.png') }}" class="h-20 w-auto dark:hidden" alt="Logo">
                <img src="{{ asset('images/logo-dark.png') }}" class="h-20 w-auto hidden dark:block" alt="Logo">
            </div>
            <div class="logo-icon hidden w-10 h-10 bg-primary-500 rounded-xl items-center justify-center shadow-lg shadow-primary-500/30 text-white">
                <!-- Mini logo when collapsed -->
<img
    src="{{ asset('images/logo-light.png') }}"
    alt="Logo Mini"
    class="w-auto h-10 dark:hidden transition-all duration-300"
    x-show="sidebarCollapsed"
    x-transition.opacity.duration.300ms
>

<img
    src="{{ asset('images/logo-dark.png') }}"
    alt="Logo Mini"
    class="w-auto h-10 hidden dark:block transition-all duration-300"
    x-show="sidebarCollapsed"
    x-transition.opacity.duration.300ms
>

            </div>
        </div>

        <nav class="flex-1 overflow-y-auto scrollbar-thin px-3 py-4 space-y-1">
            <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 sidebar-text">Umum</p>
            <a href="{{ route('participant.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.dashboard') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-home w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Dashboard</span>
            </a>
            <a href="{{ route('participant.announcements.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.announcements.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-bullhorn w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Pengumuman</span>
            </a>
             <a href="{{ route('participant.program.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.program.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-project-diagram w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Program</span>
            </a>

            <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 mt-4 sidebar-text">Akademik</p>
            <a href="{{ route('participant.kelas.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.kelas.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-chalkboard-teacher w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Kelas Saya</span>
                <span class="sidebar-badge ml-auto bg-primary-100 text-primary-700 text-[10px] px-2 py-0.5 rounded-full font-bold">Aktif</span>
            </a>
             <a href="{{ route('participant.assignments.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.assignments.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-tasks w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Tugas Mandiri</span>
            </a>
            <a href="{{ route('participant.materi.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.materi.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-book w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Materi</span>
            </a>
             <a href="{{ route('participant.progress.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.progress.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-chart-line w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Nilai & Progres</span>
            </a>

             <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 mt-4 sidebar-text">Lainnya</p>
             <a href="{{ route('participant.discussion.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.discussion.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-comments w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Diskusi</span>
            </a>
             <a href="{{ route('participant.badges.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.badges.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-shield-alt w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Lencana</span>
            </a>
             <a href="{{ route('participant.support.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.support.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-life-ring w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Bantuan</span>
            </a>
             <a href="{{ route('participant.profil.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium menu-item {{ request()->routeIs('participant.profil.*') ? 'menu-active' : 'text-gray-600 dark:text-gray-400' }}">
                <i class="fas fa-user-cog w-5 text-center text-lg"></i><span class="sidebar-text ml-3">Profil & Akun</span>
            </a>
{{-- Tombol kembali ke admin (hanya saat impersonate) --}}
@if (session()->has('impersonate_admin_id'))
    <div class="pt-3 mt-3 border-t border-gray-200 dark:border-gray-700">
        <a href="{{ route('superadmin.users.leave-impersonate') }}"
           class="flex items-center px-3 py-2 rounded bg-red-500 text-white hover:bg-red-600">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke SuperAdmin
        </a>
    </div>
@endif


        </nav>

        <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/30">
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                    <i class="fas fa-power-off w-5 text-center"></i><span class="sidebar-text ml-3 text-sm font-medium">Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content-area overflow-y-auto scrollbar-thin scroll-smooth">
        <div class="flex-1 w-full max-w-8xl mx-auto content-pad-desktop content-pad-mobile px-4 sm:px-6 lg:px-8">

            @yield('content')

            <footer class="mt-12 mb-6 pt-8 border-t border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400">
                <div class="container mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <h5 class="font-bold text-primary-700 dark:text-primary-400 mb-2 flex items-center gap-2 text-lg">
                                <i class="fas fa-graduation-cap"></i>Learning Management System
                            </h5>
                            <p class="text-sm">
                                Program pendidikan kepemimpinan berkelanjutan untuk membangun pemimpin masa depan melalui pembelajaran interaktif.
                            </p>
                            <div class="mt-4">
                                <img src="{{ asset('images/logo-light.png') }}" alt="Logo" class="h-20 w-auto dark:hidden">
                                <img src="{{ asset('images/logo-dark.png') }}" alt="Logo" class="h-20 w-auto hidden dark:block">
                            </div>
                        </div>

                        <div>
                            <h5 class="font-bold text-primary-700 dark:text-primary-400 mb-2 flex items-center gap-2 text-lg">
                                <i class="fas fa-headset"></i>Kontak Kami
                            </h5>
                            <ul class="text-sm space-y-2">
                                <li class="flex items-center gap-2 hover:text-primary-600"><i class="fas fa-envelope w-4"></i> instituthijau.id@gmail.com</li>
                                <li class="flex items-center gap-2 hover:text-primary-600"><i class="fas fa-phone w-4"></i> +62 821-2971-1623 (IT Support)</li>
                                <li class="flex items-center gap-2 hover:text-primary-600"><i class="fas fa-map-marker-alt w-4"></i> Jalan Palapa XVII Nomor 3 Jakarta Selatan</li>
                            </ul>
                             <div class="mt-4 flex flex-wrap gap-2">
                                <a href="#" class="text-primary-600 border border-primary-600 text-xs px-3 py-1.5 rounded-lg hover:bg-primary-600 hover:text-white transition-colors"><i class="fas fa-question-circle"></i> Bantuan</a>
                                <a href="{{ route('participant.support.index') }}" class="text-primary-600 border border-primary-600 text-xs px-3 py-1.5 rounded-lg hover:bg-primary-600 hover:text-white transition-colors"><i class="fas fa-bug"></i> Laporkan Masalah</a>
                            </div>
                        </div>

                        <div>
                            <h5 class="font-bold text-primary-700 dark:text-primary-400 mb-2 flex items-center gap-2 text-lg">
                                <i class="fas fa-users"></i>Terhubung Dengan Kami
                            </h5>
                             <div class="flex flex-wrap gap-2 mb-4">
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white transition-colors"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white transition-colors"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-xl border-l-4 border-primary-500 text-xs">
                                <h6 class="font-bold text-primary-700 dark:text-primary-400 mb-1"><i class="fas fa-code"></i> Dikembangkan Oleh</h6>
                                <p class="mb-1">
                                    Tim IT Institut Hijau Indonesia<br>
                                    <span class="text-primary-600 font-semibold">Learning Management System v2.0</span>
                                </p>
                                <a href="https://github.com/Trifebri3" target="_blank" class="block mt-1 font-bold hover:text-primary-600">Developer : <i class="fas fa-cat"></i> Teriyaki#3</a>
                                <p class="mt-2 text-gray-400">&copy; 2025 Hak Cipta Dilindungi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>
</div>

<div x-show="mobileMenuOpen"
     style="display: none;"
     class="fixed inset-0 z-[60] bg-white dark:bg-gray-900 flex flex-col md:hidden transition-all duration-300"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-full"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-full">

    <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-800">
        <span class="font-bold text-lg text-primary-700 dark:text-primary-400">Semua Menu</span>
        <button @click="mobileMenuOpen = false" class="w-8 h-8 bg-gray-100 rounded-full text-gray-600 hover:bg-gray-200">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-5 grid grid-cols-3 gap-4 content-start">
        <a href="{{ route('participant.dashboard') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 group">
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-200"><i class="fas fa-home"></i></div>
            <span class="text-xs font-medium text-center text-gray-600 dark:text-gray-300">Dashboard</span>
        </a>
        <a href="{{ route('participant.announcements.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 group">
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-200"><i class="fas fa-bullhorn"></i></div>
            <span class="text-xs font-medium text-center text-gray-600 dark:text-gray-300">Info</span>
        </a>
        <a href="{{ route('participant.program.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 group">
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-200"><i class="fas fa-project-diagram"></i></div>
            <span class="text-xs font-medium text-center text-gray-600 dark:text-gray-300">Program</span>
        </a>
        <a href="{{ route('participant.kelas.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 group">
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-200"><i class="fas fa-chalkboard-teacher"></i></div>
            <span class="text-xs font-medium text-center text-gray-600 dark:text-gray-300">Kelas</span>
        </a>
        <a href="{{ route('participant.assignments.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 group">
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-200"><i class="fas fa-tasks"></i></div>
            <span class="text-xs font-medium text-center text-gray-600 dark:text-gray-300">Tugas</span>
        </a>
        <a href="{{ route('participant.materi.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 group">
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-200"><i class="fas fa-book"></i></div>
            <span class="text-xs font-medium text-center text-gray-600 dark:text-gray-300">Materi</span>
        </a>
        <a href="{{ route('participant.progress.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 group">
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-200"><i class="fas fa-chart-line"></i></div>
            <span class="text-xs font-medium text-center text-gray-600 dark:text-gray-300">Nilai</span>
        </a>
        <a href="{{ route('participant.discussion.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 group">
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-200"><i class="fas fa-comments"></i></div>
            <span class="text-xs font-medium text-center text-gray-600 dark:text-gray-300">Diskusi</span>
        </a>
        <a href="{{ route('participant.profil.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 group">
            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center group-hover:bg-primary-200"><i class="fas fa-user-cog"></i></div>
            <span class="text-xs font-medium text-center text-gray-600 dark:text-gray-300">Profil</span>
        </a>
    </div>

    <div class="p-5 border-t border-gray-100 dark:border-gray-800">
         <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full py-3 bg-red-50 text-red-600 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-red-100 transition-colors">
                <i class="fas fa-power-off"></i> Log Out
            </button>
        </form>
    </div>
</div>

<div class="md:hidden fixed bottom-0 left-0 right-0 h-[70px] bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-t border-gray-200/60 dark:border-gray-800/60 z-50 shadow-up">
    <div class="grid grid-cols-5 h-full items-center justify-items-center">

        <a href="{{ route('participant.dashboard') }}"
           class="flex flex-col items-center justify-center w-full h-full space-y-1 group"
           :class="activeBottomNav === 'dashboard' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400'">
            <i class="fas fa-home text-lg mb-0.5 transition-transform group-active:scale-90"></i>
            <span class="text-[10px] font-medium">Home</span>
        </a>

        <a href="{{ route('participant.program.index') }}"
           class="flex flex-col items-center justify-center w-full h-full space-y-1 group"
           :class="activeBottomNav === 'program' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400'">
            <i class="fas fa-project-diagram text-lg mb-0.5 transition-transform group-active:scale-90"></i>
            <span class="text-[10px] font-medium">Program</span>
        </a>

        <a href="{{ route('participant.kelas.index') }}"
           class="relative -top-6 flex flex-col items-center justify-center">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 shadow-glow flex items-center justify-center text-white border-4 border-white dark:border-gray-900 transform transition-transform active:scale-95">
                <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
            <span class="text-[10px] font-medium mt-1 text-primary-600 dark:text-primary-400">Kelas</span>
        </a>

        <a href="{{ route('participant.assignments.index') }}"
           class="flex flex-col items-center justify-center w-full h-full space-y-1 group"
           :class="activeBottomNav === 'assignments' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400'">
            <i class="fas fa-tasks text-lg mb-0.5 transition-transform group-active:scale-90"></i>
            <span class="text-[10px] font-medium">Tugas</span>
        </a>

        <button @click="mobileMenuOpen = !mobileMenuOpen"
           class="flex flex-col items-center justify-center w-full h-full space-y-1 group text-gray-400 hover:text-primary-600">
            <i class="fas fa-th-large text-lg mb-0.5 transition-transform group-active:scale-90" :class="mobileMenuOpen ? 'text-primary-600' : ''"></i>
            <span class="text-[10px] font-medium" :class="mobileMenuOpen ? 'text-primary-600' : ''">Menu</span>
        </button>

    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        const theme = html.classList.contains('dark') ? 'dark' : 'light';
        localStorage.setItem('theme', theme);
    }

    // Bottom Nav Active Logic
    document.addEventListener('DOMContentLoaded', () => {
        const path = window.location.pathname;
        const alpineEl = document.querySelector('[x-data]');
        if(alpineEl && alpineEl.__x) {
            const data = alpineEl.__x.$data;
            if(path.includes('dashboard')) data.activeBottomNav = 'dashboard';
            else if(path.includes('program')) data.activeBottomNav = 'program';
            else if(path.includes('kelas')) data.activeBottomNav = 'classes';
            else if(path.includes('assignments')) data.activeBottomNav = 'assignments';
        }
    });
</script>

</body>
</html>
