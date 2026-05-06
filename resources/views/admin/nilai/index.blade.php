@extends('layouts.app')

@section('title', 'Input Nilai Siswa')
@section('subtitle', 'Input nilai per mata pelajaran dan kelas')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Filter Jadwal / Mata Pelajaran</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('nilai.index') }}" method="GET" id="filterForm" class="row g-3">
                <div class="col-md-4">
                    <label for="periode_id" class="form-label">Tahun Akademik</label>
                    <select name="periode_id" id="periode_id" class="form-select" onchange="this.form.submit()">
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" {{ $periode_id == $p->id ? 'selected' : '' }}>
                                {{ $p->tahun_ajaran }} - {{ $p->semester }} {{ $p->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8">
                    <label for="subject_class" class="form-label">Pilih Mata Pelajaran & Kelas</label>
                    <select id="subject_class" class="form-select" onchange="updateFilters(this.value)">
                        <option value="">-- Pilih Mapel & Kelas --</option>
                        @foreach ($ampu_mapel as $am)
                            <option value="{{ $am->mapel_id }}|{{ $am->kelas_id }}" {{ ($selected_mapel == $am->mapel_id && $selected_kelas == $am->kelas_id) ? 'selected' : '' }}>
                                {{ $am->mata_pelajaran->nama_mapel }} - {{ $am->kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="mapel_id" id="mapel_id" value="{{ $selected_mapel }}">
                    <input type="hidden" name="kelas_id" id="kelas_id" value="{{ $selected_kelas }}">
                </div>
            </form>
        </div>
    </div>

    <script>
    function updateFilters(value) {
        if (!value) return;
        const parts = value.split('|');
        document.getElementById('mapel_id').value = parts[0];
        document.getElementById('kelas_id').value = parts[1];
        document.getElementById('filterForm').submit();
    }
    </script>

    @if ($selected_mapel && $selected_kelas && $info)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="card-title">Daftar Siswa - {{ $info->kelas->nama_kelas }}</h4>
                <p class="text-muted mb-0">Mata Pelajaran: {{ $info->mata_pelajaran->nama_mapel }} | Semester: {{ $info->tahun_akademik->semester }} ({{ $info->tahun_akademik->tahun_ajaran }})</p>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('nilai.store') }}" method="POST">
                @csrf
                <input type="hidden" name="mapel_id" value="{{ $selected_mapel }}">
                <input type="hidden" name="kelas_id" value="{{ $selected_kelas }}">
                <input type="hidden" name="tahun_akademik_id" value="{{ $periode_id }}">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th style="width: 15%;">Nilai Tugas</th>
                                <th style="width: 15%;">Nilai UTS</th>
                                <th style="width: 15%;">Nilai UAS</th>
                                <th style="width: 15%;">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $siswa)
                            <tr class="siswa-row" data-siswa-id="{{ $siswa->id }}">
                                <td>{{ $siswa->nis }}</td>
                                <td>{{ $siswa->nama_lengkap }}</td>
                                <td>
                                    <input type="number" step="0.01" name="nilai[{{ $siswa->id }}][nilai_tugas]" class="form-control nilai-input nilai-tugas" value="{{ $siswa->nilai->nilai_tugas ?? '' }}" min="0" max="100">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="nilai[{{ $siswa->id }}][nilai_uts]" class="form-control nilai-input nilai-uts" value="{{ $siswa->nilai->nilai_uts ?? '' }}" min="0" max="100">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="nilai[{{ $siswa->id }}][nilai_uas]" class="form-control nilai-input nilai-uas" value="{{ $siswa->nilai->nilai_uas ?? '' }}" min="0" max="100">
                                </td>
                                <td>
                                    <input type="text" class="form-control bg-light nilai-akhir" value="{{ $siswa->nilai->nilai_akhir ?? '-' }}" readonly>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada siswa di kelas ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (count($siswas) > 0)
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Semua Nilai</button>
                </div>
                <p class="text-muted small mt-2">* Nilai Akhir dihitung otomatis sebagai rata-rata: (Tugas + UTS + UAS) / 3</p>
                @endif
            </form>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Silakan pilih jadwal terlebih dahulu untuk menginput nilai.
    </div>
    @endif
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.nilai-input');
    
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            const row = this.closest('.siswa-row');
            const tugas = parseFloat(row.querySelector('.nilai-tugas').value) || 0;
            const uts = parseFloat(row.querySelector('.nilai-uts').value) || 0;
            const uas = parseFloat(row.querySelector('.nilai-uas').value) || 0;
            
            const akhir = (tugas + uts + uas) / 3;
            row.querySelector('.nilai-akhir').value = akhir.toFixed(2);
        });
    });
});
</script>
@endsection
