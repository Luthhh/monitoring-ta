<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;

class DosenController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        return view('dosen.d-profile', compact('user', 'dosen'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        $request->validate([
            'nip' => 'required',
            'name' => 'required',
            'prodi' => 'required',
            'email' => 'required|email',
        ]);

        // Update user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update dosen
        $dosen->update([
            'nip' => $request->nip,
            'prodi' => $request->prodi,
        ]);

        return back()->with('success', 'Biodata berhasil diperbarui');
    }

    public function totalMahasiswa()
    {
        $dosen = Auth::user()->dosen;

        $mahasiswas = Mahasiswa::with(['user', 'tugasAkhir.milestones'])
            ->where('pembimbing1_id', $dosen->id)
            ->orWhere('pembimbing2_id', $dosen->id)
            ->get();

        return view('dosen.d-totalmahasiswa', compact('mahasiswas'));
    }
}