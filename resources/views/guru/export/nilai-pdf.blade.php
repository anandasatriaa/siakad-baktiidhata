<!DOCTYPE html>
<html>
<head>
    <title>Rekap Nilai Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin-bottom: 5px; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #000; padding: 8px; text-align: left; }
        table.data th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAPITULASI NILAI SISWA</h2>
        <h3>SMK BAKTI IDHATA</h3>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="150">Mata Pelajaran</td>
                <td>: {{ $jadwal->mata_pelajaran->nama_mapel }}</td>
                <td width="150">Kelas</td>
                <td>: {{ $jadwal->kelas->nama_kelas }}</td>
            </tr>
            <tr>
                <td>Guru Pengajar</td>
                <td>: {{ $jadwal->guru->nama_lengkap }}</td>
                <td>Tahun Akademik</td>
                <td>: {{ $jadwal->tahun_akademik->tahun_ajaran }} ({{ $jadwal->tahun_akademik->semester }})</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="100">NIS</th>
                <th>Nama Siswa</th>
                <th width="60" class="text-center">Tugas</th>
                <th width="60" class="text-center">UTS</th>
                <th width="60" class="text-center">UAS</th>
                <th width="70" class="text-center">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilais as $n)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $n->siswa->nis }}</td>
                <td>{{ $n->siswa->nama_lengkap }}</td>
                <td class="text-center">{{ $n->nilai_tugas ?? '-' }}</td>
                <td class="text-center">{{ $n->nilai_uts ?? '-' }}</td>
                <td class="text-center">{{ $n->nilai_uas ?? '-' }}</td>
                <td class="text-center"><strong>{{ $n->nilai_akhir ?? '-' }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Jakarta, {{ date('d F Y') }}</p>
        <p>Guru Mata Pelajaran,</p>
        <br><br><br>
        <p><strong>{{ $jadwal->guru->nama_lengkap }}</strong></p>
    </div>
</body>
</html>
