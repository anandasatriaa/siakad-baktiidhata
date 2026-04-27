@extends('layouts.app')

@section('title', 'Input Nilai Siswa')
@section('subtitle', 'Input nilai per mata pelajaran dan kelas')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filter Jadwal / Mata Pelajaran</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('nilai.index') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <label for="jadwal_id" class="form-label">Pilih Jadwal (Mata Pelajaran - Kelas)</label>
                    <select name="jadwal_id" id="jadwal_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach ($jadwals as $j)
                            <option value="{{ $j->id }}" {{ $selected_jadwal == $j->id ? 'selected' : '' }}>
                                {{ $j->mata_pelajaran->nama_mapel }} - {{ $j->kelas->nama_kelas }} ({{ $j->hari }}, {{ $j->jam_mulai }} - {{ $j->jam_selesai }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if ($selected_jadwal && $jadwal_info)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title">Daftar Siswa - {{ $jadwal_info->kelas->nama_kelas }}</h4>
                <p class="text-muted mb-0">Mata Pelajaran: {{ $jadwal_info->mata_pelajaran->nama_mapel }} | Semester: {{ $jadwal_info->tahun_akademik->semester }} ({{ $jadwal_info->tahun_akademik->tahun_ajaran }})</p>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf
                <input type="hidden" name="jadwal_id" value="{{ $selected_jadwal }}">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th style="width: 15%;">Nilai Tugas</th>
                                <th style="width: 15%;">Nilai UTS</th>
                                <th style="width: 15%;">Nilai UAS</th>
                                <th style="width: 15%;">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $siswa)
                            <tr>
                                <td>{{ $siswa->nis }}</td>
                                <td>{{ $siswa->nama_lengkap }}</td>
                                <td>
                                    <input type="number" step="0.01" name="nilai[{{ $siswa->id }}][nilai_tugas]" class="form-control" value="{{ $siswa->nilai->nilai_tugas ?? '' }}" min="0" max="100">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="nilai[{{ $siswa->id }}][nilai_uts]" class="form-control" value="{{ $siswa->nilai->nilai_uts ?? '' }}" min="0" max="100">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="nilai[{{ $siswa->id }}][nilai_uas]" class="form-control" value="{{ $siswa->nilai->nilai_uas ?? '' }}" min="0" max="100">
                                </td>
                                <td>
                                    <input type="text" class="form-control bg-light" value="{{ $siswa->nilai->nilai_akhir ?? '-' }}" readonly>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada siswa di kelas ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (count($siswas) > 0)
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Semua Nilai</button>
                </div>
                <p class="text-muted small mt-2">* Nilai Akhir akan dihitung otomatis sebagai rata-rata dari Tugas, UTS, dan UAS setelah disimpan.</p>
                @endif
            </form>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Silakan pilih jadwal terlebih dahulu untuk menginput nilai.
    </div>
    @endif
</section>
@endsection
