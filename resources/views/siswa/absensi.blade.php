@extends('layouts.app')

@section('title', 'Riwayat Kehadiran')
@section('subtitle', 'Pantau kehadiran harian Anda')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon purple">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Hadir</h6>
                            <h6 class="font-extrabold mb-0">{{ $absensis->where('status', 'Hadir')->count() }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon blue">
                                <i class="bi bi-calendar-minus"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Izin/Sakit</h6>
                            <h6 class="font-extrabold mb-0">{{ $absensis->whereIn('status', ['Izin', 'Sakit'])->count() }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon red">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Alpa</h6>
                            <h6 class="font-extrabold mb-0">{{ $absensis->where('status', 'Alpa')->count() }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Detail Riwayat Kehadiran</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-lg">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absensis as $a)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</td>
                            <td>
                                @if($a->status == 'Hadir')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif($a->status == 'Sakit')
                                    <span class="badge bg-warning">Sakit</span>
                                @elseif($a->status == 'Izin')
                                    <span class="badge bg-info">Izin</span>
                                @else
                                    <span class="badge bg-danger">Alpa</span>
                                @endif
                            </td>
                            <td>{{ $a->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada riwayat kehadiran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
