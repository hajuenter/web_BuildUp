<header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center">
        <div class="container d-flex justify-content-center justify-content-md-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-house-door d-flex align-items-center"><a>Disperkimtan Nganjuk</a></i>
                <i class="bi bi-phone d-flex align-items-center ms-4"><span>0358330055</span></i>
            </div>
            <div class="social-links d-none d-md-flex align-items-center">
                <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-cente">

        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">

                <img src="{{ asset('content/dinas.png') }}" alt="">
                <h1 class="sitename text-primary">BuildUp</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#beranda" class="active">Beranda</a></li>
                    <li><a href="#berita">Berita</a></li>
                    <li><a href="#jadwal">Jadwal</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
            <!-- Tombol Login -->
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm ms-3">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        </div>

    </div>

</header>
