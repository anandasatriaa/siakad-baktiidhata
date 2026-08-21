<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\JadwalPelajaran;
use App\Models\AbsensiHarian;
use App\Models\Keterlambatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $activePeriod = \App\Models\TahunAkademik::where('is_active', true)->first();
        
        $data = [
            'pengumuman' => Pengumuman::with('penulis')->latest()->take(5)->get(),
            'activePeriod' => $activePeriod,
        ];

        if (in_array($user->role, ['super_admin', 'admin', 'kepala_sekolah'])) {
            $data['stats'] = [
                'total_guru' => Guru::count(),
                'total_siswa' => $activePeriod ? \App\Models\AnggotaKelas::where('tahun_akademik_id', $activePeriod->id)->distinct('siswa_id')->count() : Siswa::count(),
                'total_kelas' => Kelas::count(),
                'total_pengumuman' => Pengumuman::count(),
            ];
        }

        if (in_array($user->role, ['super_admin', 'guru'])) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru && $activePeriod) {
                $myClassesIds = JadwalPelajaran::where('guru_id', $guru->id)
                    ->where('tahun_akademik_id', $activePeriod->id)
                    ->pluck('kelas_id')
                    ->unique();

                $data['guru_stats'] = [
                    'total_jadwal' => JadwalPelajaran::where('guru_id', $guru->id)->where('tahun_akademik_id', $activePeriod->id)->count(),
                    'total_siswa' => \App\Models\AnggotaKelas::whereIn('kelas_id', $myClassesIds)
                        ->where('tahun_akademik_id', $activePeriod->id)
                        ->distinct('siswa_id')
                        ->count(),
                ];
                $data['today_schedule'] = JadwalPelajaran::with(['kelas', 'mata_pelajaran'])
                    ->where('guru_id', $guru->id)
                    ->where('tahun_akademik_id', $activePeriod->id)
                    ->where('hari', self::translateDay(date('l')))
                    ->orderBy('jam_mulai')
                    ->get();
            }
        }

        if (in_array($user->role, ['super_admin', 'siswa'])) {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa && $activePeriod) {
                $anggotaKelas = \App\Models\AnggotaKelas::with('kelas')
                    ->where('siswa_id', $siswa->id)
                    ->where('tahun_akademik_id', $activePeriod->id)
                    ->first();
                
                $data['siswa'] = $siswa;
                $data['kelas_siswa'] = $anggotaKelas->kelas ?? null;

                $data['siswa_stats'] = [
                    'hadir' => AbsensiHarian::where('siswa_id', $siswa->id)
                        ->whereHas('jadwal', function($q) use ($activePeriod) {
                            $q->where('tahun_akademik_id', $activePeriod->id);
                        })->where('status', 'Hadir')->count(),
                    'sakit' => AbsensiHarian::where('siswa_id', $siswa->id)
                         ->whereHas('jadwal', function($q) use ($activePeriod) {
                            $q->where('tahun_akademik_id', $activePeriod->id);
                        })->where('status', 'Sakit')->count(),
                    'izin' => AbsensiHarian::where('siswa_id', $siswa->id)
                         ->whereHas('jadwal', function($q) use ($activePeriod) {
                            $q->where('tahun_akademik_id', $activePeriod->id);
                        })->where('status', 'Izin')->count(),
                    'alpa' => AbsensiHarian::where('siswa_id', $siswa->id)
                         ->whereHas('jadwal', function($q) use ($activePeriod) {
                            $q->where('tahun_akademik_id', $activePeriod->id);
                        })->where('status', 'Alpa')->count(),
                ];

                if ($anggotaKelas) {
                    $data['today_schedule_siswa'] = JadwalPelajaran::with(['mata_pelajaran', 'guru'])
                        ->where('kelas_id', $anggotaKelas->kelas_id)
                        ->where('tahun_akademik_id', $activePeriod->id)
                        ->where('hari', self::translateDay(date('l')))
                        ->orderBy('jam_mulai')
                        ->get();
                }

                $data['latest_absensi_siswa'] = AbsensiHarian::with(['jadwal.mata_pelajaran'])
                    ->where('siswa_id', $siswa->id)
                    ->whereHas('jadwal', function($q) use ($activePeriod) {
                        $q->where('tahun_akademik_id', $activePeriod->id);
                    })
                    ->latest('tanggal')
                    ->take(5)
                    ->get();
            }
        }

        if (in_array($user->role, ['super_admin', 'guru_piket'])) {
            $data['piket_stats'] = [
                'absensi_today' => AbsensiHarian::whereDate('tanggal', Carbon::today())->count(),
                'terlambat_today' => Keterlambatan::whereDate('tanggal', Carbon::today())->count(),
            ];

            // Tambahkan rekap data khusus untuk laporan di dashboard
            $periode_id_piket = $request->periode_id ?? ($activePeriod->id ?? null);
            $tahun_akademik_piket = \App\Models\TahunAkademik::find($periode_id_piket) ?? $activePeriod;
            
            $data['periodes_piket'] = \App\Models\TahunAkademik::orderBy('tahun_ajaran', 'desc')->get();
            $data['kelas_piket'] = Kelas::where('tahun_akademik_id', $periode_id_piket)->get();
            
            $selected_kelas_piket = $request->kelas_id;
            $data['info_kelas_piket'] = $selected_kelas_piket ? Kelas::find($selected_kelas_piket) : null;
            $data['tanggal_mulai_piket'] = $request->tanggal_mulai ?? date('Y-m-01');
            $data['tanggal_selesai_piket'] = $request->tanggal_selesai ?? date('Y-m-d');

            $query_anggota = \App\Models\AnggotaKelas::with(['siswa', 'kelas'])
                ->where('tahun_akademik_id', $periode_id_piket);

            if ($selected_kelas_piket) {
                $query_anggota->where('kelas_id', $selected_kelas_piket);
            }

            $anggota_kelas = $query_anggota->get();
            $siswas_piket = [];

            foreach ($anggota_kelas as $ak) {
                $siswa_piket = $ak->siswa;
                $siswa_piket->nama_kelas = $ak->kelas->nama_kelas;

                $absensi = AbsensiHarian::where('siswa_id', $siswa_piket->id)
                    ->whereBetween('tanggal', [$data['tanggal_mulai_piket'], $data['tanggal_selesai_piket']])
                    ->get();
                
                $siswa_piket->rekap_absensi = [
                    'Hadir' => $absensi->where('status', 'Hadir')->count(),
                    'Sakit' => $absensi->where('status', 'Sakit')->count(),
                    'Izin' => $absensi->where('status', 'Izin')->count(),
                    'Alpa' => $absensi->where('status', 'Alpa')->count(),
                ];

                $keterlambatan = Keterlambatan::where('siswa_id', $siswa_piket->id)
                    ->whereBetween('tanggal', [$data['tanggal_mulai_piket'], $data['tanggal_selesai_piket']])
                    ->get();
                
                $siswa_piket->total_keterlambatan = $keterlambatan->count();
                $siswa_piket->total_menit = $keterlambatan->sum('lama_menit');
                
                $siswas_piket[] = $siswa_piket;
            }

            $data['siswas_piket'] = $siswas_piket;
            $data['selected_kelas_piket'] = $selected_kelas_piket;
            $data['periode_id_piket'] = $periode_id_piket;
            $data['tahun_akademik_piket'] = $tahun_akademik_piket;
        }

        return view('dashboard.index', $data);
    }

    public static function translateDay($day)
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        return $days[$day] ?? $day;
    }
}
