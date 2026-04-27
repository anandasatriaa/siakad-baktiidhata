<?php

namespace App\Http\Controllers;

use App\Models\AbsensiHarian;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $selected_kelas = $request->kelas_id;
        $tanggal = $request->tanggal ?? date('Y-m-d');

        $siswas = [];
        if ($selected_kelas) {
            $siswas = Siswa::where('kelas_id', $selected_kelas)->get();
            
            // Get existing attendance for these students on this date
            $existing_absensi = AbsensiHarian::whereIn('siswa_id', $siswas->pluck('id'))
                ->where('tanggal', $tanggal)
                ->get()
                ->keyBy('siswa_id');
            
            foreach ($siswas as $siswa) {
                $siswa->absensi = $existing_absensi->get($siswa->id);
            }
        }

        return view('admin.absensi.index', compact('kelas', 'siswas', 'selected_kelas', 'tanggal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        foreach ($request->absensi as $siswa_id => $data) {
            AbsensiHarian::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'tanggal' => $request->tanggal,
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
