@extends('participant.layouts.app')

@section('title', 'Redeem Kode Program')

@section('content')
<div class="py-8">
     <div class="flex flex-col max-w-full min-w-0 ml-4 mr-12 sm:ml-4 sm:mr-12 md:ml-6 md:mr-18 lg:ml-8 lg:mr-24">

        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-primary-100 dark:bg-primary-900 rounded-lg mr-4">
                    <i class="fas fa-gift text-primary-600 dark:text-primary-400 text-xl"></i>
                </div>

                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Redeem Kode Program') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Gunakan kode program untuk mengakses konten eksklusif
                    </p>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-8">
                <!-- Information Alert -->
                <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 text-xl mt-1"></i>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-semibold text-blue-800 dark:text-blue-300 text-lg">Informasi Redeem</h4>
                            <p class="text-blue-700 dark:text-blue-400 mt-2 leading-relaxed">
                                Anda hanya dapat me-redeem kode satu kali. Masukkan kode program unik Anda di bawah ini untuk mendaftar ke program yang dituju.
                            </p>
                            <div class="mt-3 flex items-center text-sm text-blue-600 dark:text-blue-500">
                                <i class="fas fa-shield-alt mr-2"></i>
                                <span>Kode program bersifat rahasia dan unik untuk setiap peserta</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Redeem Form -->
                <form method="POST" action="{{ route('participant.redeem.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Redeem Field -->
                        <div class="space-y-3">
                            <label for="kode" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Kode Redeem Program <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    id="kode"
                                    name="kode"
                                    type="text"
                                    value="{{ old('kode') }}"
                                    required
                                    autofocus
                                    autocomplete="off"
                                    placeholder="Contoh: PRG-2024-ABC123"
                                    class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200 @error('kode') border-red-500 dark:border-red-400 @enderror"
                                />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                            </div>
                            @error('kode')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Nomor Induk Field -->
                        <div class="space-y-3">
                            <label for="nomor_induk" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Nomor Induk <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    id="nomor_induk"
                                    name="nomor_induk"
                                    type="text"
                                    value="{{ old('nomor_induk') }}"
                                    required
                                    autocomplete="off"
                                    placeholder="Masukkan nomor induk Anda"
                                    class="w-full px-4 py-3 pl-11 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white transition-all duration-200 @error('nomor_induk') border-red-500 dark:border-red-400 @enderror"
                                />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                </div>
                            </div>
                            @error('nomor_induk')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            <span>Pastikan kode program dan nomor induk yang Anda masukkan sudah benar sebelum melakukan redeem</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('participant.dashboard') }}"
                           class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Dashboard
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 focus:scale-105 shadow-lg">
                            <i class="fas fa-gift mr-2"></i>
                            {{ __('Redeem Sekarang') }}
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>

                <!-- Success Message (if any) -->
                @if(session('success'))
                    <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-green-800 dark:text-green-300">Berhasil!</h4>
                                <p class="text-green-700 dark:text-green-400 text-sm mt-1">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Error Message (if any) -->
                @if(session('error'))
                    <div class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                            <div>
                                <h4 class="font-medium text-red-800 dark:text-red-300">Terjadi Kesalahan</h4>
                                <p class="text-red-700 dark:text-red-400 text-sm mt-1">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Additional Help Section -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                <i class="fas fa-question-circle text-primary-600 mr-2"></i>
                Butuh Bantuan?
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-start">
                    <i class="fas fa-phone text-primary-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Hubungi Admin</h4>
                        <p class="mt-1">+62 822 4743 1493 (PIC LMS)</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-envelope text-primary-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-700 dark:text-gray-300">Email Support</h4>
                        <p class="mt-1">info@greenleadership.id</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .focus\:ring-primary-500:focus {
        --tw-ring-color: rgb(34 197 94 / 0.5);
    }

    input:focus {
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
    }

    /* Smooth transitions for all interactive elements */
    button, a, input {
        transition: all 0.2s ease-in-out;
    }
</style>

<script>
    // Add some interactive features
    document.addEventListener('DOMContentLoaded', function() {
        const kodeInput = document.getElementById('kode');
        const nomorIndukInput = document.getElementById('nomor_induk');

        // Auto-format kode input to uppercase
        if (kodeInput) {
            kodeInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }

        // Add input validation styling
        const inputs = [kodeInput, nomorIndukInput];
        inputs.forEach(input => {
            if (input) {
                input.addEventListener('blur', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('border-green-500', 'dark:border-green-400');
                    } else {
                        this.classList.remove('border-green-500', 'dark:border-green-400');
                    }
                });
            }
        });
    });
</script>
@endsection
