@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')
@section('subtitle', 'Tambah data mata pelajaran baru')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Form Tambah Mata Pelajaran</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('mapel.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="kode_mapel">Kode Mapel</label>
                            <input type="text" id="kode_mapel" class="form-control @error('kode_mapel') is-invalid @enderror" name="kode_mapel" value="{{ old('kode_mapel') }}" required placeholder="Contoh: MP-001">
                            @error('kode_mapel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama_mapel">Nama Mata Pelajaran</label>
                            <input type="text" id="nama_mapel" class="form-control @error('nama_mapel') is-invalid @enderror" name="nama_mapel" value="{{ old('nama_mapel') }}" required placeholder="Contoh: Matematika">
                            @error('nama_mapel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('mapel.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
