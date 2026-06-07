@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')
@section('subtitle', 'Manajemen jadwal pelajaran sekolah')

@section('content')
<style>
    .accordion-custom {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .accordion-custom .accordion-item {
        border: none !important;
        border-radius: 16px !important;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .accordion-custom .accordion-item:hover {
        box-shadow: 0 8px 30px rgba(67, 94, 190, 0.1);
        transform: translateY(-2px);
    }
    .accordion-custom .accordion-button {
        padding: 1.5rem;
        font-weight: 600;
        color: #2b3445;
        background-color: transparent;
        box-shadow: none !important;
        border-radius: 16px !important;
        transition: all 0.3s ease;
    }
    .accordion-custom .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #435ebe 0%, #3a51a3 100%);
        color: #ffffff;
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
    .accordion-custom .accordion-button::after {
        background-size: 1.25rem;
        transition: transform 0.3s ease;
    }
    .accordion-custom .accordion-button:not(.collapsed)::after {
        filter: brightness(0) invert(1);
    }
    .accordion-custom .badge-count {
        background: rgba(67, 94, 190, 0.1);
        color: #435ebe;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 50rem;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(67, 94, 190, 0.2);
    }
    .accordion-custom .accordion-button:not(.collapsed) .badge-count {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .accordion-custom .wali-kelas {
        font-size: 0.85rem;
        color: #6c757d;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.3s ease;
    }
    .accordion-custom .accordion-button:not(.collapsed) .wali-kelas {
        color: rgba(255, 255, 255, 0.8);
    }
    .accordion-custom .accordion-body {
        padding: 0;
        background-color: #fafbfd;
    }
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
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group has-icon-left">
                            <div class="position-relative">
                                <input type="text" class="form-control" placeholder="Cari Kelas..." id="searchClass">
                                <div class="form-control-icon">
                                    <i class="bi bi-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion accordion-custom" id="accordionJadwal">
                    @forelse ($jadwals as $kelasNama => $kelasJadwal)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ Str::slug($kelasNama) }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($kelasNama) }}" aria-expanded="false" aria-controls="collapse{{ Str::slug($kelasNama) }}">
                                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                        <div class="d-flex flex-column gap-1">
                                            <span class="fs-5 fw-bold">{{ $kelasNama }}</span>
                                            @if($kelasJadwal->first()->kelas->wali_kelas)
                                                <div class="wali-kelas">
                                                    <i class="bi bi-person-badge"></i> Wali Kelas: {{ $kelasJadwal->first()->kelas->wali_kelas->name }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="badge-count shadow-sm">
                                            {{ $kelasJadwal->count() }} Mata Pelajaran
                                        </span>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ Str::slug($kelasNama) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ Str::slug($kelasNama) }}" data-bs-parent="#accordionJadwal">
                                <div class="accordion-body">
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
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">Belum ada jadwal pelajaran yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchClass').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let items = document.querySelectorAll('#accordionJadwal .accordion-item');

            items.forEach(item => {
                let className = item.querySelector('.accordion-header').innerText.toLowerCase();
                if (className.includes(searchValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
@endpush
