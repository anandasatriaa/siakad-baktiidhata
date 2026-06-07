<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('nama');
            $table->string('nuptk')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nip')->nullable();
            $table->string('status_kepegawaian')->nullable();
            $table->string('jenis_ptk')->nullable();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->string('jenjang')->nullable();
            $table->string('jurusan_prodi')->nullable();
            $table->string('sertifikasi')->nullable();
            $table->date('tmt_kerja')->nullable();
            $table->text('tugas_tambahan')->nullable();
            $table->text('mengajar')->nullable();
            $table->integer('jam_tugas_tambahan')->nullable();
            $table->integer('jjm')->nullable();
            $table->integer('total_jjm')->nullable();
            $table->integer('jumlah_siswa')->nullable();
            $table->string('kompetensi')->nullable();
            $table->string('nik')->nullable();
            $table->string('jabatan_ptk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
