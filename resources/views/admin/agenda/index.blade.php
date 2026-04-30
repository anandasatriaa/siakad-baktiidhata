@extends('layouts.app')

@section('title', 'Agenda Mengajar')
@section('subtitle', 'Jurnal mengajar harian guru')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Agenda</h4>
            <a href="{{ route('agenda.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle icon-mid"></i> Tambah Agenda
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Guru</th>
                            <th>Mata Pelajaran - Kelas</th>
                            <th>Materi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agendas as $a)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $a->guru->nama_lengkap }}</td>
                            <td>{{ $a->jadwal->mata_pelajaran->nama_mapel }} - {{ $a->jadwal->kelas->nama_kelas }}</td>
                            <td>{{ $a->materi }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('agenda.edit', $a->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill icon-mid"></i>
                                    </a>
                                    <form action="{{ route('agenda.destroy', $a->id) }}" method="POST" class="delete-form">
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
                            <td colspan="6" class="text-center text-muted">Belum ada agenda mengajar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
