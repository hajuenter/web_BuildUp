@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Jadwal</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Menu</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.jadwal') }}">Jadwal</a></li>
                <li class="breadcrumb-item active">Tambah Jadwal</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section jadwal">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Tambah Jadwal Form</h6>

                        <form class="forms-sample" method="post" action="{{ route('admin.store.jadwal') }}"
                            enctype="multipart/form-data" onsubmit="disableButton()">
                            {{ csrf_field() }}

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Judul <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="judul" placeholder="Masukkan judul"
                                        value="{{ old('judul') }}">
                                    @error('judul')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Dropdown Kategori -->
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Kategori <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="kategori">
                                        <option value="" disabled selected>Pilih Kategori</option>
                                        <option value="pengecekan" {{ old('kategori') == 'pengecekan' ? 'selected' : '' }}>
                                            Pengecekan</option>
                                        <option value="verifikasi" {{ old('kategori') == 'verifikasi' ? 'selected' : '' }}>
                                            Verifikasi</option>
                                        <option value="sosialisasi"
                                            {{ old('kategori') == 'sosialisasi' ? 'selected' : '' }}>Sosialisasi</option>
                                        <option value="perbaikan" {{ old('kategori') == 'perbaikan' ? 'selected' : '' }}>
                                            Perbaikan</option>
                                    </select>
                                    @error('kategori')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Tempat <span style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="alamat" placeholder="Masukkan tempat">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Tanggal Mulai <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" name="tanggal_start"
                                        value="{{ old('tanggal_start') }}">
                                    @error('tanggal_start')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Tanggal Selesai <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" name="tanggal_end"
                                        value="{{ old('tanggal_end') }}">
                                    @error('tanggal_end')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Waktu Mulai <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="time" class="form-control" name="waktu_start"
                                        value="{{ old('waktu_start') }}">
                                    @error('waktu_start')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Waktu Selesai <span
                                        style="color: red;">*</span></label>
                                <div class="col-sm-9">
                                    <input type="time" class="form-control" name="waktu_end"
                                        value="{{ old('waktu_end') }}">
                                    @error('waktu_end')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" id="tambahJadwalButton" class="btn btn-primary me-2">Tambah</button>
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
        let judul = document.querySelector('input[name="judul"]').value.trim();
        let kategori = document.querySelector('select[name="kategori"]').value.trim();
        let alamat = document.querySelector('textarea[name="alamat"]').value.trim();
        let tanggalStart = document.querySelector('input[name="tanggal_start"]').value;
        let tanggalEnd = document.querySelector('input[name="tanggal_end"]').value;
        let waktuStart = document.querySelector('input[name="waktu_start"]').value;
        let waktuEnd = document.querySelector('input[name="waktu_end"]').value;

        let button = document.getElementById('tambahJadwalButton');

        // Jika ada input kosong, form tidak dikirim dan tombol tetap aktif
        if (!judul || !kategori || !alamat || !tanggalStart || !tanggalEnd || !waktuStart || !waktuEnd) {
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
            let button = document.getElementById('tambahJadwalButton');
            button.disabled = false;
            button.innerHTML = "Tambah";
        });
    });
</script>
