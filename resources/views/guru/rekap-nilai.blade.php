@extends('layouts.app')

@section('title', 'Rekap Nilai Siswa')
@section('subtitle', 'Lihat dan ekspor rekap nilai per mata pelajaran')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Pilih Kelas</h4>
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
                    <label for="kelas_id" class="form-label">Pilih Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($daftar_kelas as $kelas)
                            @php
                                $isWali = in_array($kelas->id, $kelas_wali_ids);
                            @endphp
                            <option value="{{ $kelas->id }}" {{ $selected_kelas == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} {{ $isWali ? '(Wali Kelas)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const exportBtns = document.querySelectorAll('.btn-export');

        exportBtns.forEach(function(btn) {
            btn.addEventListener('click', function () {
                let originalHtml = this.innerHTML;
                if (this.classList.contains('btn-danger')) {
                    originalHtml = '<i class="bi bi-file-earmark-pdf"></i>';
                } else {
                    originalHtml = '<i class="bi bi-file-earmark-excel"></i>';
                }
                handleDownloadLoading(this, originalHtml);
            });
        });

        function handleDownloadLoading(btn, originalHtml) {
            if (btn.classList.contains('disabled')) return;

            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

            // Restore original state after 4 seconds
            setTimeout(function () {
                btn.classList.remove('disabled');
                btn.style.pointerEvents = 'auto';
                btn.innerHTML = originalHtml;
            }, 4000);
        }
    });
    </script>

    @if ($selected_kelas && $info_kelas)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Rekap Nilai Kelas: {{ $info_kelas->nama_kelas }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            @foreach ($mapels as $mapel)
                            <th class="text-center">{{ $mapel->nama_mapel }}</th>
                            @endforeach
                            @if ($is_wali_kelas)
                            <th class="text-center bg-light">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nilais_matrix as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->siswa->nis }}</td>
                            <td>{{ $row->siswa->nama_lengkap }}</td>
                            @foreach ($mapels as $mapel)
                            <td class="text-center">{{ $row->nilai_per_mapel[$mapel->id] ?? '-' }}</td>
                            @endforeach
                            @if ($is_wali_kelas)
                            <td class="text-center">
                                <a href="{{ route('guru.input-rapor', ['siswa_id' => $row->siswa->id, 'kelas_id' => $selected_kelas, 'periode_id' => $periode_id]) }}" class="btn btn-sm btn-primary" title="Input Catatan Rapor">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="{{ route('guru.export-nilai-pdf', ['siswa_id' => $row->siswa->id, 'kelas_id' => $selected_kelas, 'periode_id' => $periode_id]) }}" class="btn btn-sm btn-danger btn-export" title="Export PDF">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                                <a href="{{ route('guru.export-nilai-excel', ['siswa_id' => $row->siswa->id, 'kelas_id' => $selected_kelas, 'periode_id' => $periode_id]) }}" class="btn btn-sm btn-success btn-export" title="Export Excel">
                                    <i class="bi bi-file-earmark-excel"></i>
                                </a>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ 3 + count($mapels) + ($is_wali_kelas ? 1 : 0) }}" class="text-center text-muted">Data nilai belum diinput.</td>
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
