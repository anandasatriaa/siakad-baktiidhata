<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold; font-size: 14pt;">LAPORAN ABSENSI DAN KETERLAMBATAN</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold; font-size: 12pt;">SMK BAKTI IDHATA</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; font-size: 10pt;">
                Tahun Akademik: {{ $tahun_akademik ? $tahun_akademik->tahun_ajaran . ' (' . ucfirst($tahun_akademik->semester) . ')' : '-' }} | Kelas: {{ $info_kelas ? $info_kelas->nama_kelas : 'Semua Kelas' }} | Periode: {{ date('d/m/Y', strtotime($tanggal_mulai)) }} - {{ date('d/m/Y', strtotime($tanggal_selesai)) }}
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">No</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #D3D3D3;">Nama Siswa</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Kelas</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Hadir (H)</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Sakit (S)</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Izin (I)</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Alpa (A)</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Terlambat</th>
        </tr>
    </thead>
    <tbody>
        @forelse($siswas as $siswa)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $loop->iteration }}</td>
            <td style="border: 1px solid #000;">{{ $siswa->nama_lengkap }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $siswa->nama_kelas }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $siswa->rekap_absensi['Hadir'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $siswa->rekap_absensi['Sakit'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $siswa->rekap_absensi['Izin'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $siswa->rekap_absensi['Alpa'] }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $siswa->total_keterlambatan }}x</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="border: 1px solid #000; text-align: center;">Belum ada data rekapitulasi pada periode ini.</td>
        </tr>
        @endforelse
    </tbody>
</table>
