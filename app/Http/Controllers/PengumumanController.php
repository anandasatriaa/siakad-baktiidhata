<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        
        // Jika tidak ada parameter periode_id di URL, gunakan yang aktif
        // Jika ada tapi kosong (Semua Periode), gunakan null
        $periode_id = $request->has('periode_id') ? $request->periode_id : ($active_periode->id ?? null);

        $query = Pengumuman::with(['penulis', 'tahunAkademik']);

        // Jika periode_id dipilih (bukan string kosong), filter berdasarkan ID tersebut
        if ($periode_id !== null && $periode_id !== '') {
            $query->where('tahun_akademik_id', $periode_id);
        }

        $pengumumans = $query->latest()->get();
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.pengumuman.index', compact('pengumumans', 'periodes', 'periode_id'));
    }

    public function show(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.show', compact('pengumuman'));
    }

    public function create()
    {
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        return view('admin.pengumuman.create', compact('periodes', 'active_periode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'konten' => 'required',
            'tanggal' => 'required|date',
            'tahun_akademik_id' => 'nullable|exists:tahun_akademik,id',
        ]);

        Pengumuman::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal' => $request->tanggal,
            'penulis_id' => Auth::id(),
            'tahun_akademik_id' => $request->tahun_akademik_id,
        ]);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan');
    }

    public function edit(Pengumuman $pengumuman)
    {
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        return view('admin.pengumuman.edit', compact('pengumuman', 'periodes'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required',
            'konten' => 'required',
            'tanggal' => 'required|date',
            'tahun_akademik_id' => 'nullable|exists:tahun_akademik,id',
        ]);

        $pengumuman->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal' => $request->tanggal,
            'tahun_akademik_id' => $request->tahun_akademik_id,
        ]);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus');
    }
}
