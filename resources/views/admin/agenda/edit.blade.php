@extends('layouts.app')

@section('title', 'Edit Agenda Mengajar')
@section('subtitle', 'Ubah data agenda mengajar')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Form Edit Agenda</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('agenda.update', $agenda->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="jadwal_id">Jadwal</label>
                            <select id="jadwal_id" class="form-select @error('jadwal_id') is-invalid @enderror" name="jadwal_id" required>
                                <option value="">-- Pilih Jadwal --</option>
                                @foreach ($jadwals as $j)
                                    <option value="{{ $j->id }}" {{ old('jadwal_id', $agenda->jadwal_id) == $j->id ? 'selected' : '' }}>
                                        {{ $j->mata_pelajaran->nama_mapel }} - {{ $j->kelas->nama_kelas }} ({{ $j->hari }}, {{ $j->jam_mulai }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jadwal_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ old('tanggal', $agenda->tanggal) }}" required>
                            @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="materi">Materi Pelajaran</label>
                            <input type="text" id="materi" class="form-control @error('materi') is-invalid @enderror" name="materi" value="{{ old('materi', $agenda->materi) }}" required placeholder="Contoh: Pengenalan Aljabar">
                            @error('materi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="keterangan">Keterangan / Catatan</label>
                            <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="3">{{ old('keterangan', $agenda->keterangan) }}</textarea>
                            @error('keterangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                    <a href="{{ route('agenda.index') }}" class="btn btn-light-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
