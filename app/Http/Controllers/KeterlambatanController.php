<?php

namespace App\Http\Controllers;

use App\Models\Keterlambatan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeterlambatanController extends Controller
{
    public function index()
    {
        $keterlambatans = Keterlambatan::with('siswa.kelas')->latest()->get();
        return view('admin.keterlambatan.index', compact('keterlambatans'));
    }

    public function create()
    {
        $siswas = Siswa::with('kelas')->get();
        return view('admin.keterlambatan.create', compact('siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'lama_menit' => 'required|integer|min:1',
            'alasan' => 'nullable',
        ]);

        Keterlambatan::create([
            'siswa_id' => $request->siswa_id,
            'tanggal' => $request->tanggal,
            'lama_menit' => $request->lama_menit,
            'alasan' => $request->alasan,
            'pencatat_id' => Auth::id(),
        ]);

        return redirect()->route('keterlambatan.index')->with('success', 'Data keterlambatan berhasil ditambahkan');
    }

    public function edit(Keterlambatan $keterlambatan)
    {
        $siswas = Siswa::with('kelas')->get();
        return view('admin.keterlambatan.edit', compact('keterlambatan', 'siswas'));
    }

    public function update(Request $request, Keterlambatan $keterlambatan)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'lama_menit' => 'required|integer|min:1',
            'alasan' => 'nullable',
        ]);

        $keterlambatan->update([
            'siswa_id' => $request->siswa_id,
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
