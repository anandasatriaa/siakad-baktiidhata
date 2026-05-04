@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')
@section('subtitle', 'Manajemen jadwal pelajaran sekolah')

@section('content')
<style>
    .accordion-item {
        border: none !important;
        margin-bottom: 1.5rem !important;
        border-radius: 15px !important;
        overflow: hidden;
    }
    .accordion-button {
        border-radius: 15px !important;
        transition: all 0.3s ease;
        padding: 1.25rem 1.5rem;
    }
    .accordion-button:not(.collapsed) {
        background-color: #435ebe !important;
        color: white !important;
        box-shadow: 0 10px 20px rgba(67, 94, 190, 0.2);
    }
    .accordion-button:not(.collapsed) .text-dark,
    .accordion-button:not(.collapsed) .text-primary,
    .accordion-button:not(.collapsed) .text-muted {
        color: white !important;
    }
    .accordion-button:not(.collapsed) .badge {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: white !important;
    }
    .accordion-button:after {
        background-size: 1.25rem;
    }
    .class-icon-wrapper {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        transition: all 0.3s ease;
    }
    .accordion-button.collapsed .class-icon-wrapper {
        background-color: rgba(67, 94, 190, 0.1);
    }
    .accordion-button:not(.collapsed) .class-icon-wrapper {
        background-color: rgba(255, 255, 255, 0.2);
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

                <div class="accordion" id="accordionJadwal">
                    @forelse ($jadwals as $kelasNama => $kelasJadwal)
                        <div class="accordion-item mb-3 border shadow-sm rounded overflow-hidden">
                            <h2 class="accordion-header" id="heading{{ Str::slug($kelasNama) }}">
                                <button class="accordion-button collapsed py-3" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($kelasNama) }}"
                                    aria-expanded="false" aria-controls="collapse{{ Str::slug($kelasNama) }}">
                                    <div class="d-flex align-items-center w-100 me-3">
                                        <div class="class-icon-wrapper">
                                            <i class="bi bi-door-open-fill fs-4 text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-0 fw-bold text-dark">{{ $kelasNama }}</h5>
                                                    @if($kelasJadwal->first()->kelas->wali_kelas)
                                                        <small class="text-muted">
                                                            <i class="bi bi-person-badge me-1"></i> Wali Kelas: {{ $kelasJadwal->first()->kelas->wali_kelas->name }}
                                                        </small>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-primary rounded-pill px-3 shadow-sm">
                                                        <i class="bi bi-book me-1"></i> {{ $kelasJadwal->count() }} Mata Pelajaran
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ Str::slug($kelasNama) }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ Str::slug($kelasNama) }}" data-bs-parent="#accordionJadwal">
                                <div class="accordion-body p-0">
                                    @php
                                        $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                        $groupedByDay = $kelasJadwal
                                            ->groupBy('hari')
                                            ->sortBy(function ($val, $key) use ($daysOrder) {
                                                return array_search($key, $daysOrder);
                                            });
                                    @endphp

                                    @foreach ($groupedByDay as $hari => $items)
                                        <div class="px-4 py-2 bg-light border-top border-bottom">
                                            <h6 class="mb-0 fw-bold text-secondary text-uppercase small tracking-wider">
                                                <i class="bi bi-calendar3 me-2"></i> {{ $hari }}
                                            </h6>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
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
                                                                            {{ strtoupper(substr($j->guru->nama_lengkap, 0, 1)) }}
                                                                        </div>
                                                                    </div>
                                                                    <span
                                                                        class="small fw-medium">{{ $j->guru->nama_lengkap }}</span>
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
                            <a href="{{ route('jadwal.create') }}" class="btn btn-primary mt-2">Buat Jadwal Baru</a>
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
