<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Register - {{ config('app.name') }}</title>

    <link href="{{ asset('content/favicon.ico') }}" rel="icon">
    <link href="{{ asset('content/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Nunito|Poppins" rel="stylesheet">

    <link href="{{ asset('niceadmin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('niceadmin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('niceadmin/assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-8 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex justify-content-center py-4">
                                <a class="logo d-flex align-items-center w-auto">
                                    <span class="d-none d-lg-block">BuildUp</span>
                                </a>
                            </div><!-- End Logo -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <a href="{{ route('login') }}"
                                        class="position-absolute top-0 start-0 m-3 text-dark">
                                        <i class="bi bi-arrow-left-square-fill fs-3"></i>
                                    </a>
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Buat Akun Baru</h5>
                                        <p class="text-center small">Silakan isi form di bawah untuk mendaftar</p>
                                    </div>
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <form action="{{ route('register') }}" method="POST"
                                        class="row g-3 needs-validation" novalidate
                                        onsubmit="return disableButton(event)">
                                        @csrf

                                        <!-- Nama -->
                                        <div class="col-12">
                                            <label for="name" class="form-label">Nama</label>
                                            <input type="text" id="yourName" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" required>
                                            <div class="invalid-feedback">
                                                @error('name')
                                                    {{ $message }}
                                                @else
                                                    Tolong masukkan nama lengkap Anda.
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- No HP -->
                                        <div class="col-12">
                                            <label for="no_hp" class="form-label">No HP</label>
                                            <input type="text" id="yourNoHp" name="no_hp"
                                                class="form-control @error('no_hp') is-invalid @enderror"
                                                value="{{ old('no_hp') }}" required>
                                            <div class="invalid-feedback">
                                                @error('no_hp')
                                                    {{ $message }}
                                                @else
                                                    Tolong masukkan nomor HP yang valid.
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="yourEmail" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" required>
                                            <div class="invalid-feedback">
                                                @error('email')
                                                    {{ $message }}
                                                @else
                                                    Tolong masukkan email yang valid.
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Password -->
                                        <div class="col-12">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" id="yourPassword" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    required>
                                                <span class="input-group-text toggle-password"
                                                    style="cursor: pointer;"><i class="bi bi-eye"></i></span>
                                                <div class="invalid-feedback">
                                                    @error('password')
                                                        {{ $message }}
                                                    @else
                                                        Tolong masukkan password Anda.
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" id="confirmPassword" name="password_confirmation"
                                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                                    required>
                                                <span class="input-group-text toggle-password" style="cursor: pointer;">
                                                    <i class="bi bi-eye"></i>
                                                </span>
                                                <div class="invalid-feedback">
                                                    @error('password_confirmation')
                                                        {{ $message }}
                                                    @else
                                                        Ulangi password Anda.
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tombol Register -->
                                        <div class="col-12">
                                            <button class="btn btn-success w-100" id="btnregister"
                                                type="submit">Daftar</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="{{ asset('niceadmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('niceadmin/assets/js/main.js') }}"></script>
    <script>
        function disableButton(event) {
            let name = document.getElementById('yourName').value.trim();
            let no_hp = document.getElementById('yourNoHp').value.trim();
            let email = document.getElementById('yourEmail').value.trim();
            let password = document.getElementById('yourPassword').value.trim();
            let konpassword = document.getElementById('confirmPassword').value.trim();
            let button = document.getElementById('btnregister');

            // Jika ada input kosong, hentikan pengiriman form
            if (!name || !no_hp || !email || !password || !konpassword) {
                event.preventDefault(); // Mencegah form terkirim
                return false;
            }

            // Nonaktifkan tombol saat form dikirim
            button.disabled = true;
            button.innerHTML = "Memproses...";
            return true; // Izinkan form terkirim
        }

        // Aktifkan tombol kembali jika ada perubahan input
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                let button = document.getElementById('btnregister');
                button.disabled = false;
                button.innerHTML = "Daftar";
            });
        });


        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function() {
                let passwordField = this.previousElementSibling; // Ambil input yang ada sebelum tombol
                let icon = this.querySelector('i');

                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    icon.classList.remove("bi-eye");
                    icon.classList.add("bi-eye-slash");
                } else {
                    passwordField.type = "password";
                    icon.classList.remove("bi-eye-slash");
                    icon.classList.add("bi-eye");
                }
            });
        });
    </script>
</body>

</html>
