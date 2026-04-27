<?php

namespace App\Http\Controllers;

use App\Models\AgendaMengajar;
use App\Models\JadwalPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = AgendaMengajar::with(['guru', 'jadwal.kelas', 'jadwal.mata_pelajaran']);

        if ($user->role == 'guru') {
            $query->whereHas('guru', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $agendas = $query->latest()->get();
        return view('admin.agenda.index', compact('agendas'));
    }

    public function create()
    {
        $user = Auth::user();
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran']);

        if ($user->role == 'guru') {
            $query->whereHas('guru', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $jadwals = $query->get();
        return view('admin.agenda.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'tanggal' => 'required|date',
            'materi' => 'required',
            'keterangan' => 'nullable',
        ]);

        $jadwal = JadwalPelajaran::find($request->jadwal_id);

        AgendaMengajar::create([
            'guru_id' => $jadwal->guru_id,
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => $request->tanggal,
            'materi' => $request->materi,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('agenda.index')->with('success', 'Agenda mengajar berhasil ditambahkan');
    }

    public function edit(AgendaMengajar $agenda)
    {
        $user = Auth::user();
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran']);

        if ($user->role == 'guru') {
            $query->whereHas('guru', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $jadwals = $query->get();
        return view('admin.agenda.edit', compact('agenda', 'jadwals'));
    }

    public function update(Request $request, AgendaMengajar $agenda)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'tanggal' => 'required|date',
            'materi' => 'required',
            'keterangan' => 'nullable',
        ]);

        $jadwal = JadwalPelajaran::find($request->jadwal_id);

        $agenda->update([
            'guru_id' => $jadwal->guru_id,
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => $request->tanggal,
            'materi' => $request->materi,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('agenda.index')->with('success', 'Agenda mengajar berhasil diperbarui');
    }

    public function destroy(AgendaMengajar $agenda)
    {
        $agenda->delete();
        return redirect()->route('agenda.index')->with('success', 'Agenda mengajar berhasil dihapus');
    }
}
