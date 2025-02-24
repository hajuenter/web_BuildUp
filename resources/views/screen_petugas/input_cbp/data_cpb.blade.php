@extends('layouts.layouts_petugas')

@section('content-petugas')
    <section class="input-cpb">
        {{-- input --}}
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card mb-0">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Form Input Data CPB</h6>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Berhasil !</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="inputCPBForm" method="POST" action="{{ route('petugas.create.inputcpb') }}"
                            enctype="multipart/form-data" onsubmit="disableButton()">
                            @csrf
                            <div class="container-fluid">
                                <div class="row">
                                    <!-- Nama -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                                            <input type="text" name="nama"
                                                class="form-control @error('nama') is-invalid @enderror"
                                                placeholder="Masukkan nama" value="{{ old('nama') }}">
                                            <div class="invalid-feedback">
                                                @error('nama')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Alamat -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                            <input type="text" name="alamat"
                                                class="form-control @error('alamat') is-invalid @enderror"
                                                placeholder="Masukkan alamat" value="{{ old('alamat') }}">
                                            <div class="invalid-feedback">
                                                @error('alamat')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NIK -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">NIK <span class="text-danger">*</span></label>
                                            <input type="text" name="nik"
                                                class="form-control @error('nik') is-invalid @enderror"
                                                placeholder="Masukkan NIK" value="{{ old('nik') }}">
                                            <div class="invalid-feedback">
                                                @error('nik')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- NO KK -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">NO KK <span class="text-danger">*</span></label>
                                            <input type="text" name="no_kk"
                                                class="form-control @error('no_kk') is-invalid @enderror"
                                                placeholder="Masukkan NO KK" value="{{ old('no_kk') }}">
                                            <div class="invalid-feedback">
                                                @error('no_kk')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pekerjaan -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                                            <input type="text" name="pekerjaan"
                                                class="form-control @error('pekerjaan') is-invalid @enderror"
                                                placeholder="Masukkan pekerjaan" value="{{ old('pekerjaan') }}">
                                            <div class="invalid-feedback">
                                                @error('pekerjaan')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Masukkan email" value="{{ old('email') }}">
                                            <div class="invalid-feedback">
                                                @error('email')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Foto Rumah -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Foto Rumah <span class="text-danger">*</span></label>
                                            <input type="file" name="foto_rumah"
                                                class="form-control @error('foto_rumah') is-invalid @enderror"
                                                accept="image/*">
                                            <div class="invalid-feedback">
                                                @error('foto_rumah')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Koordinat -->
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">Koordinat <span class="text-danger">*</span></label>
                                            <input type="text" name="koordinat"
                                                class="form-control @error('koordinat') is-invalid @enderror"
                                                placeholder="Masukkan koordinat" value="{{ old('koordinat') }}">
                                            <div class="invalid-feedback">
                                                @error('koordinat')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <button type="submit" id="tambahCPBButton" class="btn btn-primary">Tambah</button>
                                <a href="{{ route('petugas.inputcpb') }}" class="btn btn-danger">Batal</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        {{-- input end --}}

        <!-- Tabel Data CPB -->
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Daftar Data CPB</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>NIK</th>
                                        <th>NO KK</th>
                                        <th>Pekerjaan</th>
                                        <th>Email</th>
                                        <th>Foto Rumah</th>
                                        <th>Koordinat</th>
                                        <th>Maps</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataCPB as $index => $cpb)
                                        <tr>
                                            <td>{{ $cpb->id }}</td>
                                            <td>{{ $cpb->nama }}</td>
                                            <td>{{ $cpb->alamat }}</td>
                                            <td>{{ $cpb->nik }}</td>
                                            <td>{{ $cpb->no_kk }}</td>
                                            <td>{{ $cpb->pekerjaan }}</td>
                                            <td>{{ $cpb->email }}</td>
                                            <td>
                                                @if ($cpb->foto_rumah)
                                                    <img src="{{ asset('up/data_cpb/' . $cpb->foto_rumah) }}"
                                                        width="100" alt="Foto Rumah">
                                                @else
                                                    Tidak ada foto
                                                @endif
                                            </td>
                                            <td>{{ $cpb->koordinat }}</td>
                                            <td>
                                                @if ($cpb->koordinat)
                                                    <a href="https://www.google.com/maps?q={{ $cpb->koordinat }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        Lihat di Maps
                                                    </a>
                                                @else
                                                    <span class="text-muted">Tidak tersedia</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">Tidak ada data CPB tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $dataCPB->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        {{-- tabel end --}}
    </section>
@endsection
<script>
    function disableButton() {
        let button = document.getElementById('tambahCPBButton');
        button.disabled = true;
        button.innerHTML = "Menambah...";
    }
</script>
