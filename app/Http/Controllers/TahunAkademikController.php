<?php

namespace App\Http\Controllers;

use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $tahun_akademiks = TahunAkademik::orderBy('tahun_ajaran', 'desc')->orderBy('semester', 'desc')->get();
        return view('admin.tahun_akademik.index', compact('tahun_akademiks'));
    }

    public function create()
    {
        return view('admin.tahun_akademik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($data['is_active']) {
            TahunAkademik::where('is_active', true)->update(['is_active' => false]);
        }

        TahunAkademik::create($data);

        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tahun_akademik = TahunAkademik::findOrFail($id);
        return view('admin.tahun_akademik.edit', compact('tahun_akademik'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $tahun_akademik = TahunAkademik::findOrFail($id);
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($data['is_active'] && !$tahun_akademik->is_active) {
            TahunAkademik::where('is_active', true)->update(['is_active' => false]);
        }

        $tahun_akademik->update($data);

        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tahun_akademik = TahunAkademik::findOrFail($id);
        if ($tahun_akademik->is_active) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus tahun akademik yang sedang aktif');
        }
        $tahun_akademik->delete();
        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil dihapus');
    }

    public function activate($id)
    {
        TahunAkademik::where('is_active', true)->update(['is_active' => false]);
        TahunAkademik::where('id', $id)->update(['is_active' => true]);

        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil diaktifkan');
    }
}
