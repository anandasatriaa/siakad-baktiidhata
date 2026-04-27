@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang kembali, ' . Auth::user()->name)

@section('content')
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <!-- STATS FOR ADMIN/SUPERADMIN -->
                @if(isset($stats))
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
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
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Siswa</h6>
                                    <h6 class="font-extrabold mb-0">{{ $stats['total_siswa'] }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
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
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon red">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Info</h6>
                                    <h6 class="font-extrabold mb-0">{{ $stats['total_pengumuman'] }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- STATS FOR GURU PIKET -->
                @if(isset($piket_stats))
                <div class="col-6 col-lg-6 col-md-6">
                    <div class="card bg-primary">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon white mb-2">
                                        <i class="bi bi-calendar-check-fill text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 text-white">
                                    <h6 class="text-white font-semibold">Absensi Hari Ini</h6>
                                    <h6 class="font-extrabold mb-0 text-white">{{ $piket_stats['absensi_today'] }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-6 col-md-6">
                    <div class="card bg-warning">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon white mb-2">
                                        <i class="bi bi-alarm-fill text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 text-white">
                                    <h6 class="text-white font-semibold">Keterlambatan Hari Ini</h6>
                                    <h6 class="font-extrabold mb-0 text-white">{{ $piket_stats['terlambat_today'] }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- STATS FOR SISWA -->
                @if(isset($siswa_stats))
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
                            <h4>Jadwal Hari Ini ({{ \App\Http\Controllers\DashboardController::translateDay(date('l')) }})</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Jam</th>
                                            <th>Mata Pelajaran</th>
                                            @if(isset($today_schedule)) <th>Kelas</th> @endif
                                            @if(isset($today_schedule_siswa)) <th>Guru</th> @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $schedules = isset($today_schedule) ? $today_schedule : (isset($today_schedule_siswa) ? $today_schedule_siswa : []);
                                        @endphp

                                        @forelse($schedules as $s)
                                        <tr>
                                            <td class="col-3">
                                                <div class="d-flex align-items-center">
                                                    <p class="font-bold mb-0 ms-3">{{ substr($s->jam_mulai, 0, 5) }} - {{ substr($s->jam_selesai, 0, 5) }}</p>
                                                </div>
                                            </td>
                                            <td class="col-auto">
                                                <p class=" mb-0">{{ $s->mata_pelajaran->nama_mapel }}</p>
                                            </td>
                                            @if(isset($today_schedule))
                                            <td class="col-auto">
                                                <p class=" mb-0">{{ $s->kelas->nama_kelas }}</p>
                                            </td>
                                            @endif
                                            @if(isset($today_schedule_siswa))
                                            <td class="col-auto">
                                                <p class=" mb-0">{{ $s->guru->nama_lengkap }}</p>
                                            </td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted italic">Tidak ada jadwal hari ini.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h4>Profil</h4>
                </div>
                <div class="card-body py-4 px-5">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-xl">
                            <img src="{{ asset('assets/images/faces/1.jpg') }}" alt="Face 1">
                        </div>
                        <div class="ms-3 name">
                            <h5 class="font-bold">{{ Auth::user()->name }}</h5>
                            <h6 class="text-muted mb-0">{{ strtoupper(str_replace('_', ' ', Auth::user()->role)) }}</h6>
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
                            <h6 class="text-muted mb-0 small">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</h6>
                            <a href="{{ route('pengumuman.show', $p->id) }}" class="small">Baca selengkapnya</a>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-3 text-muted italic">Belum ada pengumuman.</div>
                    @endforelse

                    <div class="px-4">
                        <a href="{{ route('pengumuman.index') }}" class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
