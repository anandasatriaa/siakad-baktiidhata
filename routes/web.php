<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KeterlambatanController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AkademikSiswaController;
use App\Http\Controllers\AkademikGuruController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::get('guru/export-pdf', [GuruController::class, 'exportPdf'])->name('guru.export-pdf');
    Route::get('guru/export-excel', [GuruController::class, 'exportExcel'])->name('guru.export-excel');
    Route::resource('guru', GuruController::class);
    Route::get('siswa/export-pdf', [SiswaController::class, 'exportPdf'])->name('siswa.export-pdf');
    Route::get('siswa/export-excel', [SiswaController::class, 'exportExcel'])->name('siswa.export-excel');
    Route::get('siswa/template-excel', [SiswaController::class, 'downloadTemplate'])->name('siswa.template-excel');
    Route::post('siswa/import-excel', [SiswaController::class, 'importExcel'])->name('siswa.import-excel');
    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('mapel', MataPelajaranController::class);
    Route::resource('jadwal', JadwalController::class);
    Route::resource('pengumuman', PengumumanController::class);
    Route::resource('tahun-akademik', TahunAkademikController::class);
    Route::post('tahun-akademik/{id}/activate', [TahunAkademikController::class, 'activate'])->name('tahun-akademik.activate');
    Route::resource('users', UserController::class);

    // Piket
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::resource('keterlambatan', KeterlambatanController::class);

    // Akademik Guru
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::resource('agenda', AgendaController::class);

    // Akademik Guru Tambahan
    Route::get('/jadwal-mengajar', [AkademikGuruController::class, 'jadwal'])->name('guru.jadwal-mengajar');
    Route::get('/data-siswa-ajar', [AkademikGuruController::class, 'dataSiswa'])->name('guru.data-siswa-ajar');
    Route::get('/rekap-nilai', [AkademikGuruController::class, 'rekapNilai'])->name('guru.rekap-nilai');
    Route::get('/export-nilai-pdf', [AkademikGuruController::class, 'exportPdf'])->name('guru.export-nilai-pdf');
    Route::get('/export-nilai-excel', [AkademikGuruController::class, 'exportExcel'])->name('guru.export-nilai-excel');
    Route::get('/input-rapor/{siswa_id}/{kelas_id}/{periode_id}', [AkademikGuruController::class, 'inputRapor'])->name('guru.input-rapor');
    Route::post('/input-rapor/{siswa_id}/{kelas_id}/{periode_id}', [AkademikGuruController::class, 'storeRapor'])->name('guru.store-rapor');

    // Akademik Siswa
    Route::get('/my-jadwal', [AkademikSiswaController::class, 'jadwal'])->name('siswa.my-jadwal');
    Route::get('/my-absensi', [AkademikSiswaController::class, 'absensi'])->name('siswa.my-absensi');
    Route::get('/my-keterlambatan', [AkademikSiswaController::class, 'keterlambatan'])->name('siswa.my-keterlambatan');
    Route::get('/my-nilai', [AkademikSiswaController::class, 'nilai'])->name('siswa.my-nilai');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Laporan
    Route::get('/laporan-absensi-keterlambatan', [LaporanController::class, 'absensiKeterlambatan'])->name('laporan.absensi-keterlambatan');
    Route::get('/laporan-absensi-keterlambatan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
    Route::get('/laporan-absensi-keterlambatan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');

});

