@extends('layouts.app')

@section('title', 'Data Guru')
@section('subtitle', 'Manajemen data guru SMK Bakti Idhata')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Guru</h4>
            <a href="{{ route('guru.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle icon-mid"></i> Tambah Guru
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gurus as $guru)
                        <tr>
                            <td>{{ $guru->nip ?? '-' }}</td>
                            <td>{{ $guru->nama_lengkap }}</td>
                            <td>{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $guru->no_hp ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('guru.edit', $guru->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill icon-mid"></i>
                                    </a>
                                    <form action="{{ route('guru.destroy', $guru->id) }}" method="POST" class="delete-form">
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
                            <td colspan="5" class="text-center text-muted">Belum ada data guru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
