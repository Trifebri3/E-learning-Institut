

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        /* Animasi smooth untuk semua elemen */
        * {
            transition-property: color, background-color, border-color, transform, opacity, width, margin;
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

        /* Animasi untuk icon */
        .icon-pulse {
            transition: all 0.2s ease;
        }

        .icon-pulse:hover {
            transform: scale(1.1);
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

        /* Badge animation */
        .badge-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(34, 197, 94, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        /* Untuk mencegah scroll body saat sidebar terbuka di mobile */
        body.sidebar-open-mobile {
            overflow: hidden;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .menu-item {
                border: 1px solid transparent;
            }

            .menu-item:hover {
                border-color: currentColor;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            * {
                transition: none !important;
                animation: none !important;
            }
        }

        /* Safe area untuk device dengan notch */
        .safe-area-top {
            padding-top: env(safe-area-inset-top);
        }

        .safe-area-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>

    <script>
        // Konfigurasi Tailwind
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
                    },
                    fontFamily: {
                        'montserrat': ['Montserrat', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out',
                        'slide-in-left': 'slideInLeft 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideInLeft: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(0)' },
                        }
                    }
                }
            }
        }

        // Inisialisasi tema
        document.addEventListener('DOMContentLoaded', function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', theme === 'dark');
        });
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-montserrat min-h-screen">
    <!-- AlpineJS Data -->
    <div x-data="sidebarManager()" x-init="init()">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="isMobile && sidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
             x-cloak>
        </div>

        <!-- Sidebar -->
        <aside :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen,
                'lg:translate-x-0': !isMobile,
                'w-64': !sidebarCollapsed || isMobile,
                'w-20': sidebarCollapsed && !isMobile
            }"
            class="fixed top-0 left-0 z-50 h-screen transition-all duration-300 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-lg">

            <!-- Logo and Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-primary-100 dark:from-gray-800 dark:to-gray-900 safe-area-top">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3" :class="sidebarCollapsed && !isMobile ? 'justify-center w-full' : ''">
                        <!-- Logo -->
                        <div class="flex" :class="sidebarCollapsed && !isMobile ? 'justify-center' : ''">
                            <img
                                src="{{ asset('images/logo-light.png') }}"
                                alt="Logo Light"
    class="h-24 w-auto dark:hidden transition-all duration-300"
    :class="sidebarCollapsed && !isMobile ? 'h-4' : 'h-24'"
                            >
                            <img
                                src="{{ asset('images/logo-dark.png') }}"
                                alt="Logo Dark"
    class="h-24 w-auto hidden dark:block transition-all duration-300"
    :class="sidebarCollapsed && !isMobile ? 'h-4' : 'h-24'"
                            >
                        </div>
                    </div>

                    <!-- Collapse Toggle Button (Desktop) -->
                    <button x-show="!sidebarCollapsed && !isMobile"
                            @click="sidebarCollapsed = !sidebarCollapsed"
                            class="hidden lg:flex items-center justify-center w-8 h-8 rounded text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            aria-label="Sembunyikan sidebar">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>

                    <!-- Expand Toggle Button (Desktop) -->
                    <button x-show="sidebarCollapsed && !isMobile"
                            @click="sidebarCollapsed = !sidebarCollapsed"
                            class="hidden lg:flex items-center justify-center w-8 h-8 rounded text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                            aria-label="Perluas sidebar">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- User Profile Mini (Collapsed State) -->

            <!-- Navigation Menu -->
            <nav class="flex-1 px-3 py-4 h-[calc(100vh-180px)] overflow-y-auto scrollbar-thin">
                <ul class="space-y-1">
                    <!-- Menu Kategori: Umum -->
                    <li x-show="!sidebarCollapsed || isMobile" class="pt-2">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-1 transition-all duration-300">
                            Umum
                        </p>
                    </li>

                    <!-- Dashboard -->
                    <li>
                        <a href="#"
                           @click="setActiveMenu('dashboard')"
                           :class="activeMenu === 'dashboard' ?
                           'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold' :
                           'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700'"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden">
                            <i class="fas fa-home w-5 text-center icon-pulse"
                               :class="activeMenu === 'dashboard' ? 'text-primary-600 dark:text-primary-400' : 'group-hover:text-primary-600 dark:group-hover:text-primary-400'"></i>
                            <span class="ml-3 transition-all duration-300"
                                  x-show="!sidebarCollapsed || isMobile">Dashboard</span>
                            <span x-show="activeMenu === 'dashboard' && (sidebarCollapsed && !isMobile)"
                                  class="absolute left-2 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-primary-500 rounded-full"></span>
                        </a>
                    </li>

                    <!-- Pengumuman -->
                    <li>
                        <a href="#"
                           @click="setActiveMenu('pengumuman')"
                           :class="activeMenu === 'pengumuman' ?
                           'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold' :
                           'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700'"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden">
                            <i class="fas fa-bullhorn w-5 text-center icon-pulse"
                               :class="activeMenu === 'pengumuman' ? 'text-orange-500' : 'group-hover:text-orange-500'"></i>
                            <span class="ml-3 transition-all duration-300"
                                  x-show="!sidebarCollapsed || isMobile">Pengumuman</span>
                            <span x-show="activeMenu === 'pengumuman' && (sidebarCollapsed && !isMobile)"
                                  class="absolute left-2 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-orange-500 rounded-full"></span>
                            <span x-show="!sidebarCollapsed || isMobile" class="ml-auto">
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">3</span>
                            </span>
                        </a>
                    </li>

                    <!-- Program -->
                    <li>
                        <a href="#"
                           @click="setActiveMenu('program')"
                           :class="activeMenu === 'program' ?
                           'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold' :
                           'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700'"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden">
                            <i class="fas fa-project-diagram w-5 text-center icon-pulse"
                               :class="activeMenu === 'program' ? 'text-blue-500' : 'group-hover:text-blue-500'"></i>
                            <span class="ml-3 transition-all duration-300"
                                  x-show="!sidebarCollapsed || isMobile">Program</span>
                            <span x-show="activeMenu === 'program' && (sidebarCollapsed && !isMobile)"
                                  class="absolute left-2 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-blue-500 rounded-full"></span>
                        </a>
                    </li>

                    <!-- Menu Kategori: Kelas -->
                    <li x-show="!sidebarCollapsed || isMobile" class="pt-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-1 transition-all duration-300">
                            Kelas
                        </p>
                    </li>

                    <!-- Kelas Saya -->
                    <li>
                        <a href="#"
                           @click="if(hasEnrolled) setActiveMenu('kelas-saya')"
                           :class="activeMenu === 'kelas-saya' ?
                           'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold' :
                           (hasEnrolled ? 'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700' : 'text-gray-400 dark:text-gray-500 cursor-not-allowed')"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden"
                           :title="!hasEnrolled ? 'Belum enroll program' : ''">
                            <i class="fas fa-chalkboard-teacher w-5 text-center icon-pulse"
                               :class="activeMenu === 'kelas-saya' ? 'text-green-500' : (hasEnrolled ? 'group-hover:text-green-500' : '')"></i>
                            <span class="ml-3 transition-all duration-300"
                                  x-show="!sidebarCollapsed || isMobile">Kelas Saya</span>
                            <span x-show="activeMenu === 'kelas-saya' && (sidebarCollapsed && !isMobile)"
                                  class="absolute left-2 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-green-500 rounded-full"></span>

                            <i x-show="(!sidebarCollapsed || isMobile) && !hasEnrolled"
                               class="fas fa-lock ml-auto text-xs text-gray-400"></i>
                            <span x-show="hasEnrolled && (!sidebarCollapsed || isMobile)"
                                  class="ml-auto bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full badge-pulse">
                                Aktif
                            </span>
                        </a>
                    </li>

                    <!-- Tugas Mandiri -->
                    <li>
                        <a href="#"
                           @click="if(hasEnrolled) setActiveMenu('tugas-mandiri')"
                           :class="activeMenu === 'tugas-mandiri' ?
                           'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold' :
                           (hasEnrolled ? 'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700' : 'text-gray-400 dark:text-gray-500 cursor-not-allowed')"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden"
                           :title="!hasEnrolled ? 'Belum enroll program' : ''">
                            <i class="fas fa-tasks w-5 text-center icon-pulse"
                               :class="activeMenu === 'tugas-mandiri' ? 'text-purple-500' : (hasEnrolled ? 'group-hover:text-purple-500' : '')"></i>
                            <span class="ml-3 transition-all duration-300"
                                  x-show="!sidebarCollapsed || isMobile">Tugas Mandiri</span>
                            <span x-show="activeMenu === 'tugas-mandiri' && (sidebarCollapsed && !isMobile)"
                                  class="absolute left-2 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-purple-500 rounded-full"></span>

                            <i x-show="(!sidebarCollapsed || isMobile) && !hasEnrolled"
                               class="fas fa-lock ml-auto text-xs text-gray-400"></i>
                        </a>
                    </li>

                    <!-- Materi -->
                    <li>
                        <a href="#"
                           @click="if(hasEnrolled) setActiveMenu('materi')"
                           :class="activeMenu === 'materi' ?
                           'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold' :
                           (hasEnrolled ? 'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700' : 'text-gray-400 dark:text-gray-500 cursor-not-allowed')"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden"
                           :title="!hasEnrolled ? 'Belum enroll program' : ''">
                            <i class="fas fa-book w-5 text-center icon-pulse"
                               :class="activeMenu === 'materi' ? 'text-indigo-500' : (hasEnrolled ? 'group-hover:text-indigo-500' : '')"></i>
                            <span class="ml-3 transition-all duration-300"
                                  x-show="!sidebarCollapsed || isMobile">Materi</span>
                            <span x-show="activeMenu === 'materi' && (sidebarCollapsed && !isMobile)"
                                  class="absolute left-2 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-indigo-500 rounded-full"></span>

                            <i x-show="(!sidebarCollapsed || isMobile) && !hasEnrolled"
                               class="fas fa-lock ml-auto text-xs text-gray-400"></i>
                        </a>
                    </li>

                    <!-- Menu Kategori: Lainnya -->
                    <li x-show="!sidebarCollapsed || isMobile" class="pt-4">
                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-1 transition-all duration-300">
                            Lainnya
                        </p>
                    </li>

                    <!-- Diskusi -->
                    <li>
                        <a href="#"
                           @click="setActiveMenu('diskusi')"
                           :class="activeMenu === 'diskusi' ?
                           'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold' :
                           'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700'"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden">
                            <i class="fas fa-comments w-5 text-center icon-pulse"
                               :class="activeMenu === 'diskusi' ? 'text-cyan-500' : 'group-hover:text-cyan-500'"></i>
                            <span class="ml-3 transition-all duration-300"
                                  x-show="!sidebarCollapsed || isMobile">Diskusi</span>
                            <span x-show="activeMenu === 'diskusi' && (sidebarCollapsed && !isMobile)"
                                  class="absolute left-2 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-cyan-500 rounded-full"></span>
                        </a>
                    </li>

                    <!-- Pengaduan Masalah -->
                    <li>
                        <a href="#"
                           @click="setActiveMenu('pengaduan')"
                           :class="activeMenu === 'pengaduan' ?
                           'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold' :
                           'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700'"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden">
                            <i class="fas fa-life-ring w-5 text-center icon-pulse"
                               :class="activeMenu === 'pengaduan' ? 'text-red-500' : 'group-hover:text-red-500'"></i>
                            <span class="ml-3 transition-all duration-300"
                                  x-show="!sidebarCollapsed || isMobile">Pengaduan Masalah</span>
                            <span x-show="activeMenu === 'pengaduan' && (sidebarCollapsed && !isMobile)"
                                  class="absolute left-2 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-red-500 rounded-full"></span>
                        </a>
                    </li>

                    <!-- Setting -->
                    <li>
                        <a href="#"
                           @click="setActiveMenu('setting')"
                           :class="activeMenu === 'setting' ?
                           'bg-primary-50 dark:bg-gray-700 border-l-4 border-primary-500 text-primary-600 dark:text-primary-400 font-semibold' :
                           'text-gray-700 dark:text-gray-300 hover:bg-primary-50 dark:hover:bg-gray-700'"
                           class="flex items-center p-3 rounded-lg menu-item transition-all duration-200 group relative overflow-hidden">
                            <i class="fas fa-cog w-5 text-center icon-pulse"
                               :class="activeMenu === 'setting' ? 'text-gray-500' : 'group-hover:text-gray-500'"></i>
                            <span class="ml-3 transition-all duration-300"
                                  x-show="!sidebarCollapsed || isMobile">Setting</span>
                            <span x-show="activeMenu === 'setting' && (sidebarCollapsed && !isMobile)"
                                  class="absolute left-2 top-1/2 transform -translate-y-1/2 w-1 h-6 bg-gray-500 rounded-full"></span>
                        </a>
                    </li>
                                        <li>
                <a href="#"
                   @click="setActiveMenu('logout')"
                   class="flex items-center p-3 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 group">
                    <i class="fas fa-sign-out-alt w-5 text-center icon-pulse"></i>
                    <span class="ml-3 transition-all duration-300" x-show="!sidebarCollapsed || isMobile">Log Out</span>
                </a>
                    </li>
                </ul>
            </nav>
            </div>
        </aside>

    <script>
        // Sidebar Manager
        function sidebarManager() {
            return {
                // State
                sidebarOpen: false,
                sidebarCollapsed: false,
                userMenuOpen: false,
                hasEnrolled: true,
                activeMenu: 'dashboard',
                isMobile: false,
                isDark: false,

                // Initialize
                init() {
                    // Check if mobile
                    this.checkScreenSize();

                    // Listen for resize events
                    window.addEventListener('resize', () => {
                        this.checkScreenSize();
                    });

                    // Check initial theme
                    this.isDark = document.documentElement.classList.contains('dark');

                    // Close sidebar when clicking on a link (mobile)
                    document.querySelectorAll('nav a').forEach(link => {
                        link.addEventListener('click', () => {
                            if (this.isMobile) {
                                this.sidebarOpen = false;
                            }
                        });
                    });
                },

                // Check screen size and adjust sidebar state
                checkScreenSize() {
                    this.isMobile = window.innerWidth < 1024;

                    if (this.isMobile) {
                        this.sidebarOpen = false;
                        document.body.classList.remove('sidebar-open-mobile');
                    } else {
                        this.sidebarOpen = true;
                    }
                },

                // Set active menu
                setActiveMenu(menu) {
                    if (menu === 'logout') {
                        if (confirm('Apakah Anda yakin ingin keluar?')) {
                            // Logout logic here
                            console.log('Logging out...');
                        }
                        return;
                    }

                    if ((menu === 'kelas-saya' || menu === 'tugas-mandiri' || menu === 'materi') && !this.hasEnrolled) {
                        alert('Anda belum enroll program. Silakan enroll terlebih dahulu.');
                        return;
                    }

                    this.activeMenu = menu;

                    if (this.isMobile) {
                        this.sidebarOpen = false;
                    }
                },

                // Toggle theme
                toggleTheme() {
                    this.isDark = !this.isDark;
                    document.documentElement.classList.toggle('dark', this.isDark);
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                },

                // Get page title based on active menu
                getPageTitle() {
                    const titles = {
                        'dashboard': 'Dashboard',
                        'pengumuman': 'Pengumuman',
                        'program': 'Program',
                        'kelas-saya': 'Kelas Saya',
                        'tugas-mandiri': 'Tugas Mandiri',
                        'materi': 'Materi',
                        'diskusi': 'Diskusi',
                        'pengaduan': 'Pengaduan Masalah',
                        'setting': 'Pengaturan'
                    };

                    return titles[this.activeMenu] || 'Dashboard';
                }
            }
        }
    </script>
</body>

