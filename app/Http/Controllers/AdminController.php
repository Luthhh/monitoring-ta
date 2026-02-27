<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $dosens = Dosen::with('user')->get();

        return view('admin.a-manajemendosen', compact('dosens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:dosens',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'prodi' => 'required',
            'password' => 'required|min:6',
        ]);

        // ambil role dosen
        $roleDosen = Role::where('name', 'dosen')->first();

        // buat akun user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleDosen->id,
        ]);

        // buat data dosen
        Dosen::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'prodi' => $request->prodi,
        ]);

        return back()->with('success', 'Dosen berhasil ditambahkan');
    }
    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);

        $dosen->update([
            'nip' => $request->nip,
            'prodi' => $request->prodi,
        ]);

        $dosen->user->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Data berhasil diupdate');
    }
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);

        $dosen->user()->delete();
        $dosen->delete();

        return back()->with('success', 'Dosen berhasil dihapus');
    }
}
