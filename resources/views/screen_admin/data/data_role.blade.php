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
                                    <h4 class="card-title">Data Petugas Input CPB</h4>
                                    <form method="GET" action="{{ route('admin.data_role') }}" class="mb-0">
                                        <label for="perPage_petugas">Tampilkan:</label>
                                        <select name="perPage_petugas" id="perPage_petugas"
                                            class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                            <option value="5" {{ request('perPage_petugas') == 5 ? 'selected' : '' }}>5
                                            </option>
                                            <option value="10" {{ request('perPage_petugas') == 10 ? 'selected' : '' }}>
                                                10
                                            </option>
                                            <option value="25" {{ request('perPage_petugas') == 25 ? 'selected' : '' }}>
                                                25
                                            </option>
                                            <option value="50" {{ request('perPage_petugas') == 50 ? 'selected' : '' }}>
                                                50
                                            </option>
                                            <option value="all"
                                                {{ request('perPage_petugas') == 'all' ? 'selected' : '' }}>Semua
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
                                                <th>Email</th>
                                                <th>Verifikasi</th>
                                                <th>Role</th>
                                                <th>Desa / Kelurahan</th>
                                                <th>Kecamatan</th>
                                                <th>No Hp</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($dataPetugas as $value)
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
                                                    @php
                                                        $alamatParts = explode(';', $value->alamat);
                                                        $desaKelurahan = $alamatParts[1] ?? '-';
                                                        $kecamatan = $alamatParts[2] ?? '-';
                                                    @endphp

                                                    <td style="min-width: 250px;">{{ $desaKelurahan }}</td>
                                                    <td style="min-width: 200px;">{{ $kecamatan }}</td>
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
                                                    <td>
                                                        <!-- Tombol Hapus -->
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModalPetugas{{ $value->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                        <div class="modal fade" id="deleteModalPetugas{{ $value->id }}"
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
                                @if ($perPagePetugas !== 'all')
                                    <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                        {{ $dataPetugas->appends(request()->except('petugas_page'))->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex p-3 justify-content-between align-items-center flex-wrap">
                                    <h4 class="card-title">Data Petugas Verifikasi CPB</h4>
                                    <form method="GET" action="{{ route('admin.data_role') }}" class="mb-0">
                                        <label for="perPage_user">Tampilkan:</label>
                                        <select name="perPage_user" id="perPage_user"
                                            class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                            <option value="5" {{ request('perPage_user') == 5 ? 'selected' : '' }}>5
                                            </option>
                                            <option value="10" {{ request('perPage_user') == 10 ? 'selected' : '' }}>
                                                10
                                            </option>
                                            <option value="25" {{ request('perPage_user') == 25 ? 'selected' : '' }}>
                                                25
                                            </option>
                                            <option value="50" {{ request('perPage_user') == 50 ? 'selected' : '' }}>
                                                50
                                            </option>
                                            <option value="all"
                                                {{ request('perPage_user') == 'all' ? 'selected' : '' }}>
                                                Semua
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
                                                <th>Email</th>
                                                <th>Verifikasi</th>
                                                <th>Role</th>
                                                <th>Alamat</th>
                                                <th>No Hp</th>
                                                <th>Verifikasi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($dataUser as $value)
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
                                                    <td style="min-width: 250px;">{{ $value->alamat }}</td>
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
                                                    <td>
                                                        <!-- Tombol Hapus -->
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModalUser{{ $value->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                        <div class="modal fade" id="deleteModalUser{{ $value->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="deleteModalUserLabel{{ $value->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="deleteModalUserLabel{{ $value->id }}">
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

                                @if ($perPageUser !== 'all')
                                    <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                        {{ $dataUser->appends(request()->except('user_page'))->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">
                            Konfirmasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="confirmMessage">
                        <!-- Pesan konfirmasi akan muncul di sini -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="confirmForm" method="POST">
                            @csrf
                            <button type="submit" id="confirmButton" class="btn btn-primary">
                                <span id="buttonText">Ya, Lanjutkan</span>
                                <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Token Pendaftaran</h5>
                        @if (session('success'))
                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.token.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="token_login" class="form-label">Masukkan Token</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('token_login') is-invalid @enderror"
                                        id="token_login" name="token_login" value="{{ old('token_login') }}" required>

                                    <button type="button" class="btn btn-outline-secondary" id="toggleToken">
                                        <i class="bi bi-eye" id="iconToggle"></i>
                                    </button>

                                    {{-- Letakkan di sini agar muncul --}}
                                    @error('token_login')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit Token</button>
                        </form>

                        @if ($originalToken)
                            <div class="mt-4">
                                <label class="form-label">Token Saat Ini:</label><br>
                                <span class="badge bg-success" id="badgeToken">{{ $originalToken }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
        <script>
            const tokenInput = document.getElementById('token_login');
            const toggleBtn = document.getElementById('toggleToken');
            const iconToggle = document.getElementById('iconToggle');

            toggleBtn.addEventListener('click', function() {
                if (tokenInput.type === 'password') {
                    tokenInput.type = 'text';
                    iconToggle.classList.remove('bi-eye');
                    iconToggle.classList.add('bi-eye-slash');
                } else {
                    tokenInput.type = 'password';
                    iconToggle.classList.remove('bi-eye-slash');
                    iconToggle.classList.add('bi-eye');
                }
            });
        </script>
    </section>
@endsection
