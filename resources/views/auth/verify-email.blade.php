@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
<div class="mb-6 text-center">
    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-envelope text-primary-600 dark:text-primary-400 text-2xl"></i>
    </div>
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Verifikasi Email</h3>
    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
        Terima kasih telah mendaftar! Sebelum memulai, verifikasi alamat email Anda dengan mengklik link yang kami kirimkan. Jika tidak menerima email, kami akan dengan senang hati mengirimkan yang lain.
    </p>
</div>

@if (session('status') == 'verification-link-sent')
    <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <span class="text-green-800 dark:text-green-300 text-sm">
                Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
            </span>
        </div>
    </div>
@endif

<div class="flex flex-col space-y-4">
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105">
            <i class="fas fa-paper-plane mr-2"></i>
            {{ __('Kirim Ulang Email Verifikasi') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full py-3 px-4 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors duration-200">
            <i class="fas fa-sign-out-alt mr-2"></i>
            {{ __('Keluar') }}
        </button>
    </form>
</div>
@endsection
