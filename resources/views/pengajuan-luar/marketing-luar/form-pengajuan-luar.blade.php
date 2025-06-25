@extends('layouts.parent-layout')

@section('breadcrumb-title', '/ Form Pengajuan')
@section('page-title', 'Pengajuan Nasabah Luar')

@section('content')
    <style>
        .form-step {
            display: none;
        }

        .form-step-active {
            display: block;
        }
    </style>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main id="main" class="main">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-header d-flex justify-content-center align-items-center">
                        <h4 class="card-title text-center mt-2 mb-2">Form Pengajuan Pinjaman</h4>
                    </div>
                    <div class="card-body">
                        <form id="loanForm" action="{{ route('marketingLuar.form.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- Step 1: Informasi Nasabah -->
                            <div class="form-step form-step-active">
                                <h5 class="mb-3">Informasi Nasabah</h5>
                                <div class="form-group">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                        required>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="text" id="nik" name="nik" class="form-control" required>
                                        <small id="nik-error" class="text-danger"></small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="no_kk" class="form-label">No KK</label>
                                        <input type="text" id="no_kk" name="no_kk" class="form-control" required>
                                        <small id="no_kk-error" class="text-danger"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status_nikah" class="form-label">Status Pernikahan</label>
                                    <select name="status_nikah" id="status_nikah" class="form-select" required>
                                        <option value="">Pilih Status Pernikahan</option>
                                        <option value="Belum Menikah">Belum Menikah</option>
                                        <option value="Menikah">Menikah</option>
                                        <option value="Janda/Duda">Janda/Duda</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="no_hp" class="form-label">Nomor HP/WA</label>
                                        <input type="text" id="no_hp" name="no_hp" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foto_ktp" class="form-label">Foto KTP</label>
                                    <input type="file" id="foto_ktp" name="foto_ktp" class="file-upload-default"
                                        accept="image/*" required>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Foto KTP">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-info py-3"
                                                type="button">Pilih File</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foto_kk" class="form-label">Foto Kartu Keluarga</label>
                                    <input type="file" id="foto_kk" name="foto_kk" class="file-upload-default"
                                        accept="image/*" required>
                                    <div class="input-group col-xs-12">
                                        <input type="text" class="form-control file-upload-info" disabled
                                            placeholder="Upload Foto Kartu Keluarga">
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
                                            placeholder="Upload Dokumen Pendukung">
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-info py-3"
                                                type="button">Pilih File</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <!-- Step 2: Alamat Nasabah -->
                            <div class="form-step">
                                <h5>Alamat Nasabah</h5>
                                <div class="form-group">
                                    <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                                    <input type="text" id="alamat_ktp" name="alamat_ktp" class="form-control">
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="kelurahan" class="form-label">Kelurahan</label>
                                        <input type="text" id="kelurahan" name="kelurahan" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rt_rw" class="form-label">RT/RW</label>
                                        <input type="text" id="rt_rw" name="rt_rw" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <input type="text" id="kecamatan" name="kecamatan" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="kota" class="form-label">Kabupaten/Kota</label>
                                    <input type="text" id="kota" name="kota" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="status_rumah" class="form-label">Status Rumah</label>
                                    <select id="status_rumah" name="status_rumah" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="kontrak bulanan">Kontrak Bulanan</option>
                                        <option value="kontrak tahunan">Kontrak Tahunan</option>
                                        <option value="milik orang tua">Milik Orang Tua</option>
                                        <option value="pribadi">Milik Pribadi</option>
                                    </select>
                                </div>
                                <div class="form-group" id="kontrak-bulanan-group" style="display: none;">
                                    <label for="biaya_perbulan" class="form-label">Biaya Perbulan</label>
                                    <input type="number" id="biaya_perbulan" name="biaya_perbulan"
                                        class="form-control">
                                </div>
                                <div class="form-group" id="kontrak-tahunan-group" style="display: none;">
                                    <label for="biaya_pertahun" class="form-label">Biaya Pertahun</label>
                                    <input type="number" id="biaya_pertahun" name="biaya_pertahun"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="domisili" class="form-label">Alamat Domisili</label>
                                    <select id="domisili" name="domisili" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="sesuai ktp">Sesuai KTP</option>
                                        <option value="tidak sesuai ktp">Tidak Sesuai KTP</option>
                                    </select>
                                </div>
                                <div class="form-group" id="alamat-lengkap-group" style="display: none;">
                                    <div class="form-group">
                                        <label for="alamat_domisili" class="form-label">Alamat Lengkap</label>
                                        <textarea name="alamat_domisili" id="alamat_domisili" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="rumah_domisili" class="form-label">Status Rumah Domisili</label>
                                        <select id="rumah_domisili" name="rumah_domisili" class="form-select">
                                            <option value="">Pilih</option>
                                            <option value="kontrak bulanan">Kontrak Bulanan</option>
                                            <option value="kontrak tahunan">Kontrak Tahunan</option>
                                            <option value="milik orang tua">Milik Orang Tua</option>
                                            <option value="pribadi">Milik Pribadi</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="domisili-bulanan-group" style="display: none;">
                                        <label for="biaya_perbulan_domisili" class="form-label">Biaya Perbulan</label>
                                        <input type="number" id="biaya_perbulan_domisili" name="biaya_perbulan_domisili"
                                            class="form-control">
                                    </div>
                                    <div class="form-group" id="domisili-tahunan-group" style="display: none;">
                                        <label for="biaya_pertahun_domisili" class="form-label">Biaya Pertahun</label>
                                        <input type="number" id="biaya_pertahun_domisili" name="biaya_pertahun_domisili"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lama_tinggal" class="form-label">Lama Tinggal</label>
                                    <input type="text" id="lama_tinggal" name="lama_tinggal" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="share_loc_link" class="form-label">Share Loc Alamat Nasabah</label>
                                    <input type="text" id="share_loc_link" name="share_loc_link"
                                        class="form-control">
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-4">
                                        <label for="atas_nama_listrik" class="form-label">Rekening Listrik Atas
                                            Nama</label>
                                        <input type="text" class="form-control" name="atas_nama_listrik"
                                            id="atas_nama_listrik">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="hubungan_rek_listrik">Hubungan dengan Pemilik Rekening Listrik</label>
                                        <input type="text" class="form-control" name="hubungan_rek_listrik"
                                            id="hubungan_rek_listrik">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="foto_meteran_listrik">Foto Meteran Listrik Rumah</label>
                                        <input type="file" id="foto_meteran_listrik" name="foto_meteran_listrik"
                                            class="form-control" accept="image/*">
                                    </div>
                                </div>
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
                                    <input type="text" class="form-control" name="perusahaan" id="perusahaan">
                                </div>
                                <div class="form-group">
                                    <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan</label>
                                    <textarea name="alamat_perusahaan" id="alamat_perusahaan" cols="30" rows="10" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="kontak_perusahaan" class="form-label">No Telp Perusahaan</label>
                                    <input type="text" class="form-control" name="kontak_perusahaan"
                                        id="kontak_perusahaan">
                                </div>
                                <div class="form-group">
                                    <label for="jabatan" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" name="jabatan" id="jabatan">
                                </div>
                                <div class="form-group">
                                    <label for="lama_kerja" class="form-label">Lama Bekerja</label>
                                    <input type="text" class="form-control" name="lama_kerja" id="lama_kerja">
                                </div>
                                <div class="form-group">
                                    <label for="status_karyawan" class="form-label">Status Karyawan</label>
                                    <select id="status_karyawan" name="status_karyawan" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="tetap">Tetap</option>
                                        <option value="kontrak">Kontrak</option>
                                    </select>
                                </div>
                                <div class="form-group" id="div-kontrak" style="display: none;">
                                    <label for="lama_kontrak" class="form-label">Lama Kontrak</label>
                                    <input type="text" name="lama_kontrak" id="lama_kontrak" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="pendapatan_perbulan" class="form-label">Pendapatan Perbulan</label>
                                    <input type="number" name="pendapatan_perbulan" id="pendapatan_perbulan"
                                        class="form-control">
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="foto_id_card" class="form-label">Foto ID Card/SKU</label>
                                        <input type="file" name="foto_id_card" id="foto_id_card" class="form-control"
                                            accept="image/*">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="foto_slip_gaji" class="form-label">Slip Gaji/Mutasi</label>
                                        <input type="file" name="foto_slip_gaji" id="foto_slip_gaji"
                                            class="form-control" accept="application/pdf">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="norek" class="form-label">Foto Buku Rekening</label>
                                    <input type="file" name="norek" id="norek" class="form-control"
                                        accept="image/*">
                                </div>

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
                                        <option value="orang tua">Orang Tua</option>
                                        <option value="suami">Suami</option>
                                        <option value="istri">Istri</option>
                                        <option value="anak">Anak</option>
                                        <option value="keluarga kandung">Keluarga Kandung</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_penjamin" class="form-label">Nama Penjamin</label>
                                    <input type="text" id="nama_penjamin" name="nama_penjamin" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_penjamin" class="form-label">Pekerjaan Penjamin</label>
                                    <input type="text" id="pekerjaan_penjamin" name="pekerjaan_penjamin"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="penghasilan_penjamin" class="form-label">Penghasilan</label>
                                    <input type="number" id="penghasilan_penjamin" name="penghasilan_penjamin"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="no_hp_penjamin" class="form-label">No HP/WA</label>
                                    <input type="text" id="no_hp_penjamin" name="no_hp_penjamin"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="foto_ktp_penjamin" class="form-label">KTP Penjamin</label>
                                    <input type="file" id="foto_ktp_penjamin" name="foto_ktp_penjamin"
                                        class="form-control" accept="image/*">
                                </div>

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
                                    <textarea name="kondisi_tanggungan" id="kondisi_tanggungan" cols="30" rows="10" class="form-control"></textarea>
                                </div>

                                <!-- Dropdown Cicilan Lain -->
                                <div class="form-group">
                                    <label for="cicilan_lain" class="form-label">Cicilan Lain</label>
                                    <select name="cicilan_lain" id="cicilan_lain" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="Ada">Ada</option>
                                        <option value="Tidak">Tidak</option>
                                    </select>
                                </div>

                                <!-- Container Form Cicilan -->
                                <div id="cicilan-container" style="display: none;">
                                    <!-- Form Pertama -->
                                    <div id="dynamic-form-container">
                                        <div class="dynamic-form mb-3" id="form-0">
                                            <h6>Form Cicilan Lain 1</h6>
                                            <div class="form-group">
                                                <label for="nama_pembiayaan_0" class="form-label">Nama Pembiayaan</label>
                                                <input type="text" id="nama_pembiayaan_0" name="nama_pembiayaan[]"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="total_pinjaman_0" class="form-label">Total Pinjaman</label>
                                                <input type="number" id="total_pinjaman_0" name="total_pinjaman[]"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="cicilan_perbulan_0" class="form-label">Cicilan
                                                    Perbulan</label>
                                                <input type="number" id="cicilan_perbulan_0" name="cicilan_perbulan[]"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="sisa_tenor_cicilan_0" class="form-label">Sisa Tenor</label>
                                                <input type="number" id="sisa_tenor_cicilan_0"
                                                    name="sisa_tenor_cicilan[]" class="form-control">
                                            </div>
                                            <hr>
                                        </div>
                                    </div>

                                    <!-- Tombol Tambah -->
                                    <div class="text-first">
                                        <button type="button" id="add-dynamic-form"
                                            class="btn btn-success">Tambah</button>
                                    </div>
                                </div>

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
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="hubungan_kontak_darurat" class="form-label">Hubungan</label>
                                    <input type="text" name="hubungan_kontak_darurat" id="hubungan_kontak_darurat"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="no_hp_kontak_darurat" class="form-label">No HP/WA</label>
                                    <input type="text" name="no_hp_kontak_darurat" id="no_hp_kontak_darurat"
                                        class="form-control">
                                </div>

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
                                        <option value="baru">Baru</option>
                                        <option value="lama">Lama</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nominal_pinjaman" class="form-label">Nominal Pinjaman</label>
                                    <input type="number" id="nominal_pinjaman" name="nominal_pinjaman"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="tenor" class="form-label">Jangka Waktu</label>
                                    <input type="number" name="tenor" id="tenor" class="form-control" required>
                                </div>
                                <div id="riwayat-pinjaman-group" style="display: none;">
                                    <h5>Riwayat Pinjaman</h5>
                                    <div class="form-group">
                                        <label for="pinjaman_ke">Pinjaman Ke</label>
                                        <input type="number" name="pinjaman_ke" id="pinjaman_ke" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="pinjaman_terakhir">Nominal Pinjaman Terakhir</label>
                                        <input type="number" name="pinjaman_terakhir" id="pinjaman_terakhir"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="sisa_pinjaman">Sisa Pinjaman</label>
                                        <input type="number" name="sisa_pinjaman" id="sisa_pinjaman"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="realisasi_pinjaman">Realisasi Pinjaman</label>
                                        <input type="text" name="realisasi_pinjaman" id="realisasi_pinjaman"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="cicilan_perbulan_pinjaman">Cicilan Perbulan Pinjaman</label>
                                        <input type="number" name="cicilan_perbulan_pinjaman"
                                            id="cicilan_perbulan_pinjaman" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_pembiayaan">Jenis Pembiayaan</label>
                                    <select name="jenis_pembiayaan" id="jenis_pembiayaan" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="BPJS">BPJS</option>
                                        <option value="SHM">SHM</option>
                                        <option value="BPKB">BPKB</option>
                                        <option value="UMKM">UMKM</option>
                                        <option value="SF">SF</option>
                                        <option value="Kedinasan">Kedinasan</option>
                                        <option value="Kecamatan">Kecamatan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="berkas_jaminan" class="form-label">Upload Berkas Jaminan</label>
                                    <input type="file" name="berkas_jaminan" id="berkas_jaminan" class="form-control"
                                        accept="application/pdf" required>
                                </div>

                                <div class="form-group">
                                    <label for="catatan_marketing" class="form-label">Catatan Marketing</label>
                                    <textarea name="catatan_marketing" id="catatan_marketing" cols="30" rows="10" class="form-control"
                                        required></textarea>
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
        $(document).ready(function() {
            // Event ketika NIK diinputkan
            $('#nik').on('input', function() {
                let nik = $(this).val();
                if (nik.length > 0) {
                    $.ajax({
                        url: '{{ route('marketingLuar.validate.nik') }}', // Route untuk validasi NIK
                        method: 'GET',
                        data: {
                            nik: nik
                        },
                        success: function(response) {
                            if (response.exists) {
                                $('#nik-error').removeClass('text-success').addClass(
                                    'text-danger').text('NIK sudah terdaftar.');
                            } else {
                                $('#nik-error').removeClass('text-danger').addClass(
                                    'text-success').text('NIK dapat digunakan.');
                            }
                        }
                    });
                }
            });

            // Event ketika No KK diinputkan
            $('#no_kk').on('input', function() {
                let no_kk = $(this).val();
                if (no_kk.length > 0) {
                    $.ajax({
                        url: '{{ route('marketingLuar.validate.no.kk') }}', // Route untuk validasi No KK
                        method: 'GET',
                        data: {
                            no_kk: no_kk
                        },
                        success: function(response) {
                            if (response.exists) {
                                $('#no_kk-error').removeClass('text-success').addClass(
                                    'text-danger').text('No KK sudah terdaftar.');
                            } else {
                                $('#no_kk-error').removeClass('text-danger').addClass(
                                    'text-success').text('NO KK dapat digunakan');
                            }
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            let formIndex = 1; // Indeks dimulai dari 1 karena form pertama sudah ada

            // Event ketika dropdown cicilan_lain berubah
            $('#cicilan_lain').on('change', function() {
                const value = $(this).val();
                if (value === 'Ada') {
                    $('#cicilan-container').show(); // Tampilkan container
                } else {
                    $('#cicilan-container').hide(); // Sembunyikan container
                    $('#dynamic-form-container').html(''); // Hapus semua form tambahan
                    // Tambahkan kembali form pertama
                    $('#dynamic-form-container').append(getFormTemplate(0));
                    formIndex = 1;
                }
            });

            // Tambah form dinamis baru
            $('#add-dynamic-form').on('click', function() {
                const newForm = getFormTemplate(formIndex);
                $('#dynamic-form-container').append(newForm);
                formIndex++;
            });

            // Template form dinamis
            function getFormTemplate(index) {
                return `
                <div class="dynamic-form mb-3" id="form-${index}">
                    <h6>Form Cicilan Lain ${index + 1}</h6>
                    <div class="form-group">
                        <label for="nama_pembiayaan_${index}" class="form-label">Nama Pembiayaan</label>
                        <input type="text" id="nama_pembiayaan_${index}" name="nama_pembiayaan[]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="total_pinjaman_${index}" class="form-label">Total Pinjaman</label>
                        <input type="number" id="total_pinjaman_${index}" name="total_pinjaman[]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="cicilan_perbulan_${index}" class="form-label">Cicilan Perbulan</label>
                        <input type="number" id="cicilan_perbulan_${index}" name="cicilan_perbulan[]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="sisa_tenor_cicilan_${index}" class="form-label">Sisa Tenor</label>
                        <input type="number" id="sisa_tenor_cicilan_${index}" name="sisa_tenor_cicilan[]" class="form-control">
                    </div>
                    <button type="button" class="btn btn-danger remove-dynamic-form" data-form="form-${index}">Hapus</button>
                    <hr>
                </div>
            `;
            }

            // Hapus form dinamis tertentu
            $(document).on('click', '.remove-dynamic-form', function() {
                const formId = $(this).data('form');
                $('#' + formId).remove();
            });
        });

        $(document).ready(function() {
            $('#status_karyawan').on('change', function() {
                const value = $(this).val();
                if (value === 'kontrak') {
                    $('#div-kontrak').show(); // Tampilkan elemen lama kontrak
                } else {
                    $('#div-kontrak').hide(); // Sembunyikan elemen lama kontrak
                }
            });

            $('#status_pinjaman').on('change', function() {
                const value = $(this).val();
                if (value === 'lama') {
                    $('#riwayat-pinjaman-group').show(); // Tampilkan elemen lama
                } else {
                    $('#riwayat-pinjaman-group').hide(); // Sembunyikan elemen lama
                }
            });
        });

        $(document).ready(function() {
            // Event untuk Status Rumah
            $('#status_rumah').on('change', function() {
                const status = $(this).val();

                // Sembunyikan semua grup
                $('#kontrak-bulanan-group').hide();
                $('#kontrak-tahunan-group').hide();

                // Tampilkan grup yang sesuai
                if (status === 'kontrak bulanan') {
                    $('#kontrak-bulanan-group').show();
                } else if (status === 'kontrak tahunan') {
                    $('#kontrak-tahunan-group').show();
                }
            });

            // Event untuk Alamat Domisili
            $('#domisili').on('change', function() {
                const domisili = $(this).val();

                // Tampilkan grup alamat lengkap jika tidak sesuai KTP
                if (domisili === 'tidak sesuai ktp') {
                    $('#alamat-lengkap-group').show();
                } else {
                    $('#alamat-lengkap-group').hide();
                    $('#rumah_domisili').val(''); // Reset dropdown Rumah Domisili
                    $('#domisili-bulanan-group').hide();
                    $('#domisili-tahunan-group').hide();
                }
            });

            // Event untuk Status Rumah Domisili
            $('#rumah_domisili').on('change', function() {
                const statusDomisili = $(this).val();

                // Sembunyikan semua grup domisili
                $('#domisili-bulanan-group').hide();
                $('#domisili-tahunan-group').hide();

                // Tampilkan grup yang sesuai
                if (statusDomisili === 'kontrak bulanan') {
                    $('#domisili-bulanan-group').show();
                } else if (statusDomisili === 'kontrak tahunan') {
                    $('#domisili-tahunan-group').show();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const steps = Array.from(document.querySelectorAll('.form-step'));
            const nextBtn = document.querySelectorAll('.next-btn');
            const prevBtn = document.querySelectorAll('.prev-btn');
            let currentStep = 0;

            // Fungsi untuk menampilkan langkah tertentu
            function showStep(stepIndex) {
                steps.forEach((step, index) => {
                    step.classList.toggle('form-step-active', index === stepIndex);
                });
            }

            // Event listener untuk tombol Next
            nextBtn.forEach(button => {
                button.addEventListener('click', () => {
                    const currentFormStep = steps[currentStep];
                    const inputs = currentFormStep.querySelectorAll('input');
                    let isValid = true;

                    // Periksa validitas semua input di langkah saat ini
                    inputs.forEach(input => {
                        if (!input.checkValidity()) {
                            isValid = false;
                            input
                                .reportValidity(); // Menampilkan pesan error bawaan browser
                        }
                    });

                    if (isValid && currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            // Event listener untuk tombol Previous
            prevBtn.forEach(button => {
                button.addEventListener('click', () => {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            // Tampilkan langkah pertama
            showStep(currentStep);
        });
    </script>

@endsection
