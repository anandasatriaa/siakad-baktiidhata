@extends('layouts.app')

@section('title', 'Data Guru')
@section('subtitle', 'Manajemen data guru SMK Bakti Idhata')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Guru</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('guru.export-pdf') }}" class="btn btn-danger btn-export">
                    <i class="bi bi-file-earmark-pdf icon-mid"></i> Export PDF
                </a>
                <a href="{{ route('guru.export-excel') }}" class="btn btn-success btn-export">
                    <i class="bi bi-file-earmark-excel icon-mid"></i> Export Excel
                </a>
                <a href="{{ route('guru.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle icon-mid"></i> Tambah Guru
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table">
                    <thead>
                        <tr>
                            <th>NIP / NUPTK</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Jenis PTK</th>
                            <th>Akun Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gurus as $guru)
                        <tr>
                            <td>
                                <div>NIP: {{ $guru->nip ?? '-' }}</div>
                                <div>NUPTK: {{ $guru->nuptk ?? '-' }}</div>
                            </td>
                            <td>{{ $guru->gelar_depan ? $guru->gelar_depan . ' ' : '' }}{{ $guru->nama }}{{ $guru->gelar_belakang ? ', ' . $guru->gelar_belakang : '' }}</td>
                            <td>{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td>{{ $guru->jenis_ptk ?? '-' }}</td>
                            <td>
                                <div><small>Email: {{ $guru->user->email ?? '-' }}</small></div>
                                <div><small>Pass: {{ $guru->user->password_plain ?? '-' }}</small></div>
                            </td>
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
                            <td colspan="6" class="text-center text-muted">Belum ada data guru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
    </div>
</section>
@endsection
