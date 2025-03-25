<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ config('app.name') }}</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('content/favicon.ico') }}" rel="icon">
    <link href="{{ asset('content/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('niceadmin/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('niceadmin/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('niceadmin/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
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
                                    <a href="{{ route('BuildUp') }}"
                                        class="position-absolute top-0 start-0 m-3 text-dark">
                                        <i class="bi bi-arrow-left-square-fill fs-3"></i>
                                    </a>
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login ke Akun Anda</h5>
                                        <p class="text-center small">Masukkan email dan password kamu untuk login</p>
                                    </div>
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <form action="{{ route('login') }}" method="POST" class="row g-3 needs-validation"
                                        novalidate onsubmit="disableButton()">
                                        @csrf

                                        <!-- Email Input -->
                                        <div class="col-12">
                                            <label for="yourEmail" class="form-label">Email</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="yourEmail" value="{{ old('email') }}" required>
                                                <div class="invalid-feedback">
                                                    @error('email')
                                                        {{ $message }}
                                                    @else
                                                        Tolong masukkan email anda dengan benar.
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- input password --}}
                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" name="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="yourPassword" required>
                                                <span class="input-group-text" id="togglePassword"
                                                    style="cursor: pointer;">
                                                    <i class="bi bi-eye"></i>
                                                </span>
                                                <div class="invalid-feedback">
                                                    @error('password')
                                                        {{ $message }}
                                                    @else
                                                        Tolong masukkan password Anda dengan benar.
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 text-end">
                                            <p class="small mb-4"><a class="text-decoration-underline"
                                                    href="{{ route('lupa.password') }}">Lupa
                                                    password?</a></p>
                                        </div>

                                        <!-- Tombol Login -->
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" id="loginButton"
                                                type="submit">Login</button>
                                        </div>

                                        <!-- Tombol register -->
                                        <div class="col-12">
                                            <button class="btn btn-success w-100" id="regisButton" type="button"
                                                onclick="window.location.href='{{ route('register') }}'">Register</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <script src="{{ asset('niceadmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('niceadmin/assets/js/main.js') }}"></script>

    <script>
        function disableButton() {
            let email = document.getElementById('yourEmail').value.trim();
            let password = document.getElementById('yourPassword').value.trim();
            let button = document.getElementById('loginButton');

            // Jika ada input kosong, hentikan pengiriman form
            if (!email || !password) {
                return false; // Mencegah form terkirim
            }

            // Nonaktifkan tombol saat form dikirim
            button.disabled = true;
            button.innerHTML = "Memproses...";
            return true; // Izinkan form terkirim
        }

        // Aktifkan tombol kembali jika ada perubahan input
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                let button = document.getElementById('loginButton');
                button.disabled = false;
                button.innerHTML = "Login";
            });
        });

        // Toggle visibility password
        document.getElementById('togglePassword').addEventListener('click', function() {
            let passwordField = document.getElementById('yourPassword');
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
    </script>

</body>

</html>
