@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Ganti Password</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Admin</a></li>
                <li class="breadcrumb-item active">Ganti Password</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section>
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Berhasil !</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form onsubmit="disableButton()" action="{{ route('admin.update.password') }}" method="POST">
                    @csrf

                    <!-- Password Lama -->
                    <div class="row mb-3">
                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Password Lama</label>
                        <div class="col-md-8 col-lg-9 input-group">
                            <input name="current_password" type="password"
                                class="form-control @error('current_password') is-invalid @enderror" id="currentPassword"
                                autocomplete="current-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                data-target="currentPassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                            <div class="invalid-feedback">
                                @error('current_password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password Baru -->
                    <div class="row mb-3">
                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Password Baru</label>
                        <div class="col-md-8 col-lg-9 input-group">
                            <input name="new_password" type="password"
                                class="form-control @error('new_password') is-invalid @enderror" id="newPassword"
                                autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                data-target="newPassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                            <div class="invalid-feedback">
                                @error('new_password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <!-- Indikator Kekuatan Password -->
                        <div class="col-12">
                            <small id="passwordHelpBlock" class="form-text text-muted">
                                <span id="password-strength-text"></span>
                            </small>
                            <div id="password-strength-bar" class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;"
                                    id="password-strength-meter"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div class="row mb-3">
                        <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password Baru</label>
                        <div class="col-md-8 col-lg-9 input-group">
                            <input name="renew_password" type="password"
                                class="form-control @error('renew_password') is-invalid @enderror" id="renewPassword"
                                autocomplete="new-password">
                            <button class="btn btn-outline-secondary toggle-password" type="button"
                                data-target="renewPassword">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                            <div class="invalid-feedback">
                                @error('renew_password')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="text-center">
                        <button type="submit" id="gantiPasswordButton" class="btn btn-primary">Perbarui Password</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        // Show/Hide Password
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const target = document.getElementById(this.getAttribute('data-target'));
                if (target.type === 'password') {
                    target.type = 'text';
                    this.innerHTML = '<i class="bi bi-eye"></i>';
                } else {
                    target.type = 'password';
                    this.innerHTML = '<i class="bi bi-eye-slash"></i>';
                }
            });
        });

        // Validasi Kekuatan Password
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            const strengthText = document.getElementById('password-strength-text');
            const strengthBar = document.getElementById('password-strength-meter');

            let strength = 0;

            if (password.length >= 8) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[\W]/)) strength += 1;

            switch (strength) {
                case 0:
                    strengthText.innerHTML = "";
                    strengthBar.style.width = "0%";
                    strengthBar.className = "progress-bar";
                    break;
                case 1:
                    strengthText.innerHTML = "Sangat Lemah";
                    strengthBar.style.width = "20%";
                    strengthBar.className = "progress-bar bg-danger";
                    break;
                case 2:
                    strengthText.innerHTML = "Lemah";
                    strengthBar.style.width = "40%";
                    strengthBar.className = "progress-bar bg-warning";
                    break;
                case 3:
                    strengthText.innerHTML = "Sedang";
                    strengthBar.style.width = "60%";
                    strengthBar.className = "progress-bar bg-info";
                    break;
                case 4:
                    strengthText.innerHTML = "Kuat";
                    strengthBar.style.width = "80%";
                    strengthBar.className = "progress-bar bg-primary";
                    break;
                case 5:
                    strengthText.innerHTML = "Sangat Kuat";
                    strengthBar.style.width = "100%";
                    strengthBar.className = "progress-bar bg-success";
                    break;
            }
        });
    </script>
    <script>
        function disableButton() {
            let button = document.getElementById('gantiPasswordButton');
            button.disabled = true;
            button.innerHTML = "Memperbarui...";
        }
    </script>
@endsection
