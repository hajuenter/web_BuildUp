@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Rekap Data Calon Penerima Bantuan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Rekap</a></li>
                <li class="breadcrumb-item active">Data CPB</li>
            </ol>
        </nav>
    </div>

    <section>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-0">
                <div class="card">
                    <div class="card-body">
                        <div class="mt-4">
                            <h4 class="text-center card-title mb-3">Download Laporan Data CPB</h4>
                            <div class="row justify-content-center mt-3">
                                @foreach (['excel' => 'success', 'pdf' => 'danger', 'word' => 'primary'] as $format => $color)
                                    <div class="col-md-4 mb-1">
                                        <form action="{{ route('rekap.cpb.' . $format) }}" method="POST"
                                            class="p-2 border rounded shadow-sm">
                                            @csrf
                                            <div class="mb-2">
                                                <label for="status" class="form-label">Pilih Data:</label>
                                                <select name="status" class="form-control">
                                                    <option value="all">Semua Data</option>
                                                    <option value="checked">Sudah Dicek</option>
                                                    <option value="unchecked">Belum Dicek</option>
                                                    <option value="verif">Mendapat Bantuan</option>
                                                    <option value="unverif">Tidak Mendapat Bantuan</option>
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
                                    <a href="{{ route('admin.rekap.cpb') }}"
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

        @foreach ([['title' => 'Semua Data CPB', 'data' => $dataCPB, 'perPage' => $perPageAll, 'param' => 'perPageAll'], ['title' => 'Data CPB - Sudah Dicek', 'data' => $dataCekTrue, 'perPage' => $perPageTrue, 'param' => 'perPageTrue'], ['title' => 'Data CPB - Belum Dicek', 'data' => $dataCekFalse, 'perPage' => $perPageFalse, 'param' => 'perPageFalse'], ['title' => 'Data CPB - Mendapat Bantuan', 'data' => $dataCekVerif, 'perPage' => $perPageVerif, 'param' => 'perPageVerif'], ['title' => 'Data CPB - Tidak Mendapat Bantuan', 'data' => $dataCekUnverif, 'perPage' => $perPageUnverif, 'param' => 'perPageUnverif']] as $table)
            <div class="row mt-0">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex p-3 justify-content-between align-items-center flex-wrap">
                                <h4 class="card-title">{{ $table['title'] }}</h4>
                                <form method="GET" action="{{ route('admin.rekap.cpb') }}" class="mb-0">
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
                                            <th>Nama</th>
                                            <th>NIK</th>
                                            <th>No KK</th>
                                            <th>Email</th>
                                            <th>Koordinat</th>
                                            <th>Pekerjaan</th>
                                            <th>Alamat</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($table['data'] as $index => $cpb)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->nama }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->nik }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->no_kk }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->email }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->koordinat }}</td>
                                                <td style="min-width: 200px;">{{ $cpb->pekerjaan }}</td>
                                                <td style="min-width: 300px;">{{ $cpb->alamat }}</td>
                                                <td style="min-width: 150px;">{{ $cpb->created_at->format('d-m-Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data</td>
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
