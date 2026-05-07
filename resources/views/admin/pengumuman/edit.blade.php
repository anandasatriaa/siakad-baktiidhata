@extends('layouts.app')

@section('title', 'Edit Pengumuman')
@section('subtitle', 'Ubah pengumuman yang sudah ada')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Form Edit Pengumuman</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tahun_akademik_id">Tahun Akademik</label>
                            <select id="tahun_akademik_id" class="form-select @error('tahun_akademik_id') is-invalid @enderror" name="tahun_akademik_id">
                                <option value="">-- Pengumuman (Semua Periode) --</option>
                                @foreach ($periodes as $p)
                                    <option value="{{ $p->id }}" {{ old('tahun_akademik_id', $pengumuman->tahun_akademik_id) == $p->id ? 'selected' : '' }}>
                                        {{ $p->tahun_ajaran }} - {{ $p->semester }} {{ $p->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_akademik_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', $pengumuman->tanggal) }}" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="judul">Judul Pengumuman</label>
                    <input type="text" id="judul" class="form-control @error('judul') is-invalid @enderror" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required placeholder="Masukkan judul pengumuman">
                    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="konten">Konten / Isi Pengumuman</label>
                    <textarea id="konten" class="form-control @error('konten') is-invalid @enderror" name="konten" rows="10" required placeholder="Tuliskan isi pengumuman di sini...">{{ old('konten', $pengumuman->konten) }}</textarea>
                    @error('konten') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('pengumuman.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
