<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function access(Request $request, $resourceId)
    {
        $user = Auth::user();
        $resource = Resource::findOrFail($resourceId);

        // 1. [SECURITY CHECK] Pastikan user terdaftar di program ini
       $userProgramIds = $user->programs()->pluck('programs.id')->toArray();

        $resourceProgramId = $resource->kelas->program_id;

        if (!in_array($resourceProgramId, $userProgramIds)) {
            // User tidak boleh mengakses resource dari program yang tidak diikutinya
            abort(403, 'Akses ditolak. Resource bukan milik program Anda.');
        }

        // 2. [TRACKING] Catat akses di tabel resource_user
        // syncWithoutDetaching memastikan ini hanya dicatat sekali.
        $resource->users()->syncWithoutDetaching([
            $user->id => ['opened_at' => now()]
        ]);

        // 3. [REDIRECT] Tentukan tujuan
        if ($resource->link_url) {
            // Redirect ke Link URL
            return redirect()->away($resource->link_url);
        } elseif ($resource->file_path) {
            // Redirect ke file download
            return redirect(Storage::url($resource->file_path));
        }

        // Fallback jika tidak ada link maupun file
        return back()->with('error', 'Resource tidak dapat diakses.');
    }
}
