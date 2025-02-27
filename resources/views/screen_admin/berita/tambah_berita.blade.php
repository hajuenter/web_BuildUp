@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Berita</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Menu</a></li>
                <li class="breadcrumb-item">Berita</li>
                <li class="breadcrumb-item active">Tambah Berita</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section berita">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Tambah Berita Form</h6>

                        <form class="forms-sample" method="post" action="{{ route('admin.store.berita') }}"
                            enctype="multipart/form-data" onsubmit="disableButton()">
                            {{ csrf_field() }}

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Judul <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                        name="judul" placeholder="Masukkan judul" value="{{ old('judul') }}">
                                    @error('judul')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Isi <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control @error('isi') is-invalid @enderror" name="isi" placeholder="Masukkan isi berita">{{ old('isi') }}</textarea>
                                    @error('isi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Tempat <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control @error('tempat') is-invalid @enderror" name="tempat" placeholder="Masukkan tempat">{{ old('tempat') }}</textarea>
                                    @error('tempat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Penulis <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('penulis') is-invalid @enderror"
                                        name="penulis" placeholder="Masukkan penulis" value="{{ old('penulis') }}">
                                    @error('penulis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Foto Berita <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                        name="photo" accept=".jpg, .png, .jpeg">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" id="tambahBeritaButton" class="btn btn-primary me-2">Tambah</button>
                            <a href="{{ route('admin.berita') }}" class="btn btn-secondary">Kembali</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<script>
    function disableButton() {
        let judul = document.querySelector('input[name="judul"]').value.trim();
        let isi = document.querySelector('textarea[name="isi"]').value.trim();
        let tempat = document.querySelector('textarea[name="tempat"]').value.trim();
        let penulis = document.querySelector('input[name="penulis"]').value.trim();
        let photo = document.querySelector('input[name="photo"]').value;

        let button = document.getElementById('tambahBeritaButton');

        // Jika ada input kosong, form tidak dikirim dan tombol tetap aktif
        if (!judul || !isi || !tempat || !penulis || !photo) {
            return false;
        }

        // Nonaktifkan tombol saat form dikirim
        button.disabled = true;
        button.innerHTML = "Menambah...";
        return true;
    }

    // Aktifkan tombol kembali jika user mengedit input
    document.querySelectorAll('input, textarea').forEach(element => {
        element.addEventListener('input', function() {
            let button = document.getElementById('tambahBeritaButton');
            button.disabled = false;
            button.innerHTML = "Tambah";
        });
    });
</script>
