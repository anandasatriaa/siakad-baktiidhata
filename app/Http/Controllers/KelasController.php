<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $gurus = User::where('role', 'guru')->get();
        return view('admin.kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas',
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        Kelas::create($request->all());

        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil ditambahkan');
    }

    public function edit(Kelas $kela) // Laravel resource routing uses singular form for parameters
    {
        $gurus = User::where('role', 'guru')->get();
        return view('admin.kelas.edit', ['kelas' => $kela, 'gurus' => $gurus]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas,' . $kela->id,
            'wali_kelas_id' => 'nullable|exists:users,id',
        ]);

        $kela->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil diperbarui');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('kelas.index')->with('success', 'Data Kelas berhasil dihapus');
    }
}
