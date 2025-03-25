@extends('layouts.layouts_petugas')

@section('content-petugas')
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Petugas</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="{{ Auth::user()->foto ? asset('up/profile/' . Auth::user()->foto) : asset('up/profile/default-profile.png') }}"
                            alt="Profile Image" width="100" class="rounded-circle">
                        <h2>{{ $user->name }}</h2>
                        <h3>Petugas</h3>
                    </div>
                </div>

            </div>
            <div class="col-xl-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Berhasil !</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal !</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button
                                    class="nav-link {{ session('active_tab', 'profile-overview') == 'profile-overview' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#profile-overview">
                                    Info
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ session('active_tab') == 'profile-edit' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#profile-edit">
                                    Edit Profile
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content pt-2">

                            <div class="tab-pane fade {{ session('active_tab', 'profile-overview') == 'profile-overview' ? 'show active' : '' }}"
                                id="profile-overview">
                                <h5 class="card-title">Detail Profile</h5>
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Nama</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Role</div>
                                    <div class="col-lg-9 col-md-8">Petugas Verifikasi Data CPB</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">No Hp</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->no_hp }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                </div>
                            </div>

                            <div class="tab-pane fade {{ session('active_tab') == 'profile-edit' ? 'show active' : '' }}"
                                id="profile-edit">

                                <form action="{{ route('petugas.profile.update') }}" method="POST"
                                    enctype="multipart/form-data" onsubmit="disableButton()">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Foto
                                            Profile</label>
                                        <div class="col-md-8 col-lg-9">
                                            <img src="{{ Auth::user()->foto ? asset('up/profile/' . Auth::user()->foto) : asset('up/profile/default-profile.png') }}"
                                                alt="Profile Image" width="100" class="rounded-circle">
                                            <div class="pt-2">
                                                <input type="file" name="foto"
                                                    class="form-control @error('foto') is-invalid @enderror">
                                                @error('foto')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Nama" class="col-md-4 col-lg-3 col-form-label">Nama <span
                                                style="color: red;">*</span></label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="nama" type="text"
                                                class="form-control @error('nama') is-invalid @enderror" id="Nama"
                                                value="{{ old('nama', $user->name) }}">
                                            @error('nama')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="No_hp" class="col-md-4 col-lg-3 col-form-label">No HP <span
                                                style="color: red;">*</span></label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="no_hp" type="text"
                                                class="form-control @error('no_hp') is-invalid @enderror" id="No_hp"
                                                value="{{ old('no_hp', $user->no_hp) }}">
                                            @error('no_hp')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email <span
                                                style="color: red;">*</span></label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" id="Email"
                                                value="{{ old('email', $user->email) }}">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" id="simpanProfileButton"
                                            class="btn btn-primary">Simpan</button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
<script>
    function disableButton() {
        let nama = document.querySelector('input[name="nama"]').value.trim();
        let no_hp = document.querySelector('input[name="no_hp"]').value.trim();
        let email = document.querySelector('input[name="email"]').value.trim();
        let foto = document.querySelector('input[name="foto"]').value;

        let button = document.getElementById('simpanProfileButton');

        // Jika ada input kosong, tombol tetap aktif
        if (!nama || !no_hp || !email) {
            return false;
        }

        // Nonaktifkan tombol saat form dikirim
        button.disabled = true;
        button.innerHTML = "Menyimpan...";
        return true;
    }

    // Aktifkan tombol kembali jika user mengedit input
    document.querySelectorAll('input').forEach(element => {
        element.addEventListener('input', function() {
            let button = document.getElementById('simpanProfileButton');
            button.disabled = false;
            button.innerHTML = "Simpan";
        });
    });
</script>
