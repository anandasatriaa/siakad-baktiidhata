@extends('layouts.app')

@section('title', 'Data Siswa Yang Diajar')
@section('subtitle', 'Daftar siswa berdasarkan kelas yang Anda ampu')

@section('content')
<section class="section">
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">Filter Periode</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.data-siswa-ajar') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="periode_id" class="form-label">Tahun Akademik</label>
                    <select name="periode_id" id="periode_id" class="form-select" onchange="this.form.submit()">
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" {{ $periode_id == $p->id ? 'selected' : '' }}>
                                {{ $p->tahun_ajaran }} - {{ $p->semester }} {{ $p->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @foreach ($siswas as $nama_kelas => $group)
    <div class="card mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Kelas: {{ $nama_kelas }}</h4>
            <span class="badge bg-primary">{{ $group->count() }} Siswa</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>No. HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($group as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->nis }}</td>
                            <td>{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $s->no_hp ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    @if($siswas->isEmpty())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Anda belum memiliki jadwal mengajar atau data siswa belum tersedia.
    </div>
    @endif
</section>
@endsection
