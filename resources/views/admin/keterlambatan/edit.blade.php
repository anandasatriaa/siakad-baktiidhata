@extends('layouts.app')

@section('title', 'Edit Keterlambatan')
@section('subtitle', 'Ubah data keterlambatan siswa')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Form Edit Keterlambatan</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('keterlambatan.update', $keterlambatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="siswa_id">Siswa</label>
                            <select id="siswa_id" class="form-select @error('siswa_id') is-invalid @enderror" name="siswa_id" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($siswas as $s)
                                    <option value="{{ $s->id }}" {{ old('siswa_id', $keterlambatan->siswa_id) == $s->id ? 'selected' : '' }}>{{ $s->nama_lengkap }} ({{ $s->kelas->nama_kelas ?? '-' }})</option>
                                @endforeach
                            </select>
                            @error('siswa_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', $keterlambatan->tanggal) }}" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="lama_menit">Lama Terlambat (Menit)</label>
                            <div class="input-group">
                                <input type="number" id="lama_menit" class="form-control @error('lama_menit') is-invalid @enderror" name="lama_menit" value="{{ old('lama_menit', $keterlambatan->lama_menit) }}" required min="1">
                                <span class="input-group-text">Menit</span>
                            </div>
                            @error('lama_menit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="alasan">Alasan / Keterangan</label>
                            <textarea id="alasan" class="form-control @error('alasan') is-invalid @enderror" name="alasan" rows="3">{{ old('alasan', $keterlambatan->alasan) }}</textarea>
                            @error('alasan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                    <a href="{{ route('keterlambatan.index') }}" class="btn btn-light-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
