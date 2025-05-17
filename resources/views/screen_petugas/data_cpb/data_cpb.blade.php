@extends('layouts.layouts_petugas')

@section('content-petugas')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Pencarian Data CPB</h6>
                        <form method="GET" action="{{ route('petugas.datacpb') }}">
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
                                <a href="{{ route('petugas.datacpb') }}" class="btn btn-secondary ms-2">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if (session('successEditCPB'))
            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                <strong>Berhasil !</strong> {{ session('successEditCPB') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('successDeleteCPB'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Berhasil !</strong> {{ session('successDeleteCPB') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title">Daftar Data CPB</h6>
                            <form method="GET" action="{{ route('petugas.datacpb') }}" class="mb-0">
                                <label for="perPage" class="me-2">Tampilkan:</label>
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

                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Desa/Kelurahan</th>
                                        <th>Kecamatan</th>
                                        <th>NIK</th>
                                        <th>NO KK</th>
                                        <th>Pekerjaan</th>
                                        <th>Email</th>
                                        <th>Foto Rumah</th>
                                        <th>Koordinat</th>
                                        <th>Maps</th>
                                        <th>Cetak</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataCPB as $index => $cpb)
                                        <tr>
                                            <td>{{ ($dataCPB->currentPage() - 1) * $dataCPB->perPage() + $index + 1 }}</td>
                                            <td style="min-width: 150px;">{{ $cpb->nama }}</td>
                                            @php
                                                $alamatParts = explode(';', $cpb->alamat);
                                            @endphp
                                            <td style="min-width: 200px;">{{ $alamatParts[0] ?? '-' }}</td>
                                            <td style="min-width: 150px;">{{ $alamatParts[1] ?? '-' }}</td>
                                            <td style="min-width: 150px;">{{ $alamatParts[2] ?? '-' }}</td>
                                            <td>{{ $cpb->nik }}</td>
                                            <td>{{ $cpb->no_kk }}</td>
                                            <td>{{ $cpb->pekerjaan }}</td>
                                            <td>{{ $cpb->email }}</td>
                                            <td>
                                                @if ($cpb->foto_rumah)
                                                    <img src="{{ asset($cpb->foto_rumah) }}" width="100"
                                                        alt="Foto Rumah">
                                                @else
                                                    Tidak ada foto
                                                @endif
                                            </td>
                                            <td>{{ $cpb->koordinat }}</td>
                                            <td>
                                                @if ($cpb->koordinat)
                                                    <a href="https://www.google.com/maps?q={{ $cpb->koordinat }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-geo-alt"></i> Maps
                                                    </a>
                                                @else
                                                    <span class="text-muted">Tidak tersedia</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('cpb.cetakSurat', $cpb->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="bi bi-file-earmark-text"></i> Cetak
                                                </a>
                                            </td>
                                            <td class="d-flex flex-column">
                                                <a href="{{ route('petugas.edit.cpb', $cpb->id) }}"
                                                    class="btn btn-success mb-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    onclick="setDeleteForm({{ $cpb->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center">Tidak ada data CPB tersedia.</td>
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
    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data CPB ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" onsubmit="disableDeleteButton()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="deleteButton">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function setDeleteForm(id) {
        let form = document.getElementById('deleteForm');
        form.action = "{{ url('/petugas/cpb/delete') }}/" + id;
    }

    function disableDeleteButton() {
        let button = document.getElementById('deleteButton');
        button.disabled = true;
        button.innerHTML = "Menghapus...";
    }
</script>
