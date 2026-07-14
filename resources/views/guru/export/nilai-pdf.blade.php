<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Nilai Siswa</title>
    <style>
        @page {
            margin-top: 160px;
            margin-bottom: 60px;
            margin-left: 45px;
            margin-right: 45px;
        }

        body {
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.35;
        }

        header {
            position: fixed;
            top: -135px;
            left: 0px;
            right: 0px;
            height: 115px;
            border-bottom: 2px solid #000;
        }

        footer {
            position: fixed; 
            bottom: -40px; 
            left: 0px; 
            right: 0px;
            height: 25px; 
            font-size: 10pt;
            font-style: italic;
            border-top: 2px solid #000;
            padding-top: 5px;
        }
        
        .page-number:after { 
            content: counter(page); 
        }

        .document-title {
            margin: 4px 0 14px;
            text-align: center;
        }

        .document-title h1 {
            display: inline-block;
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
        }

        .identity {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .identity td {
            padding: 2px 0;
            line-height: 1.5;
            vertical-align: top;
        }

        .identity .label {
            width: 15%;
        }

        .identity .separator {
            width: 2%;
            text-align: center;
        }

        .identity .value {
            width: 38%;
            padding-left: 15px;
        }
        
        .identity td:nth-child(6) {
            padding-left: 15px;
        }

        .data {
            width: 100%;
            border-collapse: collapse;
        }

        .data th,
        .data td {
            padding: 6px 7px;
            border: 1px solid #111827;
        }

        .data th {
            background: #e4e6eb;
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
        }

        .data tbody tr:nth-child(even) td {
            background: transparent;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .score-final {
            font-weight: 700;
        }

        .note {
            margin-top: 10px;
            color: #4b5563;
            font-size: 10px;
        }

        .signature-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 33.33%;
            vertical-align: top;
        }

        .signature-box {
            width: 100%;
            text-align: center;
        }

        .signature-space {
            height: 58px;
        }

        .signature-name {
            display: inline-block;
            min-width: 180px;
            padding-top: 2px;
            border-bottom: 1px solid #111827;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <footer>
        <table style="width: 100%">
            <tr>
                <td style="text-align: left;">| {{ $kelas->nama_kelas }} | {{ strtoupper($siswa->nama_lengkap) }} | {{ $siswa->nis }}</td>
                <td style="text-align: right;">Halaman: <span class="page-number"></span></td>
            </tr>
        </table>
    </footer>

    <header>
        <table class="identity">
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td class="value">{{ strtoupper($siswa->nama_lengkap) }}</td>
                <td class="label" style="width: 15%;">Kelas</td>
                <td class="separator">:</td>
                <td style="width: 28%;">{{ strtoupper($kelas->nama_kelas) }}</td>
            </tr>
            <tr>
                <td class="label">NIS / NISN</td>
                <td class="separator">:</td>
                <td class="value">{{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td>
                <td class="label">Fase</td>
                <td class="separator">:</td>
                <td>{{ $kelas->tingkat == 10 ? 'E' : 'F' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Sekolah</td>
                <td class="separator">:</td>
                <td class="value">{{ strtoupper($sekolah['nama'] ?? 'SMKS BAKTI IDHATA') }}</td>
                <td class="label">Semester</td>
                <td class="separator">:</td>
                <td>{{ ucfirst($tahun_akademik->semester) }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td class="value">{{ strtoupper($sekolah['alamat'] ?? 'JL. MELATI NO. 25 CILANDAK') }}</td>
                <td class="label">Tahun Pelajaran</td>
                <td class="separator">:</td>
                <td>{{ $tahun_akademik->tahun_ajaran }}</td>
            </tr>
        </table>

    </header>

    <main>

        <div class="document-title">
            <h1>LAPORAN HASIL BELAJAR</h1>
        </div>

    <table class="data">
        <thead>
            <tr>
                <th width="28">No</th>
                <th width="150">Mata Pelajaran</th>
                <th width="60">Nilai Akhir</th>
                <th>Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" style="font-weight: bold;">A. Kelompok Mata Pelajaran Umum</td>
            </tr>
            @forelse($nilais as $n)
                @php
                    $nilaiAkhir = $n->nilai_akhir;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $n->mata_pelajaran->nama_mapel ?? '-' }}</td>
                    <td class="text-center score-final">{{ $nilaiAkhir ?? '-' }}</td>
                    <td style="text-align: justify;">{{ $n->capaian_kompetensi ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data nilai untuk mata pelajaran dan kelas ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>    <br>

    @if($catatan && $catatan->kokurikuler)
    <table class="data">
        <thead>
            <tr>
                <th style="text-align: center;">Kokurikuler</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: justify; padding: 10px;">{{ $catatan->kokurikuler }}</td>
            </tr>
        </tbody>
    </table>
    <div style="height: 15px;"></div>
    @endif

    <div style="page-break-before: always;"></div>

    @if($ekstrakurikuler && count($ekstrakurikuler) > 0)
    <table class="data">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="150">Kegiatan Ekstrakurikuler</th>
                <th width="80">Predikat</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ekstrakurikuler as $ekstra)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $ekstra->nama_kegiatan }}</td>
                <td class="text-center">{{ $ekstra->predikat ?? '-' }}</td>
                <td style="text-align: justify;">{{ $ekstra->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="height: 15px;"></div>
    @endif

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 40%; vertical-align: top; padding-right: 10px;">
                <table class="data">
                    <thead>
                        <tr>
                            <th colspan="3" style="text-align: center;">Ketidakhadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sakit</td>
                            <td class="text-center" width="10">:</td>
                            <td>{{ $sakit ?? 0 }} Hari</td>
                        </tr>
                        <tr>
                            <td>Izin</td>
                            <td class="text-center" width="10">:</td>
                            <td>{{ $izin ?? 0 }} Hari</td>
                        </tr>
                        <tr>
                            <td>Tanpa Keterangan</td>
                            <td class="text-center" width="10">:</td>
                            <td>{{ $tanpa_keterangan ?? 0 }} Hari</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 60%; vertical-align: top; padding-left: 10px;">
                <table class="data">
                    <thead>
                        <tr>
                            <th style="text-align: center;">Catatan Wali Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: justify; padding: 10px; height: 75px; vertical-align: top;">
                                {{ $catatan->catatan ?? '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <div style="height: 15px;"></div>

    <table class="data">
        <thead>
            <tr>
                <th style="text-align: center;">Tanggapan Orang Tua/Wali Murid</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="height: 60px;"></td>
            </tr>
        </tbody>
    </table>
    <div style="height: 15px;"></div>

    @if($catatan && $catatan->keputusan && stripos($tahun_akademik->semester, 'genap') !== false)
    <div style="margin-bottom: 20px;">
        <h3 style="font-size: 14pt; margin-bottom: 10px;">Keputusan</h3>
        <table style="width: 100%; border: 1px solid #000; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px;">
                    Berdasarkan pencapaian hasil belajar dari semester ganjil dan genap, siswa ditetapkan:<br><br>
                    <strong>
                        @php
                            $nextTingkat = $kelas->tingkat + 1;
                            $angkaTerbilang = [
                                11 => 'sebelas',
                                12 => 'dua belas',
                                13 => 'tiga belas'
                            ];
                            $terbilang = $angkaTerbilang[$nextTingkat] ?? '';
                            $naikText = "Naik ke kelas {$nextTingkat} " . ($terbilang ? "({$terbilang})" : "");
                        @endphp
                        @if($catatan->keputusan == 'Naik ke kelas')
                            {{ $naikText }} / <strike>Tinggal Kelas</strike>
                        @elseif($catatan->keputusan == 'Tinggal Kelas')
                            <strike>{{ $naikText }}</strike> / Tinggal Kelas
                        @else
                            {{ $naikText }} / Tinggal Kelas
                        @endif
                    </strong>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-box">
                    <p style="margin: 0 0 4px;">Mengetahui,</p>
                    <p style="margin: 0 0 4px;">Orang Tua/Wali,</p>
                    <div class="signature-space"></div>
                    <p class="signature-name" style="margin-bottom: 2px; border-bottom: 1px solid #000;">........................</p>
                </div>
            </td>
            <td>
                <div class="signature-box">
                    <p style="margin: 0 0 4px;">Mengetahui</p>
                    <p style="margin: 0 0 4px;">Kepala Sekolah,</p>
                    <div class="signature-space"></div>
                    <p class="signature-name" style="margin-bottom: 2px; border-bottom: 1px solid #000;">{{ $nama_kepsek }}</p>
                    <p style="margin:0;">NIP. {{ $nip_kepsek }}</p>
                </div>
            </td>
            <td>
                <div class="signature-box">
                    <p style="margin: 0 0 4px;">Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
                    <p style="margin: 0 0 4px;">Wali Kelas,</p>
                    <div class="signature-space"></div>
                    <p class="signature-name" style="margin-bottom: 2px; border-bottom: 1px solid #000;">{{ $nama_wali }}</p>
                    <p style="margin:0;">NIP. {{ $nip_wali ?? '-' }}</p>
                </div>
            </td>
        </tr>
    </table>
    </main>
</body>
</html>
