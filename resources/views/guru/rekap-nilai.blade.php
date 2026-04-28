@extends('layouts.app')

@section('title', 'Rekap Nilai Siswa')
@section('subtitle', 'Lihat dan ekspor rekap nilai per mata pelajaran')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Pilih Mata Pelajaran & Kelas</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.rekap-nilai') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <label for="jadwal_id" class="form-label">Jadwal (Mapel - Kelas)</label>
                    <select name="jadwal_id" id="jadwal_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach ($jadwals as $j)
                            <option value="{{ $j->id }}" {{ $selected_jadwal == $j->id ? 'selected' : '' }}>
                                {{ $j->mata_pelajaran->nama_mapel }} - {{ $j->kelas->nama_kelas }} ({{ $j->tahun_akademik->tahun_ajaran }} {{ $j->tahun_akademik->semester }})
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
            <h4 class="card-title">Rekap Nilai: {{ $jadwal_info->mata_pelajaran->nama_mapel }} - {{ $jadwal_info->kelas->nama_kelas }}</h4>
            <div class="btn-group">
                <a href="{{ route('guru.export-nilai-pdf', $selected_jadwal) }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf icon-mid"></i> Export PDF
                </a>
                <a href="{{ route('guru.export-nilai-excel', $selected_jadwal) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel icon-mid"></i> Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th class="text-center">Tugas</th>
                            <th class="text-center">UTS</th>
                            <th class="text-center">UAS</th>
                            <th class="text-center bg-light">Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nilais as $n)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $n->siswa->nis }}</td>
                            <td>{{ $n->siswa->nama_lengkap }}</td>
                            <td class="text-center">{{ $n->nilai_tugas ?? '-' }}</td>
                            <td class="text-center">{{ $n->nilai_uts ?? '-' }}</td>
                            <td class="text-center">{{ $n->nilai_uas ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $n->nilai_akhir ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Data nilai belum diinput.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</section>
@endsection
