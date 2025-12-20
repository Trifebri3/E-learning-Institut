@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="mb-6 text-center">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Reset Password</h3>
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        Buat password baru untuk akun Anda
    </p>
</div>

<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email Address -->
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Email <span class="text-red-500">*</span>
        </label>
        <input id="email"
               type="email"
               name="email"
               value="{{ old('email', $request->email) }}"
               required
               autofocus
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
            Password Baru <span class="text-red-500">*</span>
        </label>
        <input id="password"
               type="password"
               name="password"
               required
               autocomplete="new-password"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('password') border-red-500 @enderror"
               placeholder="Buat password baru">

        @error('password')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-6">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Konfirmasi Password Baru <span class="text-red-500">*</span>
        </label>
        <input id="password_confirmation"
               type="password"
               name="password_confirmation"
               required
               autocomplete="new-password"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
               placeholder="Konfirmasi password baru">

        @error('password_confirmation')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <button type="submit" class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105">
        <i class="fas fa-redo-alt mr-2"></i>
        {{ __('Reset Password') }}
    </button>
</form>

@section('footer-links')
<div class="flex justify-center">
    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-sm transition-colors duration-200">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
    </a>
</div>
@endsection
@endsection
