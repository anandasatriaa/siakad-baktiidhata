<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                        <img src="{{ asset('assets/images/logo/logo-smkbaktiidhata.png') }}" alt="Logo"
                            style="height: 3.5rem !important; width: auto;">
                        <div class="ms-2">
                            <h5 class="mb-0"
                                style="font-weight: 800; color: #435ebe; font-size: 1.2rem; line-height: 1;">SIAKAD</h5>
                            <span
                                style="font-size: 0.65rem; font-weight: 700; color: #7c8db5; text-transform: uppercase; letter-spacing: 0.5px;">SMK
                                Bakti Idhata</span>
                        </div>
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu Utama</li>

                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- KEPALA SEKOLAH / ADMIN -->
                @if (in_array(Auth::user()->role, ['super_admin', 'admin', 'kepala_sekolah']))
                    <li class="sidebar-title">Data Master</li>
                    <li class="sidebar-item {{ request()->routeIs('siswa.*') ? 'active' : '' }}">
                        <a href="{{ route('siswa.index') }}" class='sidebar-link'>
                            <i class="bi bi-stack"></i>
                            <span>Data Siswa</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('guru.*') ? 'active' : '' }}">
                        <a href="{{ route('guru.index') }}" class='sidebar-link'>
                            <i class="bi bi-person-badge-fill"></i>
                            <span>Data Guru</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('kelas.*') ? 'active' : '' }}">
                        <a href="{{ route('kelas.index') }}" class='sidebar-link'>
                            <i class="bi bi-house-door-fill"></i>
                            <span>Data Kelas</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('mapel.*') ? 'active' : '' }}">
                        <a href="{{ route('mapel.index') }}" class='sidebar-link'>
                            <i class="bi bi-book-fill"></i>
                            <span>Data Mata Pelajaran</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                        <a href="{{ route('jadwal.index') }}" class='sidebar-link'>
                            <i class="bi bi-calendar-day"></i>
                            <span>Jadwal Pelajaran</span>
                        </a>
                    </li>
                    <li class="sidebar-title">Laporan</li>
                    <li class="sidebar-item {{ request()->routeIs('guru.rekap-nilai') ? 'active' : '' }}">
                        <a href="{{ route('guru.rekap-nilai') }}" class='sidebar-link'>
                            <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                            <span>Hasil Nilai Siswa</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-file-earmark-person-fill"></i>
                            <span>Laporan Absensi & Pelanggaran</span>
                        </a>
                    </li>
                @endif

                <!-- GURU -->
                @if (in_array(Auth::user()->role, ['super_admin', 'guru']))
                    <li class="sidebar-title">Akademik Guru</li>
                    <li class="sidebar-item {{ request()->routeIs('guru.jadwal-mengajar') ? 'active' : '' }}">
                        <a href="{{ route('guru.jadwal-mengajar') }}" class='sidebar-link'>
                            <i class="bi bi-calendar-week-fill"></i>
                            <span>Jadwal Mengajar</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('nilai.*') ? 'active' : '' }}">
                        <a href="{{ route('nilai.index') }}" class='sidebar-link'>
                            <i class="bi bi-pen-fill"></i>
                            <span>Input Nilai</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-journal-check"></i>
                            <span>Hasil Nilai Siswa</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('guru.data-siswa-ajar') ? 'active' : '' }}">
                        <a href="{{ route('guru.data-siswa-ajar') }}" class='sidebar-link'>
                            <i class="bi bi-people-fill"></i>
                            <span>Data Siswa</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-printer-fill"></i>
                            <span>Cetak / Ekspor Nilai</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('agenda.*') ? 'active' : '' }}">
                        <a href="{{ route('agenda.index') }}" class='sidebar-link'>
                            <i class="bi bi-file-earmark-medical-fill"></i>
                            <span>Agenda Mengajar</span>
                        </a>
                    </li>
                @endif

                <!-- GURU PIKET -->
                @if (in_array(Auth::user()->role, ['super_admin', 'guru_piket']))
                    <li class="sidebar-title">Piket</li>
                    <li class="sidebar-item {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                        <a href="{{ route('absensi.index') }}" class='sidebar-link'>
                            <i class="bi bi-clipboard-check-fill"></i>
                            <span>Input Absensi Harian</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('keterlambatan.*') ? 'active' : '' }}">
                        <a href="{{ route('keterlambatan.index') }}" class='sidebar-link'>
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Input Keterlambatan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-card-checklist"></i>
                            <span>Rekap Absensi Seluruh Kelas</span>
                        </a>
                    </li>
                @endif

                <!-- SISWA -->
                @if (in_array(Auth::user()->role, ['super_admin', 'siswa']))
                    <li class="sidebar-title">Akademik Siswa</li>
                    <li class="sidebar-item {{ request()->routeIs('siswa.my-jadwal') ? 'active' : '' }}">
                        <a href="{{ route('siswa.my-jadwal') }}" class='sidebar-link'>
                            <i class="bi bi-calendar-week-fill"></i>
                            <span>Jadwal Pelajaran</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('siswa.my-nilai') ? 'active' : '' }}">
                        <a href="{{ route('siswa.my-nilai') }}" class='sidebar-link'>
                            <i class="bi bi-journal-check"></i>
                            <span>Nilai</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('siswa.my-absensi') ? 'active' : '' }}">
                        <a href="{{ route('siswa.my-absensi') }}" class='sidebar-link'>
                            <i class="bi bi-clock-history"></i>
                            <span>Kehadiran</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ request()->routeIs('siswa.my-keterlambatan') ? 'active' : '' }}">
                        <a href="{{ route('siswa.my-keterlambatan') }}" class='sidebar-link'>
                            <i class="bi bi-alarm-fill"></i>
                            <span>Keterlambatan</span>
                        </a>
                    </li>
                @endif

                <li class="sidebar-title">Informasi</li>
                <li class="sidebar-item {{ request()->routeIs('pengumuman.*') ? 'active' : '' }}">
                    <a href="{{ route('pengumuman.index') }}" class='sidebar-link'>
                        <i class="bi bi-bell-fill"></i>
                        <span>Pengumuman</span>
                    </a>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
