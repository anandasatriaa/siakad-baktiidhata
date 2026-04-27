<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 14pt;">REKAPITULASI NILAI SISWA</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 12pt;">SMK BAKTI IDHATA</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold;">Mata Pelajaran</th>
            <th colspan="2">: {{ $jadwal->mata_pelajaran->nama_mapel }}</th>
            <th style="font-weight: bold;">Kelas</th>
            <th colspan="3">: {{ $jadwal->kelas->nama_kelas }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Guru Pengajar</th>
            <th colspan="2">: {{ $jadwal->guru->nama_lengkap }}</th>
            <th style="font-weight: bold;">Tahun Akademik</th>
            <th colspan="3">: {{ $jadwal->tahun_akademik->tahun_ajaran }} ({{ $jadwal->tahun_akademik->semester }})</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">No</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #D3D3D3;">NIS</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #D3D3D3;">Nama Siswa</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Tugas</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">UTS</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">UAS</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Nilai Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($nilais as $n)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $loop->iteration }}</td>
            <td style="border: 1px solid #000;">{{ $n->siswa->nis }}</td>
            <td style="border: 1px solid #000;">{{ $n->siswa->nama_lengkap }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $n->nilai_tugas ?? 0 }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $n->nilai_uts ?? 0 }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $n->nilai_uas ?? 0 }}</td>
            <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $n->nilai_akhir ?? 0 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
