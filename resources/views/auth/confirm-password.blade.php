@extends('layouts.guest')

@section('title', 'Konfirmasi Password')

@section('content')
<div class="mb-6 text-center">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Konfirmasi Password</h3>
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        Ini adalah area aman dari aplikasi. Harap konfirmasi password Anda sebelum melanjutkan.
    </p>
</div>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <!-- Password -->
    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Password <span class="text-red-500">*</span>
        </label>
        <input id="password"
               type="password"
               name="password"
               required
               autocomplete="current-password"
               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('password') border-red-500 @enderror"
               placeholder="Masukkan password Anda">

        @error('password')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <button type="submit" class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105">
        <i class="fas fa-shield-alt mr-2"></i>
        {{ __('Konfirmasi Password') }}
    </button>
</form>
@endsection
