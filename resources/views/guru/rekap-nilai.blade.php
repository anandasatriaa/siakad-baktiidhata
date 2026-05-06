@extends('layouts.app')

@section('title', 'Rekap Nilai Siswa')
@section('subtitle', 'Lihat dan ekspor rekap nilai per mata pelajaran')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Pilih Mata Pelajaran & Kelas</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.rekap-nilai') }}" method="GET" id="filterForm" class="row g-3">
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
            <h4 class="card-title">Rekap Nilai: {{ $info->mata_pelajaran->nama_mapel }} - {{ $info->kelas->nama_kelas }}</h4>
            <div class="btn-group">
                <a href="{{ route('guru.export-nilai-pdf', ['mapel_id' => $selected_mapel, 'kelas_id' => $selected_kelas, 'periode_id' => $periode_id]) }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf icon-mid"></i> Export PDF
                </a>
                <a href="{{ route('guru.export-nilai-excel', ['mapel_id' => $selected_mapel, 'kelas_id' => $selected_kelas, 'periode_id' => $periode_id]) }}" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel icon-mid"></i> Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th class="text-center">Tugas</th>
                            <th class="text-center">UTS</th>
                            <th class="text-center">UAS</th>
                            <th class="text-center bg-light">Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nilais as $n)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $n->siswa->nis }}</td>
                            <td>{{ $n->siswa->nama_lengkap }}</td>
                            <td class="text-center">{{ $n->nilai_tugas ?? '-' }}</td>
                            <td class="text-center">{{ $n->nilai_uts ?? '-' }}</td>
                            <td class="text-center">{{ $n->nilai_uas ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $n->nilai_akhir ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Data nilai belum diinput.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</section>
@endsection
