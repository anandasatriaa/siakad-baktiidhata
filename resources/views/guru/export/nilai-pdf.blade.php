<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Nilai Siswa</title>
    <style>
        @page {
            margin: 24px 32px 32px;
        }

        body {
            color: #111827;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.35;
        }

        .kop {
            position: relative;
            min-height: 86px;
            padding-bottom: 10px;
            border-bottom: 3px solid #111827;
        }

        .kop::after {
            content: "";
            position: absolute;
            right: 0;
            bottom: -7px;
            left: 0;
            border-bottom: 1px solid #111827;
        }

        .logo {
            position: absolute;
            top: 2px;
            left: 0;
            width: 78px;
            height: 78px;
            object-fit: contain;
        }

        .kop-text {
            padding: 0 20px 0 96px;
            text-align: center;
        }

        .yayasan {
            margin: 0 0 2px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
        }

        .school-name {
            margin: 0;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .school-meta {
            margin: 2px 0 0;
            font-size: 10px;
        }

        .document-title {
            margin: 24px 0 14px;
            text-align: center;
        }

        .document-title h1 {
            display: inline-block;
            margin: 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #111827;
            font-size: 14px;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .identity {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .identity td {
            padding: 3px 4px;
            vertical-align: top;
        }

        .identity .label {
            width: 110px;
            color: #374151;
            font-weight: 700;
        }

        .identity .separator {
            width: 8px;
            text-align: center;
        }

        .identity .value {
            width: 230px;
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
            background: #e5e7eb;
            font-size: 10px;
            text-align: center;
            text-transform: uppercase;
        }

        .data tbody tr:nth-child(even) td {
            background: #f9fafb;
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
            width: 50%;
            vertical-align: top;
        }

        .signature-box {
            width: 220px;
            text-align: center;
        }

        .signature-right {
            margin-left: auto;
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
    <div class="kop">
        @if (!empty($logoPath) && file_exists($logoPath))
            <img class="logo" src="{{ $logoPath }}" alt="Logo Sekolah">
        @endif

        <div class="kop-text">
            <p class="yayasan">{{ $sekolah['yayasan'] ?? 'Yayasan Pendidikan' }}</p>
            <h2 class="school-name">{{ $sekolah['nama'] ?? 'Nama Sekolah' }}</h2>
            <p class="school-meta">{{ $sekolah['alamat'] ?? 'Alamat sekolah' }}</p>
            <p class="school-meta">
                {{ $sekolah['kontak'] ?? 'Kontak sekolah' }}
                @if (!empty($sekolah['website']))
                    | {{ $sekolah['website'] }}
                @endif
            </p>
        </div>
    </div>

    <div class="document-title">
        <h1>Rekapitulasi Nilai Peserta Didik (Rapor)</h1>
    </div>

    <table class="identity">
        <tr>
            <td class="label">Nama Peserta Didik</td>
            <td class="separator">:</td>
            <td class="value">{{ $siswa->nama_lengkap }}</td>
            <td class="label">Kelas</td>
            <td class="separator">:</td>
            <td>{{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td class="label">NIS / NISN</td>
            <td class="separator">:</td>
            <td class="value">{{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td>
            <td class="label">Semester</td>
            <td class="separator">:</td>
            <td>{{ $tahun_akademik->semester }}</td>
        </tr>
        <tr>
            <td class="label">Sekolah</td>
            <td class="separator">:</td>
            <td class="value">{{ $sekolah['nama'] ?? 'SMK BAKTI IDHATA' }}</td>
            <td class="label">Tahun Pelajaran</td>
            <td class="separator">:</td>
            <td>{{ $tahun_akademik->tahun_ajaran }}</td>
        </tr>
    </table>

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
    </table>

    <p class="note">
        Dokumen ini merupakan rekapitulasi nilai peserta didik berdasarkan data akademik yang tercatat pada sistem.
    </p>

    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-box">
                    <p>Mengetahui,</p>
                    <p>Orang Tua / Wali</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">&nbsp;</p>
                </div>
            </td>
            <td>
                <div class="signature-box signature-right">
                    <p>Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
                    <p>Wali Kelas</p>
                    <div class="signature-space"></div>
                    <p class="signature-name">{{ $nama_wali }}</p>
                    <p>NIP. {{ $nip_wali ?? '-' }}</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
