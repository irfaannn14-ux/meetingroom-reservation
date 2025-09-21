<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna, termasuk relasi organisasi.
     */
    public function index()
    {
        $all = User::with('organization')->get();
        return view('user.index', ['all' => $all]);
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'no_wa' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'organization_id' => 'required|string|exists:organization,bkd_organization_id',
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Tentukan role berdasarkan organization_id
        $organization = Organization::where('bkd_organization_id', $request->organization_id)->first();
        $role = 'OPD'; // Default role
        if ($organization) {
            if ($organization->organization_name === 'ADMIN') {
                $role = 'Admin';
            } elseif ($organization->organization_name === 'SUPER ADMIN') {
                $role = 'Super Admin';
            }
        }

        // Handle upload foto profil
        $path = $request->file('foto_profil')->store('foto_profil', 'public');

        User::create([
            'nama' => $validatedData['nama'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'no_wa' => $validatedData['no_wa'],
            'password' => Hash::make($validatedData['password']),
            'organization_id' => $validatedData['organization_id'],
            'role' => $role,
            'foto_profil' => $path,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Tampilkan formulir edit untuk pengguna tertentu.
     */
    public function edit(User $user)
    {
        $organizations = Organization::all();
        return view('user.tambah', compact('user', 'organizations'));
    }

    /**
     * Perbarui pengguna yang ada.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'no_wa' => 'required|string|max:15',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'organization_id' => 'required|string|exists:organization,bkd_organization_id',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Tentukan role baru berdasarkan organization_id
        $organization = Organization::where('bkd_organization_id', $request->organization_id)->first();
        $role = 'OPD'; // Default role
        if ($organization) {
            if ($organization->organization_name === 'ADMIN') {
                $role = 'Admin';
            } elseif ($organization->organization_name === 'SUPER ADMIN') {
                $role = 'Super Admin';
            }
        }
        $validatedData['role'] = $role;

        // Periksa apakah password diisi
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        // Handle update foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            // Simpan foto baru
            $validatedData['foto_profil'] = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        $user->update($validatedData);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Hapus pengguna dari database.
     */
    public function destroy(User $user)
    {
        // Hapus foto profil jika ada
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }

    /**
     * Tampilkan formulir untuk membuat pengguna baru.
     */
    public function create()
    {
        $organizations = Organization::all();
        return view('user.tambah', compact('organizations'));
    }

    /**
     * Tampilkan halaman edit profil untuk pengguna yang sedang login.
     */
    public function showProfile()
    {
        $user = User::find(session('user_id'));
        if (!$user) {
            abort(404, 'User tidak ditemukan.');
        }
        // Organisasi tidak diperlukan untuk edit profil sendiri
        return view('user.profile', compact('user'));
    }

    /**
     * Perbarui profil pengguna yang sedang login.
     */
    public function updateProfile(Request $request)
    {
        $userId = session('user_id');
        $user = User::find($userId);

        if (!$user) {
            abort(404, 'User tidak ditemukan.');
        }

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'no_wa' => 'required|string|max:15',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed', // Menambahkan konfirmasi password
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Periksa apakah password diisi
        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        // Handle update foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            // Simpan foto baru dan perbarui session
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $validatedData['foto_profil'] = $path;
            session(['user_foto' => $path]);
        }

        $user->update($validatedData);
        
        // Perbarui nama di session jika berubah
        if($user->wasChanged('nama')){
            session(['user_nama' => $user->nama]);
        }

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }
}

