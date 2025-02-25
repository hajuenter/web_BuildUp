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
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a class="logo d-flex align-items-center w-auto">
                                    <span class="d-none d-lg-block">BuildUp</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Buat password baru</h5>
                                        <p class="text-center small">Masukkan password baru dan konfirmasi password
                                            dengan benar sesuai ketentuan</p>
                                    </div>
                                    @if (session('successotp'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('successotp') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <form class="row g-3 needs-validation" action="{{ route('perbarui.password') }}"
                                        method="POST" novalidate onsubmit="disableButton()">
                                        @csrf

                                        <!-- OTP Input -->
                                        <div class="col-12">
                                            <label for="otp" class="form-label">Kode OTP</label>
                                            <input type="text" name="otp"
                                                class="form-control @error('otp') is-invalid @enderror" id="otp"
                                                required>
                                            <div class="invalid-feedback">
                                                @error('otp')
                                                    {{ $message }}
                                                @else
                                                    Masukkan kode OTP yang dikirim ke email Anda.
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Password Baru -->
                                        <div class="col-12">
                                            <label for="newpassword" class="form-label">Password Baru</label>
                                            <div class="input-group has-validation">
                                                <input type="password" name="newpassword"
                                                    class="form-control @error('newpassword') is-invalid @enderror"
                                                    id="newpassword" required>
                                                <span class="input-group-text toggle-password" data-target="newpassword"
                                                    style="cursor: pointer;">
                                                    <i class="bi bi-eye"></i>
                                                </span>
                                                <div class="invalid-feedback">
                                                    @error('newpassword')
                                                        {{ $message }}
                                                    @else
                                                        Masukkan password baru Anda.
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Konfirmasi Password -->
                                        <div class="col-12">
                                            <label for="newkonfirpassword" class="form-label">Konfirmasi
                                                Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" name="newkonfirpassword"
                                                    class="form-control @error('newkonfirpassword') is-invalid @enderror"
                                                    id="newkonfirpassword" required>
                                                <span class="input-group-text toggle-password"
                                                    data-target="newkonfirpassword" style="cursor: pointer;">
                                                    <i class="bi bi-eye"></i>
                                                </span>
                                                <div class="invalid-feedback">
                                                    @error('newkonfirpassword')
                                                        {{ $message }}
                                                    @else
                                                        Masukkan kembali password baru Anda.
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <button id="btnPerbarui" class="btn btn-primary w-100"
                                                type="submit">Perbarui</button>
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

    <!-- Vendor JS Files -->
    <script src="{{ asset('niceadmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('niceadmin/assets/js/main.js') }}"></script>
    <script>
        document.querySelectorAll(".toggle-password").forEach(button => {
            button.addEventListener("click", function() {
                const targetId = this.getAttribute("data-target");
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector("i");

                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    icon.classList.remove("bi-eye");
                    icon.classList.add("bi-eye-slash");
                } else {
                    passwordInput.type = "password";
                    icon.classList.remove("bi-eye-slash");
                    icon.classList.add("bi-eye");
                }
            });
        });
    </script>
    <script>
        function disableButton() {
            let otp = document.getElementById('otp').value.trim();
            let newPassword = document.getElementById('newpassword').value.trim();
            let confirmPassword = document.getElementById('newkonfirpassword').value.trim();
            let button = document.getElementById('btnPerbarui');

            // Jika ada input kosong, hentikan pengiriman form dan tampilkan pesan error
            if (!otp || !newPassword || !confirmPassword) {
                return false; // Mencegah form terkirim
            }

            // Nonaktifkan tombol dan ubah teksnya saat form dikirim
            button.disabled = true;
            button.innerHTML = "Memproses...";
            return true; // Izinkan form terkirim
        }

        // Aktifkan tombol kembali jika ada perubahan input
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                let button = document.getElementById('btnPerbarui');
                button.disabled = false;
                button.innerHTML = "Perbarui";
            });
        });
    </script>

</body>

</html>
