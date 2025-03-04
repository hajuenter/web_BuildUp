@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Data Pengguna</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Data</a></li>
                <li class="breadcrumb-item active">Data Pengguna</li>
            </ol>
        </nav>
    </div>

    <section>
        <div class="row">
            <div class="col-12">
                {{-- tabel --}}
                <div class="row">
                    <div class="col-md-12 grid-margin stretch-card mb-0">
                        <a href="{{ route('admin.user.petugas.add') }}" class="btn btn-primary mb-2">
                            <i class="bi bi-person-plus"></i> Tambah Pengguna
                        </a>

                        @if (session('successY'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('successY') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('successG'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('successG') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex p-3 justify-content-between align-items-center flex-wrap">
                                    <h4 class="card-title">Data Pengguna</h4>
                                    <form method="GET" action="{{ route('admin.data_role') }}" class="mb-0">
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
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Verifikasi</th>
                                                <th>Role</th>
                                                <th>No Hp</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($dataRole as $value)
                                                <tr>
                                                    <th>{{ $value->id }}</th>
                                                    <td style="white-space: nowrap;">{{ $value->name }}</td>
                                                    <td style="white-space: nowrap;">{{ $value->email }}</td>
                                                    <td>
                                                        @if ($value->email_verified_at)
                                                            <span class="badge bg-success">Terverifikasi</span>
                                                        @else
                                                            <span class="badge bg-danger">Belum Verifikasi</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->role }}</td>
                                                    <td>{{ $value->no_hp }}</td>
                                                    <td class="d-flex align-items-center flex-column gap-2">
                                                        @if (!$value->email_verified_at)
                                                            <!-- Button Verifikasi -->
                                                            <form action="{{ route('admin.user.verify', $value->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm">
                                                                    <i class="bi bi-check-circle-fill"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <!-- Button Unverifikasi -->
                                                            <form action="{{ route('admin.user.unverify', $value->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm">
                                                                    <i class="bi bi-x-circle-fill"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <!-- Tombol Hapus -->
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $value->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>

                                                        <!-- Modal Konfirmasi -->
                                                        <div class="modal fade" id="deleteModal{{ $value->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="deleteModalLabel{{ $value->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="deleteModalLabel{{ $value->id }}">
                                                                            Konfirmasi Hapus</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Apakah Anda yakin ingin menghapus user
                                                                        <strong>{{ $value->name }}</strong>?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                        <form
                                                                            action="{{ route('admin.user.delete', $value->id) }}"
                                                                            method="post">
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
                                                    <td colspan="7" class="text-center">Data tidak ditemukan !!!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>

                                    </table>
                                </div>

                                @if ($perPage !== 'all')
                                    <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                        {{ $dataRole->appends(request()->query())->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                {{-- tabel end --}}
            </div>
        </div>
    </section>
@endsection
