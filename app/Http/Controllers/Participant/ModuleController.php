<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Module;

class ModuleController extends Controller
{
    /**
     * Menampilkan konten modul.
     */
    public function show($id)
    {
        $user = Auth::user();
        $module = Module::with('kelas.program')->findOrFail($id);

        // [SECURITY CHECK] Pastikan user terdaftar di program ini
        $userProgramIds = $user->programs()->pluck('programs.id')->toArray();

        $moduleProgramId = $module->kelas->program_id;

        if (!in_array($moduleProgramId, $userProgramIds)) {
            abort(403, 'Akses ditolak. Modul bukan milik program Anda.');
        }

        // Cek apakah user sudah menyelesaikan modul ini
        $isCompleted = DB::table('module_user')
            ->where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->exists();

        return view('participant.module.show', compact('module', 'isCompleted'));
    }

    /**
     * Mencatat status "Selesai" untuk modul.
     */
    public function complete($id)
    {
        $user = Auth::user();
        $module = Module::findOrFail($id);

        // Catat penyelesaian (updateOrInsert mencegah duplikat)
        DB::table('module_user')->updateOrInsert(
            ['user_id' => $user->id, 'module_id' => $module->id],
            ['completed_at' => now()]
        );

        return back()->with('success', 'Modul berhasil ditandai sebagai selesai!');
    }
}
