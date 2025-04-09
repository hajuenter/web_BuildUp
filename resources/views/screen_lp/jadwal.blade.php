<!-- Services Section -->
<section id="jadwal" class="services section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Jadwal</h2>
        <p><span>Cek Jadwal Kunjungan</span> <span class="description-title">Kami</span></p>
    </div><!-- End Section Title -->

    <!-- Filter Buttons -->
    <div class="container mb-4">
        <div class="text-center" data-aos="fade-up">
            <div class="category-filters">
                <button class="btn btn-primary filter-btn active" data-filter="all">Semua</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="pengecekan">Pengecekan</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="sosialisasi">Sosialisasi</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="verifikasi">Verifikasi</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="perbaikan">Perbaikan</button>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Section for category grouping -->
        <div class="category-sections">
            @php
                use Carbon\Carbon;

                // Urutkan jadwal dari yang terbaru ke terlama berdasarkan tanggal_start
                $sortedJadwal = $showJadwal->sortByDesc('tanggal_start');

                // Kelompokkan jadwal berdasarkan kategori (tetap mempertahankan urutan)
                $groupedJadwal = $sortedJadwal->groupBy('kategori');
            @endphp

            <!-- All items shown by default (sorted by newest first) -->
            <div class="category-section" id="all-section">
                <div class="row gy-4">
                    @forelse ($sortedJadwal as $jadwal)
                        @php
                            switch ($jadwal->kategori) {
                                case 'pengecekan':
                                    $icon = 'bi-house-door';
                                    $borderColor = 'border-blue';
                                    break;
                                case 'sosialisasi':
                                    $icon = 'bi-megaphone';
                                    $borderColor = 'border-green';
                                    break;
                                case 'verifikasi':
                                    $icon = 'bi-clipboard-check';
                                    $borderColor = 'border-orange';
                                    break;
                                case 'perbaikan':
                                    $icon = 'bi-hammer';
                                    $borderColor = 'border-red';
                                    break;
                                default:
                                    $icon = 'bi-calendar-event';
                                    $borderColor = '';
                            }
                        @endphp

                        <div class="col-lg-3 col-md-4 col-sm-6 jadwal-item {{ $jadwal->kategori }}" data-aos="fade-up"
                            data-aos-delay="100">
                            <div class="service-item position-relative {{ $borderColor }}">
                                <div class="category-badge">{{ ucfirst($jadwal->kategori) }}</div>
                                <div class="icon">
                                    <i class="bi {{ $icon }}"></i>
                                </div>
                                <a class="stretched-link">
                                    <h3>{{ $jadwal->judul }}</h3>
                                </a>
                                <p><i class="bi bi-geo-alt"></i> {{ $jadwal->alamat }}</p>
                                <p><i class="bi bi-clock"></i> {{ $jadwal->waktu_start }} - {{ $jadwal->waktu_end }} WIB
                                </p>
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

            <!-- Individual category sections (each sorted by newest first) -->
            @foreach ($groupedJadwal as $kategori => $jadwalItems)
                <div class="category-section" id="{{ $kategori }}-section" style="display: none;">
                    <h3 class="category-title text-center mb-4">{{ ucfirst($kategori) }}</h3>
                    <div class="row gy-4">
                        @foreach ($jadwalItems as $jadwal)
                            @php
                                switch ($jadwal->kategori) {
                                    case 'pengecekan':
                                        $icon = 'bi-house-door';
                                        $borderColor = 'border-blue';
                                        break;
                                    case 'sosialisasi':
                                        $icon = 'bi-megaphone';
                                        $borderColor = 'border-green';
                                        break;
                                    case 'verifikasi':
                                        $icon = 'bi-clipboard-check';
                                        $borderColor = 'border-orange';
                                        break;
                                    case 'perbaikan':
                                        $icon = 'bi-hammer';
                                        $borderColor = 'border-red';
                                        break;
                                    default:
                                        $icon = 'bi-calendar-event';
                                        $borderColor = '';
                                }
                            @endphp

                            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="100">
                                <div class="service-item position-relative {{ $borderColor }}">
                                    <div class="category-badge">{{ ucfirst($jadwal->kategori) }}</div>
                                    <div class="icon">
                                        <i class="bi {{ $icon }}"></i>
                                    </div>
                                    <a class="stretched-link">
                                        <h3>{{ $jadwal->judul }}</h3>
                                    </a>
                                    <p><i class="bi bi-geo-alt"></i> {{ $jadwal->alamat }}</p>
                                    <p><i class="bi bi-clock"></i> {{ $jadwal->waktu_start }} -
                                        {{ $jadwal->waktu_end }} WIB</p>
                                    <p><i class="bi bi-calendar"></i>
                                        {{ Carbon::parse($jadwal->tanggal_start)->translatedFormat('d M Y') }}
                                        @if ($jadwal->tanggal_start != $jadwal->tanggal_end)
                                            - {{ Carbon::parse($jadwal->tanggal_end)->translatedFormat('d M Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section><!-- /Services Section -->

<!-- Tambahkan styles -->
<style>
    .category-filters {
        margin-bottom: 30px;
    }

    .filter-btn {
        margin: 0 5px 10px;
        transition: all 0.3s;
    }

    .service-item {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
        position: relative;
    }

    .service-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .border-blue {
        border-top: 3px solid #03a9f4;
    }

    .border-green {
        border-top: 3px solid #4caf50;
    }

    .border-orange {
        border-top: 3px solid #ff9800;
    }

    .border-red {
        border-top: 3px solid #f44336;
    }

    .service-item .icon {
        font-size: 2rem;
        margin-bottom: 15px;
        display: inline-block;
    }

    .service-item h3 {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }

    .service-item p {
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .category-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #007bff;
        color: white;
        padding: 3px 10px;
        font-size: 0.75rem;
        border-radius: 20px;
    }

    .category-title {
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .category-title:after {
        content: '';
        position: absolute;
        display: block;
        width: 50px;
        height: 3px;
        background: #007bff;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
    }
</style>

<!-- Tambahkan script JavaScript untuk filtering -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all filter buttons
        const filterButtons = document.querySelectorAll('.filter-btn');

        // Add click event to each button
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Get the filter value
                const filter = this.getAttribute('data-filter');

                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                filterButtons.forEach(btn => {
                    if (btn.getAttribute('data-filter') === 'all') {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    } else if (btn.getAttribute('data-filter') === filter) {
                        btn.classList.add('btn-primary');
                        btn.classList.remove('btn-outline-primary');
                    } else {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                    }
                });

                // Add active class to clicked button
                this.classList.add('active');

                // Show/hide sections based on filter
                const allSection = document.getElementById('all-section');
                const categorySection = document.getElementById(filter + '-section');

                if (filter === 'all') {
                    allSection.style.display = 'block';
                    document.querySelectorAll('.category-section:not(#all-section)').forEach(
                        section => {
                            section.style.display = 'none';
                        });
                } else {
                    allSection.style.display = 'none';
                    document.querySelectorAll('.category-section:not(#all-section)').forEach(
                        section => {
                            section.style.display = 'none';
                        });
                    if (categorySection) {
                        categorySection.style.display = 'block';
                    }
                }
            });
        });
    });
</script>
