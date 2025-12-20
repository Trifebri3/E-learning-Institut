<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>institut Hijau Indonesia</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
<link href="{{ asset('css/kelas.css') }}" rel="stylesheet">
<link href="{{ asset('css/kelas-utilities.css') }}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">

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

        /* Mobile Styles */
        @media (max-width: 768px) {
            /* Hide sidebar completely on mobile */
            .sidebar-desktop {
                display: none !important;
            }

            /* Mobile header adjustments */
            .mobile-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 50;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }

            .dark .mobile-header {
                background: rgba(31, 41, 55, 0.95);
            }

            /* Main content for mobile */
            .main-content-mobile {
                margin-top: 64px; /* Height of header */
                margin-bottom: 70px; /* Height of bottom nav */
                padding: 1rem;
                min-height: calc(100vh - 64px - 70px);
                width: 100%;
                overflow-y: auto; /* Enable scrolling for main content */
            }

            /* Bottom Navigation */
            .bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                height: 70px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                z-index: 50;
            }

            .dark .bottom-nav {
                background: rgba(31, 41, 55, 0.95);
            }

            .bottom-nav-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 8px 4px;
                font-size: 0.7rem;
                color: #6b7280;
                transition: all 0.2s;
            }

            .dark .bottom-nav-item {
                color: #9ca3af;
            }

            .bottom-nav-item.active {
                color: #16a34a;
                background-color: rgba(34, 197, 94, 0.1);
            }

            .bottom-nav-item i {
                font-size: 1.25rem;
                margin-bottom: 4px;
            }

            /* Hide footer on mobile */
            .page-footer {
                display: none;
            }
        }

        /* Desktop Styles */
        @media (min-width: 769px) {
            /* Show sidebar on desktop */
            .sidebar-desktop {
                display: flex !important;
            }

            /* Hide bottom nav on desktop */
            .bottom-nav {
                display: none !important;
            }

            /* Desktop header */
            .desktop-header {
                position: fixed;
                top: 0;
                right: 0;
                z-index: 50;
                transition: margin-left 0.3s ease;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }

            .dark .desktop-header {
                background: rgba(31, 41, 55, 0.95);
            }

            /* Main content for desktop */
            .main-content-desktop {
                margin-top: 64px;
                min-height: calc(100vh - 64px);
                transition: margin-left 0.3s ease;
                margin-left: 16rem;
                padding: 1rem;
                overflow-y: auto;
            }

            .sidebar-collapsed ~ .main-content-area .main-content-desktop {
                margin-left: 5rem; /* Collapsed sidebar width */
            }

            /* Sidebar layout */
            .sidebar-container {
                display: flex;
                flex-direction: column;
                height: 100vh;
                position: fixed;
                left: 0;
                top: 0;
                z-index: 40;
                background: white;
                border-right: 1px solid #e5e7eb;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
                width: 16rem;
                transition: width 0.3s ease;
            }

            .dark .sidebar-container {
                background: #1f2937;
                border-right-color: #374151;
            }

            .sidebar-collapsed {
                width: 5rem !important;
            }

            .sidebar-collapsed .sidebar-text {
                display: none;
            }

            .sidebar-collapsed .sidebar-badge {
                display: none;
            }

            .sidebar-collapsed .logo-text {
                display: none;
            }

            .sidebar-collapsed .sidebar-toggle-icon {
                transform: rotate(180deg);
            }

            /* Improved sidebar toggle button positioning */
            .sidebar-toggle-container {
                position: absolute;
                top: 50%;
                right: -12px;
                transform: translateY(-50%);
                z-index: 60;
            }

            .sidebar-toggle-button {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: white;
                border: 1px solid #e5e7eb;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .dark .sidebar-toggle-button {
                background: #374151;
                border-color: #4b5563;
            }

            .sidebar-toggle-button:hover {
                background: #f3f4f6;
                transform: scale(1.1);
            }

            .dark .sidebar-toggle-button:hover {
                background: #4b5563;
            }
        }

        /* Menu item hover effect */
        .menu-item {
            position: relative;
            overflow: hidden;
        }

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

        /* Touch-friendly buttons */
        .touch-button {
            min-height: 44px;
            min-width: 44px;
        }

        /* Prevent content overlap */
        .content-wrapper {
            display: flex;
            width: 100%;
            height: 100vh; /* Full viewport height */
            overflow: hidden; /* Prevent body scrolling */
        }

        .main-content-area {
            flex: 1;
            overflow-x: hidden;

            margin-left: 0;
            padding: 1.5rem;
            transition: margin 0.3s;
        }

        /* Ensure body doesn't scroll */
        body {
            overflow: hidden;
        }

        /* Fix for footer positioning */
        .footer-container {
            margin-top: auto;
        }

        /* Fix for sidebar collapse transition */
        .sidebar-container, .main-content-desktop {
            transition: all 0.3s ease;
        }

        /* Ensure proper spacing when sidebar is collapsed */
        .sidebar-collapsed + .main-content-area .main-content-desktop {
            margin-left: 5rem;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900" x-data="{
    sidebarOpen: false,
    sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
    activeBottomNav: 'dashboard'
}">

<!-- Desktop Header -->
<header class="hidden md:flex fixed top-0 z-50 w-full h-16 items-center">
    <!-- Kiri: full transparan -->
    <div class="flex-1 h-full bg-transparent"></div>

    <!-- Kanan: gradiasi + blur -->
    <div class="flex items-center space-x-4 px-6 h-full backdrop-blur-md"
         style="background: linear-gradient(to left, rgba(255,255,255,0.2), rgba(255,255,255,0));
                background-color: transparent;">

        <!-- Greeting -->
        <div class="text-gray-800 dark:text-gray-100 font-medium text-sm">
<span
    x-data="{ greeting: '' }"
    x-init="
        const now = new Date();
        const hour = now.getHours();

        let baseGreeting =
            (hour < 4) ? 'Selamat Malam' :
            (hour < 11) ? 'Selamat Pagi' :
            (hour < 15) ? 'Selamat Siang' :
            (hour < 18) ? 'Selamat Sore' :
            'Selamat Malam';

        let variants = [
            baseGreeting + '!',
            baseGreeting + ' 😄',
            baseGreeting + ' 👋',
            baseGreeting + ' semoga harimu menyenangkan!',
            baseGreeting + ' tetap semangat ya!'
        ];

        greeting = variants[Math.floor(Math.random() * variants.length)];

        $el.innerText = greeting + ' {{ Auth::user()->name }}';
    "
    class="font-semibold"
></span>
        </div>

        <!-- Search Bar -->
<div x-data="{ focused: false }"
     class="relative transition-all duration-300 ease-in-out"
     :class="focused ? 'max-w-72 w-full' : 'max-w-40 w-full'">

    <input type="text"
           x-on:focus="focused = true"
           x-on:blur="focused = false"
           placeholder="Cari..."
           class="w-full pl-10 pr-4 py-2 rounded-lg bg-white/30 dark:bg-gray-700/30
                  text-gray-900 dark:text-white backdrop-blur-md
                  focus:outline-none focus:ring-2 focus:ring-primary-500
                  transition-all duration-300 ease-in-out">

    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i class="fas fa-search text-gray-400"></i>
    </div>
</div>


        <!-- Dark Mode Toggle -->
        <button onclick="toggleTheme()" class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
            <i class="fas fa-moon dark:hidden"></i>
            <i class="fas fa-sun hidden dark:block"></i>
        </button>

        <!-- Notifications -->
        <div class="relative">
            <button class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fas fa-bell"></i>
                <span class="absolute top-0 right-0 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            </button>
        </div>

<!-- User Dropdown -->
<div class="relative" x-data="{ open: false }">
    <button
        @click="open = !open"
        class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
    >
        @php
            $user = Auth::user();
            $profile = $user->profile;

            // Path foto di storage
            $fotoPath = $profile?->pas_foto_path;

            // Cek apakah file ada
            $profileUrl = ($fotoPath && Storage::disk('public')->exists($fotoPath))
                ? asset('storage/' . $fotoPath)
                : asset('images/defaultprofil.svg'); // fallback
        @endphp

        <img
            src="{{ $profileUrl }}"
            alt="Profile Photo"
            class="w-10 h-10 rounded-full object-cover"
        />

        <i class="fas fa-chevron-down text-xs text-gray-500 dark:text-gray-400"></i>
    </button>
</div>


            <div x-show="open"
                 @click.away="open = false"
                 x-transition
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-50"
                 style="display: none;">
                <a href="{{ route('participant.profil.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-user w-5 text-center mr-2"></i>Profil Saya
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fas fa-cog w-5 text-center mr-2"></i>Pengaturan
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <i class="fas fa-sign-out-alt w-5 text-center mr-2"></i>Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Header -->
<header class="md:hidden fixed top-0 z-50 w-full h-16 flex items-center justify-end px-4 backdrop-blur-md"
        style="background: linear-gradient(to left, rgba(255,255,255,0.2), rgba(255,255,255,0));
               background-color: transparent;">
    <div class="flex items-center space-x-4">
        <!-- Greeting -->
        <div class="text-gray-800 dark:text-gray-100 font-medium text-sm">
<span
    x-data="{ greeting: '' }"
    x-init="
        const now = new Date();
        const hour = now.getHours();

        let baseGreeting =
            (hour < 4) ? 'Selamat Malam' :
            (hour < 11) ? 'Selamat Pagi' :
            (hour < 15) ? 'Selamat Siang' :
            (hour < 18) ? 'Selamat Sore' :
            'Selamat Malam';

        let variants = [
            baseGreeting + '!',
            baseGreeting + ' 😄',
            baseGreeting + ' 👋',
            baseGreeting + ' semoga harimu menyenangkan!',
            baseGreeting + ' tetap semangat ya!'
        ];

        greeting = variants[Math.floor(Math.random() * variants.length)];

        $el.innerText = greeting + ' {{ Auth::user()->name }}';
    "
    class="font-semibold"
></span>

        </div>

        <!-- Dark Mode -->
        <button onclick="toggleTheme()" class="flex items-center justify-center w-10 h-10 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
            <i class="fas fa-moon dark:hidden"></i>
            <i class="fas fa-sun hidden dark:block"></i>
        </button>
    </div>
</header>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Sidebar (Desktop Only) -->
    <aside class="sidebar-desktop sidebar-container"
           :class="{'sidebar-collapsed': sidebarCollapsed}">

        <!-- Improved Sidebar Toggle Button -->
        <div class="sidebar-toggle-container">
            <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed)"
                    class="sidebar-toggle-button"
                    aria-label="Toggle sidebar">
                <i class="fas fa-chevron-left text-xs text-gray-600 dark:text-gray-300 sidebar-toggle-icon"></i>
            </button>
        </div>

        <!-- Sidebar Header -->
        <div class="sidebar-header p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-primary-100 dark:from-gray-800 dark:to-gray-900">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <!-- Logo terang -->
                    <img
                        src="{{ asset('images/logo-light.png') }}"
                        alt="Logo"
                        class="w-auto h-24 dark:hidden transition-all duration-300"
                        x-show="!sidebarCollapsed"
                        x-transition.opacity.duration.300ms
                    >

                    <!-- Logo gelap -->
                    <img
                        src="{{ asset('images/logo-dark.png') }}"
                        alt="Logo"
                        class="w-auto h-24 hidden dark:block transition-all duration-300"
                        x-show="!sidebarCollapsed"
                        x-transition.opacity.duration.300ms
                    >

                    <!-- Mini logo when collapsed -->
                    <div x-show="sidebarCollapsed" class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
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
            </div>
        </div>

        <!-- Navigation Menu -->
<nav class="sidebar-content flex-1 px-3 py-4 overflow-y-auto scrollbar-thin">

    <ul class="space-y-1">

        <!-- PANEL ADMIN -->
        <li class="pt-2">
            <p class="text-xs font-semibold text-primary-600 dark:text-primary-400 uppercase tracking-wider px-3 py-1 sidebar-text">
                SUPER ADMIN PANEL
            </p>
        </li>

        <!-- Dashboard -->
        <li>
            <a href="{{ route('superadmin.dashboard') }}"
               class="flex items-center p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
               :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/dashboard') }">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                <span class="ml-3 sidebar-text">Dashboard</span>
            </a>
        </li>

        <!-- Users -->
        <li>
            <a href="{{ route('superadmin.users.index') }}"
               class="flex items-center p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
               :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/users') }">
                <i class="fa-solid fa-users-gear w-5 text-center"></i>
                <span class="ml-3 sidebar-text">Manajemen Pengguna</span>
            </a>
        </li>

        <!-- Programs -->
        <li>
            <a href="{{ route('superadmin.programs.index') }}"
               class="flex items-center p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
               :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/programs') }">
                <i class="fa-solid fa-layer-group w-5 text-center"></i>
                <span class="ml-3 sidebar-text">Manajemen Program</span>
            </a>
        </li>


                <li>
                    <a href="{{ route('superadmin.announcements.index') }}" class="flex items-center p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
                       :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/announcements') }">
                        <i class="fas fa-bullhorn w-5 text-center"></i>
                        <span class="ml-3 sidebar-text">Kelola pengumuman</span>
                    </a>
                </li>


        <!-- SECTION: GENERAL -->
        <li class="pt-4">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-1 sidebar-text">
                Lainnya
            </p>
        </li>

        <!-- Diskusi -->
        <li>
            <a href="{{ route('superadmin.discussion.index') }}"
               class="flex items-center p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
               :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/discussions') }">
                <i class="fa-solid fa-comments w-5 text-center"></i>
                <span class="ml-3 sidebar-text">Diskusi</span>
            </a>
        </li>

        <!-- Support -->
        <li>
            <a href="{{ route('superadmin.support.index') }}"
               class="flex items-center p-3 rounded-lg menu-item text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700"
               :class="{ 'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold': $page.url.startsWith('/support') }">
                <i class="fa-solid fa-life-ring w-5 text-center"></i>
                <span class="ml-3 sidebar-text">Bantuan & Support</span>
            </a>
        </li>

    </ul>

</nav>


        <!-- Sidebar Footer -->
        <div class="sidebar-footer p-4 border-t border-gray-200 dark:border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full p-3 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i>
                    <span class="ml-3 sidebar-text">Log Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-content-area" :class="{'sidebar-collapsed': sidebarCollapsed}">
        <!-- Main content for desktop -->
        <main class="flex-1 overflow-y-auto main-content-desktop pt-2">

            <!-- Konten utama aplikasi -->
            <div class="py-2 px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>

            <!-- Footer (Desktop Only) -->
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
        </main>
    </div>
</div>

<!-- Bottom Navigation (Mobile Only) -->
<div class="bottom-nav md:hidden border-t border-gray-200 dark:border-gray-700">
    <div class="flex h-full">
        <a href="{{ route('participant.dashboard') }}"
           class="bottom-nav-item"
           :class="{ 'active': activeBottomNav === 'dashboard' }"
           @click="activeBottomNav = 'dashboard'">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('participant.program.index') }}"
           class="bottom-nav-item"
           :class="{ 'active': activeBottomNav === 'program' }"
           @click="activeBottomNav = 'program'">
            <i class="fas fa-project-diagram"></i>
            <span>Program</span>
        </a>
        <a href="#"
           class="bottom-nav-item"
           :class="{ 'active': activeBottomNav === 'classes' }"
           @click="activeBottomNav = 'classes'">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Kelas</span>
        </a>
        <a href="#"
           class="bottom-nav-item"
           :class="{ 'active': activeBottomNav === 'assignments' }"
           @click="activeBottomNav = 'assignments'">
            <i class="fas fa-tasks"></i>
            <span>Tugas</span>
        </a>
        <a href="{{ route('participant.profil.index') }}"
           class="bottom-nav-item"
           :class="{ 'active': activeBottomNav === 'profile' }"
           @click="activeBottomNav = 'profile'">
            <i class="fas fa-user"></i>
            <span>Profil</span>
        </a>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<!-- Dark Mode Toggle Script -->
<script>
    function toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    }

    // Set active bottom nav based on current URL
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const alpineData = document.querySelector('[x-data]').__x.$data;

        if (currentPath.includes('dashboard')) {
            alpineData.activeBottomNav = 'dashboard';
        } else if (currentPath.includes('program')) {
            alpineData.activeBottomNav = 'program';
        } else if (currentPath.includes('profil')) {
            alpineData.activeBottomNav = 'profile';
        }
    });
</script>

<!-- Alpine.js -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
