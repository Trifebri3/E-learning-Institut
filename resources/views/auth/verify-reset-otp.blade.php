@extends('layouts.guest')

@section('title', 'Verifikasi OTP Reset Password')

@section('content')
<div class="mb-6 text-center">
    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-lock text-primary-600 dark:text-primary-400 text-2xl"></i>
    </div>
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Verifikasi OTP</h3>
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        Kami telah mengirimkan kode OTP 6 digit ke email Anda. Silakan masukkan kode tersebut untuk melanjutkan reset password.
    </p>
</div>

@if (session('status'))
    <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <span class="text-green-800 dark:text-green-300 text-sm">{{ session('status') }}</span>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('password.verify-otp.store') }}">
    @csrf

    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

    <div class="mb-6">
        <label for="otp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Kode OTP <span class="text-red-500">*</span>
        </label>
        <input id="otp"
               type="text"
               name="otp"
               required
               autofocus
               autocomplete="one-time-code"
               maxlength="6"
               class="w-full px-4 py-3 text-center text-lg font-semibold border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-colors duration-200 @error('otp') border-red-500 @enderror"
               placeholder="••••••">

        @error('otp')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror

        @error('email')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </p>
        @enderror
    </div>

    <button type="submit" class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105">
        <i class="fas fa-check-circle mr-2"></i>
        {{ __('Verifikasi OTP') }}
    </button>
</form>

<div class="text-center mt-4">
    <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-200">
        <i class="fas fa-redo mr-1"></i> Kirim ulang OTP?
    </a>
</div>
@endsection
