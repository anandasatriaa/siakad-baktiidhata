@extends('layouts.app')

@section('title', 'Input Absensi Harian')
@section('subtitle', 'Input kehadiran siswa per mata pelajaran')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filter Jadwal</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('absensi.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <label for="jadwal_id" class="form-label">Pilih Jadwal Pelajaran</label>
                    <select name="jadwal_id" id="jadwal_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach ($jadwals as $j)
                            <option value="{{ $j->id }}" {{ $selected_jadwal == $j->id ? 'selected' : '' }}>
                                {{ $j->hari }} - {{ $j->mapel->nama_mapel }} - Kelas {{ $j->kelas->nama_kelas }} ({{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
                </div>
            </form>
        </div>
    </div>

    @if ($selected_jadwal && $jadwal)
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Daftar Siswa Kelas {{ $jadwal->kelas->nama_kelas }} - {{ $jadwal->mapel->nama_mapel }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('absensi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="jadwal_id" value="{{ $selected_jadwal }}">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Status Kehadiran</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $siswa)
                            <tr>
                                <td>{{ $siswa->nis }}</td>
                                <td>{{ $siswa->nama_lengkap }}</td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="absensi[{{ $siswa->id }}][status]" id="status_h_{{ $siswa->id }}" value="Hadir" {{ ($siswa->absensi && $siswa->absensi->status == 'Hadir') || !$siswa->absensi ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_h_{{ $siswa->id }}">H</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="absensi[{{ $siswa->id }}][status]" id="status_s_{{ $siswa->id }}" value="Sakit" {{ $siswa->absensi && $siswa->absensi->status == 'Sakit' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_s_{{ $siswa->id }}">S</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="absensi[{{ $siswa->id }}][status]" id="status_i_{{ $siswa->id }}" value="Izin" {{ $siswa->absensi && $siswa->absensi->status == 'Izin' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_i_{{ $siswa->id }}">I</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="absensi[{{ $siswa->id }}][status]" id="status_a_{{ $siswa->id }}" value="Alpa" {{ $siswa->absensi && $siswa->absensi->status == 'Alpa' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status_a_{{ $siswa->id }}">A</label>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="absensi[{{ $siswa->id }}][keterangan]" class="form-control form-control-sm" value="{{ $siswa->absensi->keterangan ?? '' }}" placeholder="Opsional">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada siswa di kelas ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (count($siswas) > 0)
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                </div>
                @endif
            </form>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Silakan pilih jadwal pelajaran terlebih dahulu untuk menginput absensi.
    </div>
    @endif
</section>
@endsection
