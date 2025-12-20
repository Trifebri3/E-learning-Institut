{{--
    File Partial: Presensi Box (Neutral Style)
    Variables: $setup, $hasil, $kelas, $awal_open, $akhir_open
--}}

<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden mb-8">

    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                <i class="fas fa-fingerprint text-lg"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Presensi Kehadiran</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Silakan isi kehadiran sesuai waktu yang ditentukan.</p>
            </div>
        </div>

        @if($hasil && $hasil->waktu_presensi_awal && $hasil->waktu_presensi_akhir)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800">
                <i class="fas fa-check-double mr-1.5"></i> Lengkap
            </span>
        @elseif($hasil && ($hasil->waktu_presensi_awal || $hasil->waktu_presensi_akhir))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                <i class="fas fa-hourglass-half mr-1.5"></i> Sebagian
            </span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">
                <i class="fas fa-circle mr-1.5 text-[8px]"></i> Belum Mengisi
            </span>
        @endif
    </div>

    @if (session('success') || session('error') || $errors->any())
        <div class="px-6 pt-6">
            @if (session('success'))
                <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-sm border border-green-100 dark:border-green-800 flex items-start gap-3">
                    <i class="fas fa-check-circle mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm border border-red-100 dark:border-red-800 flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle mt-0.5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm border border-red-100 dark:border-red-800">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <i class="fas fa-times-circle"></i> Terjadi Kesalahan:
                    </div>
                    <ul class="list-disc list-inside pl-1 space-y-0.5 opacity-90">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 relative">
        <div class="hidden lg:block absolute top-6 bottom-6 left-1/2 w-px bg-gray-100 dark:bg-gray-700"></div>

        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                    Presensi Awal
                </h4>

                @if($hasil && $hasil->waktu_presensi_awal)
                    <span class="text-xs font-bold text-green-600 flex items-center gap-1"><i class="fas fa-check"></i> Selesai</span>
                @elseif($awal_open)
                    <span class="text-xs font-bold text-blue-600 animate-pulse flex items-center gap-1"><i class="fas fa-clock"></i> Dibuka</span>
                @else
                    <span class="text-xs font-bold text-gray-400 flex items-center gap-1"><i class="fas fa-lock"></i> Ditutup</span>
                @endif
            </div>

            <div class="flex-1">
                @if ($hasil && $hasil->waktu_presensi_awal)
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-100 dark:border-gray-700 h-full">
                        <div class="flex flex-col gap-3">
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wider font-bold">Waktu Masuk</span>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($hasil->waktu_presensi_awal)->format('H:i') }} <span class="text-sm font-normal text-gray-500">WIB</span>
                                </p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($hasil->waktu_presensi_awal)->translatedFormat('l, d F Y') }}</p>
                            </div>

                            @if($hasil->refleksi_awal)
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                <span class="text-xs text-gray-400 uppercase tracking-wider font-bold block mb-1">Refleksi</span>
                                <p class="text-sm text-gray-600 dark:text-gray-300 italic">"{{ Str::limit($hasil->refleksi_awal, 100) }}"</p>
                            </div>
                            @endif
                        </div>
                    </div>

                @elseif ($awal_open)
                    <form method="POST" action="{{ route('participant.presensi.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                        <input type="hidden" name="tipe" value="awal">

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Token Presensi</label>
                            <input type="text" name="token" required autocomplete="off" placeholder="Masukkan token..."
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase">Refleksi Awal</label>
                                <span id="counter_awal" class="text-[10px] text-gray-400">0/10</span>
                            </div>
                            <textarea id="refleksi_awal" name="refleksi" rows="3" required minlength="10" placeholder="Harapan Anda untuk sesi ini..."
                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-all shadow-sm hover:shadow-md">
                            Kirim Presensi Masuk
                        </button>
                    </form>

                @else
                    <div class="flex flex-col items-center justify-center h-full py-8 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 text-gray-400">
                        <i class="fas fa-clock text-2xl mb-2 opacity-50"></i>
                        <span class="text-sm font-medium">Belum Dibuka</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="w-2 h-6 bg-purple-500 rounded-full"></span>
                    Presensi Akhir
                </h4>

                @if($hasil && $hasil->waktu_presensi_akhir)
                    <span class="text-xs font-bold text-green-600 flex items-center gap-1"><i class="fas fa-check"></i> Selesai</span>
                @elseif($akhir_open)
                    <span class="text-xs font-bold text-purple-600 animate-pulse flex items-center gap-1"><i class="fas fa-clock"></i> Dibuka</span>
                @else
                    <span class="text-xs font-bold text-gray-400 flex items-center gap-1"><i class="fas fa-lock"></i> Ditutup</span>
                @endif
            </div>

            <div class="flex-1">
                @if ($hasil && $hasil->waktu_presensi_akhir)
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-5 border border-gray-100 dark:border-gray-700 h-full">
                        <div class="flex flex-col gap-3">
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wider font-bold">Waktu Keluar</span>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    {{ \Carbon\Carbon::parse($hasil->waktu_presensi_akhir)->format('H:i') }} <span class="text-sm font-normal text-gray-500">WIB</span>
                                </p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($hasil->waktu_presensi_akhir)->translatedFormat('l, d F Y') }}</p>
                            </div>

                            @if($hasil->refleksi_akhir)
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-600">
                                <span class="text-xs text-gray-400 uppercase tracking-wider font-bold block mb-1">Refleksi</span>
                                <p class="text-sm text-gray-600 dark:text-gray-300 italic">"{{ Str::limit($hasil->refleksi_akhir, 100) }}"</p>
                            </div>
                            @endif
                        </div>
                    </div>

                @elseif ($akhir_open)
                    <form method="POST" action="{{ route('participant.presensi.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                        <input type="hidden" name="tipe" value="akhir">

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Token Keluar</label>
                            <input type="text" name="token" required autocomplete="off" placeholder="Masukkan token..."
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm">
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="text-xs font-bold text-gray-500 uppercase">Refleksi Akhir</label>
                                <span id="counter_akhir" class="text-[10px] text-gray-400">0/10</span>
                            </div>
                            <textarea id="refleksi_akhir" name="refleksi" rows="3" required minlength="10" placeholder="Apa yang Anda pelajari hari ini?"
                                      class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all text-sm resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-sm transition-all shadow-sm hover:shadow-md">
                            Kirim Presensi Keluar
                        </button>
                    </form>

                @else
                    <div class="flex flex-col items-center justify-center h-full py-8 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 text-gray-400">
                        <i class="fas fa-lock text-2xl mb-2 opacity-50"></i>
                        <span class="text-sm font-medium">Belum Dibuka</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
