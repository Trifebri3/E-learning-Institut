<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar semua Materi (Resource) yang dapat diakses user.
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil ID dari semua Program yang diikuti user
        $programIds = $user->programs()->pluck('programs.id');

        // Ambil semua Resource dari kelas-kelas yang ada di program tersebut
        $resources = Resource::whereHas('kelas', function ($query) use ($programIds) {
                            $query->whereIn('program_id', $programIds);
                        })
                        ->where('is_published', true)
                        ->with([
                            'kelas',
                            'kelas.program',
                            'users' => function($query) use ($user) {
                                $query->where('user_id', $user->id);
                            }
                        ])
                        ->latest() // Opsional: urutkan dari yang terbaru
                        ->get();

        // Kelompokkan Resource berdasarkan Program
        $groupedResources = $resources->groupBy(function ($resource) {
            return $resource->kelas->program->title;
        });

        return view('participant.materi.index', compact('groupedResources'));
    }

    /**
     * Menampilkan Detail Materi & Mencatat Status "Dibuka".
     */
    public function show($id)
    {
        $user = Auth::user();

        // 1. Ambil data resource
        $resource = Resource::with(['kelas.program', 'users'])
                        ->findOrFail($id);

        // 2. (Opsional) Validasi apakah user berhak mengakses resource ini
        // Cek apakah user terdaftar di program milik kelas resource ini
        // if (!$user->programs->contains($resource->kelas->program_id)) {
        //    abort(403, 'Anda tidak memiliki akses ke materi ini.');
        // }

        // 3. Catat history bahwa user telah membuka materi ini
        // syncWithoutDetaching: Menambahkan record jika belum ada, membiarkan jika sudah ada
        $resource->users()->syncWithoutDetaching([$user->id]);

        // 4. Tampilkan halaman detail (View yang baru kita perbaiki)
        return view('participant.materi.show', compact('resource'));
    }
}
