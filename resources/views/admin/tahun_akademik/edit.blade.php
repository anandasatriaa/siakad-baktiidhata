@extends('layouts.app')

@section('title', 'Edit Tahun Akademik')
@section('subtitle', 'Ubah data tahun akademik')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Form Edit Tahun Akademik</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('tahun-akademik.update', $tahun_akademik->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tahun_ajaran">Tahun Ajaran</label>
                            <input type="text" id="tahun_ajaran" class="form-control @error('tahun_ajaran') is-invalid @enderror" 
                                name="tahun_ajaran" value="{{ old('tahun_ajaran', $tahun_akademik->tahun_ajaran) }}" required placeholder="Contoh: 2026/2027">
                            @error('tahun_ajaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="semester">Semester</label>
                            <select id="semester" class="form-select @error('semester') is-invalid @enderror" name="semester" required>
                                <option value="">-- Pilih Semester --</option>
                                <option value="Ganjil" {{ old('semester', $tahun_akademik->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ old('semester', $tahun_akademik->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            @error('semester') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-check mb-3">
                            <div class="checkbox">
                                <input type="checkbox" id="is_active" class="form-check-input" name="is_active" {{ old('is_active', $tahun_akademik->is_active) ? 'checked' : '' }}>
                                <label for="is_active">Set sebagai tahun akademik aktif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('tahun-akademik.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
