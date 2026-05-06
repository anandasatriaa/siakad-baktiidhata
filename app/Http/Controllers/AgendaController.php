<?php

namespace App\Http\Controllers;

use App\Models\AgendaMengajar;
use App\Models\JadwalPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);

        $query = AgendaMengajar::with(['guru', 'jadwal.kelas', 'jadwal.mata_pelajaran', 'tahunAkademik']);

        if ($user->role == 'guru') {
            $query->whereHas('guru', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($periode_id) {
            $query->where('tahun_akademik_id', $periode_id);
        }

        $agendas = $query->latest()->get();
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.agenda.index', compact('agendas', 'periodes', 'periode_id'));
    }

    public function create()
    {
        $user = Auth::user();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran']);

        if ($user->role == 'guru') {
            $query->whereHas('guru', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Hanya tampilkan jadwal untuk periode aktif saat membuat agenda baru
        if ($active_periode) {
            $query->where('tahun_akademik_id', $active_periode->id);
        }

        $jadwals = $query->get();
        return view('admin.agenda.create', compact('jadwals', 'active_periode'));
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
            'tahun_akademik_id' => $jadwal->tahun_akademik_id,
            'tanggal' => $request->tanggal,
            'materi' => $request->materi,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('agenda.index')->with('success', 'Agenda mengajar berhasil ditambahkan');
    }

    public function edit(AgendaMengajar $agenda)
    {
        $user = Auth::user();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();

        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran']);

        if ($user->role == 'guru') {
            $query->whereHas('guru', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Tampilkan jadwal sesuai periode agenda tersebut
        $query->where('tahun_akademik_id', $agenda->tahun_akademik_id);

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
            'tahun_akademik_id' => $jadwal->tahun_akademik_id,
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
