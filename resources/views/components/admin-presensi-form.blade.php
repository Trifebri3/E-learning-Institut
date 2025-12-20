@props(['setup', 'action'])

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Toggle Aktif --}}
    <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg border border-indigo-100 dark:border-indigo-700 flex justify-between items-center">
        <div>
            <h4 class="font-bold text-indigo-900 dark:text-indigo-300">Status Sistem Presensi</h4>
            <p class="text-xs text-indigo-600 dark:text-indigo-400">Jika dimatikan, peserta tidak bisa input presensi.</p>
        </div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="is_active" class="sr-only peer" @checked($setup && $setup->is_active)>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
        </label>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Kolom Presensi AWAL --}}
        <div class="bg-white dark:bg-gray-700 p-5 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 border-b pb-2 flex items-center">
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded mr-2">AWAL</span> Sesi Pembuka
            </h3>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Token Awal</label>
                <div class="flex gap-2">
                    <input type="text" name="token_awal" id="token_awal"
                           value="{{ old('token_awal', $setup->token_awal ?? Str::random(6)) }}"
                           class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:text-white font-mono text-center tracking-widest font-bold uppercase" required>
                    <button type="button" onclick="generateToken('token_awal')" class="px-3 bg-gray-200 dark:bg-gray-600 rounded-lg hover:bg-gray-300"><i class="fas fa-random"></i></button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Waktu Buka</label>
                    <input type="datetime-local" name="buka_awal"
                           value="{{ $setup ? \Carbon\Carbon::parse($setup->buka_awal)->format('Y-m-d\TH:i') : '' }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white text-sm" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Waktu Tutup</label>
                    <input type="datetime-local" name="tutup_awal"
                           value="{{ $setup ? \Carbon\Carbon::parse($setup->tutup_awal)->format('Y-m-d\TH:i') : '' }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white text-sm" required>
                </div>
            </div>
        </div>

        {{-- Kolom Presensi AKHIR --}}
        <div class="bg-white dark:bg-gray-700 p-5 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 border-b pb-2 flex items-center">
                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded mr-2">AKHIR</span> Sesi Penutup
            </h3>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Token Akhir</label>
                <div class="flex gap-2">
                    <input type="text" name="token_akhir" id="token_akhir"
                           value="{{ old('token_akhir', $setup->token_akhir ?? Str::random(6)) }}"
                           class="w-full rounded-lg border-gray-300 dark:bg-gray-800 dark:text-white font-mono text-center tracking-widest font-bold uppercase" required>
                    <button type="button" onclick="generateToken('token_akhir')" class="px-3 bg-gray-200 dark:bg-gray-600 rounded-lg hover:bg-gray-300"><i class="fas fa-random"></i></button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Waktu Buka</label>
                    <input type="datetime-local" name="buka_akhir"
                           value="{{ $setup ? \Carbon\Carbon::parse($setup->buka_akhir)->format('Y-m-d\TH:i') : '' }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white text-sm" required>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Waktu Tutup</label>
                    <input type="datetime-local" name="tutup_akhir"
                           value="{{ $setup ? \Carbon\Carbon::parse($setup->tutup_akhir)->format('Y-m-d\TH:i') : '' }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-800 dark:text-white text-sm" required>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-4">
        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-lg transition">
            <i class="fas fa-save mr-2"></i> Simpan Konfigurasi
        </button>
    </div>
</form>

<script>
    function generateToken(inputId) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < 6; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById(inputId).value = result;
    }
</script>
