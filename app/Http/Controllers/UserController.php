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
        return view('user.index', compact('all'));
    }

    public function tambah(){
        return view('user.tambah');
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

    public function edit($id){
        $user = User::findOrFail($id);
        return view('user.tambah', compact('user'));
    }

    public function update(Request $request, $id){
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'no_wa' => 'required|string|max:20',
            'password' => 'nullable|string|min:8', // nullable biar bisa kosong
            'role' => 'required|string|in:APD,Admin,Super Admin',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Set admin dan superadmin berdasarkan role
        $admin = $request->role === 'Admin' || $request->role === 'Super Admin';
        $superadmin = $request->role === 'Super Admin';

        // Handle upload foto profil
        $foto_profil = $user->foto_profil; // keep existing foto if not updated
        if ($request->hasFile('foto_profil')) {
            // Delete old foto if exists
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $foto_profil = $request->file('foto_profil')->store('foto_profil', 'public');
        }

        // ambil data kecuali password
        $save = [
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'no_wa' => $request->no_wa,
            'role' => $request->role,
            'foto_profil' => $foto_profil,
            'admin' => $admin,
            'superadmin' => $superadmin,
        ];

        // kalau password diisi baru update
        if ($request->filled('password')) {
            $save['password'] = Hash::make($request->password);
        }

        $user->update($save);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id){
        // DELETE FROM mahasiswa where id;
        DB::table('users')->where('id',$id)->delete();
        return redirect()->route('user.index');
    }
}
