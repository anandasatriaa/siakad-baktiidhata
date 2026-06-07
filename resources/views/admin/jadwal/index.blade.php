@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')
@section('subtitle', 'Manajemen jadwal pelajaran sekolah')

@section('content')
<style>
    .day-divider {
        background: linear-gradient(90deg, #f1f3f8 0%, #ffffff 100%);
        color: #435ebe;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #edf2f7;
        border-left: 4px solid #435ebe;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .schedule-table th {
        background-color: transparent;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #8f9bb3;
        font-weight: 700;
        letter-spacing: 1px;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #edf2f7;
    }
    .schedule-table td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f7;
        color: #2b3445;
        font-size: 0.95rem;
    }
    .schedule-table tr:hover td {
        background-color: #ffffff;
    }
    .schedule-table tr:last-child td {
        border-bottom: none;
    }
</style>
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Daftar Jadwal</h4>
            <a href="{{ route('jadwal.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle icon-mid"></i> Tambah Jadwal
            </a>
        </div>
        <div class="card-body">
                @php
                    $groupedByTingkat = [];
                    foreach ($jadwals as $kelasNama => $kelasJadwal) {
                        $tingkat = $kelasJadwal->first()->kelas->tingkat ?? 10;
                        $groupedByTingkat[$tingkat][$kelasNama] = $kelasJadwal;
                    }
                    ksort($groupedByTingkat);
                @endphp

                @if(count($groupedByTingkat) > 0)
                    <ul class="nav nav-tabs" id="jadwalTabs" role="tablist">
                        @foreach($groupedByTingkat as $tingkat => $kelasGroup)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-kelas-{{ $tingkat }}" data-bs-toggle="tab" href="#content-kelas-{{ $tingkat }}" role="tab" aria-controls="content-kelas-{{ $tingkat }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    Kelas {{ $tingkat }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content" id="jadwalTabsContent">
                        @foreach($groupedByTingkat as $tingkat => $kelasGroup)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} pt-4" id="content-kelas-{{ $tingkat }}" role="tabpanel" aria-labelledby="tab-kelas-{{ $tingkat }}">
                                <div id="accordionJadwal-{{ $tingkat }}">
                                    @foreach ($kelasGroup as $kelasNama => $kelasJadwal)
                                        <div class="card shadow-sm mb-3 border">
                                            <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($kelasNama) }}" style="cursor: pointer;" aria-expanded="false" aria-controls="collapse{{ Str::slug($kelasNama) }}">
                                                <div class="d-flex align-items-center gap-3">
                                                    <h6 class="m-0 font-bold text-primary">{{ $kelasNama }}</h6>
                                                    <span class="badge bg-light-primary">{{ $kelasJadwal->count() }} Mapel</span>
                                                    @if($kelasJadwal->first()->kelas->wali_kelas)
                                                        <span class="text-muted small fw-normal"><i class="bi bi-person-badge"></i> Wali Kelas: {{ $kelasJadwal->first()->kelas->wali_kelas->name }}</span>
                                                    @endif
                                                </div>
                                                <i class="bi bi-chevron-down text-muted"></i>
                                            </div>
                                            <div id="collapse{{ Str::slug($kelasNama) }}" class="collapse" data-bs-parent="#accordionJadwal-{{ $tingkat }}">
                                                <div class="card-body p-0 border-top">
                                                    @php
                                                        $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                                        $groupedByDay = $kelasJadwal
                                                            ->groupBy('hari')
                                                            ->sortBy(function ($val, $key) use ($daysOrder) {
                                                                return array_search($key, $daysOrder);
                                                            });
                                                    @endphp

                                                    @foreach ($groupedByDay as $hari => $items)
                                                        <div class="day-divider">
                                                            <i class="bi bi-calendar-event me-2"></i> {{ $hari }}
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table schedule-table mb-0">
                                                                <thead class="table-light">
                                                                    <tr class="small text-uppercase text-muted">
                                                                        <th width="150" class="ps-4">Jam</th>
                                                                        <th>Mata Pelajaran</th>
                                                                        <th>Guru</th>
                                                                        <th>Semester</th>
                                                                        <th width="120" class="text-center pe-4">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($items as $j)
                                                                        <tr>
                                                                            <td class="ps-4">
                                                                                <span class="badge bg-light-primary text-primary fw-medium">
                                                                                    {{ substr($j->jam_mulai, 0, 5) }} -
                                                                                    {{ substr($j->jam_selesai, 0, 5) }}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <div class="fw-bold text-dark">
                                                                                    {{ $j->mata_pelajaran->nama_mapel }}</div>
                                                                                <code
                                                                                    class="small text-muted">{{ $j->mata_pelajaran->kode_mapel ?? '' }}</code>
                                                                            </td>
                                                                            <td>
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="avatar avatar-sm me-2">
                                                                                        <div class="avatar-content bg-info text-white shadow-sm"
                                                                                            style="width: 28px; height: 28px; font-size: 11px;">
                                                                                            {{ strtoupper(substr($j->guru->nama, 0, 1)) }}
                                                                                        </div>
                                                                                    </div>
                                                                                    <span
                                                                                        class="small fw-medium">{{ $j->guru->nama }}</span>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-light-info text-info small">
                                                                                    {{ $j->tahun_akademik->semester }}
                                                                                </span>
                                                                                <div class="small text-muted">
                                                                                    {{ $j->tahun_akademik->tahun_ajaran }}</div>
                                                                            </td>
                                                                            <td class="pe-4 text-center">
                                                                                <div class="d-flex justify-content-center gap-2">
                                                                                    <a href="{{ route('jadwal.edit', $j->id) }}"
                                                                                        class="btn btn-sm btn-warning px-2"
                                                                                        title="Edit">
                                                                                        <i class="bi bi-pencil-square icon-mid"></i>
                                                                                    </a>
                                                                                    <form action="{{ route('jadwal.destroy', $j->id) }}"
                                                                                        method="POST" class="delete-form"
                                                                                        data-message="Apakah Anda yakin ingin menghapus jadwal ini?">
                                                                                        @csrf
                                                                                        @method('DELETE')
                                                                                        <button type="submit"
                                                                                            class="btn btn-sm btn-danger px-2"
                                                                                            title="Hapus">
                                                                                            <i class="bi bi-trash icon-mid"></i>
                                                                                        </button>
                                                                                    </form>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="col-12">
                        <div class="alert alert-light-warning color-warning d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Belum ada jadwal yang diinput.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
