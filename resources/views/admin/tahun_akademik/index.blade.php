@extends('layouts.app')

@section('title', 'Tahun Akademik')
@section('subtitle', 'Manajemen data tahun akademik dan semester')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Tahun Akademik</h4>
            @if (Auth::user()->role !== 'kepala_sekolah')
            <a href="{{ route('tahun-akademik.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle icon-mid"></i> Tambah Tahun Akademik
            </a>
            @endif
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Status</th>
                            @if (Auth::user()->role !== 'kepala_sekolah')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tahun_akademiks as $ta)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $ta->tahun_ajaran }}</td>
                            <td>{{ $ta->semester }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <form action="{{ route('tahun-akademik.activate', $ta->id) }}" method="POST">
                                        @csrf
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch-{{ $ta->id }}" 
                                            {{ $ta->is_active || Auth::user()->role === 'kepala_sekolah' ? 'checked disabled' : '' }} 
                                            @if(Auth::user()->role !== 'kepala_sekolah') onchange="this.form.submit()" @endif style="cursor: {{ $ta->is_active || Auth::user()->role === 'kepala_sekolah' ? 'default' : 'pointer' }}">
                                        <label class="form-check-label ms-2" for="switch-{{ $ta->id }}">
                                            @if ($ta->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </label>
                                    </form>
                                </div>
                            </td>
                            @if (Auth::user()->role !== 'kepala_sekolah')
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('tahun-akademik.edit', $ta->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill icon-mid"></i>
                                    </a>
                                    <form action="{{ route('tahun-akademik.destroy', $ta->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" {{ $ta->is_active ? 'disabled' : '' }}>
                                            <i class="bi bi-trash-fill icon-mid"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role !== 'kepala_sekolah' ? 5 : 4 }}" class="text-center text-muted">Belum ada data tahun akademik.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
