<!-- Services Section -->
<section id="jadwal" class="services section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Jadwal</h2>
        <p><span>Cek Jadwal Kunjungan</span> <span class="description-title">Kami</span></p>
    </div><!-- End Section Title -->

    <div class="container">
        <div class="row gy-4">

            @php
                use Carbon\Carbon;
            @endphp

            @forelse ($showJadwal as $jadwal)
                @php
                    switch ($jadwal->kategori) {
                        case 'pengecekan':
                            $icon = 'bi-house-door';
                            break;
                        case 'sosialisasi':
                            $icon = 'bi-megaphone';
                            break;
                        case 'verifikasi':
                            $icon = 'bi-clipboard-check';
                            break;
                        case 'perbaikan':
                            $icon = 'bi-hammer';
                            break;
                        default:
                            $icon = 'bi-calendar-event';
                    }
                @endphp

                <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-item position-relative">
                        <div class="icon">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <a class="stretched-link">
                            <h3>{{ $jadwal->judul }}</h3>
                        </a>
                        <p><i class="bi bi-geo-alt"></i> {{ $jadwal->alamat }}</p>
                        <p><i class="bi bi-clock"></i> {{ $jadwal->waktu_start }} - {{ $jadwal->waktu_end }} WIB</p>
                        <p><i class="bi bi-calendar"></i>
                            {{ Carbon::parse($jadwal->tanggal_start)->translatedFormat('d M Y') }}
                            @if ($jadwal->tanggal_start != $jadwal->tanggal_end)
                                - {{ Carbon::parse($jadwal->tanggal_end)->translatedFormat('d M Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center">Belum ada jadwal yang tersedia.</div>
            @endforelse

        </div>
    </div>
</section><!-- /Services Section -->
