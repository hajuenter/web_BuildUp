@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">

                    <!-- Data CPB Card -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card sales-card">
                            <div class="filter">
                                <a class="icon" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter CPB</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.dashboard', ['filter_cpb' => 'hari', 'filter_berita' => request('filter_berita', 'tahun')]) }}">
                                            Hari Ini
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.dashboard', ['filter_cpb' => 'bulan', 'filter_berita' => request('filter_berita', 'tahun')]) }}">
                                            Bulan Ini
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.dashboard', ['filter_cpb' => 'tahun', 'filter_berita' => request('filter_berita', 'tahun')]) }}">
                                            Tahun Ini
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Data CPB</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $jumlahCPB }}</h6>
                                        <span class="text-success small pt-1 fw-bold">
                                            {{ $jumlahCPB > 0 ? round(($jumlahCPB / max(1, $totalCPB)) * 100, 2) : 0 }}%
                                        </span>
                                        <span class="text-muted small pt-2 ps-1">Meningkat</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Data CPB Card -->

                    <!-- Berita Card -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card revenue-card">
                            <div class="filter">
                                <a class="icon" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter Berita</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.dashboard', ['filter_berita' => 'hari', 'filter_cpb' => request('filter_cpb', 'tahun')]) }}">
                                            Hari Ini
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.dashboard', ['filter_berita' => 'bulan', 'filter_cpb' => request('filter_cpb', 'tahun')]) }}">
                                            Bulan Ini
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.dashboard', ['filter_berita' => 'tahun', 'filter_cpb' => request('filter_cpb', 'tahun')]) }}">
                                            Tahun Ini
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Data Berita</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-newspaper"></i> <!-- Ganti ikon berita -->
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $jumlahBerita }}</h6>
                                        <span class="text-success small pt-1 fw-bold">
                                            {{ $jumlahBerita > 0 ? round(($jumlahBerita / max(1, $totalBerita)) * 100, 2) : 0 }}%
                                        </span>
                                        <span class="text-muted small pt-2 ps-1">Meningkat</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Berita Card -->

                    <!-- Jadwal Card -->
                    <div class="col-xxl-4 col-md-4">
                        <div class="card info-card customers-card">
                            <div class="filter">
                                <a class="icon" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter Jadwal</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.dashboard', ['filter_jadwal' => 'hari', 'filter_cpb' => request('filter_cpb', 'tahun'), 'filter_berita' => request('filter_berita', 'tahun')]) }}">
                                            Hari Ini
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.dashboard', ['filter_jadwal' => 'bulan', 'filter_cpb' => request('filter_cpb', 'tahun'), 'filter_berita' => request('filter_berita', 'tahun')]) }}">
                                            Bulan Ini
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.dashboard', ['filter_jadwal' => 'tahun', 'filter_cpb' => request('filter_cpb', 'tahun'), 'filter_berita' => request('filter_berita', 'tahun')]) }}">
                                            Tahun Ini
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Data Jadwal</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar-event"></i> <!-- Ikon jadwal -->
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $jumlahJadwal }}</h6>
                                        <span class="text-success small pt-1 fw-bold">
                                            {{ $jumlahJadwal > 0 ? round(($jumlahJadwal / max(1, $totalJadwal)) * 100, 2) : 0 }}%
                                        </span>
                                        <span class="text-muted small pt-2 ps-1">Meningkat</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Jadwal Card -->

                </div>
            </div><!-- End Left side columns -->

            <div class="col-lg-8">
                <!-- Reports -->
                <div class="col-12">
                    <div class="card">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.7432667192843!2d111.90611727500304!3d-7.602893692412099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e784baee3d69fd3%3A0x132635346a25bd7f!2sDinas%20Perumahan%20Rakyat%20Kawasan%20Permukiman%20dan%20Pertanahan%20Kabupaten%20Nganjuk!5e0!3m2!1sid!2sid!4v1740065925010!5m2!1sid!2sid"
                            frameborder="0" style="border:0; width: 100%; height: 460px;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

            <!-- Right side columns -->
            <div class="col-lg-4">
                <!-- Website Traffic -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
                            <li><a class="dropdown-item" href="{{ url()->current() }}?filter_laporan=hari">Hari Ini</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ url()->current() }}?filter_laporan=bulan">Bulan Ini</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ url()->current() }}?filter_laporan=tahun">Tahun Ini</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body pb-0">
                        <h5 class="card-title">Laporan Data <span>| {{ ucfirst($filterLaporan) }}</span></h5>

                        <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                let dataLaporan = @json($dataLaporan);

                                echarts.init(document.querySelector("#trafficChart")).setOption({
                                    tooltip: {
                                        trigger: 'item'
                                    },
                                    legend: {
                                        top: '5%',
                                        left: 'center'
                                    },
                                    series: [{
                                        name: 'Jumlah Data',
                                        type: 'pie',
                                        radius: ['40%', '70%'],
                                        avoidLabelOverlap: false,
                                        label: {
                                            show: false,
                                            position: 'center'
                                        },
                                        emphasis: {
                                            label: {
                                                show: true,
                                                fontSize: '18',
                                                fontWeight: 'bold'
                                            }
                                        },
                                        labelLine: {
                                            show: false
                                        },
                                        data: [{
                                                value: dataLaporan.cpb,
                                                name: 'Data CPB'
                                            },
                                            {
                                                value: dataLaporan.berita,
                                                name: 'Data Berita'
                                            },
                                            {
                                                value: dataLaporan.jadwal,
                                                name: 'Data Jadwal'
                                            }
                                        ]
                                    }]
                                });
                            });
                        </script>
                    </div>
                </div>
                <!-- End Website Traffic -->
            </div><!-- End Right side columns -->

        </div>
    </section>
@endsection
