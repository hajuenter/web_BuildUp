@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Edit Berita</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Menu</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.berita') }}">Berita</a></li>
                <li class="breadcrumb-item active">Edit Berita</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section berita">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Edit Berita</h6>

                        <form class="forms-sample" method="post" action="{{ route('admin.update.berita', $berita->id) }}"
                            enctype="multipart/form-data" onsubmit="disableButton()">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Judul <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                        name="judul" value="{{ old('judul', $berita->judul) }}">
                                    @error('judul')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Isi <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control @error('isi') is-invalid @enderror" name="isi">{{ old('isi', $berita->isi) }}</textarea>
                                    @error('isi')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Penulis <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('penulis') is-invalid @enderror"
                                        name="penulis" value="{{ old('penulis', $berita->penulis) }}">
                                    @error('penulis')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Tempat <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('tempat') is-invalid @enderror"
                                        name="tempat" value="{{ old('tempat', $berita->tempat) }}">
                                    @error('tempat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Foto</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                        name="photo" accept="image/*">
                                    @error('photo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    @if ($berita->photo)
                                        <div class="mt-2">
                                            <img src="{{ asset('up/berita/' . $berita->photo) }}" alt="Foto Berita"
                                                width="150">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <button type="submit" id="editBeritaButton" class="btn btn-primary me-2">Edit</button>
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
        let button = document.getElementById('editBeritaButton');
        button.disabled = true;
        button.innerHTML = "Mengedit...";
    }
</script>
