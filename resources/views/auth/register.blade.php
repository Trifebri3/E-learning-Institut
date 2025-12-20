@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
<div class="mb-6 text-center">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Akun Baru</h3>
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        Bergabunglah dengan komunitas Green Leadership Indonesia
    </p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Nama Lengkap <span class="text-red-500">*</span>
        </label>
        <input id="name"
               type="text"
               name="name"
               value="{{ old('name') }}"
               required
               autofocus
               autocomplete="name"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('name') border-red-500 @enderror"
               placeholder="Masukkan nama lengkap Anda">

        @error('name')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Email <span class="text-red-500">*</span>
        </label>
        <input id="email"
               type="email"
               name="email"
               value="{{ old('email') }}"
               required
               autocomplete="email"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('email') border-red-500 @enderror"
               placeholder="Masukkan email Anda">

        @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Password -->
    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Password <span class="text-red-500">*</span>
        </label>
        <input id="password"
               type="password"
               name="password"
               required
               autocomplete="new-password"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('password') border-red-500 @enderror"
               placeholder="Buat password yang kuat">

        @error('password')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-4">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Konfirmasi Password <span class="text-red-500">*</span>
        </label>
        <input id="password_confirmation"
               type="password"
               name="password_confirmation"
               required
               autocomplete="new-password"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
               placeholder="Konfirmasi password Anda">

        @error('password_confirmation')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Cloudflare Turnstile -->
    <div class="mb-6">
        <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}"></div>
        @error('cf-turnstile-response')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <button type="submit" class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105 mb-4">
        <i class="fas fa-user-plus mr-2"></i>
        {{ __('Daftar') }}
    </button>

    <!-- Google Register -->
    <div class="text-center">
        <div class="relative mb-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">Atau daftar dengan</span>
            </div>
        </div>

        <a href="{{ route('google.redirect') }}" class="inline-flex items-center justify-center w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path d="M48 24C48 22.04 47.83 20.15 47.52 18.32H24.5V28.53H37.89C37.33 31.54 35.88 34.1 33.6 35.88V41.7H41.31C45.56 37.8 48 31.45 48 24Z" fill="#4285F4"></path>
                <path d="M24.5 48C30.96 48 36.43 45.82 40.35 42.04L33.09 36.33C30.96 37.77 27.97 38.6 24.5 38.6C18.27 38.6 12.9 34.69 11.07 29.33H3.14V35.08C7.03 42.92 15.11 48 24.5 48Z" fill="#34A853"></path>
                <path d="M11.07 29.33C10.59 27.87 10.32 26.33 10.32 24.77C10.32 23.21 10.59 21.67 11.07 20.21V14.46H3.14C1.12 18.23 0 22.86 0 27.77C0 32.68 1.12 37.31 3.14 41.08L11.07 29.33Z" fill="#FBBC05"></path>
                <path d="M24.5 9.4C27.09 9.4 29.37 10.31 31.18 11.98L37.16 6.06C33.32 2.58 28.32 0.5 24.5 0.5C15.11 0.5 7.03 5.58 3.14 13.42L11.07 19.17C12.9 13.81 18.27 9.4 24.5 9.4Z" fill="#EA4335"></path>
            </svg>
            Google
        </a>
    </div>
</form>

@section('footer-links')
<div class="flex justify-center space-x-4">
    <span class="text-gray-500 dark:text-gray-400 text-sm">Sudah punya akun?</span>
    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium transition-colors duration-200">
        Masuk di sini
    </a>
</div>
@endsection
@endsection
