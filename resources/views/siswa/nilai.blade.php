@extends('layouts.app')

@section('title', 'Laporan Hasil Belajar')
@section('subtitle', 'Lihat capaian nilai akademik Anda')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Rapor Nilai Siswa</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th class="text-center">Tugas</th>
                            <th class="text-center">UTS</th>
                            <th class="text-center">UAS</th>
                            <th class="text-center">Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nilais as $n)
                        <tr>
                            <td>{{ $n->jadwal->mata_pelajaran->nama_mapel }}</td>
                            <td class="text-center">{{ $n->nilai_tugas ?? '-' }}</td>
                            <td class="text-center">{{ $n->nilai_uts ?? '-' }}</td>
                            <td class="text-center">{{ $n->nilai_uas ?? '-' }}</td>
                            <td class="text-center fw-bold text-primary">{{ $n->nilai_akhir ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada data nilai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
