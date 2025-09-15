<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(){
        $all = DB::table('users')->get();
        return view('user.index', ['all' => $all]);
    }

    public function store(Request $request){
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'no_wa' => 'required|string|max:20',
            'password' => 'required|string',
            'role' => 'required|string|in:APD,Admin,Super Admin',
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Set admin dan superadmin berdasarkan role
        $admin = $request->role === 'Admin' || $request->role === 'Super Admin';
        $superadmin = $request->role === 'Super Admin';

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
            'role' => $request->role,
            'foto_profil' => $foto_profil,
            'admin' => $admin,
            'superadmin' => $superadmin,
        ];

        DB::table('users')->insert($save);
        return redirect()->route('user.index')->with([
            'success' => 'User berhasil ditambahkan!',
            'alert_dismissible' => true
        ]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.tambah', compact('user'));
    }

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
            'role' => 'required|string',
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

    public function destroy($id){
        // DELETE FROM mahasiswa where id;
        DB::table('users')->where('id',$id)->delete();
        return redirect()->route('user.index');
    }

    public function create(Request $request)
    {
        // Jika ada parameter 'id', ambil data pengguna untuk edit
        $user = $request->has('id') ? User::find($request->id) : null;

        return view('user.tambah', compact('user'));
    }
}
