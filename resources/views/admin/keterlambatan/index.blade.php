@extends('layouts.app')

@section('title', 'Data Keterlambatan')
@section('subtitle', 'Daftar keterlambatan siswa')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Keterlambatan</h4>
            <a href="{{ route('keterlambatan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle icon-mid"></i> Input Keterlambatan
            </a>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <form action="{{ route('keterlambatan.index') }}" method="GET">
                        <label for="periode_id" class="form-label">Tahun Akademik</label>
                        <select name="periode_id" id="periode_id" class="form-select" onchange="this.form.submit()">
                            @foreach ($periodes as $p)
                                <option value="{{ $p->id }}" {{ $periode_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->tahun_ajaran }} - {{ $p->semester }} {{ $p->is_active ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('keterlambatan.update-jam-masuk') }}" method="POST">
                        @csrf
                        <label for="jam_masuk_sekolah" class="form-label">Jam Masuk Sekolah</label>
                        <div class="input-group">
                            <input type="time" name="jam_masuk_sekolah" id="jam_masuk_sekolah" class="form-control" value="{{ $jam_masuk_sekolah }}" required>
                            <button class="btn btn-primary" type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>

                            <th>Alasan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keterlambatans as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $k->siswa->nama_lengkap }}</td>
                            <td>{{ $k->siswa->riwayatKelas->first()->kelas->nama_kelas ?? '-' }}</td>
                            <td>{{ $k->alasan ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('keterlambatan.edit', $k->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill icon-mid"></i>
                                    </a>
                                    <form action="{{ route('keterlambatan.destroy', $k->id) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash-fill icon-mid"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data keterlambatan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
