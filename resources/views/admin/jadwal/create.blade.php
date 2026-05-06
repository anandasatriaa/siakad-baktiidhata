@extends('layouts.app')

@section('title', 'Tambah Jadwal Pelajaran')
@section('subtitle', 'Buat banyak jadwal pelajaran sekaligus per kelas')

@section('content')
<section class="section">
    <form action="{{ route('jadwal.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Informasi Kelas & Tahun Akademik</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="kelas_id">Kelas</label>
                            <select id="kelas_id" class="form-select @error('kelas_id') is-invalid @enderror" name="kelas_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tahun_akademik_id">Tahun Akademik / Semester</label>
                            <select id="tahun_akademik_id" class="form-select @error('tahun_akademik_id') is-invalid @enderror" name="tahun_akademik_id" required>
                                <option value="">-- Pilih Tahun Akademik --</option>
                                @foreach ($tahun_akademiks as $ta)
                                    <option value="{{ $ta->id }}" {{ old('tahun_akademik_id') == $ta->id ? 'selected' : '' }}>
                                        {{ $ta->tahun_ajaran }} - {{ $ta->semester }} {{ $ta->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tahun_akademik_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Detail Jadwal</h4>
                <button type="button" class="btn btn-sm btn-success" id="add-row">
                    <i class="bi bi-plus-circle icon-mid"></i> Tambah Baris
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="schedule-table">
                        <thead>
                            <tr>
                                <th style="width: 15%;">Hari</th>
                                <th style="width: 25%;">Jam (Mulai - Selesai)</th>
                                <th style="width: 25%;">Mata Pelajaran</th>
                                <th style="width: 25%;">Guru Pengajar</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="schedule-body">
                            <tr class="schedule-row">
                                <td>
                                    <select name="schedules[0][hari]" class="form-select form-select-sm" required>
                                        @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h)
                                            <option value="{{ $h }}">{{ $h }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="time" name="schedules[0][jam_mulai]" class="form-control" required>
                                        <span class="input-group-text">-</span>
                                        <input type="time" name="schedules[0][jam_selesai]" class="form-control" required>
                                    </div>
                                </td>
                                <td>
                                    <select name="schedules[0][mapel_id]" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach ($mapels as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="schedules[0][guru_id]" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach ($gurus as $g)
                                            <option value="{{ $g->id }}">{{ $g->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger remove-row" disabled>
                                        <i class="bi bi-trash icon-mid"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Semua Jadwal</button>
                </div>
            </div>
        </div>
    </form>
</section>

@push('scripts')
<script>
    let rowCount = 1;
    document.getElementById('add-row').addEventListener('click', function() {
        const tbody = document.getElementById('schedule-body');
        const firstRow = document.querySelector('.schedule-row');
        const newRow = firstRow.cloneNode(true);
        
        // Update names
        newRow.querySelectorAll('select, input').forEach(input => {
            input.name = input.name.replace(/\[\d+\]/, `[${rowCount}]`);
            if (input.tagName === 'SELECT') input.selectedIndex = 0;
            else input.value = '';
        });

        // Enable remove button
        const removeBtn = newRow.querySelector('.remove-row');
        removeBtn.disabled = false;
        removeBtn.addEventListener('click', function() {
            newRow.remove();
        });

        tbody.appendChild(newRow);
        rowCount++;
    });

    document.querySelectorAll('.remove-row').forEach(btn => {
        btn.addEventListener('click', function() {
            if (document.querySelectorAll('.schedule-row').length > 1) {
                this.closest('tr').remove();
            }
        });
    });
</script>
@endpush
@endsection
