<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Guru - SMK Bakti Idhata</title>
    <style>
        @page {
            margin-top: 140px;
            margin-bottom: 60px;
            margin-left: 40px;
            margin-right: 40px;
        }

        body {
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.35;
        }

        header {
            position: fixed;
            top: -115px;
            left: 0px;
            right: 0px;
            height: 95px;
            border-bottom: 2px solid #000;
        }

        footer {
            position: fixed; 
            bottom: -40px; 
            left: 0px; 
            right: 0px;
            height: 25px; 
            font-size: 9pt;
            font-style: italic;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        
        .page-number:after { 
            content: counter(page); 
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            text-align: center;
        }

        .header-title h2 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
        }

        .header-title h1 {
            margin: 3px 0;
            font-size: 16pt;
            font-weight: bold;
        }

        .header-title p {
            margin: 0;
            font-size: 9pt;
        }

        .document-title {
            margin: 10px 0 15px;
            text-align: center;
        }

        .document-title h3 {
            display: inline-block;
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 10pt;
        }

        .data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .data th,
        .data td {
            padding: 6px 8px;
            border: 1px solid #111827;
        }

        .data th {
            background: #e4e6eb;
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
        }

        .data td {
            font-size: 9.5pt;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <footer>
        <table style="width: 100%">
            <tr>
                <td style="text-align: left;">SMK BAKTI IDHATA | Laporan Data Guru</td>
                <td style="text-align: right;">Halaman <span class="page-number"></span></td>
            </tr>
        </table>
    </footer>

    <header>
        <table class="header-table">
            <tr>
                <td class="header-title">
                    <h2>YAYASAN PENDIDIKAN BAKTI IDHATA</h2>
                    <h1>SMK BAKTI IDHATA</h1>
                    <p>{{ $sekolah['alamat'] ?? 'Jl. Melati No. 25 Cilandak' }} | {{ $sekolah['kontak'] ?? 'Telp. (021) 7500000 | Email: info@smkbaktiidhata.sch.id' }}</p>
                </td>
            </tr>
        </table>
    </header>

    <main>
        <div class="document-title">
            <h3>DATA GURU</h3>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 15%; font-weight: bold;">Tahun Akademik</td>
                <td style="width: 2%;">:</td>
                <td style="width: 45%;">{{ $tahun_akademik ? $tahun_akademik->tahun_ajaran . ' (' . ucfirst($tahun_akademik->semester) . ')' : '-' }}</td>
                <td style="width: 17%; font-weight: bold;">Tanggal Export</td>
                <td style="width: 2%;">:</td>
                <td style="width: 19%;">{{ date('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Total Guru</td>
                <td>:</td>
                <td>{{ $gurus->count() }} Guru</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <table class="data">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="100">NIP</th>
                    <th width="100">NUPTK</th>
                    <th>Nama Lengkap</th>
                    <th width="90">Jenis Kelamin</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th width="110">Jenis PTK</th>
                    <th>Status Kepegawaian</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gurus as $guru)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $guru->nip ?? '-' }}</td>
                        <td class="text-center">{{ $guru->nuptk ?? '-' }}</td>
                        <td>{{ trim(($guru->gelar_depan ? $guru->gelar_depan . ' ' : '') . $guru->nama . ($guru->gelar_belakang ? ', ' . $guru->gelar_belakang : '')) }}</td>
                        <td class="text-center">{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $guru->user->email ?? '-' }}</td>
                        <td>{{ $guru->user->password_plain ?? '-' }}</td>
                        <td class="text-center">{{ $guru->jenis_ptk ?? '-' }}</td>
                        <td class="text-center">{{ $guru->status_kepegawaian ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data guru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
