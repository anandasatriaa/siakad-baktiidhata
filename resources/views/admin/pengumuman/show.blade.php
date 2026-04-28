@extends('layouts.app')

@section('title', 'Detail Pengumuman')
@section('subtitle', $pengumuman->judul)

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">{{ $pengumuman->judul }}</h4>
            <a href="{{ route('pengumuman.index') }}" class="btn btn-light-secondary">
                <i class="bi bi-arrow-left icon-mid"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <span class="badge bg-light-primary text-primary">
                    <i class="bi bi-calendar-event icon-mid"></i> {{ \Carbon\Carbon::parse($pengumuman->tanggal)->format('d M Y') }}
                </span>
                <span class="badge bg-light-secondary text-secondary ms-2">
                    <i class="bi bi-person icon-mid"></i> {{ $pengumuman->penulis->name }}
                </span>
            </div>
            <hr>
            <div class="pengumuman-content mt-4" style="line-height: 1.8; font-size: 1.1rem;">
                {!! nl2br(e($pengumuman->konten)) !!}
            </div>
        </div>
        @if (in_array(Auth::user()->role, ['super_admin', 'admin', 'kepala_sekolah']))
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('pengumuman.edit', $pengumuman->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-fill icon-mid"></i> Edit
            </a>
            <form action="{{ route('pengumuman.destroy', $pengumuman->id) }}" method="POST" class="delete-form" data-message="Apakah Anda yakin ingin menghapus pengumuman ini?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash-fill icon-mid"></i> Hapus
                </button>
            </form>
        </div>
        @endif
    </div>
</section>
@endsection
