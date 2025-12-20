<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Dark Mode Script -->
    <script>
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.classList.toggle('dark', theme === 'dark');

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .dark {
            color-scheme: dark;
        }

        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, transform, opacity;
            transition-duration: 0.3s;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Scrollbar styling */
        .scrollbar-thin {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .dark .scrollbar-thin {
            scrollbar-color: #4b5563 #374151;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 4px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-track {
            background: #374151;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        /* Mobile optimizations */
        @media (max-width: 768px) {
            .menu-item {
                min-height: 44px;
                padding: 12px 16px;
            }

            .sidebar-mobile-full {
                width: 85vw;
                max-width: 300px;
            }
        }

        /* Touch-friendly buttons */
        .touch-button {
            min-height: 44px;
            min-width: 44px;
        }

        /* Menu item hover effect */
        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.1), transparent);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .menu-item:hover::before {
            width: 100%;
        }

        /* Sidebar layout untuk rata atas dan bawah */
        .sidebar-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .sidebar-header {
            flex-shrink: 0;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-footer {
            flex-shrink: 0;
        }

        /* Header floating */
        .floating-header {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 30;
            margin-left: 16rem; /* Sesuai dengan lebar sidebar di desktop */
        }

        @media (max-width: 768px) {
            .floating-header {
                margin-left: 0;
            }
        }

        /* Main content yang hemat dan efisien */
        .main-content {
            margin-top: 4rem; /* Sesuai dengan tinggi header */
            margin-left: 0;
            min-height: calc(100vh - 4rem);
        }

        @media (min-width: 768px) {
            .main-content {
                margin-left: 1rem; /* w-64 = 16rem */
            }
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <!-- Header yang mengambang di atas sebelah kanan -->
<header class="floating-header bg-white/20 dark:bg-gray-800/20 border-b border-gray-200 dark:border-gray-700 shadow-sm backdrop-blur-md">
    <div class="flex items-center justify-between px-4 md:px-6 h-16">

        <!-- Sapaaan -->
        <div class="hidden md:flex items-center space-x-2 text-gray-800 dark:text-gray-100 font-medium">
            <span x-data x-init="
                now = new Date();
                hour = now.getHours();
                greeting = (hour < 12) ? 'Selamat Pagi' : (hour < 18) ? 'Selamat Siang' : 'Selamat Sore';
                $el.innerText = greeting + ', {{ Auth::user()->name }}';
            "></span>
        </div>

        <!-- Menu toggle untuk mobile -->
        <button @click="sidebarOpen = !sidebarOpen"
                class="md:hidden flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100/20 dark:hover:bg-gray-700/20 transition-all duration-200 touch-button"
                aria-label="Toggle sidebar">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <!-- Search bar dan user info di kanan -->
        <div class="flex-1 flex items-center justify-end space-x-4">
            <!-- Search bar -->
            <div class="hidden md:flex items-center max-w-md w-full">
                <div class="relative w-full">
                    <input type="text"
                           placeholder="Cari..."
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white/20 dark:bg-gray-700/20 text-gray-900 dark:text-white backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Dark mode toggle -->
            <button onclick="toggleTheme()"
                    class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100/20 dark:hover:bg-gray-700/20 transition-all duration-200 touch-button"
                    aria-label="Toggle dark mode">
                <i class="fas fa-moon dark:hidden"></i>
                <i class="fas fa-sun hidden dark:block"></i>
            </button>

            <!-- Notifications -->
            <div class="relative">
                <button class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100/20 dark:hover:bg-gray-700/20 transition-all duration-200 touch-button"
                        aria-label="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-0 right-0 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                </button>
            </div>

            <!-- User profile dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-100/20 dark:hover:bg-gray-700/20 transition-all duration-200 touch-button"
                        aria-label="User menu">
                    <img class="w-8 h-8 rounded-full"
                         src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=22c55e&background=dcfce7' }}"
                         alt="User profile">
                    <span class="hidden md:inline text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
                </button>

                <!-- Dropdown menu -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white/90 dark:bg-gray-800/90 rounded-lg shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700 backdrop-blur-sm"
                     x-cloak>
                    <a href="{{ route('participant.profil.index') }}"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100/20 dark:hover:bg-gray-700/20">
                        <i class="fas fa-user w-5 text-center mr-2"></i>
                        Profil Saya
                    </a>
                    <a href="#"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100/20 dark:hover:bg-gray-700/20">
                        <i class="fas fa-cog w-5 text-center mr-2"></i>
                        Pengaturan
                    </a>
                    <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                            <i class="fas fa-sign-out-alt w-5 text-center mr-2"></i>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>


    <div class="flex">
        <!-- Sidebar Overlay for Mobile -->
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak>
        </div>

        <!-- Sidebar Permanen -->
        <aside
            x-show="sidebarOpen || !isMobile()"
            x-transition:enter="transition-transform duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed top-0 left-0 z-40 h-full w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-lg transform md:translate-x-0 md:static md:shadow-none sidebar-mobile-full sidebar-container"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Sidebar Header - Rata Atas -->
            <div class="sidebar-header p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-primary-100 dark:from-gray-800 dark:to-gray-900">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- Logo -->
                        <div class="flex">
                            <img
                                src="{{ asset('images/logo-light.png') }}"
                                alt="Logo"
                                class="h-24 w-auto dark:hidden"
                            >
                            <img
                                src="{{ asset('images/logo-dark.png') }}"
                                alt="Logo"
                                class="h-24 w-auto hidden dark:block"
                            >
                        </div>
                    </div>

                    <!-- Close Button (Mobile) -->
                    <button @click="sidebarOpen = false"
                            class="md:hidden flex items-center justify-center w-8 h-8 rounded text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 touch-button"
                            aria-label="Tutup sidebar">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Navigation Menu - Mengisi sisa space -->
            <nav class="sidebar-content px-3 py-4 overflow-y-auto scrollbar-thin">
                <ul class="space-y-1">
                    <!-- Menu Kategori: Umum -->
                    <li class="pt-2">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-1">
                            Umum
                        </p>
                    </li>

                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('participant.dashboard') }}"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/dashboard') }">
                            <i class="fas fa-home w-5 text-center group-hover:text-primary-600 dark:group-hover:text-primary-400"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </li>

                    <!-- Pengumuman -->
                    <li>
                        <a href="#"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/announcements') }">
                            <i class="fas fa-bullhorn w-5 text-center group-hover:text-orange-500"></i>
                            <span class="ml-3">Pengumuman</span>
                        </a>
                    </li>

                    <!-- Program -->
                    <li>
                        <a href="{{ route('participant.program.index') }}"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/programs') }">
                            <i class="fas fa-project-diagram w-5 text-center group-hover:text-blue-500"></i>
                            <span class="ml-3">Program</span>
                        </a>
                    </li>

                    <!-- Menu Kategori: Kelas -->
                    <li class="pt-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-1">
                            Kelas
                        </p>
                    </li>

                    <!-- Kelas Saya -->
                    <li>
                        <a href="#"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/my-classes') }">
                            <i class="fas fa-chalkboard-teacher w-5 text-center group-hover:text-green-500"></i>
                            <span class="ml-3">Kelas Saya</span>
                            <span class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">
                                Aktif
                            </span>
                        </a>
                    </li>

                    <!-- Tugas Mandiri -->
                    <li>
                        <a href="#"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/assignments') }">
                            <i class="fas fa-tasks w-5 text-center group-hover:text-purple-500"></i>
                            <span class="ml-3">Tugas Mandiri</span>
                        </a>
                    </li>

                    <!-- Materi -->
                    <li>
                        <a href="#"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/materials') }">
                            <i class="fas fa-book w-5 text-center group-hover:text-indigo-500"></i>
                            <span class="ml-3">Materi</span>
                        </a>
                    </li>

                    <!-- Nilai & Progres -->
                    <li>
                        <a href="#"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/progress') }">
                            <i class="fas fa-chart-line w-5 text-center group-hover:text-emerald-500"></i>
                            <span class="ml-3">Nilai & Progres</span>
                        </a>
                    </li>

                    <!-- Menu Kategori: Lainnya -->
                    <li class="pt-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-1">
                            Lainnya
                        </p>
                    </li>

                    <!-- Diskusi -->
                    <li>
                        <a href="#"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/discussions') }">
                            <i class="fas fa-comments w-5 text-center group-hover:text-cyan-500"></i>
                            <span class="ml-3">Diskusi</span>
                        </a>
                    </li>

                    <!-- Bantuan & Support -->
                    <li>
                        <a href="#"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/support') }">
                            <i class="fas fa-life-ring w-5 text-center group-hover:text-red-500"></i>
                            <span class="ml-3">Bantuan & Support</span>
                        </a>
                    </li>

                    <!-- Pengaturan -->
                    <li>
                        <a href="{{ route('participant.profil.index') }}"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden touch-button text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                           :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/settings') }">
                            <i class="fas fa-cog w-5 text-center group-hover:text-gray-500"></i>
                            <span class="ml-3">Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Sidebar Footer - Rata Bawah -->
            <div class="sidebar-footer p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <!-- Log Out Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full p-3 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 group touch-button">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span class="ml-3">Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content - Hemat dan Efisien -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
<footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 py-6 text-gray-700 dark:text-gray-300 font-sans">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- About Section -->
            <div>
                <h5 class="font-bold text-green-700 dark:text-green-500 mb-2 flex items-center gap-2 text-lg">
                    <i class="fas fa-graduation-cap"></i>Learning Management System
                </h5>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Program pendidikan kepemimpinan berkelanjutan untuk membangun pemimpin masa depan melalui pembelajaran interaktif.
                </p>
                <div class="mt-3 flex flex-col items-start">
                                        <div class="flex items-center space-x-3">
                        <!-- Logo -->
                        <div class="flex">
                            <img
                                src="{{ asset('images/logo-light.png') }}"
                                alt="Logo"
                                class="h-24 w-auto dark:hidden"
                            >
                            <img
                                src="{{ asset('images/logo-dark.png') }}"
                                alt="Logo"
                                class="h-24 w-auto hidden dark:block"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div>
                <h5 class="font-bold text-green-700 dark:text-green-500 mb-2 flex items-center gap-2 text-lg">
                    <i class="fas fa-headset"></i>Kontak Kami
                </h5>
                <ul class="text-sm space-y-1">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-envelope text-gray-500 dark:text-gray-400"></i>
                        <a href="mailto:info@greenleadership.id" class="hover:text-green-700 dark:hover:text-green-500">info@greenleadership.id</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-phone text-gray-500 dark:text-gray-400"></i>
                        <a href="tel:+622112345678" class="hover:text-green-700 dark:hover:text-green-500">+62 822 4743 1493 (PIC LMS)</a>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-gray-500 dark:text-gray-400 mt-1"></i>
                        <span>Jl. Palapa XVII No.3 11, RT.11/RW.5, Ps. Minggu, Jakarta Selatan</span>
                    </li>
                </ul>
                <div class="mt-3 flex flex-wrap gap-2">
                    <a href="#" class="text-green-700 dark:text-green-500 text-xs border border-green-700 dark:border-green-500 px-2 py-1 rounded hover:bg-green-700 hover:text-white dark:hover:bg-green-500 dark:hover:text-white">
                        <i class="fas fa-question-circle"></i> Bantuan
                    </a>
                    <a href="{{ asset('html/laporan.html') }}" class="text-green-700 dark:text-green-500 text-xs border border-green-700 dark:border-green-500 px-2 py-1 rounded hover:bg-green-700 hover:text-white dark:hover:bg-green-500 dark:hover:text-white">
                        <i class="fas fa-bug"></i> Laporkan Masalah
                    </a>
                </div>
            </div>

            <!-- Social Media & Developed By -->
            <div>
                <h5 class="font-bold text-green-700 dark:text-green-500 mb-2 flex items-center gap-2 text-lg">
                    <i class="fas fa-users"></i>Terhubung Dengan Kami
                </h5>
                <div class="flex flex-wrap gap-2 mb-3">
                    <a href="#" class="text-green-600 dark:text-green-400 border border-green-600 dark:border-green-400 rounded px-2 py-1 hover:bg-green-600 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-green-600 dark:text-green-400 border border-green-600 dark:border-green-400 rounded px-2 py-1 hover:bg-green-600 hover:text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-green-600 dark:text-green-400 border border-green-600 dark:border-green-400 rounded px-2 py-1 hover:bg-green-600 hover:text-white"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-green-600 dark:text-green-400 border border-green-600 dark:border-green-400 rounded px-2 py-1 hover:bg-green-600 hover:text-white"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="text-green-600 dark:text-green-400 border border-green-600 dark:border-green-400 rounded px-2 py-1 hover:bg-green-600 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                </div>
<div class="bg-gray-100 dark:bg-gray-800 p-3 rounded text-xs border-l-4 border-green-600">
    <h6 class="font-semibold text-green-700 dark:text-green-500 mb-1 flex items-center gap-1">
        <i class="fas fa-code"></i>Dikembangkan Oleh
    </h6>
    <p class="text-gray-600 dark:text-gray-400 mb-1">
        Tim IT Institut Hijau Indonesia<br>
        <span class="text-green-600 dark:text-green-400 font-semibold">Learning Management System v2.0</span><br>
        <span class="text-gray-700 dark:text-gray-300 font-semibold flex items-center gap-1">
            Developer :<i class="fas fa-cat"></i>  Teriyaki#3
        </span>
    </p>
    <p class="text-gray-500 dark:text-gray-400 mb-0">&copy; 2025 Hak Cipta Dilindungi</p>
</div>

            </div>

        </div>

        <div class="mt-4 text-center text-xs text-gray-500 dark:text-gray-400">
            Sistem Pembelajaran Kepemimpinan untuk Membangun Masa Depan Berkelanjutan
        </div>
    </div>
</footer>

<!-- FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>


    <!-- Dark Mode Toggle Script -->
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }

        // Alpine.js helper function untuk detect mobile
        function isMobile() {
            return window.innerWidth < 768;
        }

        // Close sidebar when clicking on links in mobile
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('nav a').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        const alpineData = document.querySelector('[x-data]').__x.$data;
                        alpineData.sidebarOpen = false;
                    }
                });
            });

            // Handle escape key to close sidebar
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const alpineData = document.querySelector('[x-data]').__x.$data;
                    if (alpineData.sidebarOpen) {
                        alpineData.sidebarOpen = false;
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                const alpineData = document.querySelector('[x-data]').__x.$data;
                if (window.innerWidth >= 768) {
                    alpineData.sidebarOpen = true;
                } else {
                    alpineData.sidebarOpen = false;
                }
            });
        });
    </script>

    <!-- Alpine.js untuk Sidebar Toggle -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
