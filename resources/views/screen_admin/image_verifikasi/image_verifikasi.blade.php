@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Kelola Foto Verifikasi Data CPB</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Foto</a></li>
                <li class="breadcrumb-item active">Foto Data Verifikasi CPB</li>
            </ol>
        </nav>
    </div>

    <section>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Daftar Foto Verifikasi</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="white-space: nowrap;">No</th>
                                <th style="white-space: nowrap;">NIK</th>
                                <th style="white-space: nowrap;">Foto KK</th>
                                <th style="white-space: nowrap;">Foto KTP</th>
                                <th style="white-space: nowrap;">Foto Penutup Atap</th>
                                <th style="white-space: nowrap;">Foto Rangka Atap</th>
                                <th style="white-space: nowrap;">Foto Kolom</th>
                                <th style="white-space: nowrap;">Foto Ring Balok</th>
                                <th style="white-space: nowrap;">Foto Dinding Pengisi</th>
                                <th style="white-space: nowrap;">Foto Kusen</th>
                                <th style="white-space: nowrap;">Foto Pintu</th>
                                <th style="white-space: nowrap;">Foto Jendela</th>
                                <th style="white-space: nowrap;">Foto Struktur Bawah</th>
                                <th style="white-space: nowrap;">Foto Penutup Lantai</th>
                                <th style="white-space: nowrap;">Foto Pondasi</th>
                                <th style="white-space: nowrap;">Foto Sloof</th>
                                <th style="white-space: nowrap;">Foto MCK</th>
                                <th style="white-space: nowrap;">Foto Air Kotor</th>
                                <th style="white-space: nowrap;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataFotoVerif as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->nik }}</td>

                                    <!-- Kolom Foto -->
                                    @foreach (['foto_kk', 'foto_ktp', 'foto_penutup_atap', 'foto_rangka_atap', 'foto_kolom', 'foto_ring_balok', 'foto_dinding_pengisi', 'foto_kusen', 'foto_pintu', 'foto_jendela', 'foto_struktur_bawah', 'foto_penutup_lantai', 'foto_pondasi', 'foto_sloof', 'foto_mck', 'foto_air_kotor'] as $foto)
                                        <td class="text-center">
                                            @if ($data->$foto)
                                                <div class="d-flex flex-column align-items-center">
                                                    <img src="{{ asset($data->$foto) }}" class="img-thumbnail"
                                                        style="width: 100px; height: 100px; object-fit: cover;">
                                                    <a href="{{ asset($data->$foto) }}" download
                                                        class="btn btn-sm btn-primary mt-1 d-flex align-items-center gap-1">
                                                        <i class="bi bi-download"></i> Download
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach

                                    <td class="text-center">
                                        <form action="{{ route('admin.download.folder') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="nik" value="{{ $data->nik }}">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-folder-symlink"></i> Download Semua
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
