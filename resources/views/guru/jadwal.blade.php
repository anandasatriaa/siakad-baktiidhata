@extends('layouts.app')

@section('title', 'Jadwal Mengajar')
@section('subtitle', 'Daftar jadwal mengajar mingguan Anda')

@section('content')
<section class="section">
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('guru.jadwal-mengajar') }}" method="GET" class="row align-items-center">
                <div class="col-md-4">
                    <label for="periode_id" class="form-label fw-bold">Pilih Tahun Akademik</label>
                    <select name="periode_id" id="periode_id" class="form-select" onchange="this.form.submit()">
                        @foreach ($periodes as $p)
                            <option value="{{ $p->id }}" {{ $periode_id == $p->id ? 'selected' : '' }}>
                                {{ $p->tahun_ajaran }} - {{ $p->semester }} {{ $p->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 pt-4">
                    @if($active_periode && $periode_id == $active_periode->id)
                        <span class="badge bg-success">Menampilkan Jadwal Periode Aktif</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @foreach ($days as $day)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success py-2">
                    <h5 class="mb-0 text-white">{{ $day }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <tbody>
                                @forelse ($jadwals->get($day, []) as $j)
                                <tr>
                                    <td class="p-3">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold text-success">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="d-block fw-bold">{{ $j->mata_pelajaran->nama_mapel }}</span>
                                            <span class="text-muted small"><i class="bi bi-house-door"></i> Kelas: {{ $j->kelas->nama_kelas }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="p-3 text-center text-muted italic small">Tidak ada jadwal mengajar</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection
