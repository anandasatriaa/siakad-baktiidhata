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
    public function index()
    {
        $user = Auth::user();
        $data = [
            'pengumuman' => Pengumuman::with('penulis')->latest()->take(5)->get(),
        ];

        if (in_array($user->role, ['super_admin', 'admin', 'kepala_sekolah'])) {
            $data['stats'] = [
                'total_guru' => Guru::count(),
                'total_siswa' => Siswa::count(),
                'total_kelas' => Kelas::count(),
                'total_pengumuman' => Pengumuman::count(),
            ];
        }

        if (in_array($user->role, ['super_admin', 'guru'])) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $data['guru_stats'] = [
                    'total_jadwal' => JadwalPelajaran::where('guru_id', $guru->id)->count(),
                    'total_siswa' => Siswa::whereIn('kelas_id', JadwalPelajaran::where('guru_id', $guru->id)->pluck('kelas_id'))->count(),
                ];
                $data['today_schedule'] = JadwalPelajaran::with(['kelas', 'mata_pelajaran'])
                    ->where('guru_id', $guru->id)
                    ->where('hari', self::translateDay(date('l')))
                    ->orderBy('jam_mulai')
                    ->get();
            }
        }

        if (in_array($user->role, ['super_admin', 'siswa'])) {
            $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
            if ($siswa) {
                $data['siswa'] = $siswa;
                $data['siswa_stats'] = [
                    'hadir' => AbsensiHarian::where('siswa_id', $siswa->id)->where('status', 'Hadir')->count(),
                    'sakit' => AbsensiHarian::where('siswa_id', $siswa->id)->where('status', 'Sakit')->count(),
                    'izin' => AbsensiHarian::where('siswa_id', $siswa->id)->where('status', 'Izin')->count(),
                    'alpa' => AbsensiHarian::where('siswa_id', $siswa->id)->where('status', 'Alpa')->count(),
                ];
                $data['today_schedule_siswa'] = JadwalPelajaran::with(['mata_pelajaran', 'guru'])
                    ->where('kelas_id', $siswa->kelas_id)
                    ->where('hari', self::translateDay(date('l')))
                    ->orderBy('jam_mulai')
                    ->get();
            }
        }

        if (in_array($user->role, ['super_admin', 'guru_piket'])) {
            $data['piket_stats'] = [
                'absensi_today' => AbsensiHarian::whereDate('tanggal', Carbon::today())->count(),
                'terlambat_today' => Keterlambatan::whereDate('tanggal', Carbon::today())->count(),
            ];
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
