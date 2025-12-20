@extends('superadmin.layouts.app')

@section('content')
<div class="container mx-auto p-6" x-data="{ activeTab: 'participants' }">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('superadmin.programs.index') }}"
           class="text-gray-500 hover:text-indigo-600 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <div class="flex gap-2">
            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-bold font-mono">
                CODE: {{ $program->redeem_code }}
            </span>

            <a href="{{ route('superadmin.programs.edit', $program->id) }}"
               class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-bold shadow">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
        </div>
    </div>

    {{-- BANNER --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden mb-8 border dark:border-gray-700">
        <div class="relative h-48 bg-gray-300 dark:bg-gray-700">

            @if($program->banner_path)
                <img src="{{ Storage::url($program->banner_path) }}"
                     class="w-full h-full object-cover opacity-90">
            @else
                <div class="w-full h-full flex items-center justify-center
                            bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                    <i class="fas fa-image text-4xl opacity-40"></i>
                </div>
            @endif

            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/80 to-transparent p-6">
                <div class="flex items-end gap-4">

                    @if($program->logo_path)
                        <img src="{{ Storage::url($program->logo_path) }}"
                             class="w-20 h-20 rounded-lg bg-white shadow-md object-contain p-1">
                    @endif

                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ $program->title }}</h1>

                        <p class="text-gray-200 text-sm flex gap-4 mt-1">
                            <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $program->lokasi }}</span>
                            <span>
                                <i class="fas fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($program->waktu_mulai)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($program->waktu_selesai)->format('d M Y') }}
                            </span>
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-200 dark:divide-gray-700 border-b border-gray-200 dark:border-gray-700">
            <div class="p-6 text-center">
                <span class="text-xs text-gray-500 uppercase font-bold">Total Peserta</span>
                <div class="text-3xl font-extrabold text-indigo-600 mt-1">
                    {{ $program->participants->count() }}
                    <span class="text-sm text-gray-400 font-normal">/ {{ $program->kuota }}</span>
                </div>
            </div>
            <div class="p-6 text-center">
                <span class="text-xs text-gray-500 uppercase font-bold">Jumlah Kelas</span>
                <div class="text-3xl font-extrabold text-green-600 mt-1">{{ $program->kelas_count }}</div>
            </div>
            <div class="p-6 text-center">
                <span class="text-xs text-gray-500 uppercase font-bold">Total Tugas</span>
                <div class="text-3xl font-extrabold text-purple-600 mt-1">{{ $allAssignments->count() }}</div>
            </div>
            <div class="p-6 text-center">
                <span class="text-xs text-gray-500 uppercase font-bold">Total Ujian</span>
                <div class="text-3xl font-extrabold text-red-600 mt-1">{{ $allExams->count() }}</div>
            </div>
        </div>
    </div>

    {{-- TAB MENU --}}
    <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
        @php
            $tabs = [
                'participants' => ['icon' => 'fa-users', 'label' => 'Peserta'],
                'kelas'        => ['icon' => 'fa-chalkboard-teacher', 'label' => 'Daftar Kelas'],
                'evaluasi'     => ['icon' => 'fa-tasks', 'label' => 'Tugas & Ujian'],
                'admins'       => ['icon' => 'fa-user-shield', 'label' => 'Admin Pengelola'],
            ];
        @endphp

        @foreach($tabs as $key => $tab)
            <button @click="activeTab = '{{ $key }}'"
                :class="activeTab === '{{ $key }}'
                    ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm transition-all">
                <i class="fas {{ $tab['icon'] }} mr-2"></i> {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    {{-- TAB CONTENT --}}
    {{-- ============================= --}}
    {{-- TAB 1: PESERTA --}}
    {{-- ============================= --}}
    <div x-show="activeTab === 'participants'" class="bg-white dark:bg-gray-800 rounded-xl shadow border dark:border-gray-700 overflow-hidden">
        <div class="p-4 border-b bg-gray-50 dark:bg-gray-700/50">
            <h3 class="font-bold text-gray-700 dark:text-gray-200">Daftar Peserta</h3>
        </div>

        <div class="overflow-x-auto max-h-96">
            <table class="w-full text-sm">
                <thead class="text-xs bg-gray-100 dark:bg-gray-700 sticky top-0">
                    <tr>
                        <th class="px-6 py-3">Nama Peserta</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">No. HP</th>
                        <th class="px-6 py-3">Instansi</th>
                        <th class="px-6 py-3">Tanggal Gabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($program->participants as $p)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 font-medium flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($p->name) }}" class="w-8 h-8 rounded-full">
                                {{ $p->name }}
                            </td>
                            <td class="px-6 py-4">{{ $p->email }}</td>
                            <td class="px-6 py-4">{{ $p->profile->nomor_hp ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $p->profile->instansi_perusahaan ?? '-' }}</td>
                            <td class="px-6 py-4">{{ optional($p->pivot->created_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada peserta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- TAB 2: KELAS --}}
    {{-- ============================= --}}
    <div x-show="activeTab === 'kelas'" class="grid grid-cols-1 gap-4">

        @forelse($classes as $kelas)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border p-4 flex justify-between items-center">

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-indigo-50 flex items-center justify-center font-bold text-indigo-600">
                        {{ $loop->iteration }}
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $kelas->title }}</h4>
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ \Carbon\Carbon::parse($kelas->tanggal)->format('d M Y') }}

                            <span class="uppercase text-xs font-bold px-2 py-0.5 rounded
                                {{ $kelas->tipe === 'interaktif' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                {{ $kelas->tipe }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="text-right text-sm">
                </div>

            </div>
        @empty
            <div class="text-center p-8 bg-white rounded-xl border border-dashed">
                <p class="text-gray-500">Belum ada kelas.</p>
            </div>
        @endforelse

    </div>

    {{-- ============================= --}}
    {{-- TAB 3: TUGAS & UJIAN --}}
    {{-- ============================= --}}
    <div x-show="activeTab === 'evaluasi'" class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- TUGAS --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border">
            <div class="p-3 bg-purple-50 dark:bg-purple-900/20 font-bold text-purple-800">
                <i class="fas fa-clipboard-list mr-2"></i> Daftar Tugas
            </div>

            <ul class="divide-y">
                @forelse($allAssignments as $task)
                    <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $task->title }}</span>
<span class="text-xs bg-gray-100 px-2 py-1 rounded">
    Max: {{ $task->max_points ?? 0 }} Pts
</span>

                        </div>

                        <div class="text-xs text-gray-500 mt-1">
                            Kelas: {{ $task->kelas_name ?? '-' }}
                            • Due: {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                        </div>
                    </li>
                @empty
                    <li class="p-4 text-center text-gray-500">Tidak ada tugas.</li>
                @endforelse
            </ul>
        </div>

        {{-- UJIAN --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border">
            <div class="p-3 bg-red-50 dark:bg-red-900/20 font-bold text-red-800">
                <i class="fas fa-edit mr-2"></i> Daftar Ujian
            </div>

            <ul class="divide-y">
                @forelse($allExams as $exam)
                    <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ $exam->title }}</span>
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded uppercase">
                                Exam
                            </span>
                        </div>

                        <div class="text-xs text-gray-500 mt-1">
                            Durasi: {{ $exam->duration_minutes }} menit
                        </div>
                    </li>
                @empty
                    <li class="p-4 text-center text-gray-500">Tidak ada ujian.</li>
                @endforelse
            </ul>
        </div>

    </div>

    {{-- ============================= --}}
    {{-- TAB 4: ADMIN --}}
    {{-- ============================= --}}
    <div x-show="activeTab === 'admins'" class="bg-white dark:bg-gray-800 rounded-xl shadow border p-6">
        <h3 class="font-bold text-gray-800 dark:text-white mb-4">Admin Program</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($program->admins as $admin)
                <div class="flex items-center gap-4 p-3 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}"
                         class="w-10 h-10 rounded-full">

                    <div>
                        <h4 class="text-sm font-bold">{{ $admin->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $admin->email }}</p>
                    </div>
                </div>
            @empty
                <p class="col-span-3 text-gray-500">Belum ada admin.</p>
            @endforelse
        </div>
        <div class="mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-2">Instruktur Program</h3>

    @if($program->instructors->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($program->instructors as $ins)
                <div class="p-3 border rounded-xl bg-gray-50">
                    <p class="font-medium text-gray-700">{{ $ins->name }}</p>
                    <p class="text-gray-500 text-sm">{{ $ins->email }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 italic">Belum ada instruktur ditunjuk.</p>
    @endif
</div>

    </div>

</div>
@endsection
