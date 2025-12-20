<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProgramStatusController extends Controller
{
    /**
     * Mengambil program aktif user.
     */
    public function getUserProgram()
    {
        $user = Auth::user();
        $program = $user->programs()->first(); // ambil program pertama user

        return $program;
    }
}
