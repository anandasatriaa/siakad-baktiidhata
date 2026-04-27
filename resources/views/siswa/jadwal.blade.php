@extends('layouts.app')

@section('title', 'Jadwal Pelajaran Saya')
@section('subtitle', 'Lihat jadwal KBM mingguan Anda')

@section('content')
<section class="section">
    <div class="row">
        @foreach ($days as $day)
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header bg-primary py-2 text-white">
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
                                            <span class="fw-bold text-primary">{{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="d-block fw-bold">{{ $j->mata_pelajaran->nama_mapel }}</span>
                                            <span class="text-muted small"><i class="bi bi-person"></i> {{ $j->guru->nama_lengkap }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="p-3 text-center text-muted italic small">Tidak ada jadwal</td>
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
