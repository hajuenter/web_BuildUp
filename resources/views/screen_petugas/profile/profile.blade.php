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
                                <button class="nav-link active" data-bs-toggle="tab"
                                    data-bs-target="#profile-overview">Info</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                    Profile</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#profile-change-password">Ganti Password</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Nama</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Role</div>
                                    <div class="col-lg-9 col-md-8">{{ ucfirst($user->role) }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Phone</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->no_hp }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                </div>
                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <form action="{{ route('petugas.profile.update') }}" method="POST"
                                    enctype="multipart/form-data" onsubmit="disableButton()">
                                    @csrf

                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Foto
                                            Profile</label>
                                        <div class="col-md-8 col-lg-9">
                                            <img src="{{ Auth::user()->foto ? asset('up/profile/' . Auth::user()->foto) : asset('up/profile/default-profile.png') }}"
                                                alt="Profile Image" width="100" class="rounded-circle">
                                            <div class="pt-2">
                                                <input type="file" name="foto" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Nama" class="col-md-4 col-lg-3 col-form-label">Nama</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="nama" type="text" class="form-control" id="Nama"
                                                value="{{ old('nama', $user->name) }}" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="No_hp" class="col-md-4 col-lg-3 col-form-label">No hp</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="no_hp" type="text" class="form-control" id="No_hp"
                                                value="{{ old('no_hp', $user->no_hp) }}" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email" class="form-control" id="Email"
                                                value="{{ old('email', $user->email) }}" required>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" id="simpanProfileButton"
                                            class="btn btn-primary">Simpan</button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>


                            <div class="tab-pane fade pt-3" id="profile-change-password">

                                <form action="{{ route('petugas.ganti.password') }}" method="POST"
                                    onsubmit="disableButtonChangePassword()">
                                    @csrf

                                    <!-- Password Lama -->
                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Password
                                            Lama</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="input-group has-validation">
                                                <input name="current_password" type="password"
                                                    class="form-control @error('current_password') is-invalid @enderror"
                                                    id="currentPassword" required>
                                                <span class="input-group-text" id="toggleCurrentPassword"
                                                    style="cursor: pointer;">
                                                    <i class="bi bi-eye"></i>
                                                </span>
                                                <div class="invalid-feedback">
                                                    @error('current_password')
                                                        {{ $message }}
                                                    @else
                                                        Tolong masukkan password lama Anda.
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Password Baru -->
                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Password
                                            Baru</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="input-group has-validation">
                                                <input name="new_password" type="password"
                                                    class="form-control @error('new_password') is-invalid @enderror"
                                                    id="newPassword" required>
                                                <span class="input-group-text" id="toggleNewPassword"
                                                    style="cursor: pointer;">
                                                    <i class="bi bi-eye"></i>
                                                </span>
                                                <div class="invalid-feedback">
                                                    @error('new_password')
                                                        {{ $message }}
                                                    @else
                                                        Password harus minimal 8 karakter dengan huruf besar, kecil, angka, dan
                                                        karakter khusus.
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Konfirmasi Password Baru -->
                                    <div class="row mb-3">
                                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Konfirmasi
                                            Password Baru</label>
                                        <div class="col-md-8 col-lg-9">
                                            <div class="input-group has-validation">
                                                <input name="renew_password" type="password"
                                                    class="form-control @error('renew_password') is-invalid @enderror"
                                                    id="renewPassword" required>
                                                <span class="input-group-text" id="toggleRenewPassword"
                                                    style="cursor: pointer;">
                                                    <i class="bi bi-eye"></i>
                                                </span>
                                                <div class="invalid-feedback">
                                                    @error('renew_password')
                                                        {{ $message }}
                                                    @else
                                                        Pastikan konfirmasi password sama dengan password baru.
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Submit -->
                                    <div class="text-center">
                                        <button type="submit" id="gantiPasswordButton" class="btn btn-primary">Ganti
                                            Password</button>
                                    </div>
                                </form>

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleIcons = document.querySelectorAll(".input-group-text");

            toggleIcons.forEach((icon) => {
                icon.addEventListener("click", function() {
                    const input = this.previousElementSibling; // Ambil input sebelum span
                    const iconElement = this.querySelector("i"); // Ambil elemen <i> dalam span

                    if (input.type === "password") {
                        input.type = "text";
                        iconElement.classList.remove("bi-eye");
                        iconElement.classList.add("bi-eye-slash");
                    } else {
                        input.type = "password";
                        iconElement.classList.remove("bi-eye-slash");
                        iconElement.classList.add("bi-eye");
                    }
                });
            });
        });
    </script>
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
<script>
    function disableButtonChangePassword() {
        let currentPassword = document.querySelector('input[name="current_password"]').value.trim();
        let newPassword = document.querySelector('input[name="new_password"]').value.trim();
        let renewPassword = document.querySelector('input[name="renew_password"]').value.trim();

        let button = document.getElementById('gantiPasswordButton');

        // Jika ada input yang kosong, tombol tetap aktif
        if (!currentPassword || !newPassword || !renewPassword) {
            return false;
        }

        // Nonaktifkan tombol saat form dikirim
        button.disabled = true;
        button.innerHTML = "Mengganti...";
        return true;
    }

    // Aktifkan kembali tombol jika user mengedit input
    document.querySelectorAll('input[type="password"]').forEach(element => {
        element.addEventListener('input', function() {
            let button = document.getElementById('gantiPasswordButton');
            button.disabled = false;
            button.innerHTML = "Ganti Password";
        });
    });
</script>
