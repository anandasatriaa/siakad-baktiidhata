@extends('layouts.app')

@section('title', 'Input Rapor Wali Kelas')
@section('subtitle', 'Input catatan dan ekstrakurikuler siswa')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Input Rapor - {{ $siswa->nama_lengkap }} ({{ $kelas->nama_kelas }})</h4>
            <a href="{{ route('guru.rekap-nilai', ['kelas_id' => $kelas->id, 'periode_id' => $tahun_akademik->id]) }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('guru.store-rapor', ['siswa_id' => $siswa->id, 'kelas_id' => $kelas->id, 'periode_id' => $tahun_akademik->id]) }}" method="POST">
                @csrf
                
                <h5 class="mt-3">Kokurikuler</h5>
                <hr>
                <div class="form-group mb-3">
                    <label for="kokurikuler">Deskripsi Kokurikuler</label>
                    <textarea name="kokurikuler" id="kokurikuler" class="form-control" rows="4">{{ old('kokurikuler', $catatan->kokurikuler ?? '') }}</textarea>
                </div>

                <h5 class="mt-4">Ekstrakurikuler</h5>
                <hr>
                <div id="ekstrakurikuler-container">
                    @forelse($ekstrakurikuler as $index => $ekstra)
                        <div class="row mb-3 ekstra-row">
                            <div class="col-md-3">
                                <label>Nama Kegiatan</label>
                                <input type="text" name="ekstrakurikuler[{{ $index }}][nama_kegiatan]" class="form-control" value="{{ $ekstra->nama_kegiatan }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="d-block mb-1">Predikat</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ekstrakurikuler[{{ $index }}][predikat]" value="Sangat Baik" {{ $ekstra->predikat == 'Sangat Baik' ? 'checked' : '' }}>
                                    <label class="form-check-label">Sangat Baik</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ekstrakurikuler[{{ $index }}][predikat]" value="Baik" {{ $ekstra->predikat == 'Baik' ? 'checked' : '' }}>
                                    <label class="form-check-label">Baik</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ekstrakurikuler[{{ $index }}][predikat]" value="Cukup" {{ $ekstra->predikat == 'Cukup' ? 'checked' : '' }}>
                                    <label class="form-check-label">Cukup</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ekstrakurikuler[{{ $index }}][predikat]" value="Kurang" {{ $ekstra->predikat == 'Kurang' ? 'checked' : '' }}>
                                    <label class="form-check-label">Kurang</label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Keterangan</label>
                                <input type="text" name="ekstrakurikuler[{{ $index }}][keterangan]" class="form-control" value="{{ $ekstra->keterangan }}">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-remove-ekstra"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    @empty
                        <div class="row mb-3 ekstra-row">
                            <div class="col-md-3">
                                <label>Nama Kegiatan</label>
                                <input type="text" name="ekstrakurikuler[0][nama_kegiatan]" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="d-block mb-1">Predikat</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ekstrakurikuler[0][predikat]" value="Sangat Baik">
                                    <label class="form-check-label">Sangat Baik</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ekstrakurikuler[0][predikat]" value="Baik">
                                    <label class="form-check-label">Baik</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ekstrakurikuler[0][predikat]" value="Cukup">
                                    <label class="form-check-label">Cukup</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ekstrakurikuler[0][predikat]" value="Kurang">
                                    <label class="form-check-label">Kurang</label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Keterangan</label>
                                <input type="text" name="ekstrakurikuler[0][keterangan]" class="form-control">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-remove-ekstra"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-sm btn-info mb-3" id="btn-add-ekstra"><i class="bi bi-plus"></i> Tambah Ekstrakurikuler</button>

                <h5 class="mt-4">Catatan Wali Kelas</h5>
                <hr>
                <div class="form-group mb-3">
                    <label for="catatan">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="4">{{ old('catatan', $catatan->catatan ?? '') }}</textarea>
                </div>

                @if(stripos($tahun_akademik->semester, 'genap') !== false)
                <h5 class="mt-4">Keputusan Akhir Tahun</h5>
                <hr>
                <div class="form-group mb-4">
                    <label class="d-block mb-2">Berdasarkan pencapaian hasil belajar dari semester ganjil dan genap, siswa ditetapkan:</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="keputusan" id="keputusan_naik" value="Naik ke kelas" {{ old('keputusan', $catatan->keputusan ?? '') == 'Naik ke kelas' ? 'checked' : '' }}>
                        <label class="form-check-label" for="keputusan_naik">Naik ke kelas</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="keputusan" id="keputusan_tinggal" value="Tinggal Kelas" {{ old('keputusan', $catatan->keputusan ?? '') == 'Tinggal Kelas' ? 'checked' : '' }}>
                        <label class="form-check-label" for="keputusan_tinggal">Tinggal Kelas</label>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-save"></i> Simpan Rapor</button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let ekstraIndex = {{ max(1, count($ekstrakurikuler)) }};
        const container = document.getElementById('ekstrakurikuler-container');
        const btnAdd = document.getElementById('btn-add-ekstra');

        btnAdd.addEventListener('click', function() {
            const html = `
                <div class="row mb-3 ekstra-row">
                    <div class="col-md-3">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="ekstrakurikuler[${ekstraIndex}][nama_kegiatan]" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="d-block mb-1">Predikat</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ekstrakurikuler[${ekstraIndex}][predikat]" value="Sangat Baik">
                            <label class="form-check-label">Sangat Baik</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ekstrakurikuler[${ekstraIndex}][predikat]" value="Baik">
                            <label class="form-check-label">Baik</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ekstrakurikuler[${ekstraIndex}][predikat]" value="Cukup">
                            <label class="form-check-label">Cukup</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ekstrakurikuler[${ekstraIndex}][predikat]" value="Kurang">
                            <label class="form-check-label">Kurang</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label>Keterangan</label>
                        <input type="text" name="ekstrakurikuler[${ekstraIndex}][keterangan]" class="form-control">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-remove-ekstra"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            ekstraIndex++;
        });

        container.addEventListener('click', function(e) {
            if(e.target.closest('.btn-remove-ekstra')) {
                e.target.closest('.ekstra-row').remove();
            }
        });
    });
</script>
@endsection
