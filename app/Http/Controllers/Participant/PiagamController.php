<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Piagam; // model baru
use App\Models\Program; // jika ingin ambil program user
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\NomorInduk;

class PiagamController extends Controller
{
public function index()
{
    $user = Auth::user();

    // Semua piagam user
    $piagam = Piagam::where('user_id', $user->id)
                    ->where('is_approved', true)
                    ->with('program')
                    ->get();

    // Semua program yang diikuti user
    $userPrograms = $user->programs()->get();

    // Buat array helper untuk cepat cek piagam per program
    $piagamMap = Piagam::where('user_id', $user->id)
                       ->get()
                       ->keyBy('program_id');

    return view('participant.piagam.index', compact('piagam', 'userPrograms', 'piagamMap'));
}


    public function request($programId)
    {
        $user = Auth::user();

        // Cek apakah user sudah punya piagam untuk program ini
        $exists = Piagam::where('user_id', $user->id)
                        ->where('program_id', $programId)
                        ->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Piagam sudah diajukan atau ada.');
        }

        // Buat permintaan piagam (status awal: belum disetujui)
        Piagam::create([
            'user_id' => $user->id,
            'program_id' => $programId,
            'issued_at' => now(),
            'grade' => null,
            'serial_number' => 'TEMP/' . time(), // bisa diganti saat admin approve
            'is_approved' => false,
        ]);

        return redirect()->back()->with('success', 'Permintaan piagam berhasil diajukan.');
    }

    // Download Piagam
public function download($piagamId)
{
    $user = Auth::user();

    $piagam = Piagam::where('id', $piagamId)
                    ->where('user_id', $user->id)
                    ->where('is_approved', true)
                    ->firstOrFail();

    // Generate PDF langsung tanpa simpan
    $pdf = Pdf::loadView('participant.piagam.print', [
        'piagam' => $piagam
    ])->setPaper('a4', 'landscape');

    // Ganti karakter / atau \ agar aman untuk filename
    $fileName = preg_replace('/[\/\\\\]/', '_', $piagam->serial_number) . '.pdf';

    return $pdf->download($fileName);
}
    // Generate PDF Piagam
    public function generatePiagam(Piagam $piagam)
    {
        $fileName = $piagam->serial_number . '.pdf';

        $pdf = Pdf::loadView('participant.piagam.print', [
            'piagam' => $piagam
        ])->setPaper('a4', 'landscape');

        // Simpan PDF ke storage
        Storage::put("piagam/{$fileName}", $pdf->output());

        $piagam->file_path = "piagam/{$fileName}";
        $piagam->save();

        return response()->download(storage_path("app/piagam/{$fileName}"));
    }

public function preview($piagamId)
{
    $user = Auth::user();

    // Ambil piagam yang sudah disetujui
    $piagam = Piagam::where('id', $piagamId)
                    ->where('user_id', $user->id)
                    ->where('is_approved', true)
                    ->with('program')
                    ->firstOrFail();

    // Ambil nomor induk sesuai program dari tabel NomorInduk
    $nomorInduk = NomorInduk::where('user_id', $user->id)
                             ->where('program_id', $piagam->program_id)
                             ->value('nomor_induk') ?? '-';

    $pdf = Pdf::loadView('participant.piagam.print', [
        'piagam' => $piagam,
        'nomorInduk' => $nomorInduk
    ])
    ->setPaper('a4', 'portrait')
    ->setOption('margin-top', 0)
    ->setOption('margin-bottom', 0)
    ->setOption('margin-left', 0)
    ->setOption('margin-right', 0)
    ->setOption('enable-local-file-access', true) // agar bisa load gambar background lokal
    ->setOption('dpi', 150) // resolusi gambar tetap tajam
    ->setOption('no-outline', true);

    // Preview PDF 1 lembar di browser
    $fileName = preg_replace('/[\/\\\\]/', '_', $piagam->serial_number) . '.pdf';
    return $pdf->stream($fileName);
}

}
