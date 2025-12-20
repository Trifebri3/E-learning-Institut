<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LMS')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

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
    </style>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 transition-colors duration-300">
    <!-- Header -->
    @include('participant.layouts.header')

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <!-- Main Content -->
        <main class="flex-1 p-6 ml-64"> <!-- Adjust margin-left sesuai lebar sidebar -->
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    @include('participant.layouts.footer')

    <!-- Dark Mode Toggle Script -->
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }
    </script>
</body>
</html>
