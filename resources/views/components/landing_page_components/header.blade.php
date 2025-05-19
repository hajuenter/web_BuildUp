<header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center">
        <div class="container d-flex justify-content-center justify-content-md-between">
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-house-door d-flex align-items-center"><a>Disperkimtan Nganjuk</a></i>
                <i class="bi bi-phone d-flex align-items-center ms-4"><span>0358330055</span></i>
            </div>
            <div class="social-links d-none d-md-flex align-items-center">
                <a href="https://www.tiktok.com/@dinas_prkppnganju?_t=ZS-8wTUXFZkwfS&_r=1" class="facebook"
                    target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-tiktok"></i>
                </a>
                <a href="https://www.facebook.com/dinas.prkpp.nganjuk.2025?mibextid=rS40aB7S9Ucbxw6v" class="facebook"
                    target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://www.instagram.com/dinas_prkppnganjuk?igsh=ajFzazV2M3A1a2Vu" class="instagram"
                    target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-instagram"></i>
                </a>
            </div>
        </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-cente">

        <div class="container position-relative d-flex flex-wrap align-items-center justify-content-between">
            <!-- Logo dan Nama Situs -->
            <a href="" class="logo d-flex align-items-center">
                <img src="{{ asset('content/newlogo.svg') }}" alt="">
                <!-- Nama situs tetap tampil di semua ukuran -->
                <h1 class="sitename text-primary ms-2 fs-5 fs-lg-2">NganjukMase</h1>
            </a>

            <!-- Navigasi: tampil di semua ukuran -->
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#beranda" class="active">Beranda</a></li>
                    <li><a href="#berita">Berita</a></li>
                    <li><a href="#jadwal">Jadwal</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <!-- Tombol Login dan Download -->
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="d-none d-lg-inline">Login</span>
                </a>
                <a href="{{ asset('mobile_apk/nganjukmase.apk') }}" class="btn btn-success btn-sm" target="_blank"
                    rel="noopener noreferrer">
                    <i class="bi bi-download"></i>
                    <span class="d-none d-lg-inline">Download</span>
                </a>
            </div>
        </div>

    </div>

</header>
