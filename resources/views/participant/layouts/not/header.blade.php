<header class="bg-transparent transition-colors duration-300 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Left Side - Logo and Brand -->
            <div>
                <!-- Tambahkan logo atau brand di sini -->
            </div>

            <!-- Right Side - Theme Toggle, Sapa & User Avatar -->
            <div class="flex items-center space-x-4">
                <!-- Sapaan Pengguna -->
                @auth
                <div class="text-right">
                    <p class="text-gray-700 dark:text-gray-200 text-sm">Halo, <span class="font-semibold">{{ Auth::user()->name }}</span>!</p>
                </div>
                @endauth

                <!-- User Avatar -->
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 overflow-hidden flex items-center justify-center border-2 border-white dark:border-gray-700 shadow-md">
                        @auth
                            @if(Auth::user()->profile && Auth::user()->profile->pas_foto_path)
                                <img src="{{ Storage::url(Auth::user()->profile->pas_foto_path) }}"
                                     alt="{{ Auth::user()->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-user text-primary-600 dark:text-primary-400 text-sm"></i>
                            @endif
                        @endauth
                    </div>
                </div>
                                <!-- Theme Toggle -->
                <button id="theme-toggle" type="button"
                        class="p-2.5 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zM5.05 14.95a1 1 0 101.414 1.414l.707-.707a1 1 0 00-1.414-1.414l-.707.707z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>


<script>
    // Theme Toggle Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Check current theme and set appropriate icon
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
            document.documentElement.classList.add('dark');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
            document.documentElement.classList.remove('dark');
        }

        themeToggle.addEventListener('click', function() {
            // Toggle icons
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // Toggle theme
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    });
</script>
