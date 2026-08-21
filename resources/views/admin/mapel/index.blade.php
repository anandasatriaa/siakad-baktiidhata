@extends('layouts.app')

@section('title', 'Data Mata Pelajaran')
@section('subtitle', 'Manajemen data mata pelajaran SMK Bakti Idhata')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Mata Pelajaran</h4>
            <div class="d-flex gap-2">
                @if (Auth::user()->role !== 'kepala_sekolah')
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload icon-mid"></i> Import Excel
                </button>
                <a href="{{ route('mapel.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle icon-mid"></i> Tambah Mapel
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Mapel</th>
                            <th>Nama Mata Pelajaran</th>
                            @if (Auth::user()->role !== 'kepala_sekolah')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mapels as $m)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><span class="badge bg-light-primary">{{ $m->kode_mapel }}</span></td>
                            <td>{{ $m->nama_mapel }}</td>
                            @if (Auth::user()->role !== 'kepala_sekolah')
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
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role !== 'kepala_sekolah' ? 4 : 3 }}" class="text-center text-muted">Belum ada data mata pelajaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (Auth::user()->role !== 'kepala_sekolah')
    <!-- Modal Import Excel -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('mapel.import-excel') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Data Mata Pelajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p class="text-muted">Gunakan template berikut untuk memasukkan data mata pelajaran. Data yang sudah ada dengan Kode Mapel yang sama akan diabaikan.</p>
                            <a href="{{ route('mapel.template-excel') }}" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download"></i> Download Template Excel
                            </a>
                        </div>
                        <div class="form-group mb-0">
                            <label for="file_excel">Pilih File Excel (.xlsx, .xls)</label>
                            <input type="file" id="file_excel" name="file_excel" class="form-control" accept=".xlsx, .xls, .csv" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Mulai Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</section>
@endsection
