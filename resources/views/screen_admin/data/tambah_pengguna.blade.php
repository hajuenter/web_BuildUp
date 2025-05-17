@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Form Tambah Petugas</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Data</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.data_role') }}">Data Pengguna</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Tambah Pengguna Form</h6>

                    <form action="{{ route('admin.user.create') }}" method="POST" enctype="multipart/form-data"
                        onsubmit="disableButton()">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-1">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Indikator Kekuatan Password --}}
                        <div class="col-12 mb-3">
                            <small id="passwordHelpBlock" class="form-text text-muted">
                                <span id="password-strength-text">Masukkan password</span>
                            </small>
                            <div id="password-strength-bar" class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%;"
                                    id="password-strength-meter"></div>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas Input Data
                                    CPB</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Petugas Verifikasi Data
                                    CPB</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No HP -->
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                                value="{{ old('no_hp') }}">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label" id="labelAlamat">Alamat (Jalan)</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Desa/Kelurahan -->
                        <div class="mb-3 d-none" id="desaField">
                            <label for="alamat_desa" class="form-label">Desa/Kelurahan</label>
                            <input type="text" name="alamat_desa"
                                class="form-control @error('alamat_desa') is-invalid @enderror"
                                value="{{ old('alamat_desa') }}">
                            @error('alamat_desa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kecamatan -->
                        <div class="mb-3 d-none" id="kecamatanField">
                            <label for="kecamatan" class="form-label">Kecamatan</label>
                            <input type="text" name="kecamatan"
                                class="form-control @error('kecamatan') is-invalid @enderror"
                                value="{{ old('kecamatan') }}">
                            @error('kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Foto -->
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto (Opsional)</label>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex mt-4 justify-content-end">
                            <a href="{{ route('admin.data_role') }}" class="btn btn-secondary me-2">Kembali</a>
                            <button type="submit" id="tambahPenggunaButton" class="btn btn-primary">
                                <i class="bi bi-person-plus"></i> Tambah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.querySelector('select[name="role"]');
        const desaField = document.getElementById('desaField');
        const labelAlamat = document.getElementById('labelAlamat');
        const kecamatanField = document.getElementById('kecamatanField');

        function toggleExtraFields() {
            if (roleSelect.value === 'petugas') {
                desaField.classList.remove('d-none');
                kecamatanField.classList.remove('d-none');
                labelAlamat.textContent = "Alamat (Jalan)";
            } else {
                desaField.classList.add('d-none');
                kecamatanField.classList.add('d-none');
                labelAlamat.textContent = "Alamat";
            }
        }

        roleSelect.addEventListener('change', toggleExtraFields);
        toggleExtraFields(); // inisialisasi saat pertama kali load
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");
        const strengthMeter = document.getElementById("password-strength-meter");
        const strengthText = document.getElementById("password-strength-text");

        // Toggle Show/Hide Password
        togglePassword.addEventListener("click", function() {
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;

            // Ganti ikon mata
            this.innerHTML = type === "password" ?
                '<i class="bi bi-eye"></i>' :
                '<i class="bi bi-eye-slash"></i>';
        });

        // Indikator Kekuatan Password
        passwordInput.addEventListener("input", function() {
            const password = passwordInput.value;
            let strength = 0;

            // Periksa panjang minimal 8 karakter
            if (password.length >= 8) {
                if (password.match(/[a-z]/)) strength++; // Huruf kecil
                if (password.match(/[A-Z]/)) strength++; // Huruf besar
                if (password.match(/[0-9]/)) strength++; // Angka
                if (password.match(/[\W]/)) strength++; // Karakter khusus
            }

            // Atur warna dan teks indikator
            let strengthLabel = "Terlalu Lemah";
            let strengthColor = "bg-danger";
            let strengthWidth = "0%";

            if (password.length >= 8) {
                switch (strength) {
                    case 1:
                        strengthLabel = "Lemah";
                        strengthWidth = "25%";
                        strengthColor = "bg-danger";
                        break;
                    case 2:
                        strengthLabel = "Sedang";
                        strengthWidth = "50%";
                        strengthColor = "bg-warning";
                        break;
                    case 3:
                        strengthLabel = "Kuat";
                        strengthWidth = "75%";
                        strengthColor = "bg-info";
                        break;
                    case 4:
                        strengthLabel = "Sangat Kuat";
                        strengthWidth = "100%";
                        strengthColor = "bg-success";
                        break;
                }
            }

            // Perbarui UI
            strengthText.innerText = strengthLabel;
            strengthMeter.style.width = strengthWidth;
            strengthMeter.className = `progress-bar ${strengthColor}`;
        });
    });
</script>
<script>
    function disableButton() {
        let button = document.getElementById('tambahPenggunaButton');
        button.disabled = true;
        button.innerHTML = "Menambah...";
    }
</script>
