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
                                <div class="col-md-4 mb-1">
                                    <form action="{{ route('rekap.cpb.excel') }}" method="POST"
                                        class="p-2 border rounded shadow-sm">
                                        @csrf
                                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-file-earmark-excel"></i> Download Excel
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <form action="{{ route('rekap.cpb.pdf') }}" method="POST"
                                        class="p-2 border rounded shadow-sm">
                                        @csrf
                                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="bi bi-file-earmark-pdf"></i> Download PDF
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <form action="{{ route('rekap.cpb.word') }}" method="POST"
                                        class="p-2 border rounded shadow-sm">
                                        @csrf
                                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-file-earmark-word"></i> Download Word
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex p-3 justify-content-between align-items-center flex-wrap">
                            <h4 class="card-title">Data CPB</h4>
                            <form method="GET" action="{{ route('admin.rekap.cpb') }}" class="mb-0">
                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                <label for="perPage">Tampilkan:</label>
                                <select name="perPage" id="perPage" class="form-select d-inline-block w-auto"
                                    onchange="this.form.submit()">
                                    <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>Semua
                                    </option>
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
                                        <th>Pekerjaan</th>
                                        <th>Alamat</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataCPB as $index => $cpb)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $cpb->nama }}</td>
                                            <td>{{ $cpb->nik }}</td>
                                            <td>{{ $cpb->no_kk }}</td>
                                            <td>{{ $cpb->pekerjaan }}</td>
                                            <td>{{ $cpb->alamat }}</td>
                                            <td>{{ $cpb->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($perPage !== 'all')
                            <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                {{ $dataCPB->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
