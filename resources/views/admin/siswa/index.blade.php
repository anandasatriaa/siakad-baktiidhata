@extends('layouts.app')

@section('title', 'Data Siswa')
@section('subtitle', 'Manajemen data siswa SMK Bakti Idhata')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Daftar Siswa</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('siswa.export-pdf') }}" class="btn btn-danger btn-export">
                        <i class="bi bi-file-earmark-pdf icon-mid"></i> Export PDF
                    </a>
                    <a href="{{ route('siswa.export-excel') }}" class="btn btn-success btn-export">
                        <i class="bi bi-file-earmark-excel icon-mid"></i> Export Excel
                    </a>
                    @if (Auth::user()->role !== 'kepala_sekolah')
                    <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-upload icon-mid"></i> Import Excel
                    </button>
                    <a href="{{ route('siswa.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle icon-mid"></i> Tambah Siswa
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="table">
                        <thead>
                            <tr>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Akun Login</th>
                                @if (Auth::user()->role !== 'kepala_sekolah')
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $siswa)
                                <tr>
                                    <td>
                                        <strong>{{ $siswa->nis }}</strong>
                                    </td>
                                    <td>{{ $siswa->nama_lengkap }}</td>
                                    <td>{{ $siswa->kelas->kelas->nama_kelas ?? '-' }}</td>
                                    <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>
                                        <div><small>Email: {{ $siswa->user->email ?? '-' }}</small></div>
                                        <div><small>Pass: {{ $siswa->user->password_plain ?? '-' }}</small></div>
                                    </td>
                                    @if (Auth::user()->role !== 'kepala_sekolah')
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-fill icon-mid"></i>
                                            </a>
                                            <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST"
                                                class="delete-form">
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
                                    <td colspan="{{ Auth::user()->role !== 'kepala_sekolah' ? 6 : 5 }}" class="text-center text-muted">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            });
        </script>

        @if (Auth::user()->role !== 'kepala_sekolah')
        <!-- Modal Import Excel -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('siswa.import-excel') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Import Data Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <p class="text-muted">Gunakan template berikut untuk memasukkan data siswa. Data yang sudah ada dengan NIS yang sama akan diabaikan.</p>
                                <a href="{{ route('siswa.template-excel') }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-download"></i> Download Template Excel
                                </a>
                            </div>
                            <div class="form-group mb-3">
                                <label for="file_excel">Pilih File Excel (.xlsx, .xls)</label>
                                <input type="file" id="file_excel" name="file_excel" class="form-control" accept=".xlsx, .xls, .csv" required>
                            </div>
                            <div class="alert alert-warning py-2 mb-0">
                                <i class="bi bi-exclamation-triangle"></i> Semua akun (User) untuk siswa yang diimpor akan otomatis dibuat dengan password: <strong>smkbaktiidhata</strong>
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
