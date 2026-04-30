@extends('layouts.app')

@section('title', 'Tambah Agenda Mengajar')
@section('subtitle', 'Catat kegiatan belajar mengajar')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Form Tambah Agenda</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('agenda.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jadwal_id">Pilih Jadwal</label>
                            <select id="jadwal_id" class="form-select @error('jadwal_id') is-invalid @enderror" name="jadwal_id" required>
                                <option value="">-- Pilih Jadwal --</option>
                                @foreach ($jadwals as $j)
                                    <option value="{{ $j->id }}" {{ old('jadwal_id') == $j->id ? 'selected' : '' }}>
                                        {{ $j->mata_pelajaran->nama_mapel }} - {{ $j->kelas->nama_kelas }} ({{ $j->hari }}, {{ $j->jam_mulai }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jadwal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="materi">Materi Pelajaran</label>
                            <input type="text" id="materi" class="form-control @error('materi') is-invalid @enderror" name="materi" value="{{ old('materi') }}" required placeholder="Contoh: Pengenalan Aljabar">
                            @error('materi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan / Catatan</label>
                            <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="3" placeholder="Opsional">{{ old('keterangan') }}</textarea>
                            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('agenda.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
