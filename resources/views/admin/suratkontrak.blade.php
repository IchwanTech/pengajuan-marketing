@extends('layouts.parent-layout')

@section('page-title', 'Surat Kontrak')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between border-bottom">
                        <h2 class="card-title">Surat Kontrak</h2>
                        <button type="button" class="btn btn-success btn-sm ms-2 mb-3" data-bs-toggle="modal"
                            data-bs-target="#kontrakModal">
                            Input Surat Kontrak
                        </button>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">No Kontrak</th>
                                    <th class="text-center">Pembuatan Kontrak</th>
                                    <th class="text-center">Jenis Kontrak</th>
                                    <th class="text-center">Nama Nasabah</th>
                                    <th class="text-center">Pokok Pinjaman</th>
                                    <th class="text-center">Angsuran</th>
                                    <th class="text-center">MKT - CA</th>
                                    <th class="text-center">Kontrak</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kontrak as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            {{ $item->nomor_kontrak }}
                                        </td>
                                        <td class="text-center">
                                            {{ $item->created_at->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="text-center">
                                            <div class="mb-1">
                                                {{ $item->type }}
                                            </div>
                                            <small>
                                                @if ($item->type === 'Kedinasan & Taspen')
                                                    {{ $item->kedinasan }}
                                                @elseif ($item->type === 'Internal' || $item->type === 'Internal BPJS')
                                                    {{ $item->perusahaan }} - {{ $item->golongan }}
                                                @elseif ($item->type === 'Borongan' || $item->type === 'Borongan BPJS')
                                                    {{ $item->perusahaan }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                {{ $item->nama }}
                                            </div>
                                            <small>
                                                @if ($item->id_card == null)
                                                    NIK : {{ $item->no_ktp }}
                                                @else
                                                    ID Card : {{ $item->id_card }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                Rp. {{ number_format($item->pokok_pinjaman, 0, ',', '.') }}
                                            </div>
                                            <small>
                                                @if ($item->pinjaman_ke == 0 || $item->pinjaman_ke == null)
                                                    Pinjaman Baru
                                                @else
                                                    Pinjaman Ke: {{ $item->pinjaman_ke ?? '0' }}
                                                @endif
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <div class="mb-1">
                                                Rp {{ number_format($item->cicilan, 0, ',', '.') }}
                                            </div>
                                            <small>{{ $item->tenor }}
                                                {{ $item->type === 'Borongan' ? 'Periode' : 'Bulan' }}</small>
                                        </td>
                                        <td class="text-center">
                                            {{ $item->inisial_marketing ?? '-' }} - {{ $item->inisial_ca ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{-- <a href="{{ route('admin.surat.kontrak.download', $item->id) }}"
                                                class="btn btn-primary btn-sm" target="_blank"><i
                                                    class="mdi mdi-folder-download"></i>
                                            </a> --}}
                                            <a href="{{ route('admin.surat.kontrak.show', $item->id) }}"
                                                class="btn btn-primary btn-sm" target="_blank">Lihat <i
                                                    class="mdi mdi-eye-outline"></i></a>
                                        </td>
                                        <td class="text-center">
                                            <!-- Tombol Edit -->
                                            <button type="button" class="btn btn-info btn-sm open-edit-modal"
                                                data-id="{{ $item->id }}"
                                                data-nomor-kontrak="{{ $item->nomor_kontrak }}"
                                                data-nama="{{ $item->nama }}" data-no-ktp="{{ $item->no_ktp }}"
                                                data-alamat="{{ $item->alamat }}" data-type="{{ $item->type }}"
                                                data-pokok-pinjaman="{{ $item->pokok_pinjaman }}"
                                                data-tenor="{{ $item->tenor }}" data-cicilan="{{ $item->cicilan }}"
                                                data-biaya-layanan="{{ $item->biaya_layanan }}"
                                                data-biaya-admin="{{ $item->biaya_admin }}"
                                                data-bunga="{{ $item->bunga }}"
                                                data-tanggal-jatuh-tempo="{{ $item->tanggal_jatuh_tempo }}"
                                                data-id-card="{{ $item->id_card }}"
                                                data-kedinasan="{{ $item->kedinasan }}"
                                                data-perusahaan="{{ $item->perusahaan }}"
                                                data-golongan="{{ $item->golongan }}"
                                                data-pinjaman-ke="{{ $item->pinjaman_ke }}"
                                                data-inisial-marketing="{{ $item->inisial_marketing }}"
                                                data-inisial-ca="{{ $item->inisial_ca }}"
                                                data-catatan="{{ $item->catatan }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <!-- Tombol Delete -->
                                            <button type="button" class="btn btn-danger btn-sm open-delete-modal"
                                                data-id="{{ $item->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Input Surat Kontrak -->
    <div class="modal fade" id="kontrakModal" tabindex="-1" aria-labelledby="kontrakModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kontrakModalLabel">Input Surat Kontrak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="kontrakForm" action="{{ route('admin.surat.kontrak.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nomor_kontrak" class="form-label">No Kontrak</label>
                                    <input type="text" class="form-control" id="nomor_kontrak" name="nomor_kontrak"
                                        placeholder="Masukkan No Kontrak" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="nama" class="form-label">Nama Nasabah</label>
                                    <input type="text" class="form-control" name="nama"
                                        placeholder="Masukkan Nama Nasabah" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="no_ktp" class="form-label">No KTP</label>
                                    <input type="text" class="form-control" name="no_ktp" placeholder="Masukkan NIK"
                                        required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="id_card" class="form-label">No ID Card</label>
                                    <input type="text" class="form-control mb-1" name="id_card"
                                        placeholder="Input ID Card untuk nasabah baru">
                                    <small class="text-danger">Kosongkan Apabila Pinjaman Lama</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" name="alamat" cols="30" rows="10"
                                        placeholder="Masukkan Alamat" required></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">Pilih Jenis Kontrak</label>
                                    <select name="type" id="type" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Internal">Internal</option>
                                        <option value="Internal Agunan">Internal Agunan</option>
                                        <option value="Internal BPJS">Internal BPJS</option>
                                        <option value="Borongan">Borongan</option>
                                        <option value="Borongan BPJS">Borongan BPJS</option>
                                        <option value="Kedinasan">Kedinasan</option>
                                        <option value="Kedinasan & Agunan">Kedinasan & Agunan</option>
                                        <option value="Kedinasan & Taspen">Kedinasan & Taspen</option>
                                        <option value="PT Luar">PT Luar</option>
                                        <option value="PT Luar Agunan">PT Luar Agunan</option>
                                        <option value="PT Luar BPJS Agunan">PT Luar BPJS Agunan</option>
                                        <option value="PT Luar BPJS">PT Luar BPJS</option>
                                        <option value="PT SF">PT SF</option>
                                        <option value="PT SF Agunan">PT SF Agunan</option>
                                        <option value="PT SF BPJS Agunan">PT SF BPJS Agunan</option>
                                        <option value="PT SF BPJS">PT SF BPJS</option>
                                        <option value="UMKM">UMKM</option>
                                        <option value="UMKM Agunan">UMKM Agunan</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3" id="golongan-group" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="perusahaan" class="form-label">Perusahaan</label>
                                            <select name="perusahaan" id="perusahaan" class="form-select">
                                                <option value="">-- Pilih Perusahaan --</option>
                                                <option value="ILW">ILW</option>
                                                <option value="FOTS">FOTS</option>
                                                <option value="UBL">UBL</option>
                                                <option value="TSII">TSII</option>
                                                <option value="IBU">IBU</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="golongan" class="form-label">Pilihan Golongan</label>
                                            <input type="text" class="form-control mb-1" name="golongan"
                                                placeholder="Input Golongan">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3" id="kedinasan-group" style="display: none;">
                                    <label for="kedinasan" class="form-label">Pilihan Kedinasan</label>
                                    <select name="kedinasan" id="kedinasan" class="form-select">
                                        <option value="" disabled selected>-- Pilih --</option>
                                        <option value="OS">OS</option>
                                        <option value="PNS">PNS</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3" id="borongan-group" style="display: none;">
                                    <label for="perusahaan" class="form-label">Perusahaan</label>
                                    <input type="text" name="perusahaan_borongan" class="form-control"
                                        placeholder="Input Perusahaan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group mb-3">
                                    <div class="col-md-6">
                                        <label for="inisial_marketing" class="form-label">Inisial Marketing</label>
                                        <select name="inisial_marketing" id="inisial_marketing" class="form-select"
                                            required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($marketing as $item)
                                                <option value="{{ $item->kode_marketing }}">{{ Str::title($item->name) }}
                                                </option>
                                            @endforeach
                                            {{-- <option value="DW">Dewi</option>
                                            <option value="EF">Elisa</option>
                                            <option value="NA">Nanda</option>
                                            <option value="NV">Novi</option>
                                            <option value="FN">Alfina</option>
                                            <option value="AW">Anisa</option>
                                            <option value="ST">Stevany</option>
                                            <option value="DR">Deril</option> --}}
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inisial_ca" class="form-label">Inisial Credit Analyst</label>
                                        <select name="inisial_ca" id="inisial_ca" class="form-select">
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($ca as $item)
                                                <option value="{{ $item->kode_marketing }}">{{ Str::title($item->name) }}
                                                </option>
                                            @endforeach
                                            {{-- <option value="AD">Afdul</option>
                                            <option value="DL">Daniel</option> --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="pokok_pinjaman" class="form-label">Pokok Pinjaman</label>
                                    <input type="number" class="form-control" name="pokok_pinjaman"
                                        placeholder="Masukkan Pokok Pinjaman" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="pinjaman_ke" class="form-label">Pinjaman Ke</label>
                                    <input type="number" class="form-control" name="pinjaman_ke"
                                        placeholder="Masukkan Pokok Pinjaman" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="tenor" class="form-label">Tenor Pinjaman</label>
                                    <input type="number" class="form-control" name="tenor"
                                        placeholder="Masukkan Tenor Pinjaman" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="cicilan" class="form-label">Cicilan</label>
                                    <input type="number" class="form-control" name="cicilan"
                                        placeholder="Masukkan Angsuran" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="bunga" class="form-label">Bunga Pinjaman</label>
                                    <input type="number" class="form-control" name="bunga"
                                        placeholder="Masukkan Bunga Pinjaman" required>
                                </div>
                                <div class="row form-group mb-3">
                                    <div class="col-md-6">
                                        <label for="biaya_layanan" class="form-label">Biaya Layanan</label>
                                        <input type="number" class="form-control" name="biaya_layanan"
                                            placeholder="Masukkan Biaya Layanan" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="biaya_admin" class="form-label">Biaya Admin</label>
                                        <input type="number" class="form-control" name="biaya_admin"
                                            placeholder="Masukkan Biaya Admin" required>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                                    <input type="date" class="form-control" name="tanggal_jatuh_tempo"
                                        placeholder="Masukkan Tanggal Jatuh Tempo" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" id="catatan" cols="30" rows="10"
                                placeholder="Masukkan Catatan"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="kontrakForm" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Surat Kontrak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="editId">

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Kiri -->
                                <div class="form-group mb-3">
                                    <label for="editNomorKontrak" class="form-label">No Kontrak</label>
                                    <input type="text" class="form-control" name="nomor_kontrak"
                                        id="editNomorKontrak" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editNama" class="form-label">Nama Nasabah</label>
                                    <input type="text" class="form-control" name="nama" id="editNama" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editNoKtp" class="form-label">No KTP</label>
                                    <input type="text" class="form-control" name="no_ktp" id="editNoKtp" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editIdCard" class="form-label">No ID Card</label>
                                    <input type="text" class="form-control" name="id_card" id="editIdCard"
                                        placeholder="Pinjaman Lama">
                                    <small class="text-danger">Kosongkan apabila pinjaman lama</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editAlamat" class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" id="editAlamat" rows="3" required></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editType" class="form-label">Jenis Kontrak</label>
                                    <select name="type" id="editType" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Internal">Internal</option>
                                        <option value="Internal BPJS">Internal BPJS</option>
                                        <option value="Borongan">Borongan</option>
                                        <option value="Borongan BPJS">Borongan BPJS</option>
                                        <option value="Kedinasan">Kedinasan</option>
                                        <option value="Kedinasan & Agunan">Kedinasan & Agunan</option>
                                        <option value="Kedinasan & Taspen">Kedinasan & Taspen</option>
                                        <option value="PT Luar">PT Luar</option>
                                        <option value="PT Luar Agunan">PT Luar Agunan</option>
                                        <option value="PT Luar BPJS Agunan">PT Luar BPJS Agunan</option>
                                        <option value="PT Luar BPJS">PT Luar BPJS</option>
                                        <option value="PT SF">PT SF</option>
                                        <option value="PT SF Agunan">PT SF Agunan</option>
                                        <option value="PT SF BPJS Agunan">PT SF BPJS Agunan</option>
                                        <option value="PT SF BPJS">PT SF BPJS</option>
                                        <option value="UMKM">UMKM</option>
                                        <option value="UMKM Agunan">UMKM Agunan</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3" id="editGolonganGroup" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="editPerusahaan" class="form-label">Perusahaan</label>
                                            <select name="perusahaan" id="editPerusahaan" class="form-select">
                                                <option value="">-- Pilih Perusahaan --</option>
                                                <option value="ILW">ILW</option>
                                                <option value="FOTS">FOTS</option>
                                                <option value="UBL">UBL</option>
                                                <option value="TSII">TSII</option>
                                                <option value="IBU">IBU</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="editGolongan" class="form-label">Golongan</label>
                                            <input type="text" class="form-control" name="golongan"
                                                id="editGolongan">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3" id="editKedinasanGroup" style="display: none;">
                                    <label for="editKedinasan" class="form-label">Kedinasan</label>
                                    <select name="kedinasan" id="editKedinasan" class="form-select">
                                        <option value="">-- Pilih --</option>
                                        <option value="OS">OS</option>
                                        <option value="PNS">PNS</option>
                                    </select>
                                </div>
                                <div class="form-group mb-3" id="editBoronganGroup" style="display: none;">
                                    <label for="editPerusahaan" class="form-label">Perusahaan</label>
                                    <input type="text" name="perusahaan_borongan" class="form-control"
                                        id="editBorongan" placeholder="Input Perusahaan">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Kanan -->
                                <div class="row form-group mb-3">
                                    <div class="col-md-6">
                                        <label for="editMarketing" class="form-label">Inisial Marketing</label>
                                        <select name="inisial_marketing" id="editMarketing" class="form-select" required>
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($marketing as $item)
                                                <option value="{{ $item->kode_marketing }}">{{ Str::title($item->name) }}
                                                </option>
                                            @endforeach
                                            {{-- <option value="DW">Dewi</option>
                                            <option value="EF">Elisa</option>
                                            <option value="NA">Nanda</option>
                                            <option value="NV">Novi</option>
                                            <option value="FN">Alfina</option>
                                            <option value="AW">Anisa</option>
                                            <option value="ST">Stevany</option>
                                            <option value="DR">Deril</option> --}}
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editCA" class="form-label">Inisial Credit Analyst</label>
                                        <select name="inisial_ca" id="editCA" class="form-select">
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($ca as $item)
                                                <option value="{{ $item->kode_marketing }}">{{ Str::title($item->name) }}
                                                </option>
                                            @endforeach
                                            {{-- <option value="AD">Afdul</option>
                                            <option value="DL">Daniel</option> --}}
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editPokokPinjaman" class="form-label">Pokok Pinjaman</label>
                                    <input type="number" class="form-control" name="pokok_pinjaman"
                                        id="editPokokPinjaman" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editPinjamanKe" class="form-label">Pinjaman Ke</label>
                                    <input type="number" class="form-control" name="pinjaman_ke" id="editPinjamanKe"
                                        required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editTenor" class="form-label">Tenor Pinjaman</label>
                                    <input type="number" class="form-control" name="tenor" id="editTenor" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editCicilan" class="form-label">Cicilan</label>
                                    <input type="number" class="form-control" name="cicilan" id="editCicilan" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editBunga" class="form-label">Bunga Pinjaman</label>
                                    <input type="number" class="form-control" name="bunga" id="editBunga" required>
                                </div>
                                <div class="row form-group mb-3">
                                    <div class="col-md-6">
                                        <label for="editBiayaLayanan" class="form-label">Biaya Layanan</label>
                                        <input type="number" class="form-control" name="biaya_layanan"
                                            id="editBiayaLayanan" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="editBiayaAdmin" class="form-label">Biaya Admin</label>
                                        <input type="number" class="form-control" name="biaya_admin"
                                            id="editBiayaAdmin" required>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="editTanggalJatuhTempo" class="form-label">Tanggal Jatuh Tempo</label>
                                    <input type="date" class="form-control" name="tanggal_jatuh_tempo"
                                        id="editTanggalJatuhTempo" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="editCatatan" class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" cols="30" rows="10" id="editCatatan"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="editForm" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Surat Kontrak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus surat kontrak ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan elemen toast -->
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div id="sessionToast" class="toast text-bg-success border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Edit Modal with AJAX
            const editModal = document.getElementById('editModal');
            const editForm = document.getElementById('editForm');

            const editGolonganGroup = document.getElementById('editGolonganGroup');
            const editKedinasanGroup = document.getElementById('editKedinasanGroup');
            const editBoronganGroup = document.getElementById('editBoronganGroup');

            function toggleOptionalFields(type) {
                if (type === 'Kedinasan & Taspen' || type === 'Kedinasan' || type === 'Kedinasan & Agunan') {
                    editKedinasanGroup.style.display = 'block';
                } else {
                    editKedinasanGroup.style.display = 'none';
                    document.getElementById('editKedinasan').value = '';
                }

                if (type === 'Internal' || type === 'Internal BPJS' || type === 'Internal Agunan') {
                    editGolonganGroup.style.display = 'block';
                } else {
                    editGolonganGroup.style.display = 'none';
                    document.getElementById('editGolongan').value = '';
                }

                if (type === 'Borongan' || type === 'Borongan BPJS') {
                    editBoronganGroup.style.display = 'block';
                } else {
                    editBoronganGroup.style.display = 'none';
                    document.getElementById('editBorongan').value = '';
                }
            }

            // Event: saat type diganti di modal
            document.getElementById('editType').addEventListener('change', function() {
                toggleOptionalFields(this.value);
            });

            editForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(editForm);
                const id = document.getElementById('editId').value;
                const url = `/admin/surat-kontrak/${id}`;

                fetch(url, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(Object.fromEntries(formData)),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            bootstrap.Modal.getInstance(editModal).hide();

                            const row = document.querySelector(`button[data-id="${id}"]`).closest('tr');

                            // Update kolom: No Kontrak (kolom ke-2)
                            row.cells[1].textContent = formData.get('nomor_kontrak');

                            // Kolom 3 = Pembuatan Kontrak (tidak berubah via edit, biarkan)

                            // Kolom 4 = Jenis Kontrak
                            row.cells[3].querySelector('.mb-1').textContent = formData.get('type');
                            const smallType = row.cells[3].querySelector('small');
                            if (['Kedinasan & Taspen', 'Kedinasan', 'Kedinasan & Agunan'].includes(
                                    formData.get('type'))) {
                                smallType.textContent = formData.get('kedinasan');
                            } else if (['Internal', 'Internal BPJS', 'Internal Agunans'].includes(formData.get('type'))) {
                                const perusahaan = formData.get('perusahaan');
                                const golongan = formData.get('golongan');
                                smallType.textContent = `${perusahaan} - ${golongan}`;
                            } else if (['Borongan', 'Borongan BPJS'].includes(formData.get('type'))) {
                                smallType.textContent = formData.get('perusahaan');
                            } else {
                                smallType.textContent = '';
                            }

                            // Kolom 5 = Nama Nasabah
                            row.cells[4].querySelector('.mb-1').textContent = formData.get('nama');
                            const idCard = formData.get('id_card');
                            const ktpOrId = idCard ? `ID Card : ${idCard}` :
                                `NIK : ${formData.get('no_ktp')}`;
                            row.cells[4].querySelector('small').textContent = ktpOrId;

                            // Kolom 6 = Pokok Pinjaman
                            row.cells[5].querySelector('.mb-1').textContent =
                                `Rp. ${formatNumber(formData.get('pokok_pinjaman'))}`;
                            const pinjamanKe = formData.get('pinjaman_ke');
                            row.cells[5].querySelector('small').textContent = (pinjamanKe === '0' || !
                                    pinjamanKe) ?
                                'Pinjaman Baru' :
                                `Pinjaman Ke: ${pinjamanKe}`;

                            // Kolom 7 = Angsuran
                            row.cells[6].querySelector('.mb-1').textContent =
                                `Rp ${formatNumber(formData.get('cicilan'))}`;
                            row.cells[6].querySelector('small').textContent =
                                `${formData.get('tenor')} ${formData.get('type') === 'Borongan' ? 'Periode' : 'Bulan'}`;

                            // Kolom 8 = Inisial
                            row.cells[7].innerHTML =
                                `${formData.get('inisial_marketing')} - ${formData.get('inisial_ca')}`;

                            showToast('success', 'Data berhasil diperbarui!');
                        } else {
                            showToast('error', 'Gagal memperbarui data.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'Terjadi kesalahan saat memperbarui data.');
                    });
            });

            // Populate Edit Modal
            document.querySelectorAll('.open-edit-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    editForm.action = `/admin/surat-kontrak/${id}`;
                    document.getElementById('editId').value = id;
                    document.getElementById('editNomorKontrak').value = this.dataset.nomorKontrak;
                    document.getElementById('editNama').value = this.dataset.nama;
                    document.getElementById('editNoKtp').value = this.dataset.noKtp;
                    document.getElementById('editIdCard').value = this.dataset.idCard ?? '';
                    document.getElementById('editAlamat').value = this.dataset.alamat;
                    document.getElementById('editType').value = this.dataset.type;
                    document.getElementById('editGolongan').value = this.dataset.golongan ?? '';
                    document.getElementById('editBorongan').value = this.dataset.perusahaan ?? '';
                    document.getElementById('editPerusahaan').value = this.dataset.perusahaan ?? '';
                    document.getElementById('editKedinasan').value = this.dataset.kedinasan ?? '';
                    document.getElementById('editMarketing').value = this.dataset.inisialMarketing;
                    document.getElementById('editCA').value = this.dataset.inisialCa;
                    document.getElementById('editPokokPinjaman').value = this.dataset.pokokPinjaman;
                    document.getElementById('editPinjamanKe').value = this.dataset.pinjamanKe;
                    document.getElementById('editTenor').value = this.dataset.tenor;
                    document.getElementById('editCicilan').value = this.dataset.cicilan;
                    document.getElementById('editBunga').value = this.dataset.bunga;
                    document.getElementById('editBiayaLayanan').value = this.dataset.biayaLayanan;
                    document.getElementById('editBiayaAdmin').value = this.dataset.biayaAdmin;
                    document.getElementById('editTanggalJatuhTempo').value = this.dataset
                        .tanggalJatuhTempo;
                    document.getElementById('editCatatan').value = this.dataset.catatan ?? '';

                    // Tampilkan/hilangkan field sesuai type
                    toggleOptionalFields(this.dataset.type);

                    new bootstrap.Modal(editModal).show();
                });
            });

            // Helper function to format numbers
            function formatNumber(value) {
                return new Intl.NumberFormat('id-ID').format(value);
            }

            // Open Delete Modal
            document.querySelectorAll('.open-delete-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    deleteForm.action = `/admin/surat-kontrak/${id}`;
                    new bootstrap.Modal(deleteModal).show();
                });
            });

            // Handle Delete Modal with AJAX
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');

            deleteForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const id = deleteForm.action.split('/').pop();
                const url = `/admin/surat-kontrak/${id}`;

                fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            bootstrap.Modal.getInstance(deleteModal).hide();
                            const row = document.querySelector(`button[data-id="${id}"]`).closest('tr');
                            row.remove();
                            showToast('success', 'Data berhasil dihapus!');
                        } else {
                            showToast('error', 'Gagal menghapus data.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('error', 'Terjadi kesalahan saat menghapus data.');
                    });
            });

            // Function to show toast
            function showToast(type, message) {
                const toast = document.getElementById('sessionToast');
                const toastMessage = document.getElementById('toastMessage');

                // Set message and type
                toastMessage.textContent = message;
                toast.classList.remove('text-bg-success', 'text-bg-danger');
                toast.classList.add(type === 'success' ? 'text-bg-success' : 'text-bg-danger');

                // Initialize and show toast
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }

            const typeSelect = document.getElementById('type');
            const perusahaanSelect = document.getElementById('perusahaan');
            const kedinasanGroup = document.getElementById('kedinasan-group');
            const golonganGroup = document.getElementById('golongan-group');
            const boronganGroup = document.getElementById('borongan-group');

            const toggleKedinasan = (value) => {
                const kedinasanInput = document.getElementById('kedinasan');
                if (value === 'Kedinasan & Taspen' || value === 'Kedinasan' || value === 'Kedinasan & Agunan') {
                    kedinasanGroup.style.display = 'block';
                } else {
                    kedinasanGroup.style.display = 'none';
                    if (kedinasanInput) kedinasanInput.value = '';
                }
            };

            const toggleInternal = (value) => {
                const golonganInput = document.getElementById('golongan');
                if (value === 'Internal' || value === 'Internal BPJS' || value === 'Internal Agunan') {
                    golonganGroup.style.display = 'block';
                } else {
                    golonganGroup.style.display = 'none';
                    if (golonganInput) golonganInput.value = '';
                }
            };

            const toggleBorongan = (value) => {
                const boronganInput = document.getElementById('borongan');
                if (value === 'Borongan' || value === 'Borongan BPJS') {
                    boronganGroup.style.display = 'block';
                } else {
                    boronganGroup.style.display = 'none';
                    if (boronganInput) boronganInput.value = '';
                }
            }

            const updateNomorKontrak = () => {
                const type = typeSelect.value;
                const perusahaan = perusahaanSelect ? perusahaanSelect.value : null;

                if (type !== '') {
                    let url = `/admin/surat-kontrak/generate-nomor/${encodeURIComponent(type)}`;
                    if (perusahaan) {
                        url += `?perusahaan=${encodeURIComponent(perusahaan)}`;
                    }

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            document.querySelector('[name="nomor_kontrak"]').value = data.nomor_kontrak;
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            showToast('error', 'Gagal generate nomor kontrak');
                        });
                }
            };

            typeSelect.addEventListener('change', (e) => {
                toggleInternal(e.target.value);
                toggleKedinasan(e.target.value);
                toggleBorongan(e.target.value);
                updateNomorKontrak();
            });

            perusahaanSelect.addEventListener('change', updateNomorKontrak);

            // Inisialisasi awal kalau sudah ada value
            toggleKedinasan(typeSelect.value);
            toggleInternal(typeSelect.value);
            toggleBorongan(typeSelect.value);
        });
    </script>

@endsection
