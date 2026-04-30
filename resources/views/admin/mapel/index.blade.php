@extends('layouts.app')

@section('title', 'Data Mata Pelajaran')
@section('subtitle', 'Manajemen data mata pelajaran SMK Bakti Idhata')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Mata Pelajaran</h4>
            <a href="{{ route('mapel.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle icon-mid"></i> Tambah Mapel
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Mapel</th>
                            <th>Nama Mata Pelajaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mapels as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-light-primary">{{ $m->kode_mapel }}</span></td>
                            <td>{{ $m->nama_mapel }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('mapel.edit', $m->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill icon-mid"></i>
                                    </a>
                                    <form action="{{ route('mapel.destroy', $m->id) }}" method="POST" class="delete-form">
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
                            <td colspan="4" class="text-center text-muted">Belum ada data mata pelajaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
