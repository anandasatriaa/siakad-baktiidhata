<?php

namespace App\Http\Controllers;

use App\Models\Keterlambatan;
use App\Models\Siswa;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $pengaturanJamMasuk = Pengaturan::where('key', 'jam_masuk_sekolah')->first();
        $jam_masuk_sekolah = $pengaturanJamMasuk ? $pengaturanJamMasuk->value : '06:30';

        return view('admin.keterlambatan.index', compact('keterlambatans', 'periodes', 'periode_id', 'jam_masuk_sekolah'));
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
            'waktu_kedatangan' => 'required',
            'alasan' => 'nullable',
        ]);

        $pengaturanJamMasuk = Pengaturan::where('key', 'jam_masuk_sekolah')->first();
        $jam_masuk_sekolah = $pengaturanJamMasuk ? $pengaturanJamMasuk->value : '06:30';

        $waktu_kedatangan = Carbon::parse($request->waktu_kedatangan);
        $waktu_batas = Carbon::parse($jam_masuk_sekolah);

        if ($waktu_kedatangan->lessThanOrEqualTo($waktu_batas)) {
            return back()->withErrors(['waktu_kedatangan' => 'Waktu kedatangan tidak boleh sebelum atau sama dengan jam masuk sekolah (' . $jam_masuk_sekolah . ')'])->withInput();
        }

        $lama_menit = $waktu_kedatangan->diffInMinutes($waktu_batas);

        Keterlambatan::create([
            'siswa_id' => $request->siswa_id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'tanggal' => $request->tanggal,
            'waktu_kedatangan' => $request->waktu_kedatangan,
            'lama_menit' => $lama_menit,
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
            'waktu_kedatangan' => 'required',
            'alasan' => 'nullable',
        ]);

        $pengaturanJamMasuk = Pengaturan::where('key', 'jam_masuk_sekolah')->first();
        $jam_masuk_sekolah = $pengaturanJamMasuk ? $pengaturanJamMasuk->value : '06:30';

        $waktu_kedatangan = Carbon::parse($request->waktu_kedatangan);
        $waktu_batas = Carbon::parse($jam_masuk_sekolah);

        if ($waktu_kedatangan->lessThanOrEqualTo($waktu_batas)) {
            return back()->withErrors(['waktu_kedatangan' => 'Waktu kedatangan tidak boleh sebelum atau sama dengan jam masuk sekolah (' . $jam_masuk_sekolah . ')'])->withInput();
        }

        $lama_menit = $waktu_kedatangan->diffInMinutes($waktu_batas);

        $keterlambatan->update([
            'siswa_id' => $request->siswa_id,
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'tanggal' => $request->tanggal,
            'waktu_kedatangan' => $request->waktu_kedatangan,
            'lama_menit' => $lama_menit,
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('keterlambatan.index')->with('success', 'Data keterlambatan berhasil diperbarui');
    }

    public function destroy(Keterlambatan $keterlambatan)
    {
        $keterlambatan->delete();
        return redirect()->route('keterlambatan.index')->with('success', 'Data keterlambatan berhasil dihapus');
    }

    public function updateJamMasuk(Request $request)
    {
        $request->validate([
            'jam_masuk_sekolah' => 'required'
        ]);

        Pengaturan::updateOrCreate(
            ['key' => 'jam_masuk_sekolah'],
            ['value' => $request->jam_masuk_sekolah]
        );

        return redirect()->back()->with('success', 'Jam masuk sekolah berhasil diperbarui');
    }
}
