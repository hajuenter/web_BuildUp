@extends('layouts.layouts_petugas')

@section('content-petugas')
    <div class="pagetitle">
        <h1>Edit Data CPB</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('petugas.datacpb') }}">Data CPB</a></li>
                <li class="breadcrumb-item active">Edit Data CPB</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section cpb">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Edit Data CPB</h6>

                        <form method="POST" action="{{ route('petugas.update.cpb', $cpb->id) }}"
                            enctype="multipart/form-data" onsubmit="disableButton()">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control"
                                    value="{{ old('nama', $cpb->nama) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat (Jalan)</label>
                                <input type="text" name="alamat_jalan" class="form-control"
                                    value="{{ old('alamat_jalan', explode(';', $cpb->alamat)[0] ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Desa/Kelurahan</label>
                                <input type="text" readonly style="pointer-events: none;" tabindex="-1"
                                    name="alamat_desa" class="form-control"
                                    value="{{ old('alamat_desa', explode(';', $cpb->alamat)[1] ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kecamatan</label>
                                <input type="text" readonly style="pointer-events: none;" tabindex="-1"
                                    name="alamat_kecamatan" class="form-control"
                                    value="{{ old('alamat_kecamatan', explode(';', $cpb->alamat)[2] ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NIK</label>
                                <input type="text" name="nik" class="form-control"
                                    value="{{ old('nik', $cpb->nik) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">No KK</label>
                                <input type="text" name="no_kk" class="form-control"
                                    value="{{ old('no_kk', $cpb->no_kk) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" name="pekerjaan" class="form-control"
                                    value="{{ old('pekerjaan', $cpb->pekerjaan) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $cpb->email) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto Rumah</label>
                                <input type="file" name="foto_rumah" class="form-control">
                                @if ($cpb->foto_rumah)
                                    <img src="{{ asset($cpb->foto_rumah) }}" width="150" class="mt-2">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Koordinat</label>
                                <input type="text" name="koordinat" class="form-control"
                                    value="{{ old('koordinat', $cpb->koordinat) }}">
                            </div>

                            <button type="submit" id="editCPBButton" class="btn btn-primary">Edit</button>
                            <a href="{{ route('petugas.datacpb') }}" class="btn btn-danger">Batal</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<script>
    function disableButton() {
        let button = document.getElementById('editCPBButton');
        button.disabled = true;
        button.innerHTML = "Mengedit...";
    }
</script>
