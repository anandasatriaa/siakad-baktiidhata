<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi dan Keterlambatan - SMK Bakti Idhata</title>
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

        .font-bold {
            font-weight: bold;
        }

        .text-danger {
            color: #dc2626;
        }
    </style>
</head>
<body>
    <footer>
        <table style="width: 100%">
            <tr>
                <td style="text-align: left;">SMK BAKTI IDHATA | Laporan Absensi dan Keterlambatan</td>
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
            <h3>LAPORAN ABSENSI DAN KETERLAMBATAN</h3>
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 15%; font-weight: bold;">Tahun Akademik</td>
                <td style="width: 2%;">:</td>
                <td style="width: 35%;">{{ $tahun_akademik ? $tahun_akademik->tahun_ajaran . ' (' . ucfirst($tahun_akademik->semester) . ')' : '-' }}</td>
                <td style="width: 15%; font-weight: bold;">Periode Tanggal</td>
                <td style="width: 2%;">:</td>
                <td style="width: 31%;">{{ date('d/m/Y', strtotime($tanggal_mulai)) }} s/d {{ date('d/m/Y', strtotime($tanggal_selesai)) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Kelas</td>
                <td>:</td>
                <td>{{ $info_kelas ? $info_kelas->nama_kelas : 'Semua Kelas' }}</td>
                <td style="font-weight: bold;">Tanggal Export</td>
                <td>:</td>
                <td>{{ date('d/m/Y H:i') }}</td>
            </tr>
        </table>

        <table class="data">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th>Nama Siswa</th>
                    <th width="80">Kelas</th>
                    <th width="40">H</th>
                    <th width="40">S</th>
                    <th width="40">I</th>
                    <th width="40">A</th>
                    <th width="80">Terlambat</th>
                    <th width="90">Total Menit</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswas as $siswa)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $siswa->nama_lengkap }}</td>
                        <td class="text-center">{{ $siswa->nama_kelas }}</td>
                        <td class="text-center">{{ $siswa->rekap_absensi['Hadir'] }}</td>
                        <td class="text-center">{{ $siswa->rekap_absensi['Sakit'] }}</td>
                        <td class="text-center">{{ $siswa->rekap_absensi['Izin'] }}</td>
                        <td class="text-center font-bold {{ $siswa->rekap_absensi['Alpa'] > 0 ? 'text-danger' : '' }}">{{ $siswa->rekap_absensi['Alpa'] }}</td>
                        <td class="text-center font-bold {{ $siswa->total_keterlambatan > 0 ? 'text-danger' : '' }}">{{ $siswa->total_keterlambatan }}x</td>
                        <td class="text-center">{{ $siswa->total_menit }} Menit</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data rekapitulasi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
