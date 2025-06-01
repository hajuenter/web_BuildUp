@extends('layouts.layouts_admin')

@section('content-admin')
    <div class="pagetitle">
        <h1>Edit Data CPB</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Data</li>
                <li class="breadcrumb-item"><a href="{{ route('admin.data_verif_cpb') }}">Data CPB</a></li>
                <li class="breadcrumb-item active">Edit Data Verif CPB</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section cpb">
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h6 class="card-title">Edit Data CPB</h6>

                        @php
                            // Dropdown untuk decimal 0.25,0.5,0.75,1.0
                            $dropdownOptionsDecimal = ['0.0', '0.25', '0.5', '0.75', '1.0'];

                            // Dropdown untuk 0.0 dan 1.0
                            $dropdownOptions2 = ['0.0', '1.0'];

                            // Field dengan pilihan dropdown decimal
                            $fieldsDropdownDecimal = [
                                'penutup_atap',
                                'rangka_atap',
                                'kolom',
                                'ring_balok',
                                'dinding_pengisi',
                                'kusen',
                                'pintu',
                                'jendela',
                                'struktur_bawah',
                                'penutup_lantai',
                                'mck', // ini tetap di decimal tapi nanti label diganti
                            ];

                            // Field dengan pilihan dropdown 0.0 dan 1.0
                            $fieldsDropdown2 = ['pondasi', 'sloof', 'air_kotor'];

                            // Label custom untuk beberapa field (khusus dropdown 0.0,1.0)
                            $labelsDropdown2 = [
                                'pondasi' => 'Pondasi',
                                'sloof' => 'Sloof',
                                'air_kotor' => 'Air Bersih', // Ganti label
                            ];

                            // Label custom untuk dropdown decimal juga ganti 'mck' jadi 'Sanitasi'
                            $labelsDropdownDecimal = [
                                'mck' => 'Sanitasi',
                            ];
                        @endphp

                        <form method="POST" action="{{ route('admin.update.data_verif_cpb', $verifCPB->id) }}"
                            enctype="multipart/form-data" onsubmit="disableButton()">
                            @csrf
                            @method('PUT')
                            {{-- Input Foto KK --}}
                            <div class="mb-3">
                                <label class="form-label">Foto KK</label>
                                <input type="file" name="foto_kk" class="form-control">
                                @if ($verifCPB->foto_kk && file_exists(public_path($verifCPB->foto_kk)))
                                    <div class="mt-2">
                                        <img src="{{ asset($verifCPB->foto_kk) }}" alt="Foto KK" width="100">
                                    </div>
                                @else
                                    <p class="text-muted mt-2">Foto KK belum tersedia</p>
                                @endif
                            </div>

                            {{-- Input Foto KTP --}}
                            <div class="mb-3">
                                <label class="form-label">Foto KTP</label>
                                <input type="file" name="foto_ktp" class="form-control">
                                @if ($verifCPB->foto_ktp && file_exists(public_path($verifCPB->foto_ktp)))
                                    <div class="mt-2">
                                        <img src="{{ asset($verifCPB->foto_ktp) }}" alt="Foto KTP" width="100">
                                    </div>
                                @else
                                    <p class="text-muted mt-2">Foto KTP belum tersedia</p>
                                @endif
                            </div>

                            {{-- Loop untuk dropdown decimal --}}
                            @foreach ($fieldsDropdownDecimal as $field)
                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ $labelsDropdownDecimal[$field] ?? ucwords(str_replace('_', ' ', $field)) }}
                                    </label>
                                    <select name="{{ $field }}" class="form-select" required>
                                        <option value="">-- Pilih Nilai --</option>
                                        @foreach ($dropdownOptionsDecimal as $opt)
                                            <option value="{{ $opt }}"
                                                {{ old($field, $verifCPB->$field) == (float) $opt ? 'selected' : '' }}>
                                                {{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Foto
                                        {{ $labelsDropdownDecimal[$field] ?? ucwords(str_replace('_', ' ', $field)) }}
                                    </label>
                                    <input type="file" name="foto_{{ $field }}" class="form-control">
                                    @php $fotoField = 'foto_' . $field; @endphp
                                    @if ($verifCPB->$fotoField && file_exists(public_path($verifCPB->$fotoField)))
                                        <div class="mt-2">
                                            <img src="{{ asset($verifCPB->$fotoField) }}" alt="Foto {{ $field }}"
                                                width="100">
                                        </div>
                                    @else
                                        <p class="text-muted mt-2">Foto
                                            {{ $labelsDropdownDecimal[$field] ?? ucwords(str_replace('_', ' ', $field)) }}
                                            belum tersedia
                                        </p>
                                    @endif
                                </div>
                            @endforeach

                            {{-- Loop untuk dropdown 0.0 dan 1.0 --}}
                            @foreach ($fieldsDropdown2 as $field)
                                <div class="mb-3">
                                    <label class="form-label">
                                        {{ $labelsDropdown2[$field] ?? ucwords(str_replace('_', ' ', $field)) }}
                                    </label>
                                    <select name="{{ $field }}" class="form-select" required>
                                        <option value="">-- Pilih Nilai --</option>
                                        @foreach ($dropdownOptions2 as $opt)
                                            <option value="{{ $opt }}"
                                                {{ old($field, $verifCPB->$field) == (float) $opt ? 'selected' : '' }}>
                                                {{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Foto
                                        {{ $labelsDropdown2[$field] ?? ucwords(str_replace('_', ' ', $field)) }}
                                    </label>
                                    <input type="file" name="foto_{{ $field }}" class="form-control">
                                    @php $fotoField = 'foto_' . $field; @endphp
                                    @if ($verifCPB->$fotoField && file_exists(public_path($verifCPB->$fotoField)))
                                        <div class="mt-2">
                                            <img src="{{ asset($verifCPB->$fotoField) }}" alt="Foto {{ $field }}"
                                                width="100">
                                        </div>
                                    @else
                                        <p class="text-muted mt-2">Foto
                                            {{ $labelsDropdown2[$field] ?? ucwords(str_replace('_', ' ', $field)) }}
                                            belum tersedia
                                        </p>
                                    @endif
                                </div>
                            @endforeach

                            {{-- Input boolean kesanggupan_berswadaya --}}
                            <div class="mb-3">
                                <label class="form-label">Kesanggupan Berswadaya</label>
                                <select name="kesanggupan_berswadaya" class="form-select" required>
                                    <option value="0"
                                        {{ old('kesanggupan_berswadaya', $verifCPB->kesanggupan_berswadaya) == 0 ? 'selected' : '' }}>
                                        Tidak</option>
                                    <option value="1"
                                        {{ old('kesanggupan_berswadaya', $verifCPB->kesanggupan_berswadaya) == 1 ? 'selected' : '' }}>
                                        Ya</option>
                                </select>
                            </div>

                            {{-- Input enum tipe --}}
                            <div class="mb-3">
                                <label class="form-label">Tipe</label>
                                <select name="tipe" class="form-select" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="T" {{ old('tipe', $verifCPB->tipe) == 'T' ? 'selected' : '' }}>T
                                    </option>
                                    <option value="K" {{ old('tipe', $verifCPB->tipe) == 'K' ? 'selected' : '' }}>K
                                    </option>
                                </select>
                            </div>

                            <button type="submit" id="editCPBButton" class="btn btn-primary">Edit</button>
                            <a href="{{ route('admin.data_verif_cpb') }}" class="btn btn-danger">Batal</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<script>
    function disableButton() {
        let button = document.getElementById('editCPBButton');
        button.disabled = true;
        button.innerHTML = "Mengedit...";
    }
</script>
