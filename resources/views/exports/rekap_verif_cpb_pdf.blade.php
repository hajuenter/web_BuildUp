<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Verifikasi CPB</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        .info-table {
            width: 100%;
            margin-top: 30px;
        }

        .info-table td {
            padding: 1px;
        }

        .table {
            font-size: 9px;
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid black;
        }

        .table th,
        .table td {
            padding: 5px;
            text-align: left;
        }

        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature p {
            margin-bottom: 70px;
        }

        .text-kd {
            margin-right: 35px;
        }
    </style>
</head>

<body>
    <p class="title">DAFTAR REKAPITULASI VERIFIKASI HASIL USULAN BANTUAN SOSIAL</p>
    <p class="title">PENYEDIAAN RUMAH LAYAK HUNI</p>

    <table class="info-table">
        <tr>
            <td width="100">DESA</td>
            <td width="10">:</td>
            <td>……………………………..</td>
        </tr>
        <tr>
            <td>KECAMATAN</td>
            <td>:</td>
            <td>……………………………..</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Penutup Atap</th>
                <th>Rangka Atap</th>
                <th>Kolom</th>
                <th>Ring Balok</th>
                <th>Dinding Pengisi</th>
                <th>Kusen</th>
                <th>Pintu</th>
                <th>Jendela</th>
                <th>Struktur Bawah</th>
                <th>Penutup Lantai</th>
                <th>Pondasi</th>
                <th>Sloof</th>
                <th>MCK</th>
                <th>Air Kotor</th>
                <th>Kesanggupan Berswadaya</th>
                <th>Tipe</th>
                <th>Penilaian Kerusakan</th>
                <th>Nilai Bantuan</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $cpb)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cpb->nik }}</td>
                    <td>{{ $cpb->penutup_atap }}</td>
                    <td>{{ $cpb->rangka_atap }}</td>
                    <td>{{ $cpb->kolom }}</td>
                    <td>{{ $cpb->ring_balok }}</td>
                    <td>{{ $cpb->dinding_pengisi }}</td>
                    <td>{{ $cpb->kusen }}</td>
                    <td>{{ $cpb->pintu }}</td>
                    <td>{{ $cpb->jendela }}</td>
                    <td>{{ $cpb->struktur_bawah }}</td>
                    <td>{{ $cpb->penutup_lantai }}</td>
                    <td>{{ $cpb->pondasi }}</td>
                    <td>{{ $cpb->sloof }}</td>
                    <td>{{ $cpb->mck }}</td>
                    <td>{{ $cpb->air_kotor }}</td>
                    <td>{{ $cpb->kesanggupan_berswadaya }}</td>
                    <td>{{ $cpb->tipe }}</td>
                    <td>{{ $cpb->penilaian_kerusakan }}</td>
                    <td>{{ $cpb->nilai_bantuan }}</td>
                    <td>{{ $cpb->catatan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <p class="text-kd">Kepala Desa</p>
        <p>……………………………..</p>
    </div>
</body>

</html>
