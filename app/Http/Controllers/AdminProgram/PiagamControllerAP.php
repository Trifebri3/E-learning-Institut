<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Piagam;
use App\Models\Program;
use App\Models\NomorInduk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class PiagamControllerAP extends Controller
{
    /**
     * LIST PIAGAM DALAM PROGRAM ADMIN
     */
public function index($programId)
{
    $program = Program::findOrFail($programId);

    $query = Piagam::where('piagam.program_id', $programId)
                   ->with('user');

    // Search
    if (request('q')) {
        $q = request('q');
        $query->whereHas('user', function ($u) use ($q) {
            $u->where('name', 'like', "%$q%")
              ->orWhere('email', 'like', "%$q%");
        });
    }

    // Sorting
    if (request('sort') == 'name') {

        // JOIN harus memakai nama tabel yang benar
        $query->join('users', 'piagam.user_id', '=', 'users.id')
              ->orderBy('users.name')
              ->select('piagam.*'); // ini penting

    } elseif (request('sort') == 'oldest') {

        $query->orderBy('piagam.created_at', 'asc');

    } else {

        $query->orderBy('piagam.created_at', 'desc');
    }

    // Paginate hemat render
    $piagam = $query->paginate(30);

    return view('adminprogram.piagam.index', compact('program', 'piagam'));
}

    /**
     * ADMIN MENYETUJUI PIAGAM PESERTA
     */
    public function approve($piagamId)
    {
        $piagam = Piagam::findOrFail($piagamId);

        if (!$piagam->serial_number || str_contains($piagam->serial_number, 'TEMP')) {
            $piagam->serial_number = strtoupper($piagam->program->code) . "/" . date('Ymd') . "/" . $piagam->id;
        }

        $piagam->is_approved = true;
        $piagam->issued_at = now();
        $piagam->save();

        return back()->with('success', 'Piagam berhasil disetujui.');
    }

    /**
     * PREVIEW PIAGAM
     */
    public function preview($piagamId)
    {
        $piagam = Piagam::with('program')->findOrFail($piagamId);

        $nomorInduk = NomorInduk::where('user_id', $piagam->user_id)
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
        ->setOption('enable-local-file-access', true)
        ->setOption('dpi', 150)
        ->setOption('no-outline', true);
    // Preview PDF 1 lembar di browser
    $fileName = preg_replace('/[\/\\\\]/', '_', $piagam->serial_number) . '.pdf';
        return $pdf->stream($fileName);
    }

    /**
     * DOWNLOAD PDF
     */
    public function download($piagamId)
    {
        $piagam = Piagam::findOrFail($piagamId);

        $nomorInduk = NomorInduk::where('user_id', $piagam->user_id)
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
        ->setOption('enable-local-file-access', true)
        ->setOption('dpi', 150);

        $fileName = preg_replace('/[\/\\\\]/', '_', $piagam->serial_number) . ".pdf";

        return $pdf->download($fileName);
    }

    /**
     * GENERATE + SIMPAN FILE PDF KE STORAGE
     */
    public function generatePiagam($piagamId)
    {
        $piagam = Piagam::findOrFail($piagamId);

        $nomorInduk = NomorInduk::where('user_id', $piagam->user_id)
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
        ->setOption('dpi', 150);

        $fileName = $piagam->serial_number . '.pdf';

        Storage::put("piagam/$fileName", $pdf->output());

        $piagam->file_path = "piagam/$fileName";
        $piagam->save();

        return response()->download(storage_path("app/piagam/$fileName"));
    }

public function programList()
{
    $user = auth()->user();

    // Ambil hanya program yang dikelola admin ini
    $programs = $user->administeredPrograms()
                     ->orderBy('title')
                     ->get();

    return view('adminprogram.piagam.programs', compact('programs'));
}


public function updateGrade(Request $request, $id)
{
    $request->validate([
        'grade' => 'required|string|max:3'
    ]);

    $piagam = Piagam::findOrFail($id);
    $piagam->grade = strtoupper($request->grade);
    $piagam->save();

    return back()->with('success', 'Grade piagam berhasil diperbarui.');
}

}
