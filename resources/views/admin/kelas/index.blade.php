@extends('layouts.app')

@section('title', 'Data Kelas')
@section('subtitle', 'Manajemen data kelas SMK Bakti Idhata')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <h4 class="card-title mb-0">Daftar Kelas</h4>
                <form action="{{ route('kelas.index') }}" method="GET" id="filterForm" class="d-flex align-items-center gap-2">
                    <select name="periode_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}" {{ $periode_id == $p->id ? 'selected' : '' }}>
                                {{ $p->tahun_ajaran }} - {{ $p->semester }}
                                {{ $p->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <a href="{{ route('kelas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle icon-mid"></i> Tambah Kelas
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Tahun Akademik</th>
                            <th>Wali Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelas as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $k->nama_kelas }}</td>
                            <td>{{ $k->tingkat }}</td>
                            <td>{{ $k->tahunAkademik->tahun_ajaran }} - {{ $k->tahunAkademik->semester }}</td>
                            <td>{{ $k->wali_kelas->name ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('kelas.edit', $k->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill icon-mid"></i>
                                    </a>
                                    <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" class="delete-form">
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
                            <td colspan="6" class="text-center text-muted">Belum ada data kelas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
