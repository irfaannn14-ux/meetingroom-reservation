<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\ActivityLog;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna, termasuk relasi organisasi.
     */
    public function index()
    {
        $all = User::with('organization')->latest()->get();
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

        $user = User::create([
            'nama' => $validatedData['nama'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'no_wa' => $validatedData['no_wa'],
            'password' => Hash::make($validatedData['password']),
            'organization_id' => $validatedData['organization_id'],
            'role' => $role,
            'foto_profil' => $path,
        ]);

        ActivityLog::create([
            'user_id' => session('user_id'),
            'activity' => 'Menambahkan pengguna baru: ' . $user->nama,
            'resource_type' => 'user',
            'resource_id' => $user->id,
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Tampilkan formulir edit untuk pengguna tertentu.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $organizations = Organization::all();
        $isEdit = true; // Flag untuk menentukan mode edit
        
        return view('user.tambah', compact('user', 'organizations', 'isEdit'));
    }

    /**
     * Perbarui pengguna yang ada.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'no_wa' => 'required|string|max:20',
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'role' => 'required|string',
            'organization_id' => 'nullable|exists:organizations,bkd_organization_id',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:8|confirmed'
        ]);
        
        // Update data user
        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_wa' => $request->no_wa,
            'username' => $request->username,
            'role' => $request->role,
            'organization_id' => $request->role == 'OPD' ? $request->organization_id : null
        ]);
        
        // Jika ada password yang diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }
        
        // Jika ada foto profil yang diupload
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            
            $path = $request->file('foto_profil')->store('profile', 'public');
            $user->foto_profil = $path;
            $user->save();
        }
        
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
        $nama_user = $user->nama;
        $user->delete();

        ActivityLog::create([
            'user_id' => session('user_id'),
            'activity' => 'Menghapus pengguna: ' . $nama_user,
            'resource_type' => 'user',
            'resource_id' => $user->id,
        ]);

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
     * Menggunakan view tambah.blade.php dengan pre-filled data.
     */
    public function editProfile()
    {
        $userId = session('user_id');
        $user = User::findOrFail($userId);
        $organizations = Organization::all();
        $isEdit = true; // Flag untuk menentukan mode edit
        
        return view('user.tambah', compact('user', 'organizations', 'isEdit'));
    }

    /**
     * Perbarui profil pengguna yang sedang login.
     */
    public function updateProfile(Request $request)
    {
        $userId = session('user_id');
        $user = User::findOrFail($userId);

        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$userId,
            'no_wa' => 'required|string|max:20',
            'username' => 'required|string|max:255|unique:users,username,'.$userId,
            'role' => 'required|string',
            'organization_id' => 'nullable|exists:organization,bkd_organization_id',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|min:8'
        ]);
        
        // Update data user
        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_wa' => $request->no_wa,
            'username' => $request->username,
            'role' => $request->role,
            'organization_id' => $request->role == 'OPD' ? $request->organization_id : null
        ]);
        
        // Jika ada password yang diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }
        
        // Jika ada foto profil yang diupload
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $user->foto_profil = $path;
            $user->save();
            
            // Update session foto
            session(['user_foto' => $path]);
        }
        
        // Update session nama jika berubah
        if ($user->wasChanged('nama')) {
            session(['user_nama' => $user->nama]);
        }
        
        // Update session role jika berubah
        if ($user->wasChanged('role')) {
            session(['user_role' => $user->role]);
        }

        ActivityLog::create([
            'user_id' => $userId,
            'activity' => 'Memperbarui profil',
            'resource_type' => 'user',
            'resource_id' => $userId,
        ]);

        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui!');
    }
}