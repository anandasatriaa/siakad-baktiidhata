<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use App\Exports\TemplateMapelExport;
use App\Imports\MapelImport;
use Maatwebsite\Excel\Facades\Excel;

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
            'kode_mapel' => 'required|unique:mata_pelajaran,kode_mapel',
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
            'kode_mapel' => 'required|unique:mata_pelajaran,kode_mapel,' . $mapel->id,
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

    public function downloadTemplate()
    {
        return Excel::download(new TemplateMapelExport, 'Template_Import_Mata_Pelajaran.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new MapelImport, $request->file('file_excel'));
            return redirect()->route('mapel.index')->with('success', 'Data Mata Pelajaran berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('mapel.index')->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
