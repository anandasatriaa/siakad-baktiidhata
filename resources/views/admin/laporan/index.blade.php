@extends('layouts.app')

@section('title', 'Laporan Absensi & Pelanggaran')
@section('subtitle', 'Rekapitulasi kehadiran dan keterlambatan siswa')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filter Laporan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.absensi-pelanggaran') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="kelas_id">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-select">
                                <option value="">Semua Kelas</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}" {{ $selected_kelas == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ $tanggal_mulai }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="{{ $tanggal_selesai }}">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Hasil Rekapitulasi</h4>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak
                </button>
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
                            <th class="text-center">Total Menit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswas as $siswa)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nama_lengkap }}</td>
                            <td>{{ $siswa->kelas->nama_kelas }}</td>
                            <td class="text-center">{{ $siswa->rekap_absensi['Hadir'] }}</td>
                            <td class="text-center">{{ $siswa->rekap_absensi['Sakit'] }}</td>
                            <td class="text-center">{{ $siswa->rekap_absensi['Izin'] }}</td>
                            <td class="text-center text-danger font-bold">{{ $siswa->rekap_absensi['Alpa'] }}</td>
                            <td class="text-center">
                                <span class="badge {{ $siswa->total_keterlambatan > 0 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $siswa->total_keterlambatan }}x
                                </span>
                            </td>
                            <td class="text-center">{{ $siswa->total_menit }} Menit</td>
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
</section>
@endsection
