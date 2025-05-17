@extends('layouts.layouts_petugas')

@section('content-petugas')
    <section class="input-cpb">
        {{-- input --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-0">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Form Input Data CPB</h6>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Berhasil !</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="inputCPBForm" method="POST" action="{{ route('petugas.create.inputcpb') }}"
                            enctype="multipart/form-data" onsubmit="disableButton()">
                            @csrf
                            <div class="container-fluid">
                                <div class="row">
                                    <!-- Nama -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                                            <input type="text" name="nama"
                                                class="form-control @error('nama') is-invalid @enderror"
                                                placeholder="Masukkan nama" value="{{ old('nama') }}">
                                            <div class="invalid-feedback">
                                                @error('nama')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alamat -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Alamat (Jalan)<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="alamat"
                                                class="form-control @error('alamat') is-invalid @enderror"
                                                placeholder="Masukkan alamat" value="{{ old('alamat') }}">
                                            <div class="invalid-feedback">
                                                @error('alamat')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Desa/Kelurahan -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Desa/Kelurahan <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="desa"
                                                class="form-control @error('desa') is-invalid @enderror"
                                                value="{{ old('desa', $desa) }}" readonly style="pointer-events: none;"
                                                tabindex="-1">
                                            <div class="invalid-feedback">
                                                @error('desa')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kecamatan -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                            <input type="text" name="kecamatan"
                                                class="form-control @error('kecamatan') is-invalid @enderror"
                                                value="{{ old('kecamatan', $kecamatan) }}" readonly
                                                style="pointer-events: none;" tabindex="-1">
                                            <div class="invalid-feedback">
                                                @error('kecamatan')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NIK -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">NIK <span class="text-danger">*</span></label>
                                            <input type="text" name="nik"
                                                class="form-control @error('nik') is-invalid @enderror"
                                                placeholder="Masukkan NIK" value="{{ old('nik') }}">
                                            <div class="invalid-feedback">
                                                @error('nik')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NO KK -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">NO KK <span class="text-danger">*</span></label>
                                            <input type="text" name="no_kk"
                                                class="form-control @error('no_kk') is-invalid @enderror"
                                                placeholder="Masukkan NO KK" value="{{ old('no_kk') }}">
                                            <div class="invalid-feedback">
                                                @error('no_kk')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pekerjaan -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                            <input type="text" name="pekerjaan"
                                                class="form-control @error('pekerjaan') is-invalid @enderror"
                                                placeholder="Masukkan pekerjaan" value="{{ old('pekerjaan') }}">
                                            <div class="invalid-feedback">
                                                @error('pekerjaan')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Masukkan email" value="{{ old('email') }}">
                                            <div class="invalid-feedback">
                                                @error('email')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Foto Rumah -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Foto Rumah <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="foto_rumah"
                                                class="form-control @error('foto_rumah') is-invalid @enderror"
                                                accept="image/*">
                                            <div class="invalid-feedback">
                                                @error('foto_rumah')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Koordinat -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Koordinat <span class="text-danger">*</span></label>
                                            <input type="text" name="koordinat"
                                                class="form-control @error('koordinat') is-invalid @enderror"
                                                placeholder="Masukkan koordinat" value="{{ old('koordinat') }}">
                                            <div class="invalid-feedback">
                                                @error('koordinat')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <button type="submit" id="tambahCPBButton" class="btn btn-primary">Tambah</button>
                                <a href="{{ route('petugas.inputcpb') }}" class="btn btn-danger">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        {{-- input end --}}

    </section>
@endsection
<script>
    function disableButton() {
        let nama = document.querySelector('input[name="nama"]').value.trim();
        let alamat = document.querySelector('input[name="alamat"]').value.trim();
        let nik = document.querySelector('input[name="nik"]').value.trim();
        let no_kk = document.querySelector('input[name="no_kk"]').value.trim();
        let pekerjaan = document.querySelector('input[name="pekerjaan"]').value.trim();
        let email = document.querySelector('input[name="email"]').value.trim();
        let foto_rumah = document.querySelector('input[name="foto_rumah"]').value;
        let koordinat = document.querySelector('input[name="koordinat"]').value.trim();

        let button = document.getElementById('tambahCPBButton');

        // Cek jika ada input yang kosong, jangan disable tombol
        if (!nama || !alamat || !nik || !no_kk || !pekerjaan || !email || !foto_rumah || !koordinat) {
            return false;
        }

        // Nonaktifkan tombol saat form dikirim
        button.disabled = true;
        button.innerHTML = "Menambah...";
        return true;
    }

    // Aktifkan kembali tombol jika ada perubahan pada input
    document.querySelectorAll('input').forEach(element => {
        element.addEventListener('input', function() {
            let button = document.getElementById('tambahCPBButton');
            button.disabled = false;
            button.innerHTML = "Tambah";
        });
    });
</script>
