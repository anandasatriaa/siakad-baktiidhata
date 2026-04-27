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
use App\Http\Controllers\AcademicSiswaController;
use App\Http\Controllers\AcademicGuruController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('guru', GuruController::class);
    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('mapel', MataPelajaranController::class);
    Route::resource('jadwal', JadwalController::class);
    Route::resource('pengumuman', PengumumanController::class);

    // Piket
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::resource('keterlambatan', KeterlambatanController::class);

    // Akademik Guru
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
    Route::resource('agenda', AgendaController::class);

    // Akademik Guru Tambahan
    Route::get('/jadwal-mengajar', [AcademicGuruController::class, 'jadwal'])->name('guru.jadwal-mengajar');
    Route::get('/data-siswa-ajar', [AcademicGuruController::class, 'dataSiswa'])->name('guru.data-siswa-ajar');
    Route::get('/rekap-nilai', [AcademicGuruController::class, 'rekapNilai'])->name('guru.rekap-nilai');
    Route::get('/export-nilai-pdf/{jadwal_id}', [AcademicGuruController::class, 'exportPdf'])->name('guru.export-nilai-pdf');
    Route::get('/export-nilai-excel/{jadwal_id}', [AcademicGuruController::class, 'exportExcel'])->name('guru.export-nilai-excel');

    // Akademik Siswa
    Route::get('/my-jadwal', [AcademicSiswaController::class, 'jadwal'])->name('siswa.my-jadwal');
    Route::get('/my-absensi', [AcademicSiswaController::class, 'absensi'])->name('siswa.my-absensi');
    Route::get('/my-keterlambatan', [AcademicSiswaController::class, 'keterlambatan'])->name('siswa.my-keterlambatan');
    Route::get('/my-nilai', [AcademicSiswaController::class, 'nilai'])->name('siswa.my-nilai');

    // Role-based routes can be enclosed in middleware groups.
    // Example:
    /*
    Route::middleware(['role:super_admin,admin'])->group(function() {
        Route::get('/master-data/guru', [GuruController::class, 'index']);
    });
    */
});
