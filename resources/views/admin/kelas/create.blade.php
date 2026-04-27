@extends('layouts.app')

@section('title', 'Tambah Kelas')
@section('subtitle', 'Tambah data kelas baru')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Form Tambah Kelas</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_kelas">Nama Kelas</label>
                            <input type="text" id="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" name="nama_kelas" value="{{ old('nama_kelas') }}" required placeholder="Contoh: X RPL 1">
                            @error('nama_kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="wali_kelas_id">Wali Kelas</label>
                            <select id="wali_kelas_id" class="form-select @error('wali_kelas_id') is-invalid @enderror" name="wali_kelas_id">
                                <option value="">-- Pilih Wali Kelas --</option>
                                @foreach ($gurus as $g)
                                    <option value="{{ $g->id }}" {{ old('wali_kelas_id') == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                                @endforeach
                            </select>
                            @error('wali_kelas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                    <a href="{{ route('kelas.index') }}" class="btn btn-light-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
