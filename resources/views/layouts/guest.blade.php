<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Green Leadership Indonesia - Membangun Pemimpin Masa Depan yang Berkelanjutan">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Green Leadership Indonesia')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<!-- Google Translate Script -->
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id',
            includedLanguages: 'id,en,es,fr,de,ja,ko,zh-CN,ar,ru',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }


    // Dark Mode Configuration

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
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                        'montserrat': ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>

/* Translate Dropdown Fix */
.goog-te-gadget-simple {
    background-color: transparent !important;
    border: 1px solid rgba(156, 163, 175, 0.3) !important;
    border-radius: 8px !important;
    padding: 6px 10px !important;
    font-size: 14px !important;
}

.goog-te-menu-value span:nth-child(3) {
    display: none !important;
}

/* Google Translate Container */
#google_translate_element {
    display: inline-block;
    margin-right: 10px;
}

/* Remove default font */
.goog-te-gadget {
    font-family: inherit !important;
}

/* Button style */
.goog-te-gadget-simple {
    background-color: transparent !important;
    border: 1px solid rgba(156, 163, 175, 0.3) !important;
    border-radius: 8px !important;
    padding: 6px 10px !important;
    font-size: 14px !important;
    color: #6b7280 !important;
    cursor: pointer !important;
    transition: 0.2s ease !important;
}

/* Dark mode support */
.dark .goog-te-gadget-simple {
    border-color: rgba(75, 85, 99, 0.5) !important;
    color: #9ca3af !important;
}

/* Hover effect */
.goog-te-gadget-simple:hover {
    background-color: rgba(156, 163, 175, 0.1) !important;
}

/* Remove arrow text */
.goog-te-gadget-simple .goog-te-menu-value span:nth-child(3) {
    display: none !important;
}

/* Add icon (FontAwesome globe) */
.goog-te-gadget-simple .goog-te-menu-value:before {
    content: "\f1ab";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    margin-right: 8px;
}

/* Hide Google top banner */
.goog-te-banner-frame {
    display: none !important;
}

body {
    top: 0px !important;
}

/* Mobile adjustments */
@media (max-width: 768px) {
    #google_translate_element {
        margin-right: 0;
        margin-bottom: 10px;
    }

    .goog-te-gadget-simple {
        font-size: 12px !important;
        padding: 4px 8px !important;
    }
}

        /* Gradient Backgrounds */
        .gradient-bg {
            background: linear-gradient(135deg, #22c55e 0%, #15803d 100%);
        }

        .logo-text {
            background: linear-gradient(90deg, #22c55e, #16a34a);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        /* Background Environment Animation */
        .environment-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -10;
            overflow: hidden;
        }

        /* Light Mode Background */
        .light-environment {
            background:
                /* Sky gradient */
                linear-gradient(180deg,
                    rgba(224, 242, 254, 0.8) 0%,
                    rgba(186, 253, 225, 0.6) 30%,
                    rgba(125, 252, 241, 0.4) 70%,
                    rgba(56, 189, 248, 0.2) 100%),
                /* Sun rays */
                radial-gradient(circle at 10% 10%,
                    rgba(253, 230, 138, 0.3) 0%,
                    transparent 50%),
                radial-gradient(circle at 90% 15%,
                    rgba(254, 240, 138, 0.2) 0%,
                    transparent 40%),
                /* Cloud texture */
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%2387ceeb' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        /* Dark Mode Background */
        .dark-environment {
            background:
                /* Night sky gradient */
                linear-gradient(180deg,
                    rgba(15, 23, 42, 0.95) 0%,
                    rgba(30, 41, 59, 0.9) 30%,
                    rgba(51, 65, 85, 0.8) 70%,
                    rgba(71, 85, 105, 0.7) 100%),
                /* Stars */
                radial-gradient(circle at 20% 30%,
                    rgba(255, 255, 255, 0.8) 1px,
                    transparent 1px),
                radial-gradient(circle at 40% 70%,
                    rgba(255, 255, 255, 0.6) 1px,
                    transparent 1px),
                radial-gradient(circle at 60% 20%,
                    rgba(255, 255, 255, 0.7) 1px,
                    transparent 1px),
                radial-gradient(circle at 80% 50%,
                    rgba(255, 255, 255, 0.5) 1px,
                    transparent 1px),
                radial-gradient(circle at 30% 80%,
                    rgba(255, 255, 255, 0.6) 1px,
                    transparent 1px),
                /* Moon glow */
                radial-gradient(circle at 85% 15%,
                    rgba(255, 255, 255, 0.1) 0%,
                    transparent 30%),
                /* Forest silhouette */
                linear-gradient(0deg,
                    transparent 60%,
                    rgba(34, 197, 94, 0.1) 85%,
                    rgba(21, 128, 61, 0.2) 100%);
            background-size:
                100% 100%,
                200px 200px,
                300px 300px,
                250px 250px,
                350px 350px,
                400px 400px,
                100% 100%;
        }

        /* Floating leaves animation */
        .floating-leaves {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .leaf {
            position: absolute;
            background: currentColor;
            opacity: 0.1;
            border-radius: 80% 0 80% 0;
            animation: float 20s infinite linear;
        }

        .leaf::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 0;
            width: 100%;
            height: 100%;
            background: inherit;
            border-radius: inherit;
            transform: rotate(45deg);
        }

        .leaf-1 {
            width: 30px;
            height: 20px;
            color: #16a34a;
            left: 10%;
            animation-delay: 0s;
            animation-duration: 25s;
        }
        .leaf-2 {
            width: 25px;
            height: 15px;
            color: #22c55e;
            left: 20%;
            animation-delay: 5s;
            animation-duration: 20s;
        }
        .leaf-3 {
            width: 35px;
            height: 25px;
            color: #15803d;
            left: 30%;
            animation-delay: 10s;
            animation-duration: 30s;
        }
        .leaf-4 {
            width: 20px;
            height: 12px;
            color: #166534;
            left: 40%;
            animation-delay: 15s;
            animation-duration: 18s;
        }
        .leaf-5 {
            width: 28px;
            height: 18px;
            color: #14532d;
            left: 50%;
            animation-delay: 7s;
            animation-duration: 22s;
        }
        .leaf-6 {
            width: 32px;
            height: 22px;
            color: #16a34a;
            left: 60%;
            animation-delay: 12s;
            animation-duration: 28s;
        }
        .leaf-7 {
            width: 24px;
            height: 16px;
            color: #22c55e;
            left: 70%;
            animation-delay: 8s;
            animation-duration: 24s;
        }
        .leaf-8 {
            width: 26px;
            height: 17px;
            color: #15803d;
            left: 80%;
            animation-delay: 3s;
            animation-duration: 26s;
        }
        .leaf-9 {
            width: 29px;
            height: 19px;
            color: #166534;
            left: 90%;
            animation-delay: 17s;
            animation-duration: 21s;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 0.1;
            }
            90% {
                opacity: 0.1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* Subtle pulse animation for background */
        @keyframes subtlePulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.95;
            }
        }

        .environment-bg {
            animation: subtlePulse 20s ease-in-out infinite;
        }

        /* Water ripple effect */
        .ripple {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 100px;
            background:
                radial-gradient(ellipse at center,
                    rgba(34, 197, 94, 0.1) 0%,
                    transparent 70%);
            animation: ripple 15s infinite linear;
        }

        @keyframes ripple {
            0%, 100% {
                transform: scale(1);
                opacity: 0.1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.15;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300 font-poppins">

    <!-- Environment Background -->
    <div class="environment-bg light-environment dark:hidden">
        <div class="floating-leaves">
            <div class="leaf leaf-1"></div>
            <div class="leaf leaf-2"></div>
            <div class="leaf leaf-3"></div>
            <div class="leaf leaf-4"></div>
            <div class="leaf leaf-5"></div>
            <div class="leaf leaf-6"></div>
            <div class="leaf leaf-7"></div>
            <div class="leaf leaf-8"></div>
            <div class="leaf leaf-9"></div>
        </div>
        <div class="ripple"></div>
    </div>

    <div class="environment-bg dark-environment hidden dark:block">
        <div class="floating-leaves">
            <div class="leaf leaf-1" style="color: #4ade80;"></div>
            <div class="leaf leaf-2" style="color: #22c55e;"></div>
            <div class="leaf leaf-3" style="color: #16a34a;"></div>
            <div class="leaf leaf-4" style="color: #15803d;"></div>
            <div class="leaf leaf-5" style="color: #166534;"></div>
            <div class="leaf leaf-6" style="color: #4ade80;"></div>
            <div class="leaf leaf-7" style="color: #22c55e;"></div>
            <div class="leaf leaf-8" style="color: #16a34a;"></div>
            <div class="leaf leaf-9" style="color: #15803d;"></div>
        </div>
        <div class="ripple" style="background: radial-gradient(ellipse at center, rgba(74, 222, 128, 0.08) 0%, transparent 70%);"></div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo Section -->
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <!-- Logo terang (light) -->
                    <img
                        src="{{ asset('images/logo-light.png') }}"
                        alt="Logo Light"
                        class="w-[180px] h-auto dark:hidden"
                    >

                    <!-- Logo gelap (dark) -->
                    <img
                        src="{{ asset('images/logo-dark.png') }}"
                        alt="Logo Dark"
                        class="w-[180px] h-auto hidden dark:block"
                    >
                </div>

                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Learning Management System
                </p>
            </div>

            <!-- Content Card -->
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-xl rounded-2xl p-8 border border-gray-100 dark:border-gray-700">
                @yield('content')
            </div>

            <!-- Footer Links -->
            <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                @hasSection('footer-links')
                    @yield('footer-links')
                @else
                    &copy; {{ date('Y') }} Green Leadership Indonesia. All rights reserved.
                @endif
            </div>
        </div>
    </div>




    <!-- Floating Tools: Translate + Dark Mode -->
<div class="fixed top-4 right-4 flex items-center gap-2 z-50">

    <!-- Translate Button -->
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
            class="w-10 h-10 flex items-center justify-center rounded-full
                   bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm
                   border border-gray-200 dark:border-gray-700
                   hover:shadow-lg transition">
            <i class="fas fa-language text-gray-700 dark:text-gray-300 text-lg"></i>
        </button>

        <!-- Dropdown -->
        <div x-show="open"
            @click.away="open = false"
            x-transition
            class="absolute right-0 mt-2 bg-white dark:bg-gray-800 border dark:border-gray-700
                   rounded-lg shadow-lg p-3 min-w-[160px] z-50"
            style="display:none;">
            <div id="google_translate_element"></div>
        </div>
    </div>

    <!-- Dark Mode Button -->
    <button onclick="toggleTheme()"
        class="w-10 h-10 flex items-center justify-center rounded-full
               bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm
               border border-gray-200 dark:border-gray-700
               hover:shadow-lg transition">
        <i class="fas fa-sun text-yellow-500 dark:hidden text-lg"></i>
        <i class="fas fa-moon text-blue-300 hidden dark:block text-lg"></i>
    </button>

</div>



    <script>
        function toggleTheme() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }

        // Additional environmental effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add random movement to leaves
            const leaves = document.querySelectorAll('.leaf');
            leaves.forEach(leaf => {
                const randomX = Math.random() * 20 - 10;
                leaf.style.setProperty('--random-x', `${randomX}px`);
            });
        });

            function setCookieTranslate(lang) {
        localStorage.setItem("selected_language", lang);
    }

    // Paksa Google Translate pilih bahasa sesuai localStorage
    function restoreTranslate() {
        const lang = localStorage.getItem("selected_language");
        if (lang && lang !== "id") {
            const select = document.querySelector("select.goog-te-combo");
            if (select) {
                select.value = lang;
                select.dispatchEvent(new Event("change"));
            }
        }
    }

    // Pantau perubahan bahasa oleh user
    document.addEventListener("change", function(e) {
        if (e.target.classList.contains("goog-te-combo")) {
            setCookieTranslate(e.target.value);
        }
    });

    // Restore pada setiap load
    window.addEventListener("load", function () {
        setTimeout(restoreTranslate, 1000);
    });
    </script>

    @stack('scripts')
</body>
</html>
