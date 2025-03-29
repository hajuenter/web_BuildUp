@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Jadwal</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Menu</a></li>
                <li class="breadcrumb-item active">Jadwal</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section jadwal">
        {{-- cari --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-0">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Cari Jadwal</h6>
                        <form id="searchForm" method="get" action="{{ route('admin.jadwal') }}">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label class="form-label">Id</label>
                                        <input value="{{ request('id') }}" type="text" name="id" value=""
                                            class="form-control" placeholder="Masukkan id">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label class="form-label">Judul</label>
                                        <input value="{{ request('judul') }}" type="text" name="judul" value=""
                                            class="form-control" placeholder="Masukkan judul">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3">
                                        <label class="form-label">Kategori</label>
                                        <input value="{{ request('kategori') }}" type="text" name="kategori"
                                            value="" class="form-control" placeholder="Masukkan kategori">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3 mt-lg-3 mt-2">
                                        <label class="form-label">Mulai Tanggal</label>
                                        <input value="{{ request('start_date') }}" type="date" name="start_date"
                                            class="form-control" placeholder="Mulai Tanggal">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="mb-3 mt-lg-3 mt-2">
                                        <label class="form-label">Akhir Tanggal</label>
                                        <input value="{{ request('end_date') }}" type="date" name="end_date"
                                            class="form-control" placeholder="Akhir Tanggal">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Cari</button>
                            <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary">Refresh</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- cari end --}}

        {{-- tabel --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-0">
                @if (session('successAddJadwal'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('successAddJadwal') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('successDeleteJadwal'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('successDeleteJadwal') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('successEditJadwal'))
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        {{ session('successEditJadwal') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex p-3 justify-content-between align-items-center flex-wrap">
                            <h4 class="card-title">Data Jadwal</h4>
                            <form method="GET" action="{{ route('admin.jadwal') }}" class="mb-0">
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
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.add.jadwal') }}" class="btn btn-primary">Tambah Jadwal</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Alamat</th>
                                        <th>Waktu</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataJadwal as $jadwal)
                                        <tr>
                                            <td>{{ $jadwal->id }}</td>
                                            <td style="min-width: 300px">{{ $jadwal->judul }}</td>
                                            <td>{{ $jadwal->kategori }}</td>
                                            <td style="min-width: 300px">{{ $jadwal->alamat }}</td>
                                            <td>{{ date('H:i', strtotime($jadwal->waktu_start)) }} -
                                                {{ date('H:i', strtotime($jadwal->waktu_end)) }}</td>
                                            <td>{{ date('d M Y', strtotime($jadwal->tanggal_start)) }} -
                                                {{ date('d M Y', strtotime($jadwal->tanggal_end)) }}</td>
                                            <td class="d-flex flex-column">
                                                <a href="{{ route('admin.edit.jadwal', $jadwal->id) }}"
                                                    class="btn btn-success mb-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    onclick="setDeleteForm({{ $jadwal->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Data tidak ditemukan !!!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($perPage !== 'all')
                            <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                {{ $dataJadwal->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
        {{-- tabel end --}}
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
                    Apakah Anda yakin ingin menghapus jadwal ini?
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
        form.action = "{{ url('/admin/jadwal/delete') }}/" + id;
    }

    function disableDeleteButton() {
        let button = document.getElementById('deleteButton');
        button.disabled = true;
        button.innerHTML = "Menghapus...";
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let searchForm = document.getElementById("searchForm"); // Target hanya form pencarian
        if (searchForm) {
            searchForm.addEventListener("submit", function(event) {
                let inputs = searchForm.querySelectorAll("input");
                inputs.forEach(input => {
                    if (input.value.trim() === "") {
                        input.removeAttribute("name"); // Hapus input kosong
                    }
                });
            });
        }
    });
</script>
