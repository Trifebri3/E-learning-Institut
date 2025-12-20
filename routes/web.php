<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\AdminProgram\DashboardController as AdminProgramDashboard;
use App\Http\Controllers\Participant\DashboardController as ParticipantDashboard;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\ProfileDataController;
use App\Http\Controllers\Participant\ProgramRedeemController;
use App\Http\Controllers\Participant\KelasController;
use App\Http\Controllers\Participant\PresensiController;
use App\Http\Controllers\Participant\ProgramController;
use App\Http\Controllers\Participant\DashboardController;
use App\Http\Controllers\Participant\BadgeController;
use App\Http\Controllers\Participant\NarasumberController;
use App\Http\Controllers\Participant\ResourceController;
use App\Http\Controllers\Participant\MateriController;
use App\Http\Controllers\Participant\ModuleController;
use App\Http\Controllers\Participant\LearningPathController;
use App\Http\Controllers\Participant\VideoController;
use App\Http\Controllers\Participant\AssignmentController;
use App\Http\Controllers\Participant\QuizController;
use App\Http\Controllers\Participant\QuizListController;
use App\Http\Controllers\Participant\EssayExamController;
use App\Http\Controllers\Participant\ProgressController;
use App\Http\Controllers\Participant\AnnouncementController;
use App\Http\Controllers\Participant\DiscussionController;
use App\Http\Controllers\Participant\SupportController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\ProgramControllerSA;
use App\Http\Controllers\SuperAdmin\DashboardControllerAS;
use App\Http\Controllers\SuperAdmin\SupportTicketControllerSA;
use App\Http\Controllers\SuperAdmin\DiscussionControllerSA;
use App\Http\Controllers\SuperAdmin\AnnouncementControllerSA;
use App\Http\Controllers\AdminProgram\ProgramControllerAP;
use App\Http\Controllers\AdminProgram\ParticipantControllerAP;
use App\Http\Controllers\AdminProgram\KelasControllerAP;
use App\Http\Controllers\AdminProgram\PresensiControllerAP;
use App\Http\Controllers\AdminProgram\ModuleControllerAP;
use App\Http\Controllers\AdminProgram\VideoControllerAP;
use App\Http\Controllers\AdminProgram\LearningPathControllerAP;
use App\Http\Controllers\AdminProgram\QuizControllerAP;
use App\Http\Controllers\AdminProgram\EssayExamControllerAP;
use App\Http\Controllers\AdminProgram\AdminAssignmentController;
use App\Http\Controllers\AdminProgram\AdminProgramQuizController;
use App\Http\Controllers\AdminProgram\AdminProgramSoalQuizController;
use App\Http\Controllers\AdminProgram\AnnouncementControllerAP;
use App\Http\Controllers\AdminProgram\DiscussionControllerAP;
use App\Http\Controllers\AdminProgram\SupportTicketControllerAP;
use App\Http\Controllers\AdminProgram\NarasumberControllerAP;
use App\Http\Controllers\AdminProgram\EraportController;
use App\Http\Controllers\AdminProgram\AdminRaportController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\Participant\PiagamController;
use App\Http\Controllers\AdminProgram\PiagamControllerAP;
use App\Http\Controllers\Instructor\DashboardControllerIN;



Route::middleware(['auth'])->group(function () {

    // Tampilkan halaman terms
    Route::get('/terms', [TermsController::class, 'show'])
        ->name('terms.show');

    // Proses persetujuan user
    Route::post('/terms/accept', [TermsController::class, 'accept'])
        ->name('terms.accept');
});
// Default redirect to login
Route::get('/', fn() => view('auth.login'));

// Dashboard redirector
Route::get('/dashboard', DashboardRedirectController::class)
    ->middleware(['auth', 'verified','terms.agreed'])
    ->name('dashboard');

// OTP
Route::get('verify-email', [OtpVerificationController::class, 'create'])
    ->middleware('auth')
    ->name('verification.notice');

Route::post('verify-otp', [OtpVerificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('otp.verify');


// Protected routes
Route::middleware(['auth', 'verified', 'terms.agreed'])->group(function () {

    // General profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('data-diri', [ProfileDataController::class, 'edit'])->name('profile-data.edit');
    Route::put('data-diri', [ProfileDataController::class, 'update'])->name('profile-data.update');

    Route::get('redeem', [ProgramRedeemController::class, 'create'])->name('participant.redeem.form');
    Route::post('/redeem', [ProgramRedeemController::class, 'store'])->name('participant.redeem.store');
// Program list


    // Participant kelas routes (TIDAK BOLEH PREFIX DOUBEL)
    Route::prefix('participant')->name('participant.')->group(function () {
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
        Route::post('presensi', [PresensiController::class, 'store'])->name('presensi.store');
// 1. Rute untuk menampilkan SEMUA program
        Route::get('programs', [ProgramController::class, 'index'])->name('program.index');

        // 2. Rute untuk menampilkan DETAIL satu program
        Route::get('programs/{id}', [ProgramController::class, 'show'])->name('program.show');
        // Di dalam grup 'participant'
Route::get('narasumber', [NarasumberController::class, 'index'])->name('narasumber.index');
Route::get('narasumber/{id}', [NarasumberController::class, 'show'])->name('narasumber.show');
Route::get('resource/{resourceId}/access', [ResourceController::class, 'access'])->name('resource.access');
Route::get('materi', [MateriController::class, 'index'])->name('materi.index');
Route::get('materi/{id}', [MateriController::class, 'show'])->name('materi.show');
Route::get('/participant/resource/access/{id}', [MateriController::class, 'access'])
    ->name('participant.resource.access');
    Route::get('module/{id}', [ModuleController::class, 'show'])
        ->name('module.show');

    Route::post('module/{id}/complete', [ModuleController::class, 'complete'])
        ->name('module.complete');


    Route::get('learning-path/section/{id}', [LearningPathController::class, 'showSection'])
        ->name('learningpath.section.show');

    // Tandai Section selesai
    Route::post('learning-path/section/{id}/complete', [LearningPathController::class, 'completeSection'])
        ->name('learningpath.section.complete');

    });
    Route::prefix('participant')->name('participant.')->middleware(['auth', 'role:participant'])->group(function () {
    // Tugas prioritas (default)
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');

    // Semua tugas
    Route::get('/assignments/all', [AssignmentController::class, 'allAssignments'])->name('assignments.all');

    // Detail tugas
    Route::get('/assignments/{id}', [AssignmentController::class, 'show'])->name('assignments.show');

    // Submit tugas
    Route::post('/assignments/{id}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');

    // API untuk count urgent
    Route::get('/assignments/urgent-count', [AssignmentController::class, 'getUrgentCount'])->name('assignments.urgent-count');

});
Route::prefix('participant')->name('participant.')->group(function () {
    // ... route lainnya ...

    // ROUTE QUIZ
    Route::get('quiz/{id}', [QuizController::class, 'show'])->name('quiz.show');
    Route::post('quiz/{id}/start', [QuizController::class, 'start'])->name('quiz.start');
    Route::get('quiz/session/{attemptId}', [QuizController::class, 'take'])->name('quiz.take');
    Route::post('quiz/session/{attemptId}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('quiz/result/{attemptId}', [QuizController::class, 'result'])->name('quiz.result');
    Route::get('/kelas/{kelasId}/quiz', [QuizListController::class, 'kelas'])->name('participant.quiz.kelas');
Route::get('/program/{programId}/quiz', [QuizListController::class, 'program'])->name('participant.quiz.program');
Route::post('/participant/quiz/{id}/start', [QuizController::class, 'start'])
    ->name('participant.quiz.start');
Route::post('quiz/{attemptId}/start', [QuizController::class, 'start'])->name('participant.quiz.start');

});
Route::prefix('participant')->name('participant.')->middleware(['auth'])->group(function () {

    Route::get('essay/{id}', [EssayExamController::class, 'show'])->name('essay.show');
    Route::post('essay/{id}/start', [EssayExamController::class, 'start'])->name('essay.start');

    Route::get('essay/session/{submissionId}', [EssayExamController::class, 'take'])->name('essay.take');
    Route::post('essay/session/{submissionId}/submit', [EssayExamController::class, 'submit'])->name('essay.submit');

    Route::get('essay/result/{submissionId}', [EssayExamController::class, 'result'])->name('essay.result');

    Route::get('/participant/essay/{exam}/preview', [EssayExamController::class, 'preview'])->name('participant.essay.preview');
Route::get('/participant/essay/{exam}/result', [EssayExamController::class, 'result'])->name('participant.essay.result');
// Routes untuk Essay
Route::get('/participant/essay/{exam}/preview', [EssayExamController::class, 'preview'])->name('participant.essay.preview');
Route::get('/participant/essay/{submission}/result', [EssayExamController::class, 'result'])->name('participant.essay.result');
Route::post('/participant/essay/{exam}/start', [EssayExamController::class, 'start'])->name('participant.essay.start');
});

// Routes untuk Essay Exam Participant
Route::prefix('participant')->middleware(['auth'])->group(function () {
    Route::get('/essay/{exam}/preview', [EssayExamController::class, 'preview'])->name('participant.essay.preview');
    Route::get('/essay/{submission}/result', [EssayExamController::class, 'result'])->name('participant.essay.result');
    Route::post('/essay/{exam}/start', [EssayExamController::class, 'start'])->name('participant.essay.start');

        Route::get('/essay/{exam}/preview', [EssayExamController::class, 'preview'])->name('participant.essay.preview');
    Route::post('/essay/{submission}/auto-save', [EssayExamController::class, 'autoSave'])->name('participant.essay.autoSave');
    Route::get('/essay/{submission}/check-time', [EssayExamController::class, 'checkTime'])->name('participant.essay.checkTime');
    Route::post('/essay/{exam}/reset', [EssayExamController::class, 'reset'])->name('participant.essay.reset');
    Route::get('/kelas/{kelas}/essays', [EssayExamController::class, 'byKelas'])->name('participant.essay.byKelas');
    Route::get('/essay/{exam}/download-pdf', [EssayExamController::class, 'downloadPdf'])->name('participant.essay.downloadPdf');
    Route::get('/essay/{exam}/statistics', [EssayExamController::class, 'statistics'])->name('participant.essay.statistics');
});








Route::middleware(['auth'])->prefix('participant')->name('participant.')->group(function() {

    // Halaman index: daftar kelas & progres
    Route::get('progress', [ProgressController::class, 'index'])->name('progress.index');

    // Halaman detail progres per kelas
    Route::get('progress/{kelas}', [ProgressController::class, 'show'])->name('progress.show');



});
Route::prefix('participant')->middleware(['auth'])->group(function () {
    // Print seluruh progress program
    Route::get('progress/{programId}/print', [ProgressController::class, 'printProgram'])
        ->name('participant.progress.print');
        // routes/web.php atau routes/participant.php

Route::prefix('progress')->group(function () {
    Route::get('/program/{programId}/print', [ProgressController::class, 'previewPDF'])->name('participant.progress.preview-pdf');
    Route::get('/program/{programId}/download', [ProgressController::class, 'downloadPDF'])->name('participant.progress.download-pdf');
});
});

// routes/web.php atau routes/participant.php

Route::prefix('progress')->group(function () {
    // Preview HTML
    Route::get('/program/{programId}/print', [ProgressController::class, 'printProgram'])->name('participant.progress.print');

    // Preview PDF di browser
    Route::get('/program/{programId}/preview-pdf', [ProgressController::class, 'previewPDF'])->name('participant.progress.preview-pdf');

    // Download PDF
    Route::get('/program/{programId}/download-pdf', [ProgressController::class, 'downloadPDF'])->name('participant.progress.download-pdf');
});

});



Route::prefix('participant/support')->name('participant.support.')->middleware('auth')->group(function () {
    // Daftar tiket milik user
    Route::get('/', [SupportController::class, 'index'])->name('index');

    // Form buat tiket baru
    Route::get('/create', [SupportController::class, 'create'])->name('create');

    // Simpan tiket baru
    Route::post('/', [SupportController::class, 'store'])->name('store');

    // Lihat detail tiket
    Route::get('/{id}', [SupportController::class, 'show'])->name('show');
});

Route::prefix('participant')->name('participant.')->middleware('auth')->group(function () {

    Route::get('/video/{id}', [VideoController::class, 'show'])
        ->name('video.show');

    Route::post('/video/{id}/complete', [VideoController::class, 'complete'])
        ->name('video.complete');

});
Route::prefix('participant')->name('participant.')->group(function () {

    Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('announcements/{id}/read', [AnnouncementController::class, 'markAsRead'])->name('announcements.read');
});
Route::prefix('participant')->name('participant.')->middleware(['auth', 'role:participant'])->group(function () {

    // Dashboard participant

    // Tambahkan ROUTE DISCUSSION INI !!!
    Route::get('/discussion', [App\Http\Controllers\Participant\DiscussionController::class, 'index'])
        ->name('discussion.index');

    // Jika ada fitur forum
    Route::get('/discussion/forum/{id}', [App\Http\Controllers\Participant\DiscussionController::class, 'showForum'])
        ->name('discussion.forum.show');

    // Kirim pesan forum
    Route::post('/discussion/forum/{id}/post', [App\Http\Controllers\Participant\DiscussionController::class, 'storeForumMessage'])
        ->name('discussion.forum.post');
        Route::get('/discussion/forum/{id}', [DiscussionController::class, 'showForum'])
    ->name('discussion.forum');
    Route::get('/discussion/dm/{userId}', [DiscussionController::class, 'showDm'])
    ->name('discussion.dm');

Route::post('/discussion/dm/{userId}', [DiscussionController::class, 'storeDm'])
    ->name('discussion.dm.send');

// Export Rekap Program


});


Route::prefix('instructor')
     ->name('instructor.')
     ->middleware(['auth', 'verified', 'role:instructor']) // Middleware untuk membatasi akses hanya ke instruktur
     ->group(function () {

    // DASHBOARD INSTRUCTOR
    // URL: /instructor/dashboard
    // Name: instructor.dashboard
    Route::get('dashboard', [DashboardControllerIN::class, 'index'])->name('dashboard');

    // ... rute instruktur lainnya (seperti grading, diskusi, dll) bisa ditambahkan di sini
});




Route::prefix('superadmin')->middleware(['auth', 'role:superadmin'])->name('superadmin.')->group(function () {

    // INDEX DISKUSI
    Route::get('/discussion', [DiscussionControllerSA::class, 'index'])
        ->name('discussion.index');

    // FORUM THREAD
    Route::get('/discussion/forum/{id}', [DiscussionControllerSA::class, 'showForum'])
        ->name('discussion.forum');

    Route::post('/discussion/forum/{id}', [DiscussionControllerSA::class, 'storeForumMessage'])
        ->name('discussion.forum.store');

    // DIRECT MESSAGE
    Route::get('/discussion/dm/{userId}', [DiscussionControllerSA::class, 'showDm'])
        ->name('discussion.dm');

    Route::post('/discussion/dm/{userId}', [DiscussionControllerSA::class, 'storeDm'])
        ->name('discussion.dm.store');
});
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'role:superadmin'])->group(function () {

    // Daftar Pengumuman
    Route::get('/announcements', [AnnouncementControllerSA::class, 'index'])
        ->name('announcements.index');

    // Form Buat Baru
    Route::get('/announcements/create', [AnnouncementControllerSA::class, 'create'])
        ->name('announcements.create');

    // Simpan Pengumuman
    Route::post('/announcements', [AnnouncementControllerSA::class, 'store'])
        ->name('announcements.store');

    // Hapus Pengumuman
    Route::delete('/announcements/{id}', [AnnouncementControllerSA::class, 'destroy'])
        ->name('announcements.destroy');
});





Route::prefix('superadmin')->middleware(['auth'])->group(function() {
        Route::get('users', [UserController::class, 'index'])->name('users.index');       // List users
    Route::get('users/create', [UserController::class, 'create'])->name('users.create'); // Form tambah user
    Route::post('users', [UserController::class, 'store'])->name('users.store');      // Simpan user baru
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');  // Lihat detail user
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Form edit
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update'); // Update user
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/impersonate', [UserController::class, 'impersonate'])->name('superadmin.users.impersonate')
         ->middleware('can:superadmin'); // tetap butuh superadmin untuk mulai impersonate


    Route::get('/users/impersonate-back', [UserController::class, 'impersonateBack'])->name('superadmin.users.impersonate-back');
    // hapus 'can:superadmin' untuk route back
        Route::get('users/{user}', [App\Http\Controllers\SuperAdmin\UserController::class, 'show'])->name('superadmin.users.show');


});


Route::prefix('superadmin/programs')
    ->name('superadmin.programs.')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function() {
        Route::get('/', [ProgramControllerSA::class, 'index'])->name('index');
        Route::get('/create', [ProgramControllerSA::class, 'create'])->name('create');
        Route::post('/', [ProgramControllerSA::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ProgramControllerSA::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProgramControllerSA::class, 'update'])->name('update');
        Route::delete('/{id}', [ProgramControllerSA::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [ProgramControllerSA::class, 'show'])->name('show');
    });

Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'role:superadmin'])->group(function () {

    // Support Ticket
    Route::get('/support', [SupportTicketControllerSA::class, 'index'])->name('support.index');
    Route::get('/support/{id}', [SupportTicketControllerSA::class, 'show'])->name('support.show');
    Route::put('/support/{id}', [SupportTicketControllerSA::class, 'update'])->name('support.update');
    Route::delete('/support/{id}', [SupportTicketControllerSA::class, 'destroy'])->name('support.destroy');

    // Komponen list terbaru (AJAX / include)
    Route::get('/support-latest', function() {
        $tickets = \App\Models\SupportTicket::with('user')
                    ->whereIn('status', ['open', 'in_progress'])
                    ->latest()
                    ->take(5)
                    ->get();
        return view('superadmin.components.support-latest', compact('tickets'));
    })->name('support.latest');

});

Route::prefix('participant')->middleware(['auth'])->group(function () {

    // Halaman daftar piagam
    Route::get('/piagam', [PiagamController::class, 'index'])->name('participant.piagam.index');

    // Ajukan piagam untuk program tertentu
    Route::post('/piagam/request/{program}', [PiagamController::class, 'request'])
         ->name('participant.piagam.request');

    // Download piagam
    Route::get('/piagam/download/{piagam}', [PiagamController::class, 'download'])
         ->name('participant.piagam.download');
         Route::get('participant/piagam/preview/{piagam}', [PiagamController::class, 'preview'])
     ->name('participant.piagam.preview');


Route::put('/piagam/{id}/update-grade', [PiagamControllerAP::class, 'updateGrade'])
     ->name('adminprogram.piagam.updateGrade');


});
Route::prefix('adminprogram')->name('adminprogram.')->group(function () {

    // Kelola Piagam
    Route::get('/piagam/{programId}', [PiagamController::class, 'index'])
        ->name('piagam.index');

});
// web.php
Route::prefix('eraport')->group(function () {
    Route::get('/', [ERaportController::class, 'index'])->name('adminprogram.eraport.index');
    Route::get('/program/{programId}', [ERaportController::class, 'program'])->name('adminprogram.eraport.program');
    Route::get('/program/{programId}/kelas/{kelasId}', [ERaportController::class, 'show'])->name('adminprogram.eraport.show');
    // ... routes lainnya
});







Route::middleware(['auth'])->prefix('participant')->name('participant.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Program user
    Route::get('/programs', [ProgramController::class, 'index'])->name('program.index');
    Route::get('/programs/{id}', [ProgramController::class, 'show'])->name('program.show');
            Route::get('my-badges', [BadgeController::class, 'index'])->name('badges.index');
});

    // Role SUPERADMIN
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:superadmin'])
    ->group(function () {

        // Dashboard Utama
        Route::get('/dashboard', [DashboardControllerAS::class, 'index'])
            ->name('dashboard');

    });

    Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'role:superadmin'])->group(function () {

    // ====================
    // DISKUSI / FORUM
    // ====================
    Route::get('/discussion', [DiscussionControllerSA::class, 'index'])
        ->name('discussion.index');

    // Buka forum tertentu
    Route::get('/discussion/forum/{id}', [DiscussionControllerSA::class, 'showForum'])
        ->name('discussion.forum.show');

    // Kirim pesan ke forum
    Route::post('/discussion/forum/{id}/post', [DiscussionControllerSA::class, 'storeForumMessage'])
        ->name('discussion.forum.post');


    // ====================
    // DIRECT MESSAGE (DM)
    // ====================

    // Buka DM dengan user tertentu
    Route::get('/discussion/dm/{userId}', [DiscussionControllerSA::class, 'showDm'])
        ->name('discussion.dm.show');

    // Kirim DM
    Route::post('/discussion/dm/{userId}/send', [DiscussionControllerSA::class, 'storeDm'])
        ->name('discussion.dm.send');

});

Route::prefix('adminprogram')->middleware(['auth', 'role:adminprogram,instructor'])->group(function () {

    // Form tambah modul baru (terikat ke kelas)
    Route::get('kelas/{kelasId}/modules/create', [ModuleControllerAP::class, 'create'])
        ->name('adminprogram.modules.create');

    // Simpan modul baru
    Route::post('kelas/{kelasId}/modules', [ModuleControllerAP::class, 'store'])
        ->name('adminprogram.modules.store');

    // Form edit modul
    Route::get('modules/{id}/edit', [ModuleControllerAP::class, 'edit'])
        ->name('adminprogram.modules.edit');

    // Update modul
    Route::put('modules/{id}', [ModuleControllerAP::class, 'update'])
        ->name('adminprogram.modules.update');

    // Hapus modul
    Route::delete('modules/{id}', [ModuleControllerAP::class, 'destroy'])
        ->name('adminprogram.modules.destroy');
});


    // Role ADMIN PROGRAM
    Route::prefix('adminprogram')
        ->name('adminprogram.')
        ->middleware('role:adminprogram')
        ->group(function () {

            Route::get('dashboard', [AdminProgramDashboard::class, 'index'])->name('dashboard');
        });

        Route::prefix('adminprogram')
    ->name('adminprogram.')
    ->middleware(['auth', 'role:adminprogram']) // sesuaikan middleware Anda
    ->group(function () {

        // List Program yang dikelola
        Route::get('/programs', [ProgramControllerAP::class, 'index'])
            ->name('programs.index');

        // Edit Program
        Route::get('/programs/{id}/edit', [ProgramControllerAP::class, 'edit'])
            ->name('programs.edit');

        // Update Program
        Route::put('/programs/{id}', [ProgramControllerAP::class, 'update'])
            ->name('programs.update');
    });


Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {

    // ===== User Management =====
    Route::get('users', [UserController::class, 'index'])->name('users.index');         // List users
    Route::get('users/create', [UserController::class, 'create'])->name('users.create'); // Form tambah user
    Route::post('users', [UserController::class, 'store'])->name('users.store');        // Simpan user baru
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');// Form edit user
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');// Update user
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');// Hapus user

    // ===== Impersonate =====
    Route::get('users/{user}/impersonate', [UserController::class, 'impersonate'])->name('users.impersonate'); // Login sebagai peserta
    Route::get('users/leave-impersonate', [UserController::class, 'leaveImpersonate'])->name('users.leave-impersonate'); // Kembali ke akun SuperAdmin

});


Route::prefix('adminprogram')
    ->name('adminprogram.')
    ->middleware(['auth', 'role:adminprogram']) // Sesuaikan middleware
    ->group(function () {

        /*
        |----------------------------------------------------------------------
        | PARTICIPANT MANAGEMENT
        |----------------------------------------------------------------------
        */

        // INDEX (TAB: peserta + nomor induk)
        Route::get('/participants', [ParticipantControllerAP::class, 'index'])
            ->name('participants.index');

        // STORE Nomor Induk
        Route::post('/participants/nomor-induk', [ParticipantControllerAP::class, 'storeNomorInduk'])
            ->name('participants.ni.store'); // <- Sesuai Blade

        // Toggle Active/Inactive Nomor Induk
        Route::patch('/participants/nomor-induk/{id}/toggle', [ParticipantControllerAP::class, 'toggleNomorInduk'])
            ->name('participants.ni.toggle');

        // Kick / Non-aktifkan Peserta dari Program
        Route::post('/participants/{userId}/deactivate', [ParticipantControllerAP::class, 'deactivateParticipant'])
            ->name('participants.deactivate');

        // PRINT PDF / Print View
        Route::get('/participants/print', [ParticipantControllerAP::class, 'printPdf'])
            ->name('participants.print');
    });

Route::prefix('adminprogram')
    ->name('adminprogram.')
    ->middleware(['auth', 'role:adminprogram, instructor'])
    ->group(function () {

        /*
        |----------------------------------------------------------------------
        | KELAS MANAGEMENT
        |----------------------------------------------------------------------
        */

        // Daftar kelas
        Route::get('/kelas', [KelasControllerAP::class, 'index'])
            ->name('kelas.index');

        // Form tambah kelas baru
        Route::get('/kelas/create', [KelasControllerAP::class, 'create'])
            ->name('kelas.create');

        // Simpan kelas baru
        Route::post('/kelas', [KelasControllerAP::class, 'store'])
            ->name('kelas.store');

        // Edit / Dashboard kelas
        Route::get('/kelas/{id}/edit', [KelasControllerAP::class, 'edit'])
            ->name('kelas.edit');

        // Update kelas
        Route::put('/kelas/{id}', [KelasControllerAP::class, 'update'])
            ->name('kelas.update');

        // Hapus kelas
        Route::delete('/kelas/{id}', [KelasControllerAP::class, 'destroy'])
            ->name('kelas.destroy');
    });


Route::prefix('adminprogram')
    ->name('adminprogram.')
    ->middleware(['auth', 'role:adminprogram, instructor'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | PRESENSI MANAGEMENT
        |--------------------------------------------------------------------------
        */

        // Halaman Kelola Presensi (Setup & Monitoring) per Kelas
        Route::get('/kelas/{kelasId}/presensi', [PresensiControllerAP::class, 'edit'])
            ->name('kelas.presensi.edit');

        // Simpan / Update Konfigurasi Presensi
        Route::post('/kelas/{kelasId}/presensi', [PresensiControllerAP::class, 'update'])
            ->name('kelas.presensi.update');

        // Reset / Hapus presensi peserta
        Route::delete('/presensi/{hasilId}', [PresensiControllerAP::class, 'destroy'])
            ->name('kelas.presensi.destroy');
            Route::patch('/kelas/{id}/toggle-publish', [KelasControllerAP::class, 'togglePublish'])
    ->name('kelas.toggle-publish');

    });

Route::prefix('adminprogram')->middleware(['auth', 'role:adminprogram,instructor'])->group(function () {

    // Form tambah video baru (terikat ke kelas)
    Route::get('kelas/{kelasId}/videos/create', [VideoControllerAP::class, 'create'])
        ->name('adminprogram.videos.create');

    // Simpan video baru
    Route::post('kelas/{kelasId}/videos', [VideoControllerAP::class, 'store'])
        ->name('adminprogram.videos.store');

    // Form edit video
    Route::get('videos/{id}/edit', [VideoControllerAP::class, 'edit'])
        ->name('adminprogram.videos.edit');

    // Update video
    Route::put('videos/{id}', [VideoControllerAP::class, 'update'])
        ->name('adminprogram.videos.update');

    // Hapus video
    Route::delete('videos/{id}', [VideoControllerAP::class, 'destroy'])
        ->name('adminprogram.videos.destroy');
});

Route::prefix('adminprogram')->middleware(['auth', 'role:adminprogram,instructor'])->group(function () {

    // -----------------------------
    // Learning Path (Kurikulum)
    // -----------------------------

    // Buat Learning Path baru (terikat ke kelas)
    Route::post('kelas/{kelasId}/learning-path', [LearningPathControllerAP::class, 'store'])
        ->name('adminprogram.learningpath.store');

    // Halaman manajemen Learning Path (lihat & edit judul)
    Route::get('learning-path/{id}/manage', [LearningPathControllerAP::class, 'manage'])
        ->name('adminprogram.learningpath.manage');

    // Update judul Learning Path
    Route::put('learning-path/{id}', [LearningPathControllerAP::class, 'update'])
        ->name('adminprogram.learningpath.update');

    // Hapus Learning Path
    Route::delete('learning-path/{id}', [LearningPathControllerAP::class, 'destroy'])
        ->name('adminprogram.learningpath.destroy');

    // -----------------------------
    // Learning Path Sections (Bab)
    // -----------------------------

    // Form tambah section baru
// Form tambah section baru
Route::get('learning-path/{id}/section/create', [LearningPathControllerAP::class, 'createSection'])
    ->name('adminprogram.learningpath.section.create');

// Simpan section baru
Route::post('learning-path/{id}/section', [LearningPathControllerAP::class, 'storeSection'])
    ->name('adminprogram.learningpath.section.store');

// Form edit section
Route::get('section/{sectionId}/edit', [LearningPathControllerAP::class, 'editSection'])
    ->name('adminprogram.learningpath.section.edit');

// Update section
Route::put('section/{sectionId}', [LearningPathControllerAP::class, 'updateSection'])
    ->name('adminprogram.learningpath.section.update');

// Hapus section
Route::delete('section/{sectionId}', [LearningPathControllerAP::class, 'destroySection'])
    ->name('adminprogram.learningpath.section.destroy');

});



Route::prefix('adminprogram')->name('adminprogram.')->middleware(['auth'])->group(function () {

    // 1. LIST PROGRAM (index)
    Route::get('/piagam', [PiagamControllerAP::class, 'programList'])
        ->name('piagam.programs');

    // 2. PIAGAM PER PROGRAM
    Route::get('/piagam/{programId}', [PiagamControllerAP::class, 'index'])
        ->name('piagam.index');

    // 3. APPROVE
    Route::post('/piagam/{piagamId}/approve', [PiagamControllerAP::class, 'approve'])
        ->name('piagam.approve');

    // 4. PREVIEW PDF
    Route::get('/piagam/{piagamId}/preview', [PiagamControllerAP::class, 'preview'])
        ->name('piagam.preview');

    // 5. DOWNLOAD
    Route::get('/piagam/{piagamId}/download', [PiagamControllerAP::class, 'download'])
        ->name('piagam.download');
});











Route::prefix('adminprogram')->middleware(['auth', 'role:adminprogram,instructor'])->group(function () {

    // --- Quiz Induk ---
    // Routes untuk Kuis

    // Routes untuk Kuis
    Route::get('/kelas/{kelasId}/quizzes/create', [QuizControllerAP::class, 'create'])->name('adminprogram.quizzes.create');
    Route::post('/kelas/{kelasId}/quizzes', [QuizControllerAP::class, 'store'])->name('adminprogram.quizzes.store');
    Route::get('/quizzes/{id}/edit', [QuizControllerAP::class, 'edit'])->name('adminprogram.quizzes.edit');
    Route::put('/quizzes/{id}', [QuizControllerAP::class, 'update'])->name('adminprogram.quizzes.update');
    Route::delete('/quizzes/{id}', [QuizControllerAP::class, 'destroy'])->name('adminprogram.quizzes.destroy');

    // Routes untuk Soal
    Route::post('/quizzes/{quizId}/questions', [QuizControllerAP::class, 'storeQuestion'])->name('adminprogram.questions.store');
    Route::get('/questions/{questionId}/edit', [QuizControllerAP::class, 'editQuestion'])->name('adminprogram.questions.edit');
    Route::put('/questions/{questionId}', [QuizControllerAP::class, 'updateQuestion'])->name('adminprogram.questions.update');
    Route::delete('/questions/{questionId}', [QuizControllerAP::class, 'destroyQuestion'])->name('adminprogram.questions.destroy');

});


Route::get('/participant/quiz/{quiz}/start', [QuizController::class, 'start'])->name('participant.quiz.start');
/**
 * ROUTE ADMIN PROGRAM
 */

Route::prefix('adminprogram/essay')->name('adminprogram.essay.')->group(function () {

    Route::get('/', [EssayExamControllerAP::class, 'index'])->name('index');
    Route::get('/create', [EssayExamControllerAP::class, 'create'])->name('create');
    Route::post('/store', [EssayExamControllerAP::class, 'store'])->name('store');
    Route::get('/{id}/edit', [EssayExamControllerAP::class, 'edit'])->name('edit');
    Route::post('/{id}/update', [EssayExamControllerAP::class, 'update'])->name('update');
    Route::delete('/{id}', [EssayExamControllerAP::class, 'destroy'])->name('destroy');

    Route::get('/{examId}/questions', [EssayExamControllerAP::class, 'questions'])->name('questions');
    Route::post('/{examId}/questions/store', [EssayExamControllerAP::class, 'storeQuestion'])->name('questions.store');
    Route::post('/questions/{id}/update', [EssayExamControllerAP::class, 'updateQuestion'])->name('questions.update');
    Route::delete('/questions/{id}', [EssayExamControllerAP::class, 'deleteQuestion'])->name('questions.delete');

    Route::get('/{examId}/submissions', [EssayExamControllerAP::class, 'submissions'])->name('submissions');
    Route::get('/submission/{id}/grade', [EssayExamControllerAP::class, 'gradeSubmission'])->name('submissions.grade');

    Route::post('/answer/{answerId}/grade', [EssayExamControllerAP::class, 'saveGrade'])->name('grade.save');
    Route::post('/submission/{id}/finish', [EssayExamControllerAP::class, 'finishGrading'])->name('grade.finish');



});

Route::prefix('adminprogram')->name('adminprogram.')->group(function () {

    Route::prefix('essay')->name('essay.')->group(function () {

        // Update nilai final manual
        Route::put('/submission/{id}/final-score',
            [App\Http\Controllers\AdminProgram\EssayExamControllerAP::class, 'updateFinalScore']
        )->name('updateFinalScore');

        // Cetak PDF
        Route::get('/submission/{id}/print',
            [App\Http\Controllers\AdminProgram\EssayExamControllerAP::class, 'exportPDF']
        )->name('submissions.print');


        Route::get('/{examId}/submissions/preview',
            [App\Http\Controllers\AdminProgram\EssayExamControllerAP::class, 'previewPDF']
        )->name('submissions.preview');

        // PRINT ALL PDF
        Route::get('/{examId}/submissions/print-all',
            [App\Http\Controllers\AdminProgram\EssayExamControllerAP::class, 'exportAllPDF']
        )->name('submissions.printAll');

    });

});

Route::prefix('adminprogram')->name('adminprogram.')->group(function() {
    Route::resource('assignments', AdminAssignmentController::class);

    Route::get('assignments/{id}/submissions', [AdminAssignmentController::class, 'submissions'])
         ->name('assignments.submissions');

    Route::put('assignments/submissions/{submissionId}/score', [AdminAssignmentController::class, 'updateSubmissionScore'])
         ->name('assignments.submissions.updateScore');

    Route::get('assignments/{assignmentId}/download', [AdminAssignmentController::class, 'downloadAllSubmissions'])
         ->name('assignments.submissions.download');
});



Route::prefix('adminprogram')->name('adminprogram.')->group(function () {

    // Quiz
    Route::get('quiz', [AdminProgramQuizController::class, 'index'])->name('quiz.index');
    Route::get('quiz/create', [AdminProgramQuizController::class, 'create'])->name('quiz.create');
    Route::post('quiz', [AdminProgramQuizController::class, 'store'])->name('quiz.store');
    Route::get('quiz/{id}/edit', [AdminProgramQuizController::class, 'edit'])->name('quiz.edit');
    Route::put('quiz/{id}', [AdminProgramQuizController::class, 'update'])->name('quiz.update');
    Route::delete('quiz/{id}', [AdminProgramQuizController::class, 'destroy'])->name('quiz.destroy');

    // Submissions
    Route::get('quiz/{quizId}/submissions', [AdminProgramQuizController::class, 'submissions'])->name('quiz.submissions');

    // Download semua submission PDF
    Route::get('quiz/{quizId}/download', [AdminProgramQuizController::class, 'downloadAllSubmissions'])->name('quiz.download');

    // Update score
    Route::post('quiz/attempt/{attemptId}/score', [AdminProgramQuizController::class, 'updateSubmissionScore'])->name('quiz.updateScore');

    // CRUD Soal Quiz
    Route::prefix('quiz/{quizId}/soal')->name('quiz.soal.')->group(function() {
        Route::get('/', [AdminProgramSoalQuizController::class, 'index'])->name('index');
        Route::get('create', [AdminProgramSoalQuizController::class, 'create'])->name('create');
        Route::post('/', [AdminProgramSoalQuizController::class, 'store'])->name('store');
        Route::get('{questionId}/edit', [AdminProgramSoalQuizController::class, 'edit'])->name('edit');
        Route::put('{questionId}', [AdminProgramSoalQuizController::class, 'update'])->name('update');
        Route::delete('{questionId}', [AdminProgramSoalQuizController::class, 'destroy'])->name('destroy');

    });


});
Route::prefix('adminprogram')->name('adminprogram.')->group(function () {

    // Halaman edit presensi (setup & monitoring)
    Route::get('presensi/{kelasId}/edit', [PresensiControllerAP::class, 'edit'])
         ->name('presensi.edit');

    // Simpan konfigurasi presensi
    Route::put('presensi/{kelasId}', [PresensiControllerAP::class, 'update'])
         ->name('presensi.update');

    // Hapus presensi satu peserta
    Route::delete('presensi/{hasilId}', [PresensiControllerAP::class, 'destroy'])
         ->name('presensi.destroy');

    // Export program
    Route::get('presensi/export-program', [PresensiControllerAP::class, 'exportProgram'])
         ->name('presensi.exportProgram');

    // Export kelas
    Route::get('presensi/export-kelas/{kelasId}', [PresensiControllerAP::class, 'exportKelas'])
         ->name('presensi.exportKelas');
});

Route::prefix('adminprogram')->name('adminprogram.')->middleware(['auth'])->group(function () {

    // ----------------------------
    // Pengumuman Program
    // ----------------------------
    Route::get('announcements', [AnnouncementControllerAP::class, 'index'])
        ->name('announcements.index');

    Route::get('announcements/create', [AnnouncementControllerAP::class, 'create'])
        ->name('announcements.create');

    Route::post('announcements', [AnnouncementControllerAP::class, 'store'])
        ->name('announcements.store');

    Route::delete('announcements/{id}', [AnnouncementControllerAP::class, 'destroy'])
        ->name('announcements.destroy');
});
Route::prefix('adminprogram')->name('adminprogram.')->middleware('auth')->group(function () {

    // Halaman utama diskusi
    Route::get('/discussion', [DiscussionControllerAP::class, 'index'])->name('discussion.index');

    Route::get('/discussion/forum/{id}', [DiscussionControllerAP::class, 'showForum'])->name('discussion.forum');

    // Kirim pesan forum
    Route::post('/discussion/forum/{id}', [DiscussionControllerAP::class, 'storeForumMessage'])->name('discussion.forum.message');

    // Buka DM
    Route::get('/discussion/dm/{userId}', [DiscussionControllerAP::class, 'showDm'])->name('discussion.dm');

    // Kirim DM
    Route::post('/discussion/dm/{userId}', [DiscussionControllerAP::class, 'storeDm'])->name('discussion.dm.message');
        Route::get('/discussion/dm/{userId}', [DiscussionControllerAP::class, 'showDm'])->name('discussion.dm');
    Route::post('/discussion/dm/{userId}', [DiscussionControllerAP::class, 'storeDm'])->name('discussion.dm.store');
      Route::post('/discussion/forum/{id}', [DiscussionControllerAP::class, 'storeForumMessage'])->name('discussion.forum.store');
});
Route::prefix('adminprogram')->name('adminprogram.')->middleware(['auth'])->group(function () {

    // Daftar tiket
    Route::get('support', [SupportTicketControllerAP::class, 'index'])
         ->name('support.index');

    // Detail & balas tiket
    Route::get('support/{id}', [SupportTicketControllerAP::class, 'show'])
         ->name('support.show');

    // Update status & feedback tiket
    Route::put('support/{id}', [SupportTicketControllerAP::class, 'update'])
         ->name('support.update');

});

Route::prefix('adminprogram')->middleware(['auth'])->group(function () {

    // Routes untuk Narasumber
    Route::prefix('{programId}/narasumber')->group(function () {
        // Index - Daftar semua narasumber
        Route::get('/', [NarasumberControllerAP::class, 'index'])
             ->name('adminprogram.narasumber.index');

        // Create - Form tambah narasumber
        Route::get('/create', [NarasumberControllerAP::class, 'create'])
             ->name('adminprogram.narasumber.create');

        // Store - Simpan narasumber baru
        Route::post('/', [NarasumberControllerAP::class, 'store'])
             ->name('adminprogram.narasumber.store');

        // Show - Detail narasumber
        Route::get('/{narasumberId}', [NarasumberControllerAP::class, 'show'])
             ->name('adminprogram.narasumber.show');

        // Edit - Form edit narasumber
        Route::get('/{narasumberId}/edit', [NarasumberControllerAP::class, 'edit'])
             ->name('adminprogram.narasumber.edit');

        // Update - Update narasumber
        Route::put('/{narasumberId}', [NarasumberControllerAP::class, 'update'])
             ->name('adminprogram.narasumber.update');

        // Destroy - Hapus narasumber
        Route::delete('/{narasumberId}', [NarasumberControllerAP::class, 'destroy'])
             ->name('adminprogram.narasumber.destroy');
    });

});

Route::prefix('adminprogram')->name('adminprogram.')->group(function () {

    // Daftar kelas per program
    Route::get('program/{programId}/eraport', [EraportController::class, 'index'])->name('eraport.index');

    // Rapor akumulasi program
    Route::get('program/{programId}/eraport/program', [EraportController::class, 'programReport'])->name('eraport.programReport');

    // Lihat & input nilai peserta per kelas
    Route::get('program/{programId}/kelas/{kelasId}/eraport', [EraportController::class, 'show'])->name('eraport.show');
    Route::post('program/{programId}/kelas/{kelasId}/eraport', [EraportController::class, 'storeScore'])->name('eraport.storeScore');

    // Edit bobot per kelas
    Route::get('program/{programId}/kelas/{kelasId}/eraport/weight', [EraportController::class, 'editWeight'])->name('eraport.editWeight');
    Route::post('program/{programId}/kelas/{kelasId}/eraport/weight', [EraportController::class, 'updateWeight'])->name('eraport.updateWeight');

    // Kolom custom
    Route::get('program/{programId}/kelas/{kelasId}/eraport/custom-column', [EraportController::class, 'createCustomColumn'])->name('eraport.createCustomColumn');
    Route::post('program/{programId}/kelas/{kelasId}/eraport/custom-column', [EraportController::class, 'storeCustomColumn'])->name('eraport.storeCustomColumn');
});

Route::prefix('adminprogram')->name('adminprogram.')->group(function () {

    // Halaman rekap raport satu kelas
    Route::get('raport/{kelasId}', [AdminRaportController::class, 'show'])
        ->name('adminprogram.raport.show');
            Route::get('/', [AdminRaportController::class, 'index'])->name('index');

});

Route::prefix('adminprogram/raport')->name('adminprogram.raport.')->group(function () {
    Route::get('/', [AdminRaportController::class, 'index'])->name('index'); // route index
    Route::get('/{kelasId}', [AdminRaportController::class, 'show'])->name('show'); // route show
});
Route::prefix('adminprogram/eraport')->name('adminprogram.eraport.')->group(function () {
    Route::get('/{programId}/{kelasId}/input-nilai', [EraportController::class, 'editScore'])->name('editScore');
    Route::post('/{programId}/{kelasId}/store-score', [EraportController::class, 'storeScore'])->name('storeScore');

    // Optional, routes untuk edit bobot & tambah kolom custom
    Route::get('/{programId}/{kelasId}/edit-weight', [EraportController::class, 'editWeight'])->name('editWeight');
    Route::get('/{programId}/{kelasId}/create-custom-column', [EraportController::class, 'createCustomColumn'])->name('createCustomColumn');

        Route::get('{program}/{kelas}/edit', [EraportController::class, 'editScore'])->name('editScore');
    Route::post('{program}/{kelas}/store', [EraportController::class, 'storeScore'])->name('storeScore');

});

















    // Role INSTRUCTOR


    // Role PARTICIPANT
    Route::prefix('participant')
        ->name('participant.')
        ->middleware(['role:participant', 'profile.complete'])
        ->group(function () {

            Route::get('/profil', [ProfileDataController::class, 'index'])->name('profil.index');
            Route::get('/profil/edit', [ProfileDataController::class, 'edit'])->name('profil.edit');
            Route::put('/profil', [ProfileDataController::class, 'update'])->name('profil.update');

            Route::get('dashboard', [ParticipantDashboard::class, 'index'])->name('dashboard');
        });



// Auth routes Breeze
require __DIR__.'/auth.php';


// Google OAuth
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('google.callback');


// Email Verification
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');







use App\Http\Controllers\Instructor\AnnouncementControllerIN;

Route::prefix('instructor')->middleware(['auth', 'role:instructor'])->group(function () {

    // Daftar pengumuman
    Route::get('announcements', [AnnouncementControllerIN::class, 'index'])
        ->name('instructor.announcements.index');

    // Form buat pengumuman baru
    Route::get('announcements/create', [AnnouncementControllerIN::class, 'create'])
        ->name('instructor.announcements.create');

    // Simpan pengumuman
    Route::post('announcements', [AnnouncementControllerIN::class, 'store'])
        ->name('instructor.announcements.store');

    // Hapus pengumuman
    Route::delete('announcements/{id}', [AnnouncementControllerIN::class, 'destroy'])
        ->name('instructor.announcements.destroy');
});

use App\Http\Controllers\Instructor\DiscussionControllerIN;

Route::prefix('instructor')->middleware(['auth', 'role:instructor'])->group(function () {

    // Halaman utama diskusi
    Route::get('discussion', [DiscussionControllerIN::class, 'index'])
        ->name('instructor.discussion.index');

    // Buka forum tertentu
    Route::get('discussion/forum/{id}', [DiscussionControllerIN::class, 'showForum'])
        ->name('instructor.discussion.forum');

    // Kirim pesan di forum
    Route::post('discussion/forum/{id}/store', [DiscussionControllerIN::class, 'storeForumMessage'])
        ->name('instructor.discussion.forum.store');

    // Buka DM dengan user
    Route::get('discussion/dm/{userId}', [DiscussionControllerIN::class, 'showDm'])
        ->name('instructor.discussion.dm');

    // Kirim DM
    Route::post('discussion/dm/{userId}/store', [DiscussionControllerIN::class, 'storeDm'])
        ->name('instructor.discussion.dm.store');

});

use App\Http\Controllers\Instructor\SupportTicketControllerIN;

Route::prefix('instructor')->middleware(['auth', 'role:instructor'])->group(function () {

    // Daftar tiket
    Route::get('support', [SupportTicketControllerIN::class, 'index'])
        ->name('instructor.support.index');

    // Detail tiket
    Route::get('support/{id}', [SupportTicketControllerIN::class, 'show'])
        ->name('instructor.support.show');

    // Update tiket
    Route::put('support/{id}', [SupportTicketControllerIN::class, 'update'])
        ->name('instructor.support.update');
});



use App\Http\Controllers\Instructor\AssignmentControllerIN;
// Routes untuk Assignment Management oleh Instructor
Route::prefix('instructor')->name('instructor.')->group(function () {

    // Assignment Routes
    Route::prefix('assignments')->name('assignments.')->group(function () {
        // Daftar assignments (dengan optional program filter)
        Route::get('/', [AssignmentControllerIN::class, 'index'])->name('index');
        Route::get('/program/{programId}', [AssignmentControllerIN::class, 'index'])->name('index.program');

        // Create assignment
        Route::get('/create', [AssignmentControllerIN::class, 'create'])->name('create');
        Route::post('/store', [AssignmentControllerIN::class, 'store'])->name('store');

        // Edit assignment
        Route::get('/{id}/edit', [AssignmentControllerIN::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [AssignmentControllerIN::class, 'update'])->name('update');

        // Delete assignment
        Route::delete('/{id}/destroy', [AssignmentControllerIN::class, 'destroy'])->name('destroy');

        // Toggle publish status
        Route::patch('/{id}/toggle-publish', [AssignmentControllerIN::class, 'togglePublish'])->name('toggle-publish');

        // Submissions management
        Route::get('/{id}/submissions', [AssignmentControllerIN::class, 'submissions'])->name('submissions');
        Route::put('/submissions/{submissionId}/score', [AssignmentControllerIN::class, 'updateSubmissionScore'])->name('update-score');

        // Download submissions
        Route::get('/{assignmentId}/download-submissions', [AssignmentControllerIN::class, 'downloadAllSubmissions'])->name('download-submissions');
    });
});



use App\Http\Controllers\Instructor\EssayExamControllerIN;

// Routes untuk Essay Exam Management oleh Instructor
Route::prefix('instructor')->name('instructor.')->group(function () {

    // Essay Exam Routes
    Route::prefix('essay-exams')->name('essay.')->group(function () {
        // CRUD Exams
        Route::get('/', [EssayExamControllerIN::class, 'index'])->name('index');
        Route::get('/create', [EssayExamControllerIN::class, 'create'])->name('create');
        Route::post('/store', [EssayExamControllerIN::class, 'store'])->name('store');
        Route::get('/{id}/edit', [EssayExamControllerIN::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [EssayExamControllerIN::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [EssayExamControllerIN::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-publish', [EssayExamControllerIN::class, 'togglePublish'])->name('toggle-publish');

        // Questions Management
        Route::get('/{examId}/questions', [EssayExamControllerIN::class, 'questions'])->name('questions');
        Route::post('/{examId}/questions/store', [EssayExamControllerIN::class, 'storeQuestion'])->name('questions.store');
        Route::post('/questions/{id}/update', [EssayExamControllerIN::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/questions/{id}/delete', [EssayExamControllerIN::class, 'deleteQuestion'])->name('questions.delete');
        // GANTI dari PUT menjadi POST

        // Submissions Management
        Route::get('/{examId}/submissions', [EssayExamControllerIN::class, 'submissions'])->name('submissions');
        Route::get('/submissions/{submissionId}/grade', [EssayExamControllerIN::class, 'gradeSubmission'])->name('submissions.grade');
        Route::put('/submissions/{submissionId}/finish-grading', [EssayExamControllerIN::class, 'finishGrading'])->name('submissions.finish-grading');
        Route::put('/answers/{answerId}/grade', [EssayExamControllerIN::class, 'saveGrade'])->name('answers.grade');
        Route::put('/submissions/{id}/update-final-score', [EssayExamControllerIN::class, 'updateFinalScore'])->name('submissions.update-final-score');

        // Export & Reports
        Route::get('/submissions/{submissionId}/export-pdf', [EssayExamControllerIN::class, 'exportPDF'])->name('submissions.export-pdf');
        Route::get('/{examId}/preview-pdf', [EssayExamControllerIN::class, 'previewPDF'])->name('preview-pdf');
        Route::get('/{examId}/export-all-pdf', [EssayExamControllerIN::class, 'exportAllPDF'])->name('export-all-pdf');

    });
});



use App\Http\Controllers\Instructor\LearningPathControllerIN;
// Routes untuk Learning Path Management oleh Instructor
Route::prefix('instructor')->name('instructor.')->middleware(['auth'])->group(function () {

    // Learning Path
    Route::post('kelas/{kelasId}/learning-path', [LearningPathControllerIN::class, 'store'])
        ->name('learningpath.store');
    Route::get('learning-paths/{id}/manage', [LearningPathControllerIN::class, 'manage'])
        ->name('learningpath.manage');
    Route::put('learning-paths/{id}', [LearningPathControllerIN::class, 'update'])
        ->name('learningpath.update');
    Route::delete('learning-paths/{id}', [LearningPathControllerIN::class, 'destroy'])
        ->name('learningpath.destroy');

    // Sections (Bab)
    Route::get('learning-paths/{id}/sections/create', [LearningPathControllerIN::class, 'createSection'])
        ->name('learningpath.section.create');
    Route::post('learning-paths/{id}/sections', [LearningPathControllerIN::class, 'storeSection'])
        ->name('learningpath.section.store');
    Route::get('sections/{id}/edit', [LearningPathControllerIN::class, 'editSection'])
        ->name('learningpath.section.edit');
    Route::put('sections/{id}', [LearningPathControllerIN::class, 'updateSection'])
        ->name('learningpath.section.update');
    Route::delete('sections/{id}', [LearningPathControllerIN::class, 'destroySection'])
        ->name('learningpath.section.destroy');
});




use App\Http\Controllers\Instructor\ModuleControllerIN;
// Routes untuk Module Management oleh Instructor
Route::prefix('instructor')->name('instructor.')->group(function () {
    // Module CRUD
    Route::get('kelas/{kelas}/modules/create', [ModuleControllerIN::class, 'create'])->name('modules.create');
    Route::post('kelas/{kelas}/modules', [ModuleControllerIN::class, 'store'])->name('modules.store');
});


Route::prefix('instructor')->name('instructor.')->group(function () {
    // Modul Bacaan
    Route::get('kelas/{kelas}/modules/create', [ModuleControllerIN::class, 'create'])->name('modules.create');
    Route::post('kelas/{kelas}/modules', [ModuleControllerIN::class, 'store'])->name('modules.store');

    // Edit, update, delete modul (opsional)
    Route::get('modules/{id}/edit', [ModuleControllerIN::class, 'edit'])->name('modules.edit');
    Route::put('modules/{id}', [ModuleControllerIN::class, 'update'])->name('modules.update');
    Route::delete('modules/{id}', [ModuleControllerIN::class, 'destroy'])->name('modules.destroy');
});


Route::prefix('instructor')->name('instructor.')->group(function () {
    // Module Routes
    Route::prefix('modules')->name('modules.')->group(function () {
        // CRUD Modules
    Route::get('kelas/{kelas}/modules/create', [ModuleControllerIN::class, 'create'])->name('modules.create');
    Route::post('kelas/{kelas}/modules', [ModuleControllerIN::class, 'store'])->name('modules.store');
        Route::get('/{id}/edit', [ModuleControllerIN::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [ModuleControllerIN::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [ModuleControllerIN::class, 'destroy'])->name('destroy');

        // Additional Features
        Route::get('/{id}/preview', [ModuleControllerIN::class, 'preview'])->name('preview');
        Route::patch('/{id}/toggle-publish', [ModuleControllerIN::class, 'togglePublish'])->name('toggle-publish');
        Route::post('/{id}/duplicate', [ModuleControllerIN::class, 'duplicate'])->name('duplicate');
        Route::put('/{kelasId}/reorder', [ModuleControllerIN::class, 'reorder'])->name('reorder');

        // API Routes
        Route::get('/{kelasId}/get-by-kelas', [ModuleControllerIN::class, 'getByKelas'])->name('get-by-kelas');

    });
});


use App\Http\Controllers\Instructor\NarasumberControllerIN;

Route::prefix('instructor')
    ->name('instructor.')
    ->middleware(['auth', 'role:instructor']) // opsional, jika pakai spatie
    ->group(function () {

        // Semua route narasumber harus punya programId
        Route::prefix('program/{programId}/narasumber')->name('narasumber.')->group(function () {

            Route::get('/', [NarasumberControllerIN::class, 'index'])
                ->name('index');

            Route::get('/create', [NarasumberControllerIN::class, 'create'])
                ->name('create');

            Route::post('/', [NarasumberControllerIN::class, 'store'])
                ->name('store');

            Route::get('/{narasumberId}/edit', [NarasumberControllerIN::class, 'edit'])
                ->name('edit');

            Route::put('/{narasumberId}', [NarasumberControllerIN::class, 'update'])
                ->name('update');

            Route::delete('/{narasumberId}', [NarasumberControllerIN::class, 'destroy'])
                ->name('destroy');

            Route::get('/{narasumberId}', [NarasumberControllerIN::class, 'show'])
                ->name('show');
        });

    });




use App\Http\Controllers\Instructor\PresensiControllerIN;
// Routes untuk Presensi Management oleh Instructor
Route::prefix('instructor')->name('instructor.')->group(function () {

    // Presensi Routes
    Route::prefix('presensi')->name('presensi.')->group(function () {
        // Kelola Presensi
        Route::get('/{kelasId}/edit', [PresensiControllerIN::class, 'edit'])->name('edit');
        Route::put('/{kelasId}/update', [PresensiControllerIN::class, 'update'])->name('update');
        Route::delete('/{hasilId}/destroy', [PresensiControllerIN::class, 'destroy'])->name('destroy');

        // Export Laporan
        Route::post('/export-program', [PresensiControllerIN::class, 'exportProgram'])->name('export-program');
        Route::get('/{kelasId}/export-kelas', [PresensiControllerIN::class, 'exportKelas'])->name('export-kelas');
    });
});



use App\Http\Controllers\Instructor\QuizControllerIN;
// Routes untuk Quiz Management oleh Instructor
// Routes untuk Instructor
// =============================================================================
// ROUTES UNTUK INSTRUCTOR - KELAS MANAGEMENT
// =============================================================================
Route::prefix('instructor')
    ->name('instructor.')
    ->middleware(['auth', 'role:instructor'])
    ->group(function () {

        // Kelas Routes


        // Quiz Routes (yang sudah ada)
        Route::prefix('quizzes')->name('quizzes.')->group(function () {
            Route::get('/', [QuizControllerIN::class, 'index'])->name('index');
            Route::get('/{kelasId}/create', [QuizControllerIN::class, 'create'])->name('create');
            Route::post('/{kelasId}/store', [QuizControllerIN::class, 'store'])->name('store');
            Route::get('/{id}/edit', [QuizControllerIN::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [QuizControllerIN::class, 'update'])->name('update');
            Route::delete('/{id}/destroy', [QuizControllerIN::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/toggle-publish', [QuizControllerIN::class, 'togglePublish'])->name('toggle-publish');
            Route::get('/{id}/preview', [QuizControllerIN::class, 'preview'])->name('preview');

            // Questions Management
            Route::post('/{quizId}/questions/store', [QuizControllerIN::class, 'storeQuestion'])->name('questions.store');
            Route::get('/questions/{questionId}/edit', [QuizControllerIN::class, 'editQuestion'])->name('questions.edit');
            Route::put('/questions/{questionId}/update', [QuizControllerIN::class, 'updateQuestion'])->name('questions.update');
            Route::delete('/questions/{questionId}/destroy', [QuizControllerIN::class, 'destroyQuestion'])->name('questions.destroy');
        });
    });

    use App\Http\Controllers\Instructor\InstructorQuizController;

// Prefix /instructor/quiz
Route::prefix('instructor/quiz')->name('instructor.quiz.')->middleware('auth')->group(function () {

    // Daftar quiz
    Route::get('/', [InstructorQuizController::class, 'index'])->name('index');

    // Form create
    Route::get('/create', [InstructorQuizController::class, 'create'])->name('create');

    // Store quiz baru
    Route::post('/', [InstructorQuizController::class, 'store'])->name('store');

    // Edit quiz
    Route::get('/{id}/edit', [InstructorQuizController::class, 'edit'])->name('edit');

    // Update quiz
    Route::put('/{id}', [InstructorQuizController::class, 'update'])->name('update');

    // Hapus quiz
    Route::delete('/{id}', [InstructorQuizController::class, 'destroy'])->name('destroy');

    // Lihat semua submission
    Route::get('/{quizId}/submissions', [InstructorQuizController::class, 'submissions'])->name('submissions');

    // Update nilai submission
    Route::put('/submission/{attemptId}/score', [InstructorQuizController::class, 'updateSubmissionScore'])->name('updateSubmissionScore');

    // Download semua submission PDF
    Route::get('/{quizId}/submissions/download', [InstructorQuizController::class, 'downloadAllSubmissions'])->name('downloadAllSubmissions');

});


Route::prefix('instructor')->name('instructor.')->group(function() {
    // Route quiz download PDF
    Route::get('quiz/{quiz}/download', [InstructorQuizController::class, 'downloadAllSubmissions'])
        ->name('quiz.download');
});


use App\Http\Controllers\Instructor\QuizQuestionControllerIN;

Route::prefix('instructor/quiz/{quizId}/questions')->group(function () {
    Route::get('/', [QuizQuestionControllerIN::class, 'index'])->name('instructor.quiz.questions.index');
    Route::get('/create', [QuizQuestionControllerIN::class, 'create'])->name('instructor.quiz.questions.create');
    Route::post('/', [QuizQuestionControllerIN::class, 'store'])->name('instructor.quiz.questions.store');
    Route::get('/{questionId}/edit', [QuizQuestionControllerIN::class, 'edit'])->name('instructor.quiz.questions.edit');
    Route::put('/{questionId}', [QuizQuestionControllerIN::class, 'update'])->name('instructor.quiz.questions.update');
    Route::delete('/{questionId}', [QuizQuestionControllerIN::class, 'destroy'])->name('instructor.quiz.questions.destroy');
});




use App\Http\Controllers\Instructor\VideoControllerIN;
// Routes untuk Video Management oleh Instructor
Route::prefix('instructor')->name('instructor.')->group(function () {

    // Video Routes
    Route::prefix('videos')->name('videos.')->group(function () {
        // CRUD Videos
        Route::get('/{kelasId}/create', [VideoControllerIN::class, 'create'])->name('create');
        Route::post('/{kelasId}/store', [VideoControllerIN::class, 'store'])->name('store');
        Route::get('/{id}/edit', [VideoControllerIN::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [VideoControllerIN::class, 'update'])->name('update');
        Route::delete('/{id}/destroy', [VideoControllerIN::class, 'destroy'])->name('destroy');

        // Additional Features
        Route::get('/{id}/preview', [VideoControllerIN::class, 'preview'])->name('preview');
        Route::patch('/{id}/toggle-publish', [VideoControllerIN::class, 'togglePublish'])->name('toggle-publish');
    });
});



use App\Http\Controllers\Instructor\KelasControllerIN;
// Routes untuk Kelas Management oleh Instructor
Route::prefix('instructor')->middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('kelas', [KelasControllerIN::class, 'index'])->name('instructor.kelas.index');
    Route::get('kelas/{id}/edit', [KelasControllerIN::class, 'edit'])->name('instructor.kelas.edit');
    Route::put('kelas/{id}', [KelasControllerIN::class, 'update'])->name('instructor.kelas.update');
    Route::patch('kelas/{id}/toggle-publish', [KelasControllerIN::class, 'togglePublish'])->name('instructor.kelas.togglePublish');
});

Route::prefix('instructor')->name('instructor.')->middleware(['auth'])->group(function () {
    // ... route kelas lainnya

    // Export presensi kelas
    Route::get('presensi/{kelas}/export', [\App\Http\Controllers\Instructor\PresensiControllerIN::class, 'exportKelas'])
        ->name('presensi.exportKelas');
});



Route::prefix('instructor')->name('instructor.')->group(function () {
    // Route lain...
    Route::get('kelas/{kelas}/export-presensi', [PresensiControllerIN::class, 'exportKelas'])
        ->name('presensi.exportKelas');
});
Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'role:instructor'])->group(function () {

    Route::get('quizzes/create/{kelas_id}', [\App\Http\Controllers\Instructor\QuizControllerIN::class, 'create'])
        ->name('quiz.create');

    Route::post('quizzes/store', [\App\Http\Controllers\Instructor\QuizControllerIN::class, 'store'])
        ->name('quiz.store');

});






use App\Http\Controllers\Instructor\ProgressControllerIN;

// Routes untuk Instructor Progress
Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'role:instructor'])->group(function () {

    // Halaman utama daftar kelas
    Route::get('/progress', [ProgressControllerIN::class, 'index'])->name('progress.index');

    // Detail progress dan nilai untuk satu kelas
    Route::get('/progress/kelas/{kelasId}', [ProgressControllerIN::class, 'show'])->name('progress.show');

    // Input nilai manual via AJAX
    Route::post('/progress/custom-score', [ProgressControllerIN::class, 'storeCustomScore'])->name('progress.storeCustomScore');

    // Hitung ulang nilai otomatis
    Route::post('/progress/kelas/{kelasId}/calculate', [ProgressControllerIN::class, 'calculate'])->name('progress.calculate');

});



use App\Http\Controllers\AdminProgram\ResourceControllerAP;

// Perhatikan: ->name('adminprogram.') sudah ada di grup
Route::prefix('admin-program')->name('adminprogram.')->middleware(['auth', 'role:adminprogram'])->group(function () {

    // 1. Halaman PILIH PROGRAM
    // Nama Route: adminprogram.resources.index
    Route::get('/resources', [ResourceControllerAP::class, 'selectProgram'])
         ->name('resources.index');

    // 2. Halaman LIST MATERI per Program
    // Nama Route: adminprogram.resources.indexByProgram
    Route::get('/program/{programId}/resources', [ResourceControllerAP::class, 'indexByProgram'])
         ->name('resources.indexByProgram');

    // ================= RESOURCE (CRUD) =================

    // Tambah resource baru
    // Nama Route: adminprogram.resources.create
    Route::get('kelas/{kelasId}/resources/create', [ResourceControllerAP::class, 'create'])
         ->name('resources.create');

    // Nama Route: adminprogram.resources.store
    Route::post('kelas/{kelasId}/resources', [ResourceControllerAP::class, 'store'])
         ->name('resources.store');

    // Edit resource
    // Nama Route: adminprogram.resources.edit
    Route::get('resources/{id}/edit', [ResourceControllerAP::class, 'edit'])
         ->name('resources.edit');

    // Update resource
    // Nama Route: adminprogram.resources.update
    Route::put('resources/{id}', [ResourceControllerAP::class, 'update'])
         ->name('resources.update');

    // Hapus resource
    // Nama Route: adminprogram.resources.destroy
    Route::delete('resources/{id}', [ResourceControllerAP::class, 'destroy'])
         ->name('resources.destroy');
});
use App\Http\Controllers\Instructor\ResourceControllerIN;

Route::prefix('instructor')->name('instructor.')->middleware(['auth', 'role:instructor'])->group(function () {

    // 1. ENTRY POINT (Otomatis Redirect ke Program Instruktur)
    // Link Sidebar mengarah ke sini
    Route::get('/resources', [ResourceControllerIN::class, 'index'])
         ->name('resources.index');

    // 2. LIST MATERI (Halaman Utama)
    Route::get('/program/{programId}/resources', [ResourceControllerIN::class, 'indexByProgram'])
         ->name('resources.indexByProgram');

    // ... Route CRUD lainnya (Create, Store, Edit, Update, Destroy) sama seperti sebelumnya ...
    // CRUD
    Route::get('kelas/{kelasId}/resources/create', [ResourceControllerIN::class, 'create'])->name('resources.create');
    Route::post('kelas/{kelasId}/resources', [ResourceControllerIN::class, 'store'])->name('resources.store');
    Route::get('resources/{id}/edit', [ResourceControllerIN::class, 'edit'])->name('resources.edit');
    Route::put('resources/{id}', [ResourceControllerIN::class, 'update'])->name('resources.update');
    Route::delete('resources/{id}', [ResourceControllerIN::class, 'destroy'])->name('resources.destroy');
});



// Route untuk update minat program
Route::patch('/profile/minat', [ProfileDataController::class, 'updateMinatProgram'])
    ->name('profile.minat.update')
    ->middleware('auth'); // pastikan user login




use App\Http\Controllers\AdminProgram\NomorIndukControllerAP;
Route::prefix('admin-program')->middleware(['auth', 'role:adminprogram'])->group(function () {
    // === MANAJEMEN NOMOR INDUK (BARU) ===
    Route::get('/nomor-induk', [NomorIndukControllerAP::class, 'index'])->name('adminprogram.ni.index');
    Route::post('/nomor-induk', [NomorIndukControllerAP::class, 'store'])->name('adminprogram.ni.store');
    Route::patch('/nomor-induk/{id}/toggle', [NomorIndukControllerAP::class, 'toggle'])->name('adminprogram.ni.toggle');

});
