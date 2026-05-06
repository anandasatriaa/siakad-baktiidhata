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
                            <label for="tahun_akademik_id">Tahun Akademik</label>
                            <select id="tahun_akademik_id" class="form-select @error('tahun_akademik_id') is-invalid @enderror" name="tahun_akademik_id" required>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" {{ old('tahun_akademik_id', $active_periode->id ?? '') == $p->id ? 'selected' : '' }}>
                                        {{ $p->tahun_ajaran }} - {{ $p->semester }} {{ $p->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_akademik_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="nama_kelas">Nama Kelas</label>
                            <input type="text" id="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" name="nama_kelas" value="{{ old('nama_kelas') }}" required placeholder="Contoh: X RPL 1">
                            @error('nama_kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tingkat">Tingkat</label>
                            <select id="tingkat" class="form-select @error('tingkat') is-invalid @enderror" name="tingkat" required>
                                <option value="">-- Pilih Tingkat --</option>
                                <option value="10" {{ old('tingkat') == '10' ? 'selected' : '' }}>10</option>
                                <option value="11" {{ old('tingkat') == '11' ? 'selected' : '' }}>11</option>
                                <option value="12" {{ old('tingkat') == '12' ? 'selected' : '' }}>12</option>
                            </select>
                            @error('tingkat') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('kelas.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
