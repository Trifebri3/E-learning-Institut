@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<div class="mb-6 text-center">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Lupa Password?</h3>
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        Tidak masalah. Beri tahu kami email Anda dan kami akan mengirimkan link reset password.
    </p>
</div>

<!-- Session Status -->
@if (session('status'))
    <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <span class="text-green-800 dark:text-green-300 text-sm">{{ session('status') }}</span>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

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
               autofocus
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('email') border-red-500 @enderror"
               placeholder="Masukkan email Anda">

        @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Cloudflare Turnstile -->
    <div class="mb-4">
        <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}"></div>
        @error('cf-turnstile-response')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <button type="submit" class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105">
        <i class="fas fa-paper-plane mr-2"></i>
        {{ __('Kirim Link Reset Password') }}
    </button>
</form>

@section('footer-links')
<div class="flex justify-center space-x-4">
    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-sm transition-colors duration-200">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
    </a>
</div>
@endsection
@endsection
