@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')
@section('subtitle', 'Manajemen jadwal pelajaran sekolah')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Jadwal</h4>
            <a href="{{ route('jadwal.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle icon-mid"></i> Tambah Jadwal
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Kelas</th>
                            <th>Semester</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwals as $j)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $j->hari }}</strong></td>
                            <td>{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</td>
                            <td>{{ $j->mata_pelajaran->nama_mapel }}</td>
                            <td>{{ $j->guru->nama_lengkap }}</td>
                            <td>{{ $j->kelas->nama_kelas }}</td>
                            <td>{{ $j->tahun_akademik->semester }} ({{ $j->tahun_akademik->tahun_ajaran }})</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('jadwal.edit', $j->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill icon-mid"></i>
                                    </a>
                                    <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" class="delete-form" data-message="Apakah Anda yakin ingin menghapus jadwal ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash-fill icon-mid"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada jadwal pelajaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
