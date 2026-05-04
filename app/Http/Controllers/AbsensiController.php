<?php

namespace App\Http\Controllers;

use App\Models\AbsensiHarian;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'guru']);
        
        if ($user && $user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $query->where('guru_id', $guru->id);
            }
        }
        
        $jadwals = $query->get();
        $selected_jadwal = $request->jadwal_id;
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $siswas = [];
        $jadwal = null;
        if ($selected_jadwal) {
            $jadwal = JadwalPelajaran::find($selected_jadwal);
            if ($jadwal) {
                $siswas = Siswa::where('kelas_id', $jadwal->kelas_id)->get();
                
                // Get existing attendance for these students on this date for this jadwal
                $existing_absensi = AbsensiHarian::whereIn('siswa_id', $siswas->pluck('id'))
                    ->where('tanggal', $tanggal)
                    ->where('jadwal_id', $selected_jadwal)
                    ->get()
                    ->keyBy('siswa_id');
                
                foreach ($siswas as $siswa) {
                    $siswa->absensi = $existing_absensi->get($siswa->id);
                }
            }
        }

        return view('admin.absensi.index', compact('jadwals', 'siswas', 'selected_jadwal', 'tanggal', 'jadwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jadwal_id' => 'required|exists:jadwal_pelajaran,id',
            'absensi' => 'required|array',
            'absensi.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        foreach ($request->absensi as $siswa_id => $data) {
            AbsensiHarian::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'tanggal' => $request->tanggal,
                    'jadwal_id' => $request->jadwal_id,
                ],
                [
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'] ?? null,
                    'pencatat_id' => Auth::id(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan');
    }
}
