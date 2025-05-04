@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Data Verifikasi</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a>Data</a></li>
                <li class="breadcrumb-item active">Data Verifikasi</li>
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
                                <h6 class="card-title">Pencarian Data Verifikasi</h6>
                                <form method="GET" action="{{ route('admin.data_verif_cpb') }}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="nik" class="form-label">NIK</label>
                                            <input type="text" name="nik" id="nik" class="form-control"
                                                value="{{ request('nik') }}" placeholder="Masukkan NIK">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.data_verif_cpb') }}" class="btn btn-secondary ms-2">
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
                        <span class="badge bg-success p-3">Rusak Berat: {{ $totalRusakBerat }}</span>
                        <span class="badge bg-danger p-3">Rusak Sedang: {{ $totalRusakSedang }}</span>
                        <span class="badge bg-primary p-3">Rusak Ringan: {{ $totalRusakRingan }}</span>
                        <span class="badge bg-warning p-3">Tidak Mendapat Bantuan: {{ $totalTidakDapatBantuan }}</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex p-3 justify-content-between align-items-center flex-wrap">
                            <h4 class="card-title">Data Verifikasi CPB</h4>
                            <form method="GET" action="{{ route('admin.data_verif_cpb') }}" class="mb-0">
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
                                        <th>ID</th>
                                        <th>NIK</th>
                                        <th>Penutup Atap</th>
                                        <th>Rangka Atap</th>
                                        <th>Kolom</th>
                                        <th>Ring Balok</th>
                                        <th>Dinding Pengisi</th>
                                        <th>Kusen</th>
                                        <th>Pintu</th>
                                        <th>Jendela</th>
                                        <th>Struktur Bawah</th>
                                        <th>Penutup Lantai</th>
                                        <th>Pondasi</th>
                                        <th>Sloof</th>
                                        <th>Sanitasi</th>
                                        <th>Air Bersih</th>
                                        <th>Kesanggupan Berswadaya</th>
                                        <th>Tipe</th>
                                        <th>Penilaian Kerusakan</th>
                                        <th>Nilai Bantuan</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataVerifCPB as $cpb)
                                        <tr>
                                            <td>{{ $cpb->id }}</td>
                                            <td style="min-width: 300px;">{{ $cpb->nik }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->penutup_atap }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->rangka_atap }}</td>
                                            <td>{{ $cpb->kolom }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->ring_balok }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->dinding_pengisi }}</td>
                                            <td>{{ $cpb->kusen }}</td>
                                            <td>{{ $cpb->pintu }}</td>
                                            <td>{{ $cpb->jendela }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->struktur_bawah }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->penutup_lantai }}</td>
                                            <td>{{ $cpb->pondasi }}</td>
                                            <td>{{ $cpb->sloof }}</td>
                                            <td>{{ $cpb->mck }}</td>
                                            <td style="min-width: 100px;">{{ $cpb->air_kotor }}</td>
                                            <td style="min-width: 300px;">
                                                {{ $cpb->kesanggupan_berswadaya }}</td>
                                            <td>{{ $cpb->tipe }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->penilaian_kerusakan }}</td>
                                            <td style="min-width: 200px;">{{ $cpb->nilai_bantuan }}</td>
                                            <td style="min-width: 250px;">{{ $cpb->catatan }}</td>
                                            <td>
                                                <!-- Tombol untuk membuka modal -->
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#modalHapus{{ $cpb->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                                <!-- Modal Konfirmasi Hapus -->
                                                <div class="modal fade" id="modalHapus{{ $cpb->id }}" tabindex="-1"
                                                    aria-labelledby="modalHapusLabel{{ $cpb->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="modalHapusLabel{{ $cpb->id }}">Konfirmasi
                                                                    Hapus</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus data ini? Data yang
                                                                dihapus tidak dapat dikembalikan.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <form action="{{ route('admin.delete.data_verif_cpb') }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $cpb->id }}">
                                                                    <input type="hidden" name="nik"
                                                                        value="{{ $cpb->nik }}">
                                                                    <button type="submit" class="btn btn-danger">
                                                                        Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="22">Data tidak ditemukan !!!</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if ($perPage !== 'all')
                            <div class="d-flex justify-content-center justify-content-md-end mt-3">
                                {{ $dataVerifCPB->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
    </section>
@endsection
