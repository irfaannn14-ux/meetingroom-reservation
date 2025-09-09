<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            'nama_apd' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'admin' => 'required|boolean',
            'superadmin' => 'required|boolean'
        ]);

        $save = [
            'nama_apd' => $request->nama_apd,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'admin' => $request->boolean('admin'),
            'superadmin' => $request->boolean('superadmin'),
        ];

        DB::table('users')->insert($save);
        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('user.tambah', compact('user'));
    }

    public function update(Request $request, $id){
        $user = User::findOrFail($id);

        $request->validate([
            'nama_apd' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8', // nullable biar bisa kosong
            'admin' => 'required|boolean',
            'superadmin' => 'required|boolean'
        ]);

        // ambil data kecuali password
        $save = $request->only(['nama_apd','username','email','admin','superadmin']);

        // kalau password diisi baru update
        if ($request->filled('password')) {
            $save['password'] = Hash::make($request->password);
        }

        $user->update($save);

        return redirect()->route('user.index');
    }

    public function destroy($id){
        // DELETE FROM mahasiswa where id;
        DB::table('users')->where('id',$id)->delete();
        return redirect()->route('user.index');
    }
}
