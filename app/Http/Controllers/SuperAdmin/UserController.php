<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile; // Pastikan Model Profile diimport
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Menampilkan daftar user.
     * Bisa difilter berdasarkan role via query string ?role=instructor
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by Role jika ada request
        if ($request->has('role') && in_array($request->role, ['participant', 'admin_program', 'instructor', 'superadmin'])) {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->with('profile')->orderBy('created_at', 'desc')->paginate(10);

        return view('superadmin.users.index', compact('users'));
    }

    /**
     * Form tambah user baru.
     */
    public function create()
    {
        return view('superadmin.users.create');
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:superadmin,adminprogram,instructor,participant'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Auto verify jika dibuat oleh admin
        ]);

        // Buat profile kosong agar tidak error saat edit nanti
        $user->profile()->create([
            'is_complete' => false // Biarkan user melengkapi sendiri nanti, atau admin edit
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Form edit user (Akun & Profil).
     */
    public function edit(User $user)
    {
        // Pastikan profile ada (jaga-jaga)
        if (!$user->profile) {
            $user->profile()->create([]);
            $user->refresh();
        }

        // Kita perlu list provinsi untuk dropdown alamat profile
        $provinsiList = \App\Models\Provinsi::orderBy('nama')->get();

        return view('superadmin.users.edit', compact('user', 'provinsiList'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi Data Akun (User Table)
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:superadmin,adminprogram,instructor,participant'],
        ];

        // Password opsional (hanya jika diisi)
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        // 2. Validasi Data Profil (Profile Table) - Opsional tapi boleh diedit admin
        // Kita buat nullable semua agar admin tidak wajib mengisi data pribadi user
        $profileRules = [
            'nomor_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'alamat_lengkap' => 'nullable|string',
            'provinsi_id' => 'nullable|exists:provinsis,id',
            // Tambahkan field lain sesuai kebutuhan admin untuk edit
        ];

        $validated = $request->validate(array_merge($rules, $profileRules));

        // Update User Table
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update Profile Table
        // Kita ambil data profile dari request, buang data user (name, email, role, password)
        $profileData = $request->only(['nomor_hp', 'jenis_kelamin', 'alamat_lengkap', 'provinsi_id']);

        $user->profile()->update($profileData);

        return redirect()->route('superadmin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Hapus user.
     */
public function destroy(User $user)
{
    // Cek jika user ini superadmin
    if ($user->role === 'superadmin') {
        return back()->with('error', 'Akun SuperAdmin tidak dapat dihapus.');
    }

    // Cek jika mencoba hapus akun sendiri
    if ($user->id === Auth::id()) {
        return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
    }

    // Hapus foto profil jika ada
    if ($user->profile) {
        if ($user->profile->pas_foto_path) {
            Storage::disk('public')->delete($user->profile->pas_foto_path);
        }
        if ($user->profile->scan_ktp_path) {
            Storage::disk('public')->delete($user->profile->scan_ktp_path);
        }
    }

    $user->delete();

    return back()->with('success', 'User berhasil dihapus.');
}




        public function impersonate(User $user)
    {
        if ($user->role !== 'participant') {
            return back()->with('error', 'Hanya bisa login sebagai peserta.');
        }

        // Simpan ID superadmin di session agar bisa kembali
        session(['impersonate_admin_id' => Auth::id()]);

        // Login sebagai user
        Auth::login($user);

        return redirect()->route('participant.dashboard') // ganti sesuai route peserta
                         ->with('success', "Berhasil login sebagai {$user->name}");
    }

    /**
     * Kembali ke akun superadmin setelah impersonate
     */
    public function leaveImpersonate()
    {
        if (!session()->has('impersonate_admin_id')) {
            return back()->with('error', 'Tidak sedang melakukan impersonate.');
        }

        $adminId = session('impersonate_admin_id');
        $admin = User::find($adminId);

        if (!$admin) {
            abort(404, 'Akun superadmin tidak ditemukan.');
        }

        // Login kembali sebagai admin
        Auth::login($admin);

        // Hapus session
        session()->forget('impersonate_admin_id');

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'Berhasil kembali ke akun SuperAdmin.');
    }

public function impersonateBack()
{
    $adminId = session('impersonate_admin_id');

    if(!$adminId) {
        abort(403, 'Tidak bisa kembali karena session tidak ditemukan.');
    }

    $admin = User::findOrFail($adminId);

    // Hapus session impersonate
    session()->forget('impersonate_admin_id');

    // Login kembali sebagai superadmin
    Auth::login($admin);

    return redirect()->route('superadmin.users.index')->with('success', 'Kembali ke akun superadmin.');
}
   public function show(User $user)
    {
        // Pastikan profile ada
        if (!$user->profile) {
            $user->profile()->create([]);
            $user->refresh();
        }

        return view('superadmin.users.show', compact('user'));
    }
}
