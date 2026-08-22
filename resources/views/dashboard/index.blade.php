@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle')
    @if(isset($activePeriod))
        Selamat datang, {{ Auth::user()->name }} | Periode: {{ $activePeriod->tahun_ajaran }} - {{ $activePeriod->semester }}
    @else
        Selamat datang, {{ Auth::user()->name }}
    @endif
@endsection

@section('content')
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-9">
                <div class="row">
                    <!-- STATS FOR ADMIN/SUPERADMIN -->
                    @if (isset($stats))
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon purple">
                                                <i class="iconly-boldShow"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Guru</h6>
                                            <h6 class="font-extrabold mb-0">{{ $stats['total_guru'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon blue">
                                                <i class="iconly-boldProfile"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Siswa Aktif</h6>
                                            <h6 class="font-extrabold mb-0">{{ $stats['total_siswa'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon green">
                                                <i class="iconly-boldAdd-User"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Kelas</h6>
                                            <h6 class="font-extrabold mb-0">{{ $stats['total_kelas'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="stats-icon red">
                                                <i class="iconly-boldBookmark"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Pengumuman</h6>
                                            <h6 class="font-extrabold mb-0">{{ $stats['total_pengumuman'] }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- STATS FOR GURU PIKET -->
                    @if (isset($piket_stats))
                        <div class="col-6 col-lg-6 col-md-6">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="stats-icon white mb-2">
                                                <i class="bi bi-calendar-check-fill text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-10 text-white">
                                            <h6 class="text-white font-semibold">Absensi Hari Ini</h6>
                                            <h6 class="font-extrabold mb-0 text-white">{{ $piket_stats['absensi_today'] }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-6 col-md-6">
                            <div class="card bg-warning">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="stats-icon white mb-2">
                                                <i class="bi bi-alarm-fill text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-10 text-white">
                                            <h6 class="text-white font-semibold">Keterlambatan Hari Ini</h6>
                                            <h6 class="font-extrabold mb-0 text-white">{{ $piket_stats['terlambat_today'] }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- LAPORAN ABSENSI & KETERLAMBATAN FOR GURU PIKET -->
                    @if (isset($piket_stats))
                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Laporan Absensi & Keterlambatan</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('dashboard') }}" method="GET" id="laporan-filter-form">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="periode_id">Tahun Akademik</label>
                                                    <select name="periode_id" id="periode_id" class="form-select auto-submit-filter">
                                                        @foreach ($periodes_piket as $p)
                                                            <option value="{{ $p->id }}" {{ $periode_id_piket == $p->id ? 'selected' : '' }}>
                                                                {{ $p->tahun_ajaran }} - {{ $p->semester }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="kelas_id">Kelas</label>
                                                    <select name="kelas_id" id="kelas_id" class="form-select auto-submit-filter">
                                                        <option value="">Semua Kelas</option>
                                                        @foreach ($kelas_piket as $k)
                                                            <option value="{{ $k->id }}" {{ $selected_kelas_piket == $k->id ? 'selected' : '' }}>
                                                                {{ $k->nama_kelas }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="tanggal_mulai">Dari Tanggal</label>
                                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control auto-submit-filter" value="{{ $tanggal_mulai_piket }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="tanggal_selesai">Sampai Tanggal</label>
                                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control auto-submit-filter" value="{{ $tanggal_selesai_piket }}">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Hasil Rekapitulasi</h4>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('laporan.export-pdf', request()->all()) }}" class="btn btn-danger btn-export">
                                            <i class="bi bi-file-earmark-pdf icon-mid"></i> Export PDF
                                        </a>
                                        <a href="{{ route('laporan.export-excel', request()->all()) }}" class="btn btn-success btn-export">
                                            <i class="bi bi-file-earmark-excel icon-mid"></i> Export Excel
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Siswa</th>
                                                    <th>Kelas</th>
                                                    <th class="text-center bg-light-success">H</th>
                                                    <th class="text-center bg-light-warning">S</th>
                                                    <th class="text-center bg-light-info">I</th>
                                                    <th class="text-center bg-light-danger">A</th>
                                                    <th class="text-center">Terlambat</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($siswas_piket as $siswa)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $siswa->nama_lengkap }}</td>
                                                    <td>{{ $siswa->nama_kelas }}</td>
                                                    <td class="text-center">{{ $siswa->rekap_absensi['Hadir'] }}</td>
                                                    <td class="text-center">{{ $siswa->rekap_absensi['Sakit'] }}</td>
                                                    <td class="text-center">{{ $siswa->rekap_absensi['Izin'] }}</td>
                                                    <td class="text-center text-danger font-bold">{{ $siswa->rekap_absensi['Alpa'] }}</td>
                                                    <td class="text-center">
                                                        <span class="badge {{ $siswa->total_keterlambatan > 0 ? 'bg-danger' : 'bg-success' }}">
                                                            {{ $siswa->total_keterlambatan }}x
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-4 p-3 bg-light rounded">
                                        <small class="text-muted">
                                            <strong>Keterangan:</strong><br>
                                            H: Hadir | S: Sakit | I: Izin | A: Alpa (Tanpa Keterangan)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- STATS FOR SISWA -->
                    @if (isset($siswa_stats))
                        <div class="col-12 mb-2">
                            <h6 class="text-muted">Statistik Kehadiran Semester Ini</h6>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-3">
                                    <h6 class="text-muted font-semibold">Hadir</h6>
                                    <h4 class="font-extrabold mb-0 text-success">{{ $siswa_stats['hadir'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-3">
                                    <h6 class="text-muted font-semibold">Sakit</h6>
                                    <h4 class="font-extrabold mb-0 text-warning">{{ $siswa_stats['sakit'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-3">
                                    <h6 class="text-muted font-semibold">Izin</h6>
                                    <h4 class="font-extrabold mb-0 text-info">{{ $siswa_stats['izin'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body px-3 py-3">
                                    <h6 class="text-muted font-semibold">Alpa</h6>
                                    <h4 class="font-extrabold mb-0 text-danger">{{ $siswa_stats['alpa'] }}</h4>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- TODAY'S SCHEDULE -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Jadwal Hari Ini
                                    ({{ \App\Http\Controllers\DashboardController::translateDay(date('l')) }},
                                    {{ date('d F Y') }})</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-lg">
                                        <thead>
                                            <tr>
                                                <th>Jam</th>
                                                <th>Mata Pelajaran</th>
                                                @if (isset($today_schedule))
                                                    <th>Kelas</th>
                                                @endif
                                                @if (isset($today_schedule_siswa))
                                                    <th>Guru</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $schedules = isset($today_schedule)
                                                    ? $today_schedule
                                                    : (isset($today_schedule_siswa)
                                                        ? $today_schedule_siswa
                                                        : []);
                                            @endphp

                                            @forelse($schedules as $s)
                                                <tr>
                                                    <td class="col-3">
                                                        <div class="d-flex align-items-center">
                                                            <p class="font-bold mb-0 ms-3">
                                                                {{ substr($s->jam_mulai, 0, 5) }} -
                                                                {{ substr($s->jam_selesai, 0, 5) }}</p>
                                                        </div>
                                                    </td>
                                                    <td class="col-auto">
                                                        <p class=" mb-0">{{ $s->mata_pelajaran->nama_mapel }}</p>
                                                    </td>
                                                    @if (isset($today_schedule))
                                                        <td class="col-auto">
                                                            <p class=" mb-0">{{ $s->kelas->nama_kelas }}</p>
                                                        </td>
                                                    @endif
                                                    @if (isset($today_schedule_siswa))
                                                        <td class="col-auto">
                                                            <p class=" mb-0">{{ $s->guru->nama }}</p>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted italic">Tidak ada
                                                        jadwal hari ini.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LATEST ATTENDANCE FOR SISWA -->
                @if (isset($latest_absensi_siswa))
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4>Absensi Terbaru</h4>
                                    <a href="{{ route('siswa.my-absensi') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-lg">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($latest_absensi_siswa as $la)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($la->tanggal)->format('d/m/Y') }}</td>
                                                        <td>{{ $la->jadwal->mata_pelajaran->nama_mapel ?? '-' }}</td>
                                                        <td>
                                                            @if ($la->status == 'Hadir')
                                                                <span class="badge bg-success">H</span>
                                                            @elseif($la->status == 'Sakit')
                                                                <span class="badge bg-warning">S</span>
                                                            @elseif($la->status == 'Izin')
                                                                <span class="badge bg-info">I</span>
                                                            @else
                                                                <span class="badge bg-danger">A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted italic">Belum ada data absensi pada periode ini.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Profil</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                <img src="{{ asset('assets/images/faces/user-default.png') }}" alt="Face 1">
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                                <h6 class="text-muted mb-0">{{ strtoupper(str_replace('_', ' ', Auth::user()->role)) }}
                                </h6>
                                @if(isset($kelas_siswa))
                                    <span class="badge bg-light-primary mt-2">{{ $kelas_siswa->nama_kelas }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ANNOUNCEMENTS -->
                <div class="card">
                    <div class="card-header">
                        <h4>Pengumuman Terbaru</h4>
                    </div>
                    <div class="card-content pb-4">
                        @forelse($pengumuman as $p)
                            <div class="recent-message d-flex px-4 py-3">
                                <div class="avatar avatar-lg">
                                    <i class="bi bi-bell-fill text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <div class="name ms-4">
                                    <h5 class="mb-1">{{ $p->judul }}</h5>
                                    <h6 class="text-muted mb-0 small">
                                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</h6>
                                    <a href="{{ route('pengumuman.show', $p->id) }}" class="small">Baca selengkapnya</a>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-3 text-muted italic">Belum ada pengumuman.</div>
                        @endforelse

                        <div class="px-4">
                            <a href="{{ route('pengumuman.index') }}"
                                class='btn btn-block btn-xl btn-primary font-bold mt-3'>Lihat Semua</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('laporan-filter-form');
        if (form) {
            document.querySelectorAll('.auto-submit-filter').forEach(function (field) {
                field.addEventListener('change', function () {
                    form.submit();
                });
            });
        }
    });
</script>
@endpush
@endsection
