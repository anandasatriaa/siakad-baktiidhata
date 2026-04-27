@extends('layouts.app')

@section('title', 'Pengumuman')
@section('subtitle', 'Manajemen pengumuman sekolah')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Pengumuman</h4>
            <a href="{{ route('pengumuman.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Tambah Pengumuman
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengumumans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                            <td>{{ $p->judul }}</td>
                            <td>{{ $p->penulis->name }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('pengumuman.edit', $p->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('pengumuman.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada pengumuman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
