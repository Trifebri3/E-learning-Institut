@extends('instructor.layouts.app')

@section('title', 'Kelola Presensi: ' . $kelas->title)

@section('content')
<div class="container mx-auto p-6 max-w-5xl">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Manajemen Presensi</h1>
            <p class="text-gray-500 text-sm">Kelas: {{ $kelas->title }}</p>
        </div>
        <a href="{{ route('instructor.kelas.edit', $kelas->id) }}" class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Kelas
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- KOLOM KIRI: FORM SETUP -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border dark:border-gray-700 sticky top-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Konfigurasi</h3>

                {{-- Panggil Component Form --}}
                <x-admin-presensi-form
                    :setup="$kelas->presensiSetup"
                    :action="route('adminprogram.presensi.update', $kelas->id)"
                />
            </div>
        </div>

        <!-- KOLOM KANAN: MONITORING PESERTA -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 overflow-hidden">
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white">Monitoring Kehadiran</h3>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-bold">Total: {{ $attendances->count() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase font-bold text-xs">
                            <tr>
                                <th class="px-4 py-3">Peserta</th>
                                <th class="px-4 py-3 text-center">Awal</th>
                                <th class="px-4 py-3 text-center">Akhir</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($attendances as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $data->user->name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $data->nomorInduk->nomor_induk ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($data->waktu_presensi_awal)
                                        <span class="text-green-600 text-xs font-bold" title="{{ $data->waktu_presensi_awal }}">
                                            <i class="fas fa-check"></i> {{ \Carbon\Carbon::parse($data->waktu_presensi_awal)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-red-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($data->waktu_presensi_akhir)
                                        <span class="text-green-600 text-xs font-bold" title="{{ $data->waktu_presensi_akhir }}">
                                            <i class="fas fa-check"></i> {{ \Carbon\Carbon::parse($data->waktu_presensi_akhir)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-red-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $badges = [
                                            'alpha' => 'bg-red-100 text-red-800',
                                            'hadir_awal' => 'bg-yellow-100 text-yellow-800',
                                            'hadir_akhir' => 'bg-yellow-100 text-yellow-800',
                                            'hadir_full' => 'bg-green-100 text-green-800',
                                        ];
                                        $labels = [
                                            'alpha' => 'Alfa',
                                            'hadir_awal' => 'Parsial',
                                            'hadir_akhir' => 'Parsial',
                                            'hadir_full' => 'Hadir',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-bold {{ $badges[$data->status_kehadiran] }}">
                                        {{ $labels[$data->status_kehadiran] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form action="{{ route('instructor.presensi.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Reset data presensi peserta ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus Data"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data presensi masuk.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
