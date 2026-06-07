@extends('layouts.app')

@section('title', 'Tambah Guru')
@section('subtitle', 'Tambah data guru baru')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Form Tambah Guru</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('guru.store') }}" method="POST">
                @csrf
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Akun login akan dibuatkan otomatis menggunakan '<strong>NIK@smkbaktiidhata.sch.id</strong>' sebagai email dan password default adalah '<strong>smkbaktiidhata</strong>'.
                </div>
                
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <h5 class="mb-3 border-bottom pb-2">Identitas Utama</h5>
                        
                        <div class="form-group mb-3">
                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" id="nama" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan Nama Lengkap">
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="nip">NIP</label>
                                <input type="text" id="nip" class="form-control @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NIP">
                                @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="nik">NIK <span class="text-danger">*</span></label>
                                <input type="text" id="nik" class="form-control @error('nik') is-invalid @enderror" name="nik" value="{{ old('nik') }}" required placeholder="Masukkan NIK">
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nuptk">NUPTK</label>
                            <input type="text" id="nuptk" class="form-control @error('nuptk') is-invalid @enderror" name="nuptk" value="{{ old('nuptk') }}" placeholder="Masukkan NUPTK">
                            @error('nuptk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Tempat Lahir">
                                @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                                @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4 border-bottom pb-2">Pendidikan & Sertifikasi</h5>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="gelar_depan">Gelar Depan</label>
                                <input type="text" id="gelar_depan" class="form-control @error('gelar_depan') is-invalid @enderror" name="gelar_depan" value="{{ old('gelar_depan') }}" placeholder="Cth: Dr.">
                                @error('gelar_depan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="gelar_belakang">Gelar Belakang</label>
                                <input type="text" id="gelar_belakang" class="form-control @error('gelar_belakang') is-invalid @enderror" name="gelar_belakang" value="{{ old('gelar_belakang') }}" placeholder="Cth: S.Pd">
                                @error('gelar_belakang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="jenjang">Jenjang Pendidikan</label>
                                <input type="text" id="jenjang" class="form-control @error('jenjang') is-invalid @enderror" name="jenjang" value="{{ old('jenjang') }}" placeholder="Cth: S1">
                                @error('jenjang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="jurusan_prodi">Jurusan/Prodi</label>
                                <input type="text" id="jurusan_prodi" class="form-control @error('jurusan_prodi') is-invalid @enderror" name="jurusan_prodi" value="{{ old('jurusan_prodi') }}" placeholder="Jurusan">
                                @error('jurusan_prodi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="sertifikasi">Sertifikasi</label>
                            <input type="text" id="sertifikasi" class="form-control @error('sertifikasi') is-invalid @enderror" name="sertifikasi" value="{{ old('sertifikasi') }}" placeholder="Sertifikasi">
                            @error('sertifikasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <h5 class="mb-3 border-bottom pb-2">Data Kepegawaian</h5>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="status_kepegawaian">Status Kepegawaian</label>
                                <select id="status_kepegawaian" class="form-select @error('status_kepegawaian') is-invalid @enderror" name="status_kepegawaian">
                                    <option value="">-- Pilih Status --</option>
                                    <option value="GTY/PTY" {{ old('status_kepegawaian') == 'GTY/PTY' ? 'selected' : '' }}>GTY/PTY</option>
                                    <option value="Guru Honor Sekolah" {{ old('status_kepegawaian') == 'Guru Honor Sekolah' ? 'selected' : '' }}>Guru Honor Sekolah</option>
                                </select>
                                @error('status_kepegawaian') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="jenis_ptk">Jenis PTK</label>
                                <select id="jenis_ptk" class="form-select @error('jenis_ptk') is-invalid @enderror" name="jenis_ptk">
                                    <option value="">-- Pilih Jenis PTK --</option>
                                    <option value="Kepala Sekolah" {{ old('jenis_ptk') == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                    <option value="Guru" {{ old('jenis_ptk') == 'Guru' ? 'selected' : '' }}>Guru</option>
                                    <option value="Tenaga Kependidikan" {{ old('jenis_ptk') == 'Tenaga Kependidikan' ? 'selected' : '' }}>Tenaga Kependidikan</option>
                                </select>
                                @error('jenis_ptk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="jabatan_ptk">Jabatan PTK</label>
                                <input type="text" id="jabatan_ptk" class="form-control @error('jabatan_ptk') is-invalid @enderror" name="jabatan_ptk" value="{{ old('jabatan_ptk') }}" placeholder="Jabatan">
                                @error('jabatan_ptk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="tmt_kerja">TMT Kerja</label>
                                <input type="date" id="tmt_kerja" class="form-control @error('tmt_kerja') is-invalid @enderror" name="tmt_kerja" value="{{ old('tmt_kerja') }}">
                                @error('tmt_kerja') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        
                        <h5 class="mb-3 mt-4 border-bottom pb-2">Tugas & Mengajar</h5>
                        <div class="form-group mb-3">
                            <label for="kompetensi">Kompetensi</label>
                            <input type="text" id="kompetensi" class="form-control @error('kompetensi') is-invalid @enderror" name="kompetensi" value="{{ old('kompetensi') }}" placeholder="Kompetensi keahlian">
                            @error('kompetensi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="mengajar">Mata Pelajaran yang Diajarkan</label>
                            <textarea id="mengajar" class="form-control @error('mengajar') is-invalid @enderror" name="mengajar" rows="2" placeholder="Sebutkan mata pelajaran">{{ old('mengajar') }}</textarea>
                            @error('mengajar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="tugas_tambahan">Tugas Tambahan</label>
                            <textarea id="tugas_tambahan" class="form-control @error('tugas_tambahan') is-invalid @enderror" name="tugas_tambahan" rows="2" placeholder="Cth: Wali Kelas, dll">{{ old('tugas_tambahan') }}</textarea>
                            @error('tugas_tambahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="jjm">JJM</label>
                                <input type="number" id="jjm" class="form-control @error('jjm') is-invalid @enderror" name="jjm" value="{{ old('jjm') }}">
                                @error('jjm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="jam_tugas_tambahan">Jam Tugas Tambahan</label>
                                <input type="number" id="jam_tugas_tambahan" class="form-control @error('jam_tugas_tambahan') is-invalid @enderror" name="jam_tugas_tambahan" value="{{ old('jam_tugas_tambahan') }}">
                                @error('jam_tugas_tambahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label for="total_jjm">Total JJM</label>
                                <input type="number" id="total_jjm" class="form-control @error('total_jjm') is-invalid @enderror" name="total_jjm" value="{{ old('total_jjm') }}">
                                @error('total_jjm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="jumlah_siswa">Jumlah Siswa</label>
                                <input type="number" id="jumlah_siswa" class="form-control @error('jumlah_siswa') is-invalid @enderror" name="jumlah_siswa" value="{{ old('jumlah_siswa') }}">
                                @error('jumlah_siswa') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('guru.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
