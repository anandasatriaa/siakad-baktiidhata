<table>
    <thead>
        <tr>
            <th colspan="9" style="text-align: center; font-weight: bold; font-size: 14pt;">DATA SISWA</th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center; font-weight: bold; font-size: 12pt;">SMK BAKTI IDHATA</th>
        </tr>
        <tr>
            <th colspan="9" style="text-align: center; font-size: 10pt;">Tahun Akademik: {{ $tahun_akademik ? $tahun_akademik->tahun_ajaran . ' (' . ucfirst($tahun_akademik->semester) . ')' : '-' }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">No</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">NIS</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #D3D3D3;">Nama Lengkap</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Kelas</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Jenis Kelamin</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #D3D3D3;">Email</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #D3D3D3;">Password</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">No. HP</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #D3D3D3;">Alamat</th>
        </tr>
    </thead>
    <tbody>
        @forelse($siswas as $siswa)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $loop->iteration }}</td>
            <td style="border: 1px solid #000; text-align: center; mso-number-format:'\@';">{{ $siswa->nis }}</td>
            <td style="border: 1px solid #000;">{{ $siswa->nama_lengkap }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $siswa->kelas->kelas->nama_kelas ?? '-' }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td style="border: 1px solid #000;">{{ $siswa->user->email ?? '-' }}</td>
            <td style="border: 1px solid #000;">{{ $siswa->user->password_plain ?? '-' }}</td>
            <td style="border: 1px solid #000; text-align: center; mso-number-format:'\@';">{{ $siswa->no_hp ?? '-' }}</td>
            <td style="border: 1px solid #000;">{{ $siswa->alamat ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="border: 1px solid #000; text-align: center;">Belum ada data siswa.</td>
        </tr>
        @endforelse
    </tbody>
</table>
