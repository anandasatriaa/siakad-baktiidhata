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
                    <a href="{{ route('siswa.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle icon-mid"></i> Tambah Siswa
                    </a>
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
                                <th>No. HP</th>
                                <th>Aksi</th>
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
                                    <td>{{ $siswa->no_hp ?? '-' }}</td>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada data siswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const exportBtns = document.querySelectorAll('.btn-export');

                exportBtns.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        let originalHtml = this.innerHTML;
                        if (this.classList.contains('btn-danger')) {
                            originalHtml = '<i class="bi bi-file-earmark-pdf icon-mid"></i> Export PDF';
                        } else {
                            originalHtml = '<i class="bi bi-file-earmark-excel icon-mid"></i> Export Excel';
                        }
                        handleDownloadLoading(this, originalHtml);
                    });
                });

                function handleDownloadLoading(btn, originalHtml) {
                    if (btn.classList.contains('disabled')) return;

                    btn.classList.add('disabled');
                    btn.style.pointerEvents = 'none';
                    btn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

                    setTimeout(function() {
                        btn.classList.remove('disabled');
                        btn.style.pointerEvents = 'auto';
                        btn.innerHTML = originalHtml;
                    }, 4000);
                }
            });
        </script>
    </section>
@endsection
