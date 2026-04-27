<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'guru', 'tahun_akademik'])->latest()->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $mapels = MataPelajaran::all();
        $gurus = Guru::all();
        $tahun_akademiks = TahunAkademik::all();
        
        return view('admin.jadwal.create', compact('kelas', 'mapels', 'gurus', 'tahun_akademiks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'schedules' => 'required|array|min:1',
            'schedules.*.hari' => 'required',
            'schedules.*.jam_mulai' => 'required',
            'schedules.*.jam_selesai' => 'required|after:schedules.*.jam_mulai',
            'schedules.*.mapel_id' => 'required|exists:mata_pelajaran,id',
            'schedules.*.guru_id' => 'required|exists:guru,id',
        ]);

        foreach ($request->schedules as $item) {
            JadwalPelajaran::create([
                'kelas_id' => $request->kelas_id,
                'tahun_akademik_id' => $request->tahun_akademik_id,
                'hari' => $item['hari'],
                'jam_mulai' => $item['jam_mulai'],
                'jam_selesai' => $item['jam_selesai'],
                'mapel_id' => $item['mapel_id'],
                'guru_id' => $item['guru_id'],
            ]);
        }

        return redirect()->route('jadwal.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan');
    }

    public function edit(JadwalPelajaran $jadwal)
    {
        $kelas = Kelas::all();
        $mapels = MataPelajaran::all();
        $gurus = Guru::all();
        $tahun_akademiks = TahunAkademik::all();
        
        return view('admin.jadwal.edit', compact('jadwal', 'kelas', 'mapels', 'gurus', 'tahun_akademiks'));
    }

    public function update(Request $request, JadwalPelajaran $jadwal)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:guru,id',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal pelajaran berhasil diperbarui');
    }

    public function destroy(JadwalPelajaran $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal pelajaran berhasil dihapus');
    }
}
