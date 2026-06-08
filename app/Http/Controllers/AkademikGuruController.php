<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NilaiExport;

class AkademikGuruController extends Controller
{
    private function getGuru()
    {
        $user = Auth::user();
        if ($user->role == 'super_admin') return null;
        return Guru::where('user_id', $user->id)->first();
    }

    public function jadwal(Request $request)
    {
        $guru = $this->getGuru();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);

        $query = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'tahun_akademik']);

        if ($guru) {
            $query->where('guru_id', $guru->id);
        }

        if ($periode_id) {
            $query->where('tahun_akademik_id', $periode_id);
        }

        $jadwals = $query->get()->groupBy('hari');
        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('guru.jadwal', compact('jadwals', 'days', 'periodes', 'periode_id', 'active_periode'));
    }

    public function dataSiswa(Request $request)
    {
        $guru = $this->getGuru();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);
        
        $query = JadwalPelajaran::query();
        if ($guru) {
            $query->where('guru_id', $guru->id);
        }
        if ($periode_id) {
            $query->where('tahun_akademik_id', $periode_id);
        }

        $jadwals = $query->with('kelas')->get();
        $kelas_ids = $jadwals->pluck('kelas_id')->unique();
        
        // Query melalui AnggotaKelas sesuai periode yang dipilih
        $siswas = Siswa::whereHas('riwayatKelas', function($q) use ($kelas_ids, $periode_id) {
            $q->whereIn('kelas_id', $kelas_ids);
            if ($periode_id) {
                $q->where('tahun_akademik_id', $periode_id);
            }
        })->with(['riwayatKelas' => function($q) use ($periode_id) {
            if ($periode_id) $q->where('tahun_akademik_id', $periode_id);
            $q->with('kelas');
        }])->get()->groupBy(function($s) {
            // Group berdasarkan nama kelas agar lebih informatif
            return $s->riwayatKelas->first()->kelas->nama_kelas ?? 'Tanpa Kelas';
        });

        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();

        return view('guru.data-siswa', compact('siswas', 'jadwals', 'periodes', 'periode_id'));
    }

    public function rekapNilai(Request $request)
    {
        $guru = $this->getGuru();
        $active_periode = \App\Models\TahunAkademik::where('is_active', true)->first();
        $periode_id = $request->periode_id ?? ($active_periode->id ?? null);

        // Kelas yang diajar oleh guru
        $kelas_mengajar_ids = [];
        if ($guru) {
            $kelas_mengajar_ids = JadwalPelajaran::where('guru_id', $guru->id)
                ->where('tahun_akademik_id', $periode_id)
                ->pluck('kelas_id')
                ->toArray();
        }

        // Kelas dimana guru adalah wali kelas
        $kelas_wali_ids = \App\Models\Kelas::where('wali_kelas_id', Auth::id())
            ->where('tahun_akademik_id', $periode_id)
            ->pluck('id')
            ->toArray();

        $all_kelas_ids = array_unique(array_merge($kelas_mengajar_ids, $kelas_wali_ids));
        $daftar_kelas = \App\Models\Kelas::whereIn('id', $all_kelas_ids)->get();

        $periodes = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
        
        $selected_kelas = $request->kelas_id;

        $nilais_matrix = [];
        $mapels = collect();
        $info_kelas = null;
        $is_wali_kelas = false;

        if ($selected_kelas) {
            $info_kelas = \App\Models\Kelas::find($selected_kelas);
            if ($info_kelas && $info_kelas->wali_kelas_id == Auth::id() && $info_kelas->tahun_akademik_id == $periode_id) {
                $is_wali_kelas = true;
            }

            // Ambil semua mapel di kelas ini pada periode ini
            $mapels = JadwalPelajaran::with('mata_pelajaran')
                ->where('kelas_id', $selected_kelas)
                ->where('tahun_akademik_id', $periode_id)
                ->get()
                ->pluck('mata_pelajaran')
                ->unique('id');

            // Ambil siswa kelas ini
            $siswas_kelas = \App\Models\AnggotaKelas::with('siswa')
                ->where('kelas_id', $selected_kelas)
                ->where('tahun_akademik_id', $periode_id)
                ->get();

            // Ambil semua nilai di kelas ini
            $all_nilais = Nilai::where('kelas_id', $selected_kelas)
                ->where('tahun_akademik_id', $periode_id)
                ->get()
                ->groupBy('siswa_id');

            foreach ($siswas_kelas as $ak) {
                $siswa_nilais = $all_nilais->get($ak->siswa_id, collect());
                $nilai_per_mapel = [];
                
                foreach ($mapels as $mapel) {
                    $n = $siswa_nilais->firstWhere('mapel_id', $mapel->id);
                    $nilai_per_mapel[$mapel->id] = $n ? $n->nilai_akhir : null;
                }

                $nilais_matrix[] = (object)[
                    'siswa' => $ak->siswa,
                    'nilai_per_mapel' => $nilai_per_mapel,
                ];
            }
        }

        return view('guru.rekap-nilai', compact('daftar_kelas', 'kelas_wali_ids', 'nilais_matrix', 'mapels', 'selected_kelas', 'info_kelas', 'periodes', 'periode_id', 'is_wali_kelas'));
    }

    public function exportPdf(Request $request)
    {
        $siswa_id = $request->siswa_id;
        $kelas_id = $request->kelas_id;
        $periode_id = $request->periode_id;

        $kelas = \App\Models\Kelas::findOrFail($kelas_id);
        if ($kelas->wali_kelas_id != Auth::id()) {
            abort(403, 'Anda bukan wali kelas untuk kelas ini.');
        }

        $siswa = Siswa::findOrFail($siswa_id);
        $tahun_akademik = \App\Models\TahunAkademik::findOrFail($periode_id);

        $nilais = Nilai::with('mata_pelajaran')
            ->where('siswa_id', $siswa_id)
            ->where('kelas_id', $kelas_id)
            ->where('tahun_akademik_id', $periode_id)
            ->get();

        $guru_wali = Guru::where('user_id', Auth::id())->first();
        $nama_wali = $guru_wali ? $guru_wali->nama : Auth::user()->name;
        $nip_wali = $guru_wali ? $guru_wali->nip : '-';

        $pdf = Pdf::loadView('guru.export.nilai-pdf', [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'tahun_akademik' => $tahun_akademik,
            'nilais' => $nilais,
            'nama_wali' => $nama_wali,
            'nip_wali' => $nip_wali,
            'logoPath' => public_path('assets/images/logo/logo-smkbaktiidhata.png'),
            'sekolah' => [
                'nama' => 'SMK BAKTI IDHATA',
                'yayasan' => 'YAYASAN PENDIDIKAN BAKTI IDHATA',
                'alamat' => 'Jl. Melati No. 25, Cilandak Barat, Jakarta Selatan',
                'kontak' => 'Telp. (021) 7500000 | Email: info@smkbaktiidhata.sch.id',
                'website' => 'www.smkbaktiidhata.sch.id',
            ],
        ])->setPaper('a4', 'portrait');
        
        return $pdf->download('Rapor_' . $siswa->nama_lengkap . '_' . $kelas->nama_kelas . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $siswa_id = $request->siswa_id;
        $kelas_id = $request->kelas_id;
        $periode_id = $request->periode_id;

        $kelas = \App\Models\Kelas::findOrFail($kelas_id);
        if ($kelas->wali_kelas_id != Auth::id()) {
            abort(403, 'Anda bukan wali kelas untuk kelas ini.');
        }

        $siswa = Siswa::findOrFail($siswa_id);

        return Excel::download(new NilaiExport($siswa_id, $kelas_id, $periode_id), 'Rapor_' . $siswa->nama_lengkap . '_' . $kelas->nama_kelas . '.xlsx');
    }
}
