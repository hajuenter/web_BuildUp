@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Data Calon Penerima Bantuan</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Data</a></li>
                <li class="breadcrumb-item active">Data CPB</li>
            </ol>
        </nav>
    </div>

    <section>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-0">
                {{-- cari --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Pencarian Data CPB</h6>
                                <form method="GET" action="{{ route('admin.data_cpb') }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="nik" class="form-label">NIK</label>
                                            <input type="text" name="nik" id="nik" class="form-control"
                                                value="{{ request('nik') }}" placeholder="Masukkan NIK">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="no_kk" class="form-label">No KK</label>
                                            <input type="text" name="no_kk" id="no_kk" class="form-control"
                                                value="{{ request('no_kk') }}" placeholder="Masukkan No KK">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="nama" class="form-label">Nama</label>
                                            <input type="text" name="nama" id="nama" class="form-control"
                                                value="{{ request('nama') }}" placeholder="Masukkan Nama">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.data_cpb') }}" class="btn btn-secondary ms-2">
                                            <i class="bi bi-arrow-counterclockwise"></i> Refresh
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- cari end --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="p-3 d-flex gap-2 flex-wrap">
                        <span class="badge bg-success p-3">Sudah Dicek: {{ $totalSudahDicek }}</span>
                        <span class="badge bg-danger p-3">Belum Dicek: {{ $totalBelumDicek }}</span>
                        <span class="badge bg-primary p-3">Lolos Verifikasi Bantuan: {{ $totalTerverifikasi }}</span>
                        <span class="badge bg-warning p-3">Tidak Lolos Verifikasi Bantuan:
                            {{ $totalTidakTerverifikasi }}</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex p-3 justify-content-between align-items-center flex-wrap">
                            <h4 class="card-title">Data CPB</h4>
                            <form method="GET" action="{{ route('admin.data_cpb') }}" class="mb-0">
                                <label for="perPage">Tampilkan:</label>
                                <select name="perPage" id="perPage" class="form-select d-inline-block w-auto"
                                    onchange="this.form.submit()">
                                    <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5
                                    </option>
                                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10
                                    </option>
                                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25
                                    </option>
                                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50
                                    </option>
                                    <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>Semua
                                    </option>
                                </select>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nama</th>
                                        <th class="w-100">Alamat</th>
                                        <th>NIK</th>
                                        <th class="w-100">NO KK</th>
                                        <th>Pekerjaan</th>
                                        <th>Email</th>
                                        <th class="w-100">Foto Rumah</th>
                                        <th>Koordinat</th>
                                        <th>Status</th>
                                        <th>Pengecekan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataCPB as $cpb)
                                        <tr>
                                            <td>{{ $cpb->id }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->nama }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->alamat }}</td>
                                            <td>{{ $cpb->nik }}</td>
                                            <td>{{ $cpb->no_kk }}</td>
                                            <td>{{ $cpb->pekerjaan }}</td>
                                            <td>{{ $cpb->email }}</td>
                                            <td>
                                                @if ($cpb->foto_rumah)
                                                    <img src="{{ asset($cpb->foto_rumah) }}" alt="Foto Rumah"
                                                        width="100">
                                                @else
                                                    Tidak ada foto
                                                @endif
                                            </td>
                                            <td>{{ $cpb->koordinat }}</td>
                                            <td>{{ $cpb->status }}</td>
                                            <td>{{ $cpb->pengecekan }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $cpb->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <!-- Modal Konfirmasi Hapus -->
                                                <div class="modal fade" id="deleteModal{{ $cpb->id }}" tabindex="-1"
                                                    aria-labelledby="deleteModalLabel{{ $cpb->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="deleteModalLabel{{ $cpb->id }}">Konfirmasi
                                                                    Hapus</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus data
                                                                <strong>{{ $cpb->nama }}</strong>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <form
                                                                    action="{{ route('admin.delete.data_cpb', $cpb->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">Data tidak ditemukan !!!</td>
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
    </section>
@endsection
