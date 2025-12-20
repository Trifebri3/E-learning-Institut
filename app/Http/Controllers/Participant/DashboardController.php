<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Auth
use App\Models\Assignment; // <-- Import Assignment model
class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard participant.
     */
public function index()
{
    $user = Auth::user();

    // Program yang diikuti user
$programIds = $user->programs()->pluck('programs.id');

$recentAssignments = Assignment::with(['kelas', 'submissions'])
    ->whereHas('kelas', function ($q) use ($programIds) {
        $q->whereIn('program_id', $programIds);
    })
    ->where('is_published', true)
    ->orderBy('due_date', 'asc')
    ->take(3)
    ->get();

    // Badge & program enrollment
    $enrolledPrograms = $user->programs()->get();
    $badge = $user->badges()->latest()->first();
    $earnedBadgeTemplateIds = $user->badges()->pluck('badge_templates.id');
    $image_path = $badge?->image_path;

    return view('participant.dashboard', compact(
        'enrolledPrograms',
        'earnedBadgeTemplateIds',
        'image_path',
        'recentAssignments'
    ));
}


}
