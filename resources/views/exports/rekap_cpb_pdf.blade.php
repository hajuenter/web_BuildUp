<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi CPB</title>
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
            padding: 5px;
        }

        .table {
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
    <p class="title">DAFTAR REKAPITULASI USULAN BANTUAN SOSIAL</p>
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
                <th width="30">No</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>No KK</th>
                <th>Email</th>
                <th>Koordinat</th>
                <th>Pekerjaan</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $cpb)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $cpb->nama }}</td>
                    <td>{{ $cpb->nik }}</td>
                    <td>{{ $cpb->no_kk }}</td>
                    <td>{{ $cpb->email }}</td>
                    <td>{{ $cpb->koordinat }}</td>
                    <td>{{ $cpb->pekerjaan }}</td>
                    <td>{{ $cpb->alamat_lengkap }}</td>
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
