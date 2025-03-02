@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Edit Jadwal</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Menu</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.jadwal') }}">Jadwal</a></li>
                <li class="breadcrumb-item active">Edit Jadwal</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section jadwal">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Edit Jadwal</h6>

                        <form class="forms-sample" method="post" action="{{ route('admin.update.jadwal', $jadwal->id) }}"
                            enctype="multipart/form-data" onsubmit="disableButton()">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Judul <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                        name="judul" value="{{ old('judul', $jadwal->judul) }}">
                                    <div class="invalid-feedback">
                                        @error('judul')
                                            {{ $message }}
                                        @else
                                            Tolong masukkan judul.
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Kategori <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-control @error('kategori') is-invalid @enderror" name="kategori">
                                        <option value="">Pilih Kategori</option>
                                        <option value="pengecekan"
                                            {{ old('kategori', $jadwal->kategori) == 'pengecekan' ? 'selected' : '' }}>
                                            Pengecekan</option>
                                        <option value="verifikasi"
                                            {{ old('kategori', $jadwal->kategori) == 'verifikasi' ? 'selected' : '' }}>
                                            Verifikasi</option>
                                        <option value="sosialisasi"
                                            {{ old('kategori', $jadwal->kategori) == 'sosialisasi' ? 'selected' : '' }}>
                                            Sosialisasi</option>
                                        <option value="perbaikan"
                                            {{ old('kategori', $jadwal->kategori) == 'perbaikan' ? 'selected' : '' }}>
                                            Perbaikan</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        @error('kategori')
                                            {{ $message }}
                                        @else
                                            Tolong pilih kategori.
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Alamat <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                                        name="alamat" value="{{ old('alamat', $jadwal->alamat) }}">
                                    <div class="invalid-feedback">
                                        @error('alamat')
                                            {{ $message }}
                                        @else
                                            Tolong masukkan alamat.
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Tanggal Mulai <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control @error('tanggal_start') is-invalid @enderror"
                                        name="tanggal_start" value="{{ old('tanggal_start', $jadwal->tanggal_start) }}">
                                    <div class="invalid-feedback">
                                        @error('tanggal_start')
                                            {{ $message }}
                                        @else
                                            Tolong masukkan tanggal mulai.
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Tanggal Selesai <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control @error('tanggal_end') is-invalid @enderror"
                                        name="tanggal_end" value="{{ old('tanggal_end', $jadwal->tanggal_end) }}">
                                    <div class="invalid-feedback">
                                        @error('tanggal_end')
                                            {{ $message }}
                                        @else
                                            Tolong masukkan tanggal selesai.
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Waktu Mulai <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="time" class="form-control @error('waktu_start') is-invalid @enderror"
                                        name="waktu_start" value="{{ old('waktu_start', $jadwal->waktu_start) }}">
                                    <div class="invalid-feedback">
                                        @error('waktu_start')
                                            {{ $message }}
                                        @else
                                            Tolong masukkan waktu mulai.
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Waktu Selesai <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="time" class="form-control @error('waktu_end') is-invalid @enderror"
                                        name="waktu_end" value="{{ old('waktu_end', $jadwal->waktu_end) }}">
                                    <div class="invalid-feedback">
                                        @error('waktu_end')
                                            {{ $message }}
                                        @else
                                            Tolong masukkan waktu selesai.
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="editJadwalButton" class="btn btn-primary me-2">Edit</button>
                            <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary">Kembali</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<script>
    function disableButton() {
        let button = document.getElementById('editJadwalButton');
        button.disabled = true;
        button.innerHTML = "Mengedit...";
    }
</script>
