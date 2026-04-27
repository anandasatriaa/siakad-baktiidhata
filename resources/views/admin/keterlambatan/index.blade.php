@extends('layouts.app')

@section('title', 'Data Keterlambatan')
@section('subtitle', 'Daftar keterlambatan siswa')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Keterlambatan</h4>
            <a href="{{ route('keterlambatan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Input Keterlambatan
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Lama (Menit)</th>
                            <th>Alasan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keterlambatans as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $k->siswa->nama_lengkap }}</td>
                            <td>{{ $k->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td><span class="badge bg-light-danger text-danger">{{ $k->lama_menit }} Menit</span></td>
                            <td>{{ $k->alasan ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('keterlambatan.edit', $k->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('keterlambatan.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                            <td colspan="7" class="text-center text-muted">Belum ada data keterlambatan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
