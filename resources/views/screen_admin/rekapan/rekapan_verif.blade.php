@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Rekap Data Verifikasi Calon Penerima Bantuan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Rekap</a></li>
                <li class="breadcrumb-item active">Verifikasi Data CPB</li>
            </ol>
        </nav>
    </div>
    <section>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-0">
                <div class="card">
                    <div class="card-body">
                        <div class="mt-4">
                            <h4 class="text-center card-title mb-3">Download Laporan Data Verifiaksi CPB</h4>
                            <div class="row justify-content-center mt-3">
                                @foreach (['excel' => 'success', 'pdf' => 'danger', 'word' => 'primary'] as $format => $color)
                                    <div class="col-md-4 mb-1">
                                        <form action="{{ route('rekap.verif.' . $format) }}" method="POST"
                                            class="p-2 border rounded shadow-sm">
                                            @csrf
                                            <div class="mb-2">
                                                <label for="status" class="form-label">Pilih Data:</label>
                                                <select name="status" class="form-control">
                                                    <option value="all">Semua Data</option>
                                                    <option value="checked">Mendapat Bantuan</option>
                                                    <option value="unchecked">Tidak Mendapat Bantuan</option>
                                                </select>
                                            </div>

                                            <div class="mb-2">
                                                <label for="desa" class="form-label">Filter Desa:</label>
                                                <select name="desa" class="form-control">
                                                    <option value="all">Semua Desa</option>
                                                    @foreach ($desaList as $desa)
                                                        <option value="{{ $desa }}">{{ $desa }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-{{ $color }} w-100">
                                                <i class="bi bi-file-earmark-{{ $format }}"></i> Download
                                                {{ strtoupper($format) }}
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                                <div class="col-md-12 mt-3 mb-1">
                                    <a href="{{ route('admin.rekap.verif') }}"
                                        class="btn btn-secondary w-100 d-flex justify-content-center align-items-center">
                                        <i class="bi bi-arrow-clockwise me-2"></i> Refresh
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach ([['title' => 'Semua Data Verifikasi CPB', 'data' => $dataVerifCPB, 'perPage' => $perPageAll, 'param' => 'perPageAll'], ['title' => 'Data Verifikasi CPB - Mendapatkan Bantuan', 'data' => $dataCekTrue, 'perPage' => $perPageTrue, 'param' => 'perPageTrue'], ['title' => 'Data Verifikasi CPB - Tidak Mendapatkan Bantuan', 'data' => $dataCekFalse, 'perPage' => $perPageFalse, 'param' => 'perPageFalse']] as $table)
            <div class="row mt-0">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex p-3 justify-content-between align-items-center flex-wrap">
                                <h4 class="card-title">{{ $table['title'] }}</h4>
                                <form method="GET" action="{{ route('admin.rekap.verif') }}" class="mb-0">
                                    <label for="{{ $table['param'] }}">Tampilkan:</label>
                                    <select name="{{ $table['param'] }}" class="form-select d-inline-block w-auto"
                                        onchange="this.form.submit()">
                                        @foreach ([5, 10, 25, 50, 'all'] as $option)
                                            <option value="{{ $option }}"
                                                {{ request($table['param']) == $option ? 'selected' : '' }}>
                                                {{ $option == 'all' ? 'Semua' : $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIK</th>
                                            <th>Penutup Atap</th>
                                            <th>Rangka Atap</th>
                                            <th>Kolom</th>
                                            <th>Ring Balok</th>
                                            <th>Dinding Pengisi</th>
                                            <th>Kusen</th>
                                            <th>Pintu</th>
                                            <th>Jendela</th>
                                            <th>Struktur Bawah</th>
                                            <th>Penutup Lantai</th>
                                            <th>Pondasi</th>
                                            <th>Sloof</th>
                                            <th>Sanitasi</th>
                                            <th>Air Bersih</th>
                                            <th>Kesanggupan Berswadaya</th>
                                            <th>Tipe</th>
                                            <th>Penilaian Kerusakan</th>
                                            <th>Nilai Bantuan</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($table['data'] as $index => $cpb)
                                            <tr>
                                                <td>
                                                    {{ $table['perPage'] === 'all'
                                                        ? $loop->iteration
                                                        : ($table['data']->currentPage() - 1) * $table['data']->perPage() + $loop->iteration }}
                                                </td>
                                                <td style="min-width: 300px;">{{ $cpb->nik }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->penutup_atap }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->rangka_atap }}</td>
                                                <td>{{ $cpb->kolom }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->ring_balok }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->dinding_pengisi }}</td>
                                                <td>{{ $cpb->kusen }}</td>
                                                <td>{{ $cpb->pintu }}</td>
                                                <td>{{ $cpb->jendela }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->struktur_bawah }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->penutup_lantai }}</td>
                                                <td>{{ $cpb->pondasi }}</td>
                                                <td>{{ $cpb->sloof }}</td>
                                                <td>{{ $cpb->mck }}</td>
                                                <td style="min-width: 100px;">{{ $cpb->air_kotor }}</td>
                                                <td style="min-width: 300px;">
                                                    {{ $cpb->kesanggupan_berswadaya }}</td>
                                                <td>{{ $cpb->tipe }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->penilaian_kerusakan }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->nilai_bantuan }}</td>
                                                <td style="min-width: 250px;">{{ $cpb->catatan }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="21" class="text-center">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($table['perPage'] !== 'all')
                                <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                    {{ $table['data']->appends([
                                            $table['param'] => request($table['param']),
                                        ])->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
@endsection
