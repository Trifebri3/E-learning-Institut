<?php

namespace App\Http\Controllers\AdminProgram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GradeSetting;
use App\Models\CustomGradeColumn;
use App\Models\CustomGradeValue;
use App\Models\ClassReport;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;

class EraportController extends Controller
{
    /**
     * Daftar kelas di program
     */
// E-RaportController.php

 public function index(Request $request)
{
    $user = Auth::user();

    // Ambil semua program yang dikelola admin
    $programs = $user->administeredPrograms;

    // Ambil ID program untuk filter kelas/peserta
    $programIds = $programs->pluck('id');

    // Query kelas yang masuk ke program admin
    $kelasQuery = Kelas::whereIn('program_id', $programIds)
                        ->with(['program', 'participants'])
                        ->orderBy('tanggal', 'desc');

    // Filter program ID jika dipilih dari dropdown
    if ($request->filled('program_id')) {
        $kelasQuery->where('program_id', $request->program_id);
    }

    $kelas = $kelasQuery->get();

    // Statistik
    $totalKelas = $kelas->count();
    $totalPeserta = $kelas->sum(fn($k) => $k->participants->count());

    return view('adminprogram.eraport.index', compact(
        'programs',
        'kelas',
        'totalKelas',
        'totalPeserta'
    ));
}

public function program($programId)
{
    $program = Program::findOrFail($programId);
    $kelas = $program->kelas;

    // Hitung peserta per kelas
    $pesertaPerKelas = [];
    $avgScorePerKelas = [];

    foreach($kelas as $k) {
        $pesertaPerKelas[$k->id] = $k->participants->count();
        $avgScorePerKelas[$k->id] = $k->participants->avg('final_grade') ?? 0;
    }

    return view('adminprogram.eraport.program', compact(
        'program',
        'kelas',
        'pesertaPerKelas',
        'avgScorePerKelas'
    ));
}



    /**
     * Edit bobot kelas
     */
    public function editWeight($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $weight = GradeSetting::firstOrCreate(['kelas_id' => $kelas->id]);

        return view('adminprogram.eraport.weight', compact('kelas', 'weight'));
    }

    public function updateWeight(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $weight = GradeSetting::firstOrCreate(['kelas_id' => $kelas->id]);

        $request->validate([
            'weight_presensi' => 'required|integer|min:0|max:100',
            'weight_tugas'    => 'required|integer|min:0|max:100',
            'weight_quiz'     => 'required|integer|min:0|max:100',
            'weight_essay'    => 'required|integer|min:0|max:100',
            'weight_progress' => 'required|integer|min:0|max:100',
            'weight_custom'   => 'required|integer|min:0|max:100',
        ]);

        $weight->update($request->all());

        return redirect()->route('adminprogram.eraport.index', $kelas->program_id)
                         ->with('success', 'Bobot berhasil diperbarui.');
    }

    /**
     * Tambah kolom custom
     */
    public function createCustomColumn($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        return view('adminprogram.eraport.custom_column', compact('kelas'));
    }

    public function storeCustomColumn(Request $request, $kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CustomGradeColumn::create([
            'kelas_id' => $kelas->id,
            'name' => $request->name,
        ]);

return redirect()->route('adminprogram.eraport.show', [
    'programId' => $kelas->program_id,
    'kelasId' => $kelas->id
])->with('success', 'Kolom custom berhasil ditambahkan.');
    }

    /**
     * Lihat & input nilai peserta per kelas
     */
public function show($programId, $kelasId)
{
    $kelas = Kelas::with(['participants', 'customColumns', 'gradeSetting'])->findOrFail($kelasId);

    // Ambil class report peserta
    $reports = ClassReport::where('kelas_id', $kelas->id)
                          ->get()
                          ->keyBy('user_id'); // supaya gampang akses per peserta

    // Ambil nilai custom
    $customValues = CustomGradeValue::whereIn('user_id', $kelas->participants->pluck('id'))
                                    ->whereIn('custom_grade_column_id', $kelas->customColumns->pluck('id'))
                                    ->get()
                                    ->groupBy(function($item){
                                        return $item->user_id . '-' . $item->custom_grade_column_id;
                                    });

    return view('adminprogram.eraport.show', compact('kelas', 'reports', 'customValues'));
}


    public function storeScore(Request $request, $kelasId)
    {
        $kelas = Kelas::with(['participants', 'gradeSetting', 'customColumns'])->findOrFail($kelasId);

        foreach ($kelas->participants as $participant) {

            $scoreData = [
                'score_presensi' => $request->input("presensi_{$participant->id}", 0),
                'score_tugas'    => $request->input("tugas_{$participant->id}", 0),
                'score_quiz'     => $request->input("quiz_{$participant->id}", 0),
                'score_essay'    => $request->input("essay_{$participant->id}", 0),
                'score_progress' => $request->input("progress_{$participant->id}", 0),
                'score_custom'   => 0,
            ];

            // Input nilai custom
            if ($request->has("custom")) {
                foreach ($request->custom[$participant->id] ?? [] as $customId => $value) {
                    CustomGradeValue::updateOrCreate(
                        ['custom_grade_column_id' => $customId, 'user_id' => $participant->id],
                        ['score' => $value]
                    );
                    $scoreData['score_custom'] += $value;
                }
            }

            $weight = $kelas->gradeSetting;
            $totalWeight = $weight->weight_presensi + $weight->weight_tugas + $weight->weight_quiz +
                           $weight->weight_essay + $weight->weight_progress + $weight->weight_custom;
            if ($totalWeight == 0) $totalWeight = 1;

            $finalScore = (
                $scoreData['score_presensi'] * $weight->weight_presensi +
                $scoreData['score_tugas']    * $weight->weight_tugas +
                $scoreData['score_quiz']     * $weight->weight_quiz +
                $scoreData['score_essay']    * $weight->weight_essay +
                $scoreData['score_progress'] * $weight->weight_progress +
                $scoreData['score_custom']   * $weight->weight_custom
            ) / $totalWeight;

            ClassReport::updateOrCreate(
                ['kelas_id' => $kelas->id, 'user_id' => $participant->id],
                [
                    'score_presensi' => $scoreData['score_presensi'],
                    'score_tugas'    => $scoreData['score_tugas'],
                    'score_quiz'     => $scoreData['score_quiz'],
                    'score_essay'    => $scoreData['score_essay'],
                    'score_progress' => $scoreData['score_progress'],
                    'score_custom'   => $scoreData['score_custom'],
                    'final_score'    => $finalScore,
                    'is_passed'      => $request->input("passed_{$participant->id}", 0),
                    'feedback'       => $request->input("feedback_{$participant->id}", ''),
                    'letter_grade'   => $this->getLetterGrade($finalScore),
                ]
            );
        }

return redirect()->route(
    'adminprogram.eraport.show',
    ['programId' => $kelas->program_id, 'kelasId' => $kelas->id]
)->with('success', 'Nilai peserta berhasil diperbarui.');

    }

    /**
     * Kalkulasi huruf
     */
    private function getLetterGrade($score)
    {
        if ($score >= 85) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 55) return 'C';
        if ($score >= 40) return 'D';
        return 'E';
    }

    /**
     * Rapor akumulasi program
     */
    public function programReport($programId)
    {
        $program = Program::findOrFail($programId);
        $kelasIds = $program->kelas()->pluck('id');

        $reports = ClassReport::whereIn('kelas_id', $kelasIds)
                              ->with('user','kelas')
                              ->get()
                              ->groupBy('user_id');

        return view('adminprogram.eraport.program', compact('program','reports'));
    }


        public function editScore($programId, $kelasId)
    {
        $kelas = Kelas::with(['participants', 'customColumns'])->findOrFail($kelasId);

        // Ambil report nilai peserta jika sudah ada
        $reports = $kelas->participants->mapWithKeys(function($participant) use ($kelas) {
            // Contoh dummy, bisa diganti dengan query nilai asli
            $report = $participant->raport()->where('kelas_id', $kelas->id)->first();
            return [$participant->id => $report];
        });

        // Ambil custom values
        $customValues = [];
        foreach($kelas->participants as $participant) {
            foreach($kelas->customColumns as $column) {
                $key = $participant->id . '-' . $column->id;
                $customValues[$key] = CustomValue::where('participant_id', $participant->id)
                                                 ->where('custom_column_id', $column->id)
                                                 ->get();
            }
        }

        return view('adminprogram.eraport.editScore', compact('kelas', 'reports', 'customValues'));
    }


}
