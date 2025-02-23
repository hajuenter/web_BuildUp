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
                                    <input type="text" class="form-control" name="judul" required
                                        placeholder="Masukkan judul" value="{{ old('judul') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Isi <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="isi" required placeholder="Masukkan isi berita">{{ old('isi') }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Tempat <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="tempat" required placeholder="Masukkan tempat">{{ old('tempat') }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Penulis <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="penulis" required
                                        placeholder="Masukkan penulis" value="{{ old('penulis') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Foto Berita <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" name="photo" accept=".jpg, .png, .jpeg"
                                        required>
                                    <span style="color: red;">{{ $errors->first('photo') }}</span>
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
        let button = document.getElementById('tambahBeritaButton');
        button.disabled = true;
        button.innerHTML = "Menambah...";
    }
</script>
