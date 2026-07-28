<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 14pt;">DATA GURU</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 12pt;">SMK BAKTI IDHATA</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 10pt;">Tahun Akademik: {{ $tahun_akademik ? $tahun_akademik->tahun_ajaran . ' (' . ucfirst($tahun_akademik->semester) . ')' : '-' }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">No</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">NIP</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">NUPTK</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #D3D3D3;">Nama Lengkap</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Jenis Kelamin</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Jenis PTK</th>
            <th style="border: 1px solid #000; font-weight: bold; text-align: center; background-color: #D3D3D3;">Status Kepegawaian</th>
        </tr>
    </thead>
    <tbody>
        @forelse($gurus as $guru)
        <tr>
            <td style="border: 1px solid #000; text-align: center;">{{ $loop->iteration }}</td>
            <td style="border: 1px solid #000; text-align: center; mso-number-format:'\@';">{{ $guru->nip ?? '-' }}</td>
            <td style="border: 1px solid #000; text-align: center; mso-number-format:'\@';">{{ $guru->nuptk ?? '-' }}</td>
            <td style="border: 1px solid #000;">{{ trim(($guru->gelar_depan ? $guru->gelar_depan . ' ' : '') . $guru->nama . ($guru->gelar_belakang ? ', ' . $guru->gelar_belakang : '')) }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $guru->jenis_ptk ?? '-' }}</td>
            <td style="border: 1px solid #000; text-align: center;">{{ $guru->status_kepegawaian ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="border: 1px solid #000; text-align: center;">Belum ada data guru.</td>
        </tr>
        @endforelse
    </tbody>
</table>
