@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Berita</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Menu</a></li>
                <li class="breadcrumb-item active">Berita</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section berita">

        {{-- cari --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-0">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Cari Berita</h6>
                        <form id="searchForm" method="get" action="{{ route('admin.berita') }}">
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
                                        <label class="form-label">Penulis</label>
                                        <input value="{{ request('penulis') }}" type="text" name="penulis" value=""
                                            class="form-control" placeholder="Masukkan penulis">
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
                            <a href="{{ route('admin.berita') }}" class="btn btn-danger">Refresh</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- cari end --}}

        {{-- tabel --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-0">
                @if (session('successAddBerita'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('successAddBerita') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('successDeleteBerita'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('successDeleteBerita') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('successEditBerita'))
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        {{ session('successEditBerita') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex p-3 justify-content-between align-items-center flex-wrap">
                            <h4 class="card-title">Data Berita</h4>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.add.berita') }}" class="btn btn-primary">Tambah Berita</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Judul</th>
                                        <th class="w-100">Isi</th>
                                        <th>Penulis</th>
                                        <th>Tempat</th>
                                        <th>Tanggal</th>
                                        <th>Foto</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataBerita as $value)
                                        <tr>
                                            <th>{{ $value->id }}</th>
                                            <td>{{ $value->judul }}</td>
                                            <td>{{ $value->isi }}</td>
                                            <td>{{ $value->penulis }}</td>
                                            <td>{{ $value->tempat }}</td>
                                            <td>{{ date('F d, Y', strtotime($value->created_at)) }}</td>
                                            <td>
                                                <img src="{{ asset('up/berita/' . $value->photo) }}" alt="Foto Berita"
                                                    width="100">
                                            </td>
                                            <td class="d-flex flex-column">
                                                <a href="{{ route('admin.edit.berita', $value->id) }}"
                                                    class="btn btn-success mb-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    onclick="setDeleteForm({{ $value->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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

                        <div class="d-flex justify-content-center justify-content-md-end mt-3">
                            {{ $dataBerita->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>

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
                    Apakah Anda yakin ingin menghapus berita ini?
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
        form.action = "{{ url('/admin/berita/delete') }}/" + id;
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
