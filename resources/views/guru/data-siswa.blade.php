@extends('layouts.app')

@section('title', 'Data Siswa Yang Diajar')
@section('subtitle', 'Daftar siswa berdasarkan kelas yang Anda ampu')

@section('content')
<section class="section">
    @foreach ($siswas as $kelas_id => $group)
    <div class="card mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Kelas: {{ $group->first()->kelas->nama_kelas }}</h4>
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
