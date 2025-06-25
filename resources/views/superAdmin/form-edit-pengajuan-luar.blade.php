@extends('layouts.parent-layout')

@section('page-title')
    <a href="{{ url()->previous() }}" style="text-decoration: none" class="text-dark">Data Pengajuan Luar</a>
@endsection
@section('breadcrumb-title', '/ Form Edit')

@section('content')
    <style>
        .form-step {
            display: none;
        }

        .form-step-active {
            display: block;
        }
    </style>
    <main id="main" class="main">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <a href="{{ url()->previous() }}" class="btn btn-success btn-sm">Back</a>
                        <h4 class="card-title text-center mt-2 mb-2">Form Edit Pengajuan Pinjaman</h4>
                        <div style="width: 80px;"></div>
                    </div>
                    <div class="card-body">
                        <form id="loanForm"
                            action="{{ route('superAdmin.data.pengajuan.luar.update', $pengajuan->nasabahLuar->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Step 1: Informasi Nasabah -->
                            <div class="form-step form-step-active">
                                <h5 class="mb-3">Informasi Nasabah</h5>
                                <div class="form-group">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->nama_lengkap }}">
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="text" id="nik" name="nik" class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->nik }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="no_kk" class="form-label">No KK</label>
                                        <input type="text" id="no_kk" name="no_kk" class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->no_kk }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki"
                                            {{ $pengajuan->nasabahLuar->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>
                                        <option value="Perempuan"
                                            {{ $pengajuan->nasabahLuar->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan
                                        </option>
                                    </select>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->tempat_lahir }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->tanggal_lahir }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status_nikah" class="form-label">Status Pernikahan</label>
                                    <select name="status_nikah" id="status_nikah" class="form-select">
                                        <option value="">Pilih Status Pernikahan</option>
                                        <option
                                            value="Belum Menikah"{{ $pengajuan->nasabahLuar->status_nikah == 'Belum Menikah' ? 'selected' : '' }}>
                                            Belum Menikah</option>
                                        <option value="Menikah"
                                            {{ $pengajuan->nasabahLuar->status_nikah == 'Menikah' ? 'selected' : '' }}>
                                            Menikah</option>
                                        <option value="Janda/Duda"
                                            {{ $pengajuan->nasabahLuar->status_nikah == 'Janda/Duda' ? 'selected' : '' }}>
                                            Janda/Duda
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="no_hp" class="form-label">Nomor HP/WA</label>
                                        <input type="text" id="no_hp" name="no_hp" class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->no_hp }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->email }}">
                                    </div>
                                </div>
                                @php
                                    $namaNasabah = str_replace(
                                        ' ',
                                        '-',
                                        strtolower($pengajuan->nasabahLuar->nama_lengkap),
                                    );
                                    $folderNasabah = $namaNasabah . '-' . $pengajuan->nasabahLuar->id;
                                @endphp
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="foto_ktp" class="form-label">Foto KTP</label>
                                        <a href="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->foto_ktp) }}"
                                            target="_blank">
                                            <img src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->foto_ktp) }}?t={{ time() }}"
                                                alt="Foto KTP" class="img-thumbnail"
                                                style="max-width: 100%; height: auto;">
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="foto_kk" class="form-label">Foto Kartu Keluarga</label>
                                        <br>
                                        <a href="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->foto_kk) }}"
                                            target="_blank">
                                            <img src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->foto_kk) }}?t={{ time() }}"
                                                alt="Foto KTP" class="img-thumbnail"
                                                style="max-width: 100%; height: auto;">
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="dokumen_pendukung" class="form-label">Dokumen Pendukung</label>
                                        <br>
                                        @if ($pengajuan && $pengajuan->nasabahLuar)
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modalDokumenPendukung">
                                                Lihat PDF
                                            </button>

                                            <div class="modal fade" id="modalDokumenPendukung" tabindex="-1"
                                                aria-labelledby="modalDokumenPendukungLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalDokumenPendukungLabel">
                                                                Dokumen Pendukung</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <embed
                                                                src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->dokumen_pendukung ?? '') }}?t={{ time() }}"
                                                                type="application/pdf" width="100%" height="500px">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <span class="form-text">File :
                                                {{ $pengajuan->nasabahLuar->dokumen_pendukung ?? '' }}</span>
                                        @else
                                            <p class="text-danger">File PDF Dokumen Pendukung tidak tersedia.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foto_ktp" class="form-label">Foto KTP</label>
                                    <input type="file" id="foto_ktp" name="foto_ktp" class="file-upload-default"
                                        accept="image/*">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Foto KTP"
                                            value="{{ $pengajuan->nasabahLuar->foto_ktp }}">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-info py-3"
                                                type="button">Pilih File</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foto_kk" class="form-label">Foto Kartu Keluarga</label>
                                    <input type="file" id="foto_kk" name="foto_kk" class="file-upload-default"
                                        accept="image/*">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Foto Kartu Keluarga"
                                            value="{{ $pengajuan->nasabahLuar->foto_kk }}">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-info py-3"
                                                type="button">Pilih File</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dokumen_pendukung" class="form-label">Dokumen Pendukung</label>
                                    <input type="file" id="dokumen_pendukung" name="dokumen_pendukung"
                                        class="file-upload-default" accept="application/pdf">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Dokumen Pendukung"
                                            value="{{ $pengajuan->nasabahLuar->dokumen_pendukung }}">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-info py-3"
                                                type="button">Pilih File</button>
                                        </span>
                                    </div>
                                </div>
                                @if ($pengajuan->nasabahLuar->validasi_nasabah == 0)
                                    <div class="form-group mt-0" id="catatan-nasabah">
                                        <label for="catatan_nasabah" class="form-label">Catatan</label>
                                        <textarea name="catatan_nasabah" id="catatan_nasabah" class="form-control" cols="30" rows="10" disabled>{{ $pengajuan->nasabahLuar->catatan }}</textarea>
                                    </div>
                                @endif
                                <div class="text-end">
                                    <button type="button" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <!-- Step 2: Alamat Nasabah -->
                            <div class="form-step">
                                <h5>Alamat Nasabah</h5>
                                <div class="form-group">
                                    <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                                    <input type="text" id="alamat_ktp" name="alamat_ktp" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->alamatLuar->alamat_ktp }}">
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="kelurahan" class="form-label">Kelurahan</label>
                                        <input type="text" id="kelurahan" name="kelurahan" class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->alamatLuar->kelurahan }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rt_rw" class="form-label">RT/RW</label>
                                        <input type="text" id="rt_rw" name="rt_rw" class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->alamatLuar->rt_rw }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <input type="text" id="kecamatan" name="kecamatan" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->alamatLuar->kecamatan }}">
                                </div>
                                <div class="form-group">
                                    <label for="kota" class="form-label">Kabupaten/Kota</label>
                                    <input type="text" id="kota" name="kota" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->alamatLuar->kota }}">
                                </div>
                                <div class="form-group">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->alamatLuar->provinsi }}">
                                </div>
                                <div class="form-group">
                                    <label for="status_rumah" class="form-label">Status Rumah</label>
                                    <select id="status_rumah" name="status_rumah" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="kontrak bulanan"
                                            {{ $pengajuan->nasabahLuar->alamatLuar->status_rumah == 'kontrak bulanan' ? 'selected' : '' }}>
                                            Kontrak Bulanan</option>
                                        <option value="kontrak tahunan"
                                            {{ $pengajuan->nasabahLuar->alamatLuar->status_rumah == 'kontrak tahunan' ? 'selected' : '' }}>
                                            Kontrak Tahunan</option>
                                        <option value="milik orang tua"
                                            {{ $pengajuan->nasabahLuar->alamatLuar->status_rumah == 'milik orang tua' ? 'selected' : '' }}>
                                            Milik Orang Tua</option>
                                        <option value="pribadi"
                                            {{ $pengajuan->nasabahLuar->alamatLuar->status_rumah == 'pribadi' ? 'selected' : '' }}>
                                            Milik
                                            Pribadi</option>
                                    </select>
                                </div>
                                <div class="form-group" id="kontrak-bulanan-group" style="display: none;">
                                    <label for="biaya_perbulan" class="form-label">Biaya Perbulan</label>
                                    <input type="number" id="biaya_perbulan" name="biaya_perbulan" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->alamatLuar->biaya_perbulan }}">
                                </div>
                                <div class="form-group" id="kontrak-tahunan-group" style="display: none;">
                                    <label for="biaya_pertahun" class="form-label">Biaya Pertahun</label>
                                    <input type="number" id="biaya_pertahun" name="biaya_pertahun" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->alamatLuar->biaya_pertahun }}">
                                </div>
                                <div class="form-group">
                                    <label for="domisili" class="form-label">Alamat Domisili</label>
                                    <select id="domisili" name="domisili" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="sesuai ktp"
                                            {{ $pengajuan->nasabahLuar->alamatLuar->domisili == 'sesuai ktp' ? 'selected' : '' }}>
                                            Sesuai
                                            KTP
                                        </option>
                                        <option value="tidak sesuai ktp"
                                            {{ $pengajuan->nasabahLuar->alamatLuar->domisili == 'tidak sesuai ktp' ? 'selected' : '' }}>
                                            Tidak
                                            Sesuai KTP</option>
                                    </select>
                                </div>
                                <div class="form-group" id="alamat-lengkap-group" style="display: none;">
                                    <div class="form-group">
                                        <label for="alamat_domisili" class="form-label">Alamat Lengkap</label>
                                        <textarea name="alamat_domisili" id="alamat_domisili" cols="30" rows="10" class="form-control">{{ $pengajuan->nasabahLuar->alamatLuar->alamat_domisili }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="rumah_domisili" class="form-label">Status Rumah Domisili</label>
                                        <select id="rumah_domisili" name="rumah_domisili" class="form-select">
                                            <option value="">Pilih</option>
                                            <option value="kontrak bulanan"
                                                {{ $pengajuan->nasabahLuar->alamatLuar->rumah_domisili == 'kontrak bulanan' ? 'selected' : '' }}>
                                                Kontrak Bulanan</option>
                                            <option value="kontrak tahunan"
                                                {{ $pengajuan->nasabahLuar->alamatLuar->rumah_domisili == 'kontrak tahunan' ? 'selected' : '' }}>
                                                Kontrak Tahunan</option>
                                            <option value="milik orang tua"
                                                {{ $pengajuan->nasabahLuar->alamatLuar->rumah_domisili == 'milik orang tua' ? 'selected' : '' }}>
                                                Milik Orang Tua</option>
                                            <option value="pribadi"
                                                {{ $pengajuan->nasabahLuar->alamatLuar->rumah_domisili == 'pribadi' ? 'selected' : '' }}>
                                                Milik
                                                Pribadi</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="domisili-bulanan-group" style="display: none;">
                                        <label for="biaya_perbulan_domisili" class="form-label">Biaya Perbulan</label>
                                        <input type="number" id="biaya_perbulan_domisili" name="biaya_perbulan_domisili"
                                            class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->alamatLuar->biaya_perbulan_domisili }}">
                                    </div>
                                    <div class="form-group" id="domisili-tahunan-group" style="display: none;">
                                        <label for="biaya_pertahun_domisili" class="form-label">Biaya Pertahun</label>
                                        <input type="number" id="biaya_pertahun_domisili" name="biaya_pertahun_domisili"
                                            class="form-control"
                                            value="{{ $pengajuan->nasabahLuar->alamatLuar->biaya_pertahun_domisili }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lama_tinggal" class="form-label">Lama Tinggal</label>
                                    <input type="text" id="lama_tinggal" name="lama_tinggal" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->alamatLuar->lama_tinggal }}">
                                </div>
                                <div class="form-group">
                                    <label for="share_loc_link" class="form-label">Share Loc Alamat Nasabah</label>
                                    <input type="text" id="share_loc_link" name="share_loc_link" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->alamatLuar->share_loc_link }}">
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="atas_nama_listrik" class="form-label">Rekening Listrik Atas
                                            Nama</label>
                                        <input type="text" class="form-control" name="atas_nama_listrik"
                                            id="atas_nama_listrik"
                                            value="{{ $pengajuan->nasabahLuar->alamatLuar->atas_nama_listrik }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="hubungan_rek_listrik">Hubungan dengan Pemilik Rekening Listrik</label>
                                        <input type="text" class="form-control" name="hubungan_rek_listrik"
                                            id="hubungan_rek_listrik"
                                            value="{{ $pengajuan->nasabahLuar->alamatLuar->hubungan }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foto_meteran_listrik">Foto Meteran Listrik Rumah</label>
                                    <br>
                                    <a href="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->alamatLuar->foto_meteran_listrik) }}"
                                        target="_blank">
                                        <img src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->alamatLuar->foto_meteran_listrik) }}"
                                            alt="Foto Meteran Listrik" class="img-thumbnail"
                                            style="max-width: 100%; height: auto;">
                                    </a>
                                </div>
                                <div class="form-group">
                                    <label for="foto_meteran_listrik" class="form-label">Foto Meteran Listrik
                                        Rumah</label>
                                    <input type="file" id="foto_meteran_listrik" name="foto_meteran_listrik"
                                        class="file-upload-default" accept="image/*">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Foto KTP"
                                            value="{{ $pengajuan->nasabahLuar->alamatLuar->foto_meteran_listrik }}">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-info py-3"
                                                type="button">Pilih File</button>
                                        </span>
                                    </div>
                                </div>
                                @if ($pengajuan->nasabahLuar->alamatLuar->validasi_alamat == 0)
                                    <div class="form-group mt-0" id="catatan-alamat">
                                        <label for="catatan_alamat" class="form-label">Catatan</label>
                                        <textarea name="catatan_alamat" id="catatan_alamat" class="form-control" cols="30" rows="10" disabled>{{ $pengajuan->nasabahLuar->alamatLuar->catatan }}</textarea>
                                    </div>
                                @endif
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <!-- Step 3: Informasi Pekerjaan -->
                            <div class="form-step">
                                <h5>Informasi Pekerjaan</h5>
                                <div class="form-group">
                                    <label for="perusahaan" class="form-label">Perusahaan</label>
                                    <input type="text" class="form-control" name="perusahaan" id="perusahaan"
                                        value="{{ $pengajuan->nasabahLuar->pekerjaanLuar->perusahaan }}">
                                </div>
                                <div class="form-group">
                                    <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
                                    <textarea name="alamat_perusahaan" id="alamat_perusahaan" cols="30" rows="10" class="form-control">{{ $pengajuan->nasabahLuar->pekerjaanLuar->alamat_perusahaan }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="kontak_perusahaan" class="form-label">No Telp Perusahaan</label>
                                    <input type="text" class="form-control" name="kontak_perusahaan"
                                        id="kontak_perusahaan"
                                        value="{{ $pengajuan->nasabahLuar->pekerjaanLuar->kontak_perusahaan }}">
                                </div>
                                <div class="form-group">
                                    <label for="jabatan" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" name="jabatan" id="jabatan"
                                        value="{{ $pengajuan->nasabahLuar->pekerjaanLuar->jabatan }}">
                                </div>
                                <div class="form-group">
                                    <label for="lama_kerja" class="form-label">Lama Bekerja</label>
                                    <input type="text" class="form-control" name="lama_kerja" id="lama_kerja"
                                        value="{{ $pengajuan->nasabahLuar->pekerjaanLuar->lama_kerja }}">
                                </div>
                                <div class="form-group">
                                    <label for="status_karyawan" class="form-label">Status Karyawan</label>
                                    <select id="status_karyawan" name="status_karyawan" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="tetap"
                                            {{ $pengajuan->nasabahLuar->pekerjaanLuar->status_karyawan == 'tetap' ? 'selected' : '' }}>
                                            Tetap
                                        </option>
                                        <option value="kontrak"
                                            {{ $pengajuan->nasabahLuar->pekerjaanLuar->status_karyawan == 'kontrak' ? 'selected' : '' }}>
                                            Kontrak</option>
                                    </select>
                                </div>
                                <div class="form-group" id="div-kontrak" style="display: none;">
                                    <label for="lama_kontrak" class="form-label">Lama Kontrak</label>
                                    <input type="text" name="lama_kontrak" id="lama_kontrak" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->pekerjaanLuar->lama_kontrak }}">
                                </div>
                                <div class="form-group">
                                    <label for="pendapatan_perbulan" class="form-label">Pendapatan Perbulan</label>
                                    <input type="number" name="pendapatan_perbulan" id="pendapatan_perbulan"
                                        class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->pekerjaanLuar->pendapatan_perbulan }}">
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="foto_id_card" class="form-label">Foto ID Card/SKU</label>
                                        <br>
                                        <a href="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->pekerjaanLuar->id_card) }}"
                                            target="_blank">
                                            <img src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->pekerjaanLuar->id_card) }}"alt="Foto KTP"
                                                class="img-thumbnail" style="max-width: 100%; height: auto;">
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="foto_slip_gaji" class="form-label">Foto Slip Gaji/Mutasi</label>
                                        <br>
                                        @if ($pengajuan && $pengajuan->nasabahLuar && $pengajuan->nasabahLuar->pekerjaanLuar)
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modalSlipGaji">
                                                Lihat PDF
                                            </button>

                                            <div class="modal fade" id="modalSlipGaji" tabindex="-1"
                                                aria-labelledby="modalSlipGajiLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalSlipGajiLabel">Surat
                                                                Pernyataan Penjamin</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <embed
                                                                src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->pekerjaanLuar->slip_gaji) }}"
                                                                type="application/pdf" width="100%" height="500px">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <span class="form-text">File :
                                                {{ $pengajuan->nasabahLuar->pekerjaanLuar->slip_gaji ?? '' }}</span>
                                        @else
                                            <p class="text-danger">File PDF Slip Gaji/Mutasi tidak tersedia.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foto_id_card" class="form-label">Foto ID Card/SKU</label>
                                    <input type="file" id="foto_id_card" name="foto_id_card"
                                        class="file-upload-default" accept="image/*">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Foto KTP"
                                            value="{{ $pengajuan->nasabahLuar->pekerjaanLuar->id_card }}">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-info py-3"
                                                type="button">Pilih File</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foto_slip_gaji" class="form-label">Foto Slip Gaji/Mutasi</label>
                                    <input type="file" id="foto_slip_gaji" name="foto_slip_gaji"
                                        class="file-upload-default" accept="application/pdf">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Foto KTP"
                                            value="{{ $pengajuan->nasabahLuar->pekerjaanLuar->slip_gaji }}">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-info py-3"
                                                type="button">Pilih File</button>
                                        </span>
                                    </div>
                                </div>
                                @if ($pengajuan->nasabahLuar->pekerjaanLuar->validasi_pekerjaan == 0)
                                    <div class="form-group mt-0" id="catatan-pekerjaan">
                                        <label for="catatan_pekerjaan" class="form-label">Catatan</label>
                                        <textarea name="catatan_pekerjaan" id="catatan_pekerjaan" class="form-control" cols="30" rows="10"
                                            disabled>{{ $pengajuan->nasabahLuar->pekerjaanLuar->catatan }}</textarea>
                                    </div>
                                @endif
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <!-- Step 4: Penjamin Nasabah -->
                            <div class="form-step">
                                <h5>Penjamin Nasabah</h5>
                                <div class="form-group">
                                    <label for="hubungan_penjamin" class="form-label">Hubungan Keluarga</label>
                                    <select id="hubungan_penjamin" name="hubungan_penjamin" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="orang tua"
                                            {{ $pengajuan->nasabahLuar->penjaminLuar->hubungan_penjamin == 'Orang Tua' ? 'selected' : '' }}>
                                            Orang Tua</option>
                                        <option value="suami"
                                            {{ $pengajuan->nasabahLuar->penjaminLuar->hubungan_penjamin == 'Suami' ? 'selected' : '' }}>
                                            Suami
                                        </option>
                                        <option value="istri"
                                            {{ $pengajuan->nasabahLuar->penjaminLuar->hubungan_penjamin == 'Istri' ? 'selected' : '' }}>
                                            Istri
                                        </option>
                                        <option value="anak"
                                            {{ $pengajuan->nasabahLuar->penjaminLuar->hubungan_penjamin == 'Anak' ? 'selected' : '' }}>
                                            Anak
                                        </option>
                                        <option value="keluarga kandung"
                                            {{ $pengajuan->nasabahLuar->penjaminLuar->hubungan_penjamin == 'Keluarga Kandung' ? 'selected' : '' }}>
                                            Keluarga Kandung</option>
                                        <option value="lainnya"
                                            {{ $pengajuan->nasabahLuar->penjaminLuar->hubungan_penjamin == 'Lainnya' ? 'selected' : '' }}>
                                            Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_penjamin" class="form-label">Nama Penjamin</label>
                                    <input type="text" id="nama_penjamin" name="nama_penjamin" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->penjaminLuar->nama_penjamin }}">
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_penjamin" class="form-label">Pekerjaan Penjamin</label>
                                    <input type="text" id="pekerjaan_penjamin" name="pekerjaan_penjamin"
                                        class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->penjaminLuar->pekerjaan_penjamin }}">
                                </div>
                                <div class="form-group">
                                    <label for="penghasilan_penjamin" class="form-label">Penghasilan</label>
                                    <input type="number" id="penghasilan_penjamin" name="penghasilan_penjamin"
                                        class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->penjaminLuar->penghasilan_penjamin }}">
                                </div>
                                <div class="form-group">
                                    <label for="no_hp_penjamin" class="form-label">No HP/WA</label>
                                    <input type="text" id="no_hp_penjamin" name="no_hp_penjamin" class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->penjaminLuar->no_hp_penjamin }}">
                                </div>
                                <div class="form-group">
                                    <label for="foto_ktp_penjamin" class="form-label">KTP Penjamin</label>
                                    <br>
                                    <a href="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->penjaminLuar->foto_ktp_penjamin) }}"
                                        target="_blank">
                                        <img src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->nasabahLuar->penjaminLuar->foto_ktp_penjamin) }}"
                                            alt="Foto KTP" class="img-thumbnail" style="max-width: 100%; height: auto;">
                                    </a>
                                </div>

                                <div class="form-group">
                                    <label for="foto_ktp_penjamin" class="form-label">Foto KTP Penjamin</label>
                                    <input type="file" id="foto_ktp_penjamin" name="foto_ktp_penjamin"
                                        class="file-upload-default" accept="image/*">
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Foto KTP"
                                            value="{{ $pengajuan->nasabahLuar->penjaminLuar->foto_ktp_penjamin }}">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-info py-3"
                                                type="button">Pilih File</button>
                                        </span>
                                    </div>
                                </div>

                                @if ($pengajuan->nasabahLuar->penjaminLuar->validasi_penjamin == 0)
                                    <div class="form-group mt-0" id="catatan-penjamin">
                                        <label for="catatan_penjamin" class="form-label">Catatan</label>
                                        <textarea name="catatan_penjamin" id="catatan_penjamin" class="form-control" cols="30" rows="10"
                                            disabled>{{ $pengajuan->nasabahLuar->penjaminLuar->catatan }}</textarea>
                                    </div>
                                @endif

                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <!-- Step 5: Data Tanggungan dan Cicilan Lain -->
                            <div class="form-step">
                                <h5>Data Tanggungan dan Cicilan Lain</h5>
                                <div class="form-group">
                                    <label for="kondisi_tanggungan" class="form-label">Kondisi Tanggungan Nasabah</label>
                                    <textarea name="kondisi_tanggungan" id="kondisi_tanggungan" cols="30" rows="10" class="form-control">{{ $pengajuan->nasabahLuar->tanggunganLuar->kondisi_tanggungan }}</textarea>
                                </div>

                                <!-- Dropdown Cicilan Lain -->
                                <div class="form-group">
                                    <label for="cicilan_lain" class="form-label">Cicilan Lain</label>
                                    <select name="cicilan_lain" id="cicilan_lain" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="Ada"
                                            {{ optional($pengajuan->nasabahLuar->pinjamanLainLuar->first())->cicilan_lain == 'Ada' ? 'selected' : '' }}>
                                            Ada
                                        </option>
                                        <option value="Tidak"
                                            {{ optional($pengajuan->nasabahLuar->pinjamanLainLuar->first())->cicilan_lain == 'Tidak' ? 'selected' : '' }}>
                                            Tidak
                                        </option>
                                    </select>
                                </div>

                                <!-- Container Form Cicilan -->
                                <div id="cicilan-container" style="display: none;">
                                    <!-- Form Pertama -->
                                    <div id="dynamic-form-container">
                                        @foreach ($pengajuan->nasabahLuar->pinjamanLainLuar as $index => $cicilan)
                                            <div class="dynamic-form mb-3" id="form-{{ $index }}">
                                                <h6>Form Cicilan Lain {{ $index + 1 }}</h6>
                                                <div class="form-group">
                                                    <label for="nama_pembiayaan_{{ $index }}"
                                                        class="form-label">Nama
                                                        Pembiayaan</label>
                                                    <input type="text" id="nama_pembiayaan_{{ $index }}"
                                                        name="nama_pembiayaan[]" class="form-control"
                                                        value="{{ $cicilan->nama_pembiayaan }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="total_pinjaman_{{ $index }}"
                                                        class="form-label">Total Pinjaman</label>
                                                    <input type="number" id="total_pinjaman_{{ $index }}"
                                                        name="total_pinjaman[]" class="form-control"
                                                        value="{{ $cicilan->total_pinjaman }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="cicilan_perbulan_{{ $index }}"
                                                        class="form-label">Cicilan Perbulan</label>
                                                    <input type="number" id="cicilan_perbulan_{{ $index }}"
                                                        name="cicilan_perbulan[]" class="form-control"
                                                        value="{{ $cicilan->cicilan_perbulan }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="sisa_tenor_cicilan_{{ $index }}"
                                                        class="form-label">Sisa Tenor</label>
                                                    <input type="number" id="sisa_tenor_cicilan_{{ $index }}"
                                                        name="sisa_tenor_cicilan[]" class="form-control"
                                                        value="{{ $cicilan->sisa_tenor }}">
                                                </div>
                                                <hr>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if ($pengajuan->nasabahLuar->tanggunganLuar->validasi_tanggungan == 0)
                                    <div class="form-group mt-0" id="catatan-tanggungan">
                                        <label for="catatan_tanggungan" class="form-label">Catatan</label>
                                        <textarea name="catatan_tanggungan" id="catatan_tanggungan" class="form-control" cols="30" rows="10"
                                            disabled>{{ $pengajuan->nasabahLuar->tanggunganLuar->catatan }}</textarea>
                                    </div>
                                @endif

                                <!-- Navigasi -->
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <!-- Step 6: Informasi Kontak Darurat -->
                            <div class="form-step">
                                <h5>Informasi Kontak Darurat</h5>
                                <div class="form-group">
                                    <label for="nama_kontak_darurat" class="form-label">Nama Saudara Tidak Serumah</label>
                                    <input type="text" name="nama_kontak_darurat" id="nama_kontak_darurat"
                                        class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->kontakDarurat->nama_kontak_darurat }}">
                                </div>
                                <div class="form-group">
                                    <label for="hubungan_kontak_darurat" class="form-label">Hubungan</label>
                                    <input type="text" name="hubungan_kontak_darurat" id="hubungan_kontak_darurat"
                                        class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->kontakDarurat->hubungan_kontak_darurat }}">
                                </div>
                                <div class="form-group">
                                    <label for="no_hp_kontak_darurat" class="form-label">No HP/WA</label>
                                    <input type="text" name="no_hp_kontak_darurat" id="no_hp_kontak_darurat"
                                        class="form-control"
                                        value="{{ $pengajuan->nasabahLuar->kontakDarurat->no_hp_kontak_darurat }}">
                                </div>

                                @if ($pengajuan->nasabahLuar->kontakDarurat->validasi_kontak_darurat == 0)
                                    <div class="form-group mt-0" id="catatan-kontak_darurat">
                                        <label for="catatan_kontak_darurat" class="form-label">Catatan</label>
                                        <textarea name="catatan_kontak_darurat" id="catatan_kontak_darurat" class="form-control" cols="30"
                                            rows="10" disabled>{{ $pengajuan->nasabahLuar->kontakDarurat->catatan }}</textarea>
                                    </div>
                                @endif

                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <!-- Step 7: Pengajuan Pinjaman -->
                            <div class="form-step">
                                <h5>Informasi Pengajuan Pinjaman</h5>
                                <div class="form-group">
                                    <label for="status_pinjaman">Status Pinjaman</label>
                                    <select name="status_pinjaman" id="status_pinjaman" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="baru"
                                            {{ $pengajuan->status_pinjaman == 'baru' ? 'selected' : '' }}>
                                            Baru</option>
                                        <option value="lama"
                                            {{ $pengajuan->status_pinjaman == 'lama' ? 'selected' : '' }}>
                                            Lama</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nominal_pinjaman" class="form-label">Nominal Pinjaman</label>
                                    <input type="number" id="nominal_pinjaman" name="nominal_pinjaman"
                                        class="form-control" value="{{ $pengajuan->nominal_pinjaman }}">
                                </div>
                                <div class="form-group">
                                    <label for="tenor" class="form-label">Jangka Waktu</label>
                                    <input type="number" name="tenor" id="tenor" class="form-control"
                                        value="{{ $pengajuan->tenor }}">
                                </div>
                                <div id="riwayat-pinjaman-group" style="display: none;">
                                    <h5>Riwayat Pinjaman</h5>
                                    <div class="form-group">
                                        <label for="pinjaman_ke">Pinjaman Ke</label>
                                        <input type="number" name="pinjaman_ke" id="pinjaman_ke" class="form-control"
                                            value="{{ $pengajuan->pinjaman_ke }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="pinjaman_terakhir">Nominal Pinjaman Terakhir</label>
                                        <input type="number" name="pinjaman_terakhir" id="pinjaman_terakhir"
                                            class="form-control" value="{{ $pengajuan->pinjaman_terakhir }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="sisa_pinjaman">Sisa Pinjaman</label>
                                        <input type="number" name="sisa_pinjaman" id="sisa_pinjaman"
                                            class="form-control" value="{{ $pengajuan->sisa_pinjaman }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="realisasi_pinjaman">Realisasi Pinjaman</label>
                                        <input type="text" name="realisasi_pinjaman" id="realisasi_pinjaman"
                                            class="form-control" value="{{ $pengajuan->realisasi_pinjaman }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="cicilan_perbulan_pinjaman">Cicilan Perbulan Pinjaman</label>
                                        <input type="number" name="cicilan_perbulan_pinjaman"
                                            id="cicilan_perbulan_pinjaman" class="form-control"
                                            value="{{ $pengajuan->cicilan_perbulan }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_pembiayaan">Jenis Pembiayaan</label>
                                    <select name="jenis_pembiayaan" id="jenis_pembiayaan" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="BPJS"
                                            {{ $pengajuan->jenis_pembiayaan === 'BPJS' ? 'selected' : '' }}>
                                            BPJS</option>
                                        <option value="SHM"
                                            {{ $pengajuan->jenis_pembiayaan === 'SHM' ? 'selected' : '' }}>
                                            SHM</option>
                                        <option value="BPKB"
                                            {{ $pengajuan->jenis_pembiayaan === 'BPKB' ? 'selected' : '' }}>
                                            BPKB</option>
                                        <option value="Kedinasan"
                                            {{ $pengajuan->jenis_pembiayaan === 'Kedinasan' ? 'selected' : '' }}>
                                            Kedinasan</option>
                                    </select>
                                </div>
                                @php
                                    $pengajuanbpjs = $pengajuan->pengajuanBPJS;
                                    $pengajuanshm = $pengajuan->pengajuanSHM;
                                    $pengajuanbpkb = $pengajuan->pengajuanBPKB;
                                    $pengajuankedinasan = $pengajuan->pengajuanKedinasan;
                                @endphp
                                <div id="bpjs-group"
                                    style="display: {{ $pengajuan->jenis_pembiayaan === 'BPJS' ? 'block' : 'none' }}">
                                    <h5>Informasi BPJS</h5>
                                    <div class="form-group">
                                        <label for="saldo_bpjs" class="form-label">Saldo BPJS</label>
                                        <input type="number" id="saldo_bpjs" name="saldo_bpjs" class="form-control"
                                            value="{{ $pengajuan->pengajuanBPJS->saldo_bpjs ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_bayar_terakhir" class="form-label">Saldo BPJS</label>
                                        <input type="date" id="tanggal_bayar_terakhir" name="tanggal_bayar_terakhir"
                                            class="form-control"
                                            value="{{ $pengajuan->pengajuanBPJS->tanggal_bayar_terakhir ?? '' }}">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">Username BPJS</label>
                                            <input type="text" id="username" name="username" class="form-control"
                                                value="{{ $pengajuan->pengajuanBPJS->username ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Password BPJS</label>
                                            <input type="text" id="password" name="password" class="form-control"
                                                value="{{ $pengajuan->pengajuanBPJS->password ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_bukti_bpjs" class="form-label">Upload File Bukti BPJS</label>
                                        <input type="file" id="foto_bukti_bpjs" name="foto_bukti_bpjs"
                                            class="file-upload-default" accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload File Bukti BPJS"
                                                value="{{ $pengajuan->pengajuanBPJS->foto_bpjs ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_jaminan_tambahan" class="form-label">Upload File Jaminan
                                            Tambahan</label>
                                        <input type="file" id="foto_jaminan_tambahan" name="foto_jaminan_tambahan"
                                            class="file-upload-default" accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload File Jaminan Tambahan"
                                                value="{{ $pengajuan->pengajuanBPJS->foto_jaminan_tambahan ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>
                                    @if ($pengajuanbpjs)
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="foto_bpjs" class="form-label">Foto Bukti BPJS</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanBPJS)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#modalFotoBPJS">
                                                        Lihat PDF
                                                    </button>

                                                    <div class="modal fade" id="modalFotoBPJS" tabindex="-1"
                                                        aria-labelledby="modalFotoBPJSLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalFotoBPJSLabel">
                                                                        File BPJS</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanBPJS->foto_bpjs) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanBPJS->foto_bpjs ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File BPJS tidak tersedia.</p>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label for="foto_jaminan_tambahan" class="form-label">Foto Tambahan
                                                    Jaminan</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanBPJS)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#modalFotoTambahanJaminan">
                                                        Lihat PDF
                                                    </button>

                                                    <div class="modal fade" id="modalFotoTambahanJaminan" tabindex="-1"
                                                        aria-labelledby="modalFotoTambahanJaminanLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="modalFotoTambahanJaminanLabel">
                                                                        Foto Tambahan Jaminan</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanBPJS->foto_jaminan_tambahan) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanBPJS->foto_jaminan_tambahan ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File Tambahan Jaminan tidak tersedia.</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div id="shm-group"
                                    style="display: {{ $pengajuan->jenis_pembiayaan === 'SHM' ? 'block' : 'none' }}">
                                    <h5>Informasi SHM</h5>
                                    <div class="form-group">
                                        <label for="atas_nama_shm" class="form-label">Atas Nama SHM</label>
                                        <input type="text" id="atas_nama_shm" name="atas_nama_shm"
                                            class="form-control"
                                            value="{{ $pengajuan->pengajuanSHM->atas_nama_shm ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="hubungan_shm" class="form-label">Hubungan</label>
                                        <input type="text" id="hubungan_shm" name="hubungan_shm" class="form-control"
                                            value="{{ $pengajuan->pengajuanSHM->hubungan_shm ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_shm" class="form-label">Alamat</label>
                                        <textarea name="alamat_shm" id="alamat_shm" cols="30" rows="10" class="form-control">{{ $pengajuan->pengajuanSHM->alamat_shm ?? '' }}</textarea>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="luas_shm" class="form-label">Luas</label>
                                            <input type="text" id="luas_shm" name="luas_shm" class="form-control"
                                                value="{{ $pengajuan->pengajuanSHM->luas_shm ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="njop_shm" class="form-label">Total NJOP</label>
                                            <input type="text" id="njop_shm" name="njop_shm"
                                                class="form-control"
                                                value="{{ $pengajuan->pengajuanSHM->njop_shm ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_shm" class="form-label">Upload File SHM</label>
                                        <input type="file" id="foto_shm" name="foto_shm"
                                            class="file-upload-default" accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload File SHM"
                                                value="{{ $pengajuan->pengajuanSHM->foto_shm ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_kk_pemilik_shm" class="form-label">Upload File KK Pemilik
                                            SHM</label>
                                        <input type="file" id="foto_kk_pemilik_shm" name="foto_kk_pemilik_shm"
                                            class="file-upload-default" accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload File KK Pemilik SHM"
                                                value="{{ $pengajuan->pengajuanSHM->foto_kk_pemilik_shm ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_pbb" class="form-label">Upload File PBB</label>
                                        <input type="file" id="foto_pbb" name="foto_pbb"
                                            class="file-upload-default" accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload File PBB"
                                                value="{{ $pengajuan->pengajuanSHM->foto_pbb ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    @if ($pengajuanshm)
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="foto_shm" class="form-label">File PDF SHM</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanSHM)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#pdfModal">
                                                        Lihat PDF
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="pdfModal" tabindex="-1"
                                                        aria-labelledby="pdfModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="pdfModalLabel">File PDF
                                                                        -
                                                                        Atas
                                                                        Nama SHM</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanSHM->foto_shm) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanSHM->foto_shm ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File PDF SHM tidak tersedia.</p>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label for="foto_kk_pemilik_shm" class="form-label">File PDF KK Pemilik
                                                    SHM</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanSHM)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#pdfModal2">
                                                        Lihat PDF
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="pdfModal2" tabindex="-1"
                                                        aria-labelledby="pdfModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="pdfModalLabel">File PDF
                                                                        -
                                                                        KK
                                                                        Pemilik SHM</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanSHM->foto_kk_pemilik_shm) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanSHM->foto_kk_pemilik_shm ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File PDF KK Pemilik SHM tidak tersedia.</p>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label for="foto_pbb" class="form-label">File PDF PBB</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanSHM)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#pdfModal3">
                                                        Lihat PDF
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="pdfModal3" tabindex="-1"
                                                        aria-labelledby="pdfModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="pdfModalLabel">File PDF
                                                                        -
                                                                        PBB</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanSHM->foto_pbb) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanSHM->foto_pbb ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File PDF PBB tidak tersedia.</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div id="bpkb-group"
                                    style="display: {{ $pengajuan->jenis_pembiayaan === 'BPKB' ? 'block' : 'none' }}">
                                    <h5>Informasi BPKB</h5>
                                    <div class="form-group">
                                        <label for="no_stnk" class="form-label">No STNK</label>
                                        <input type="text" id="no_stnk" name="no_stnk" class="form-control"
                                            value="{{ $pengajuan->pengajuanBPKB->no_stnk ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="atas_nama_bpkb" class="form-label">Nama Pemilik</label>
                                        <input type="text" id="atas_nama_bpkb" name="atas_nama_bpkb"
                                            class="form-control"
                                            value="{{ $pengajuan->pengajuanBPKB->atas_nama_bpkb ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_pemilik_bpkb" class="form-label">Alamat Pemilik</label>
                                        <textarea name="alamat_pemilik_bpkb" id="alamat_pemilik_bpkb" cols="30" rows="10"
                                            class="form-control">{{ $pengajuan->pengajuanBPKB->alamat_pemilik_bpkb ?? '' }}</textarea>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="type_kendaraan" class="form-label">Tipe Kendaraan</label>
                                            <input type="text" id="type_kendaraan" name="type_kendaraan"
                                                class="form-control"
                                                value="{{ $pengajuan->pengajuanBPKB->type_kendaraan ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tahun_perakitan" class="form-label">Tahun Perakitan</label>
                                            <input type="text" id="tahun_perakitan" name="tahun_perakitan"
                                                class="form-control"
                                                value="{{ $pengajuan->pengajuanBPKB->tahun_perakitan ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="warna_kendaraan" class="form-label">Warna Kendaraan</label>
                                            <input type="text" id="warna_kendaraan" name="warna_kendaraan"
                                                class="form-control"
                                                value="{{ $pengajuan->pengajuanBPKB->warna_kendaraan ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="stransmisi" class="form-label">Stransmisi</label>
                                            <input type="text" id="stransmisi" name="stransmisi"
                                                class="form-control"
                                                value="{{ $pengajuan->pengajuanBPKB->stransmisi ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="no_rangka" class="form-label">No Rangka</label>
                                            <input type="text" id="no_rangka" name="no_rangka"
                                                class="form-control"
                                                value="{{ $pengajuan->pengajuanBPKB->no_rangka ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="no_mesin" class="form-label">No Mesin</label>
                                            <input type="text" id="no_mesin" name="no_mesin"
                                                class="form-control"
                                                value="{{ $pengajuan->pengajuanBPKB->no_mesin ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="no_bpkb" class="form-label">No BPKB</label>
                                            <input type="text" id="no_bpkb" name="no_bpkb" class="form-control"
                                                value="{{ $pengajuan->pengajuanBPKB->no_bpkb ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_stnk" class="form-label">Upload File STNK</label>
                                        <input type="file" id="foto_stnk" name="foto_stnk"
                                            class="file-upload-default" accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload Foto BPJS"
                                                value="{{ $pengajuan->pengajuanBPKB->foto_stnk ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_kk_pemilik_bpkb" class="form-label">Upload File KK Pemilik
                                            BPKB</label>
                                        <input type="file" id="foto_kk_pemilik_bpkb" name="foto_kk_pemilik_bpkb"
                                            class="file-upload-default" accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload Foto BPJS"
                                                value="{{ $pengajuan->pengajuanBPKB->foto_kk_pemilik_bpkb ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="foto_motor" class="form-label">Upload File Kondisi Motor</label>
                                        <input type="file" id="foto_motor" name="foto_motor"
                                            class="file-upload-default" accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload Foto BPJS"
                                                value="{{ $pengajuan->pengajuanBPKB->foto_motor ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    @if ($pengajuanbpkb)
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="foto_stnk" class="form-label">File PDF STNK</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanBpkb)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#modalSTNK">
                                                        Lihat PDF
                                                    </button>

                                                    <div class="modal fade" id="modalSTNK" tabindex="-1"
                                                        aria-labelledby="modalSTNKLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalSTNKLabel">Surat
                                                                        Permohonan Kredit</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanBpkb->foto_stnk) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanBpkb->foto_stnk ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File Surat Permohonan Kredit tidak tersedia.
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label for="foto_kk_pemilik_bpkb" class="form-label">File PDF KK Pemilik
                                                    BPKB</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanBpkb)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#modalKKPemilikBPKB">
                                                        Lihat PDF
                                                    </button>

                                                    <div class="modal fade" id="modalKKPemilikBPKB" tabindex="-1"
                                                        aria-labelledby="modalKKPemilikBPKBLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="modalKKPemilikBPKBLabel">Surat
                                                                        Permohonan Kredit</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanBpkb->foto_kk_pemilik_bpkb) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanBpkb->foto_kk_pemilik_bpkb ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File Surat Permohonan Kredit tidak tersedia.
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label for="foto_motor" class="form-label">File PDF Kondisi
                                                    Motor</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanBpkb)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#modalKondisiMotor">
                                                        Lihat PDF
                                                    </button>

                                                    <div class="modal fade" id="modalKondisiMotor" tabindex="-1"
                                                        aria-labelledby="modalKondisiMotorLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalKondisiMotorLabel">
                                                                        Surat
                                                                        Permohonan Kredit</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanBpkb->foto_motor) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanBpkb->foto_motor ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File Surat Permohonan Kredit tidak tersedia.
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div id="kedinasan-group"
                                    style="display: {{ $pengajuan->jenis_pembiayaan === 'Kedinasan' ? 'block' : 'none' }}">
                                    <h5>Informasi Kedinasan</h5>
                                    <div class="form-group">
                                        <label for="instansi" class="form-label">Nama Instansi</label>
                                        <input type="text" id="instansi" name="instansi" class="form-control"
                                            value="{{ $pengajuan->pengajuanKedinasan->instansi ?? 'Belum ada' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="surat_permohonan_kredit" class="form-label">Upload Surat Permohonan
                                            Kredit</label>
                                        <input type="file" id="surat_permohonan_kredit"
                                            name="surat_permohonan_kredit" class="file-upload-default"
                                            accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload Foto BPJS"
                                                value="{{ $pengajuan->pengajuanKedinasan->surat_permohonan_kredit ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="surat_pernyataan_penjamin" class="form-label">Upload Surat
                                            Pernyataan Penjamin</label>
                                        <input type="file" id="surat_pernyataan_penjamin"
                                            name="surat_pernyataan_penjamin" class="file-upload-default"
                                            accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload Foto BPJS"
                                                value="{{ $pengajuan->pengajuanKedinasan->surat_pernyataan_penjamin ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="surat_persetujuan_pimpinan" class="form-label">Upload Surat
                                            Persetujuan Pimpinan dan Surat Kuasa Pemotongan TPP</label>
                                        <input type="file" id="surat_persetujuan_pimpinan"
                                            name="surat_persetujuan_pimpinan" class="file-upload-default"
                                            accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload Foto BPJS"
                                                value="{{ $pengajuan->pengajuanKedinasan->surat_persetujuan_pimpinan ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="surat_keterangan_gaji" class="form-label">Upload Surat Keterangan
                                            Gaji, TPP dan Pemotongan</label>
                                        <input type="file" id="surat_keterangan_gaji" name="surat_keterangan_gaji"
                                            class="file-upload-default" accept="application/pdf">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled
                                                placeholder="Upload Foto BPJS"
                                                value="{{ $pengajuan->pengajuanKedinasan->surat_keterangan_gaji ?? '' }}">
                                            <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-gradient-info py-3"
                                                    type="button">Pilih File</button>
                                            </span>
                                        </div>
                                    </div>

                                    @if ($pengajuankedinasan)
                                        <div class="form-group row">
                                            <div class="col-md-3">
                                                <label for="surat_permohonan_kredit" class="form-label">File Surat
                                                    Permohonan Kredit</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanKedinasan)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalSuratPermohonanKredit">
                                                        Lihat PDF
                                                    </button>

                                                    <div class="modal fade" id="modalSuratPermohonanKredit"
                                                        tabindex="-1" aria-labelledby="modalSuratPermohonanKreditLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="modalSuratPermohonanKreditLabel">Surat
                                                                        Permohonan Kredit</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanKedinasan->surat_permohonan_kredit) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanKedinasan->surat_permohonan_kredit ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File Surat Permohonan Kredit tidak tersedia.
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="col-md-3">
                                                <label for="surat_pernyataan_penjamin" class="form-label">File Surat
                                                    Pernyataan Penjamin</label>
                                                <br>
                                                @if ($pengajuan && $pengajuan->pengajuanKedinasan)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalSuratPernyataanPenjamin">
                                                        Lihat PDF
                                                    </button>

                                                    <div class="modal fade" id="modalSuratPernyataanPenjamin"
                                                        tabindex="-1"
                                                        aria-labelledby="modalSuratPernyataanPenjaminLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="modalSuratPernyataanPenjaminLabel">Surat
                                                                        Pernyataan Penjamin</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanKedinasan->surat_pernyataan_penjamin) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanKedinasan->surat_pernyataan_penjamin ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File Surat Pernyataan Penjamin tidak tersedia.
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <label for="surat_persetujuan_pimpinan" class="form-label">File Surat
                                                    Persetujuan
                                                    Pimpinan dan Surat Kuasa Pemotongan TPP</label>
                                                @if ($pengajuan && $pengajuan->pengajuanKedinasan)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalSuratPersetujuanPimpinan">
                                                        Lihat PDF
                                                    </button>

                                                    <div class="modal fade" id="modalSuratPersetujuanPimpinan"
                                                        tabindex="-1"
                                                        aria-labelledby="modalSuratPersetujuanPimpinanLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="modalSuratPersetujuanPimpinanLabel">Surat
                                                                        Pernyataan Penjamin</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanKedinasan->surat_persetujuan_pimpinan) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanKedinasan->surat_pernyataan_penjamin ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File Surat Pernyataan Penjamin tidak tersedia.
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="col-md-3">
                                                <label for="surat_persetujuan_pimpinan" class="form-label">File Surat
                                                    Keterangan Gaji, TPP dan Pemotongan</label>
                                                @if ($pengajuan && $pengajuan->pengajuanKedinasan)
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalSuratKeteranganGaji">
                                                        Lihat PDF
                                                    </button>

                                                    <div class="modal fade" id="modalSuratKeteranganGaji"
                                                        tabindex="-1" aria-labelledby="modalSuratKeteranganGajiLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="modalSuratKeteranganGajiLabel">Surat
                                                                        Pernyataan Penjamin</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <embed
                                                                        src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/' . $pengajuan->pengajuanKedinasan->surat_keterangan_gaji) }}"
                                                                        type="application/pdf" width="100%"
                                                                        height="500px">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Tutup</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <span class="form-text">File :
                                                        {{ $pengajuan->pengajuanKedinasan->surat_pernyataan_penjamin ?? '' }}</span>
                                                @else
                                                    <p class="text-danger">File Surat Pernyataan Penjamin tidak tersedia.
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if ($pengajuan->validasi_pengajuan == 0)
                                    <div class="form-group mt-0" id="catatan-pengajuan">
                                        <label for="catatan_pengajuan" class="form-label">Catatan</label>
                                        <textarea name="catatan_pengajuan" id="catatan_pengajuan" class="form-control" cols="30" rows="10"
                                            disabled>{{ $pengajuan->catatan }}</textarea>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="status_pengajuan" class="form-label">Status Pengajuan</label>
                                    <select name="status_pengajuan" id="status_pengajuan" class="form-select">
                                        <option value="" disabled>Pilih Status</option>
                                        @php
                                            $statuses = [
                                                'pending' => 'Pending',
                                                'aproved ca' => 'Approved CA',
                                                'rejected ca' => 'Rejected CA',
                                                'aproved hm' => 'Approved Head',
                                                'rejected hm' => 'Rejected Head',
                                                'revisi' => 'Revisi',
                                                'banding' => 'Banding',
                                                'approved banding ca' => 'Approved Banding CA',
                                                'rejected banding ca' => 'Rejected Banding CA',
                                                'approved banding hm' => 'Approved Banding Head',
                                                'rejected banding hm' => 'Rejected Banding Head',
                                                'perlu survey' => 'Perlu Survey',
                                                'tidak perlu survey' => 'Tidak Perlu Survey',
                                                'survey selesai' => 'Survey Selesai',
                                                'verifikasi' => 'Verifikasi',
                                            ];
                                        @endphp
                                        @foreach ($statuses as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('status_pengajuan', $pengajuan->status_pengajuan ?? '') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        // document.querySelectorAll('.file-upload-browse').forEach(button => {
        //     button.addEventListener('click', function() {
        //         const fileInput = this.closest('.form-group').querySelector('.file-upload-default');
        //         fileInput.click();
        //     });
        // });

        // document.querySelectorAll('.file-upload-default').forEach(input => {
        //     input.addEventListener('change', function() {
        //         const fileName = this.value.split('\\').pop(); // Ambil nama file
        //         const textInput = this.closest('.form-group').querySelector('.file-upload-info');
        //         if (textInput) textInput.value = fileName;
        //     });
        // });

        $(document).ready(function() {

            // Event ketika dropdown berubah
            $('#jenis_pembiayaan').on('change', function() {
                const selectedValue = $(this).val();

                // Sembunyikan semua grup form
                $('#bpjs-group').hide();
                $('#shm-group').hide();
                $('#bpkb-group').hide();
                $('#kedinasan-group').hide();

                // Tampilkan form sesuai dengan pilihan
                if (selectedValue === 'BPJS') {
                    $('#bpjs-group').show();
                } else if (selectedValue === 'SHM') {
                    $('#shm-group').show();
                } else if (selectedValue === 'BPKB') {
                    $('#bpkb-group').show();
                } else if (selectedValue === 'Kedinasan') {
                    $('#kedinasan-group').show();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen untuk Status Rumah
            const statusRumahSelect = document.getElementById('status_rumah');
            const kontrakBulananGroup = document.getElementById('kontrak-bulanan-group');
            const kontrakTahunanGroup = document.getElementById('kontrak-tahunan-group');

            // Ambil elemen untuk Alamat Domisili
            const domisiliSelect = document.getElementById('domisili');
            const alamatLengkapGroup = document.getElementById('alamat-lengkap-group');
            const rumahDomisiliSelect = document.getElementById('rumah_domisili');
            const domisiliBulananGroup = document.getElementById('domisili-bulanan-group');
            const domisiliTahunanGroup = document.getElementById('domisili-tahunan-group');

            const statusKaryawanSelect = document.getElementById('status_karyawan');
            const divKontrak = document.getElementById('div-kontrak');

            // cicilan lain
            const cicilanLain = document.getElementById('cicilan_lain');
            const cicilanContainer = document.getElementById('cicilan-container');
            const dynamicFormContainer = document.getElementById('dynamic-form-container');
            const addFormButton = document.getElementById('add-form-button');

            const statusPinjamanSelect = document.getElementById('status_pinjaman');
            const riwayatPinjamanGroup = document.getElementById('riwayat-pinjaman-group');

            function toggleRiwayatPinjaman() {
                if (statusPinjamanSelect.value === 'lama') {
                    riwayatPinjamanGroup.style.display = 'block';
                } else {
                    riwayatPinjamanGroup.style.display = 'none';
                }
            }

            // Fungsi untuk toggle grup berdasarkan Status Rumah
            function toggleStatusRumah() {
                // Sembunyikan semua grup
                kontrakBulananGroup.style.display = 'none';
                kontrakTahunanGroup.style.display = 'none';

                // Tampilkan grup sesuai pilihan
                if (statusRumahSelect.value === 'kontrak bulanan') {
                    kontrakBulananGroup.style.display = 'block';
                } else if (statusRumahSelect.value === 'kontrak tahunan') {
                    kontrakTahunanGroup.style.display = 'block';
                }
            }

            // Fungsi untuk toggle grup berdasarkan Alamat Domisili
            function toggleAlamatDomisili() {
                // Tampilkan atau sembunyikan grup Alamat Lengkap
                if (domisiliSelect.value === 'tidak sesuai ktp') {
                    alamatLengkapGroup.style.display = 'block';
                } else {
                    alamatLengkapGroup.style.display = 'none';
                }
            }

            // Fungsi untuk toggle grup berdasarkan Rumah Domisili
            function toggleRumahDomisili() {
                // Sembunyikan semua grup domisili
                domisiliBulananGroup.style.display = 'none';
                domisiliTahunanGroup.style.display = 'none';

                // Tampilkan grup sesuai pilihan
                if (rumahDomisiliSelect.value === 'kontrak bulanan') {
                    domisiliBulananGroup.style.display = 'block';
                } else if (rumahDomisiliSelect.value === 'kontrak tahunan') {
                    domisiliTahunanGroup.style.display = 'block';
                }
            }

            function toggleStatusKaryawan() {
                if (statusKaryawanSelect.value === 'kontrak') {
                    divKontrak.style.display = 'block';
                } else {
                    divKontrak.style.display = 'none';
                }
            }

            function toggleCicilan() {
                if (cicilanLain.value === 'Ada') {
                    cicilanContainer.style.display = 'block';
                } else {
                    cicilanContainer.style.display = 'none';
                }
            }

            // Function to add a new form dynamically
            function addNewForm() {
                const formCount = dynamicFormContainer.childElementCount;
                const newForm = document.createElement('div');
                newForm.className = 'dynamic-form mb-3';
                newForm.id = `form-${formCount}`;
                newForm.innerHTML = `
                    <h6>Form Cicilan Lain ${formCount + 1}</h6>
                    <div class="form-group">
                        <label for="nama_pembiayaan_${formCount}" class="form-label">Nama Pembiayaan</label>
                        <input type="text" id="nama_pembiayaan_${formCount}" name="nama_pembiayaan[]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="total_pinjaman_${formCount}" class="form-label">Total Pinjaman</label>
                        <input type="number" id="total_pinjaman_${formCount}" name="total_pinjaman[]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cicilan_perbulan_${formCount}" class="form-label">Cicilan Perbulan</label>
                        <input type="number" id="cicilan_perbulan_${formCount}" name="cicilan_perbulan[]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="sisa_tenor_cicilan_${formCount}" class="form-label">Sisa Tenor</label>
                        <input type="number" id="sisa_tenor_cicilan_${formCount}" name="sisa_tenor_cicilan[]" class="form-control">
                    </div>
                    <hr>
                `;
                dynamicFormContainer.appendChild(newForm);
            }

            // Panggil fungsi toggle saat halaman dimuat untuk inisialisasi
            toggleRiwayatPinjaman();
            toggleStatusRumah();
            toggleAlamatDomisili();
            toggleRumahDomisili();
            toggleStatusKaryawan();
            toggleCicilan();

            // Tambahkan event listener untuk perubahan dropdown
            statusPinjamanSelect.addEventListener('change', toggleRiwayatPinjaman);
            statusRumahSelect.addEventListener('change', toggleStatusRumah);
            domisiliSelect.addEventListener('change', toggleAlamatDomisili);
            rumahDomisiliSelect.addEventListener('change', toggleRumahDomisili);
            statusKaryawanSelect.addEventListener('change', toggleStatusKaryawan);
            cicilanLain.addEventListener('change', toggleCicilan);
            // Add event listener for the add form button
            addFormButton.addEventListener('click', addNewForm);
        });

        document.addEventListener('DOMContentLoaded', function() {
            const steps = Array.from(document.querySelectorAll('.form-step'));
            const nextBtn = document.querySelectorAll('.next-btn');
            const prevBtn = document.querySelectorAll('.prev-btn');
            const form = document.getElementById('loanForm');
            let currentStep = 0;

            // Fungsi untuk menampilkan langkah tertentu
            function showStep(stepIndex) {
                steps.forEach((step, index) => {
                    step.classList.toggle('form-step-active', index === stepIndex);
                });
            }

            // Event listener untuk tombol Next
            nextBtn.forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault(); // Cegah pengiriman form saat klik tombol Next
                    const currentFormStep = steps[currentStep];
                    const inputs = currentFormStep.querySelectorAll('input, select, textarea');
                    let isValid = true;

                    // Periksa validitas semua input di langkah saat ini
                    inputs.forEach(input => {
                        if (!input.checkValidity()) {
                            isValid = false;
                            input
                                .reportValidity(); // Menampilkan pesan error bawaan browser
                        }
                    });

                    // Jika validasi berhasil, lanjutkan ke langkah berikutnya
                    if (isValid && currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            // Event listener untuk tombol Previous
            prevBtn.forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault(); // Cegah pengiriman form saat klik tombol Previous
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            // Tampilkan langkah pertama
            showStep(currentStep);

            // Cegah pengiriman formulir jika langkah terakhir belum selesai
            form.addEventListener('submit', (event) => {
                if (currentStep < steps.length - 1) {
                    event.preventDefault();
                    alert('Harap selesaikan semua langkah sebelum mengirim formulir.');
                }
            });
        });
    </script>

@endsection
