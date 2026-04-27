<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapels = MataPelajaran::all();
        return view('admin.mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|unique:mata_pelajarans,kode_mapel',
            'nama_mapel' => 'required',
        ]);

        MataPelajaran::create($request->all());

        return redirect()->route('mapel.index')->with('success', 'Data Mata Pelajaran berhasil ditambahkan');
    }

    public function edit(MataPelajaran $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, MataPelajaran $mapel)
    {
        $request->validate([
            'kode_mapel' => 'required|unique:mata_pelajarans,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required',
        ]);

        $mapel->update($request->all());

        return redirect()->route('mapel.index')->with('success', 'Data Mata Pelajaran berhasil diperbarui');
    }

    public function destroy(MataPelajaran $mapel)
    {
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Data Mata Pelajaran berhasil dihapus');
    }
}
