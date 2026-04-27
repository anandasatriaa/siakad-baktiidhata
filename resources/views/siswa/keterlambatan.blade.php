@extends('layouts.app')

@section('title', 'Catatan Keterlambatan')
@section('subtitle', 'Pantau kedisiplinan waktu Anda')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-md-6">
            <div class="card bg-light-danger">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon red">
                                <i class="bi bi-alarm-fill text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Total Menit Terlambat</h6>
                            <h2 class="font-extrabold mb-0">{{ $total_menit }} Menit</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light-warning">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon orange">
                                <i class="bi bi-exclamation-octagon-fill text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Frekuensi Terlambat</h6>
                            <h2 class="font-extrabold mb-0">{{ $keterlambatans->count() }} Kali</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Detail Keterlambatan</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Durasi</th>
                            <th>Alasan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keterlambatans as $k)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                            <td><span class="text-danger fw-bold">{{ $k->lama_menit }} Menit</span></td>
                            <td>{{ $k->alasan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted text-success">
                                <i class="bi bi-check-circle"></i> Anda belum pernah terlambat. Pertahankan!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
