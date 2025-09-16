<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna, termasuk relasi organisasi.
     */
    public function index()
    {
        // Memuat semua pengguna dengan relasi 'organization' menggunakan Eager Loading.
        // Ini akan mengambil data pengguna dan data organisasi terkait dalam satu query yang efisien.
        $all = User::with('organization')->get();
        return view('user.index', ['all' => $all]);
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'no_wa' => 'required|string|max:20',
            'password' => 'required|string',
            'organization_id' => 'required|string|max:255',
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle upload foto profil
        $foto_profil = null;
        if ($request->hasFile('foto_profil')) {
            $foto_profil = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        $save = [
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'no_wa' => $request->no_wa,
            'password' => Hash::make($request->password),
            'organization_id' => $request->organization_id, // Menyimpan bkd_organization_id
            'role' => 'OPD', // Nilai default, Anda bisa mengubahnya jika ada logika role lain
            'foto_profil' => $foto_profil,
        ];

        DB::table('users')->insert($save);
        return redirect()->route('user.index')->with([
            'success' => 'User berhasil ditambahkan!',
            'alert_dismissible' => true
        ]);
    }

    /**
     * Tampilkan formulir edit untuk pengguna tertentu.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $organizations = DB::table('organization')->get();
        return view('user.tambah', compact('user', 'organizations'));
    }

    /**
     * Perbarui pengguna yang ada.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi data
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_wa' => 'required|string|max:15',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:8', // Password bisa nullable
            'organization_id' => 'required|string',
        ]);

        // Periksa apakah password diisi
        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($request->password); // Hash password baru
        } else {
            unset($validatedData['password']); // Jangan ubah password jika kosong
        }

        // Update data pengguna
        $user->update($validatedData);

        return redirect()->route('user.index')->with([
            'success' => 'User berhasil diperbarui!',
            'alert_dismissible' => true
        ]);
    }

    /**
     * Hapus pengguna dari database.
     */
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->route('user.index');
    }

    /**
     * Tampilkan formulir untuk membuat atau mengedit pengguna.
     */
    public function create(Request $request)
    {
        // Jika ada parameter 'id', ambil data pengguna untuk edit
        $user = $request->has('id') ? User::find($request->id) : null;
        $organizations = DB::table('organization')->get();

        return view('user.tambah', compact('user', 'organizations'));
    }
}
