@extends('layouts.app')

@section('title', 'Tambah Pengumuman')
@section('subtitle', 'Buat pengumuman baru')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Form Tambah Pengumuman</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('pengumuman.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="judul">Judul Pengumuman</label>
                    <input type="text" id="judul" class="form-control @error('judul') is-invalid @enderror" name="judul" value="{{ old('judul') }}" required placeholder="Masukkan judul pengumuman">
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="konten">Konten / Isi Pengumuman</label>
                    <textarea id="konten" class="form-control @error('konten') is-invalid @enderror" name="konten" rows="10" required placeholder="Tuliskan isi pengumuman di sini...">{{ old('konten') }}</textarea>
                    @error('konten') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('pengumuman.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Pengumuman</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
