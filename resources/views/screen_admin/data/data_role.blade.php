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
                        <a href="{{ route('admin.data_role') }}" class="btn btn-secondary mb-2">
                            <i class="bi bi-arrow-repeat"></i> Refresh
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
                                    <table class="table table-hover table-bordered">
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
                                                    <td style="min-width: 250px;">
                                                        @if ($value->role === 'petugas')
                                                            Petugas Input Data CPB
                                                        @elseif ($value->role === 'user')
                                                            Petugas Verifikasi Data CPB
                                                        @else
                                                            Tidak diketahui
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->no_hp }}</td>
                                                    <td class="d-flex align-items-center flex-column gap-2">
                                                        @if (!$value->email_verified_at)
                                                            <!-- Button Verifikasi -->
                                                            <button type="button" class="btn btn-success btn-sm"
                                                                data-bs-toggle="modal" data-bs-target="#confirmModal"
                                                                data-action="{{ route('admin.user.verify', $value->id) }}"
                                                                data-message="Apakah Anda yakin ingin memverifikasi akun ini?">
                                                                <i class="bi bi-check-circle-fill"></i>
                                                            </button>
                                                        @else
                                                            <!-- Button Unverifikasi -->
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                data-bs-toggle="modal" data-bs-target="#confirmModal"
                                                                data-action="{{ route('admin.user.unverify', $value->id) }}"
                                                                data-message="Apakah Anda yakin ingin menonaktifkan akun ini?">
                                                                <i class="bi bi-x-circle-fill"></i>
                                                            </button>
                                                        @endif
                                                    </td>

                                                    <!-- Modal Konfirmasi -->
                                                    <div class="modal fade" id="confirmModal" tabindex="-1"
                                                        aria-labelledby="confirmModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="confirmModalLabel">
                                                                        Konfirmasi</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body" id="confirmMessage">
                                                                    <!-- Pesan konfirmasi akan muncul di sini -->
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <form id="confirmForm" method="POST">
                                                                        @csrf
                                                                        <button type="submit" id="confirmButton"
                                                                            class="btn btn-primary">
                                                                            <span id="buttonText">Ya, Lanjutkan</span>
                                                                            <span id="loadingSpinner"
                                                                                class="spinner-border spinner-border-sm d-none"
                                                                                role="status" aria-hidden="true"></span>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- JavaScript untuk Menyesuaikan Modal -->
                                                    <script>
                                                        var confirmModal = document.getElementById('confirmModal');
                                                        var confirmForm = document.getElementById('confirmForm');
                                                        var confirmButton = document.getElementById('confirmButton');
                                                        var buttonText = document.getElementById('buttonText');
                                                        var loadingSpinner = document.getElementById('loadingSpinner');

                                                        confirmModal.addEventListener('show.bs.modal', function(event) {
                                                            var button = event.relatedTarget; // Tombol yang memicu modal
                                                            var action = button.getAttribute('data-action'); // URL dari tombol
                                                            var message = button.getAttribute('data-message'); // Pesan konfirmasi

                                                            document.getElementById('confirmMessage').textContent = message; // Set pesan modal
                                                            confirmForm.setAttribute('action', action); // Set action form

                                                            // Pastikan tombol kembali normal saat modal ditampilkan kembali
                                                            confirmButton.disabled = false;
                                                            loadingSpinner.classList.add('d-none');
                                                            buttonText.classList.remove('d-none');
                                                        });

                                                        confirmForm.addEventListener('submit', function() {
                                                            // Saat tombol diklik, disable & tampilkan loading
                                                            confirmButton.disabled = true;
                                                            loadingSpinner.classList.remove('d-none');
                                                            buttonText.classList.add('d-none');
                                                        });
                                                    </script>

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
