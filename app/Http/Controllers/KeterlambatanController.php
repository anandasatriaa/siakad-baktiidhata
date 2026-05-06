<?php

namespace App\Http\Controllers;

use App\Models\Keterlambatan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeterlambatanController extends Controller
{
    public function index(Request $request)
    {
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);

        $query = Keterlambatan::with([
            'siswa.riwayatKelas' => function($q) use ($periode_id) {
                $q->where('tahun_akademik_id', $periode_id)->with('kelas');
            }, 
            'tahunAkademik', 
            'pencatat'
        ]);

        if ($periode_id) {
            $query->where('tahun_akademik_id', $periode_id);
        }

        $keterlambatans = $query->latest()->get();
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.keterlambatan.index', compact('keterlambatans', 'periodes', 'periode_id'));
    }

    public function create()
    {
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $siswas = Siswa::whereHas('riwayatKelas', function($q) use ($active_periode) {
            if ($active_periode) $q->where('tahun_akademik_id', $active_periode->id);
        })->get();
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.keterlambatan.create', compact('siswas', 'periodes', 'active_periode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'tanggal' => 'required|date',
            'lama_menit' => 'required|integer|min:1',
            'alasan' => 'nullable',
        ]);

        Keterlambatan::create([
            'siswa_id' => $request->siswa_id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'tanggal' => $request->tanggal,
            'lama_menit' => $request->lama_menit,
            'alasan' => $request->alasan,
            'pencatat_id' => Auth::id(),
        ]);

        return redirect()->route('keterlambatan.index')->with('success', 'Data keterlambatan berhasil ditambahkan');
    }

    public function edit(Keterlambatan $keterlambatan)
    {
        $siswas = Siswa::all();
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        return view('admin.keterlambatan.edit', compact('keterlambatan', 'siswas', 'periodes'));
    }

    public function update(Request $request, Keterlambatan $keterlambatan)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'tanggal' => 'required|date',
            'lama_menit' => 'required|integer|min:1',
            'alasan' => 'nullable',
        ]);

        $keterlambatan->update([
            'siswa_id' => $request->siswa_id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'tanggal' => $request->tanggal,
            'lama_menit' => $request->lama_menit,
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('keterlambatan.index')->with('success', 'Data keterlambatan berhasil diperbarui');
    }

    public function destroy(Keterlambatan $keterlambatan)
    {
        $keterlambatan->delete();
        return redirect()->route('keterlambatan.index')->with('success', 'Data keterlambatan berhasil dihapus');
    }
}
