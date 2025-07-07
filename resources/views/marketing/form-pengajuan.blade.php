@extends('layouts.parent-layout')

@section('breadcrumb-title', '/ Form Pengajuan')
@section('page-title', 'Pengajuan Nasabah')

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
                        <form id="loanForm" action="{{ route('marketing.form.store') }}" method="POST"
                            enctype="multipart/form-data"
                            onsubmit="document.getElementById('submitBtn').disabled=true; document.getElementById('submitBtn').innerHTML='Submitting...';">
                            @csrf
                            <!-- Step 1: Informasi Nasabah -->
                            <div class="form-step form-step-active">
                                <h5 class="mb-3">Informasi Nasabah</h5>
                                <div class="form-group">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="no_ktp" class="form-label">Nomor KTP</label>
                                    <input type="text" id="no_ktp" name="no_ktp" class="form-control" required>
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
                                @if (Auth::user()->usertype == 'marketing')
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="foto_ktp" class="form-label">Foto KTP</label>
                                            <input type="file" id="foto_ktp" name="foto_ktp" class="form-control"
                                                accept="image/*" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="foto_kk" class="form-label">Foto Kartu Keluarga</label>
                                            <input type="file" id="foto_kk" name="foto_kk" class="form-control"
                                                accept="image/*" required>
                                        </div>
                                    </div>
                                @endif
                                <div class="text-end">
                                    <button type="button" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <div class="form-step">
                                <h5>Alamat Nasabah</h5>
                                <div class="form-group">
                                    <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                                    <input type="text" id="alamat_ktp" name="alamat_ktp" class="form-control"
                                        required>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="kelurahan" class="form-label">Kelurahan</label>
                                        <input type="text" id="kelurahan" name="kelurahan" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rt_rw" class="form-label">RT/RW</label>
                                        <input type="text" id="rt_rw" name="rt_rw" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <input type="text" id="kecamatan" name="kecamatan" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="kota" class="form-label">Kabupaten/Kota</label>
                                    <input type="text" id="kota" name="kota" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="status_rumah_ktp" class="form-label">Status Rumah KTP</label>
                                    <select id="status_rumah_ktp" name="status_rumah_ktp" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="sewa">Sewa</option>
                                        <option value="kontrak">Kontrak</option>
                                        <option value="milik orang tua">Milik Orang Tua</option>
                                        <option value="pribadi">Milik Pribadi</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="domisili" class="form-label">Alamat Domisili</label>
                                    <select id="domisili" name="domisili" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="sesuai ktp">Sesuai KTP</option>
                                        <option value="tidak sesuai ktp">Tidak Sesuai KTP</option>
                                    </select>
                                </div>
                                <div class="form-group" id="alamat-lengkap-group" style="display: none;">
                                    <div class="form-group">
                                        <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                                        <textarea name="alamat_lengkap" id="alamat_lengkap" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="status_rumah" class="form-label">Status Rumah Domisili</label>
                                        <select id="status_rumah" name="status_rumah" class="form-select">
                                            <option value="">Pilih</option>
                                            <option value="sewa">Sewa</option>
                                            <option value="kontrak">Kontrak</option>
                                            <option value="milik orang tua">Milik Orang Tua</option>
                                            <option value="pribadi">Milik Pribadi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <div class="form-step">
                                <h5>Keluarga Nasabah</h5>
                                <div class="form-group">
                                    <label for="hubungan" class="form-label">Hubungan Keluarga</label>
                                    <select id="hubungan" name="hubungan" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="orang tua">Orang Tua</option>
                                        <option value="suami">Suami</option>
                                        <option value="istri">Istri</option>
                                        <option value="anak">Anak</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_keluarga" class="form-label">Nama Keluarga</label>
                                    <input type="text" id="nama_keluarga" name="nama_keluarga" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="bekerja" class="form-label">Apakah Bekerja ?</label>
                                    <select id="bekerja" name="bekerja" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="ya">Bekerja</option>
                                        <option value="tidak">Tidak Bekerja</option>
                                    </select>
                                </div>
                                <div id="pekerjaan-keluarga-group" style="display: none;">
                                    <div class="form-group">
                                        <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                                        <input type="text" id="nama_perusahaan" name="nama_perusahaan"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="jabatan" class="form-label">Jabatan</label>
                                        <input type="text" id="jabatan" name="jabatan" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="penghasilan" class="form-label">Penghasilan</label>
                                        <input type="number" id="penghasilan" name="penghasilan" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_kerja" class="form-label">Alamat Kerja</label>
                                        <input type="text" id="alamat_kerja" name="alamat_kerja"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp_keluarga" class="form-label">No HP/WA</label>
                                    <input type="text" id="no_hp_keluarga" name="no_hp_keluarga" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="kerabat_kerja" class="form-label">Memiliki Kerabat yg Kerja di Perusahaan
                                        yang sama ?</label>
                                    <select id="kerabat_kerja" name="kerabat_kerja" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="ya">Ya</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                                <div id="kerabat-kerja-group" style="display: none;">
                                    <div class="form-group">
                                        <label for="nama_kerabat" class="form-label">Nama Kerabat</label>
                                        <input type="text" id="nama_kerabat" name="nama_kerabat"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_kerabat" class="form-label">Alamat Kerabat</label>
                                        <textarea name="alamat_kerabat" id="alamat_kerabat" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_hp_kerabat" class="form-label">No HP/WA</label>
                                        <input type="text" id="no_hp_kerabat" name="no_hp_kerabat"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="status_hubungan_kerabat" class="form-label">Status Hubungan</label>
                                        <input type="text" id="status_hubungan_kerabat" name="status_hubungan_kerabat"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_perusahaan_kerabat" class="form-label">Perusahaan</label>
                                        <input type="text" id="nama_perusahaan_kerabat" name="nama_perusahaan_kerabat"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <!-- Step 2: Informasi Pekerjaan -->
                            <div class="form-step">
                                <h5>Informasi Pekerjaan</h5>
                                <div class="form-group">
                                    <label for="perusahaan" class="form-label">Perusahaan</label>
                                    <select id="perusahaan" name="perusahaan" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="FOTS">PT. Fresh On Time Seafood</option>
                                        <option value="ILW">PT. International Leather Works</option>
                                        <option value="ANI">PT. Andalan Nelayan Indonesia</option>
                                        <option value="TSII">PT. Tobasurimi Indonusantara</option>
                                        <option value="UBL">PT. Unggul Budi Lestari</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-7">
                                        <label for="divisi" class="form-label">Divisi</label>
                                        <input type="text" id="divisi" name="divisi" class="form-control"
                                            required>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="lama_kerja" class="form-label">Lama Kerja</label>
                                        <div class="row">
                                            <div class="col-md-6 d-flex align-items-center">
                                                <input type="number" name="lama_kerja_tahun" id="lama_kerja_tahun"
                                                    class="form-control me-1" min="0" max="100" required>
                                                <label for="lama_kerja_tahun" class="form-label mb-0 ms-2">Tahun</label>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center">
                                                <input type="number" name="lama_kerja_bulan" id="lama_kerja_bulan"
                                                    class="form-control me-1" min="0" max="11" required>
                                                <label for="lama_kerja_bulan" class="form-label mb-0 ms-2">Bulan</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="golongan" class="form-label">Golongan</label>
                                    <select id="golongan" name="golongan" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="HO">HO</option>
                                        <option value="HT">HT</option>
                                        <option value="HS">HS</option>
                                        <option value="HR">HR</option>
                                        <option value="HG">HG</option>
                                        <option value="HL">HL</option>
                                        <option value="Borongan">Borongan</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group" id="div-yayasan" style="display: none;">
                                    <label for="yayasan" class="form-label">Yayasan/Kantor</label>
                                    <input type="text" name="yayasan" id="yayasan" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="nama_atasan" class="form-label">Nama Atasan</label>
                                        <input type="text" name="nama_atasan" id="nama_atasan" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="nama_hrd" class="form-label">Nama HRD</label>
                                        <input type="text" name="nama_hrd" id="nama_hrd" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="absensi" class="form-label">Absensi</label>
                                    <input type="text" name="absensi" id="absensi" class="form-control">
                                </div>
                                @if (Auth::user()->usertype == 'marketing')
                                    <div class="form-group">
                                        <label for="foto_id_card" class="form-label">Foto ID Card</label>
                                        <input type="file" name="foto_id_card" id="foto_id_card" class="form-control"
                                            accept="image/*" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="bukti_absensi" class="form-label">Lampirkan Bukti Absensi</label>
                                        <input type="file" name="bukti_absensi" id="bukti_absensi"
                                            class="form-control" accept="image/*">
                                    </div>
                                @endif
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <!-- Step 2: Pengajuan Pinjaman -->
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
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="tenor" class="form-label">Jangka Waktu</label>
                                    <input type="number" name="tenor" id="tenor" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="keperluan" class="form-label">Keperluan</label>
                                    <input type="text" id="keperluan" name="keperluan" class="form-control" required>
                                </div>
                                <div id="riwayat-pinjaman-group" style="display: none;">
                                    <h5>Riwayat Pinjaman</h5>
                                    <div class="form-group">
                                        <label for="pinjaman_ke">Pinjaman Ke</label>
                                        <input type="number" name="pinjaman_ke" id="pinjaman_ke" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="riwayat_nominal">Nominal Pinjaman</label>
                                        <input type="number" name="riwayat_nominal" id="riwayat_nominal"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="riwayat_tenor">Jangka Waktu</label>
                                        <input type="number" name="riwayat_tenor" id="riwayat_tenor"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="sisa_pinjaman">Sisa Pinjaman</label>
                                        <input type="number" name="sisa_pinjaman" id="sisa_pinjaman"
                                            class="form-control">
                                    </div>
                                </div>
                                @if (Auth::user()->usertype == 'marketing')
                                    <div class="form-group">
                                        <label for="foto_rekening" class="form-label">Foto Buku Rekening</label>
                                        <input type="file" name="foto_rekening" id="foto_rekening"
                                            class="form-control" accept="image/*">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="no_rekening" class="form-label">No Rekening</label>
                                        <input type="text" id="no_rekening" name="no_rekening" class="form-control">
                                    </div>
                                @endif

                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div>
                            </div>

                            <div class="form-step">
                                <h5>Informasi Jaminan Pinjaman</h5>
                                <div class="form-group">
                                    <label for="jaminan_hrd" class="form-label">Jaminan di HRD</label>
                                    <input type="text" id="jaminan_hrd" name="jaminan_hrd" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="jaminan_cg" class="form-label">Jaminan di Cash Gampang</label>
                                    <input type="text" name="jaminan_cg" id="jaminan_cg" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="penjamin" class="form-label">Memiliki Penjamin</label>
                                    <select name="penjamin" id="penjamin" class="form-select">
                                        <option value="">Pilih</option>
                                        <option value="ada">Ada</option>
                                        <option value="tidak">Tidak</option>
                                    </select>
                                </div>
                                <div id="penjamin-group">
                                    <div class="form-group mb-3">
                                        <label for="nama_penjamin" class="form-label">Nama Penjamin</label>
                                        <input type="text" id="nama_penjamin" name="nama_penjamin"
                                            class="form-control">
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-7">
                                            <label for="bagian" class="form-label">Divisi</label>
                                            <input type="text" id="bagian" name="bagian" class="form-control">
                                        </div>
                                        <div class="col-md-5">
                                            <label for="lama_kerja_penjamin" class="form-label">Lama Kerja</label>
                                            <input type="text" name="lama_kerja_penjamin" id="lama_kerja_penjamin"
                                                class="form-control me-1">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="absensi_penjamin">Absensi</label>
                                        <input type="text" name="absensi_penjamin" id="absensi_penjamin"
                                            class="form-control">
                                    </div>
                                    @if (Auth::user()->usertype == 'marketing')
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label for="foto_id_card_penjamin" class="form-label">Foto ID Card
                                                    Penjamin</label>
                                                <input type="file" name="foto_id_card_penjamin"
                                                    id="foto_id_card_penjamin" class="form-control" accept="image/*">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="foto_ktp_penjamin" class="form-label">Foto KTP
                                                    Pengajuan</label>
                                                <input type="file" name="foto_ktp_penjamin" id="foto_ktp_penjamin"
                                                    class="form-control" accept="image/*">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="riwayat_pinjam_penjamin" class="form-label">Memiliki Riwayat
                                            Pinjaman</label>
                                        <select name="riwayat_pinjam_penjamin" id="riwayat_pinjam_penjamin"
                                            class="form-select">
                                            <option value="">Pilih</option>
                                            <option value="ada">Ada</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                    <div id="riwayat-penjamin-group">
                                        <div class="form-group">
                                            <label for="riwayat_nominal_penjamin">Nominal Pinjaman</label>
                                            <input type="number" name="riwayat_nominal_penjamin"
                                                id="riwayat_nominal_penjamin" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="riwayat_tenor_penjamin">Tenor Pinjaman</label>
                                            <input type="number" name="riwayat_tenor_penjamin"
                                                id="riwayat_tenor_penjamin" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="sisa_pinjaman_penjamin">Sisa Pinjaman</label>
                                            <input type="number" name="sisa_pinjaman_penjamin"
                                                id="sisa_pinjaman_penjamin" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="jaminan_cg_penjamin">Jaminan di Cash Gampang</label>
                                            <input type="text" name="jaminan_cg_penjamin" id="jaminan_cg_penjamin"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status_hubungan_penjamin">Status Hubungan</label>
                                        <input type="text" name="status_hubungan_penjamin"
                                            id="status_hubungan_penjamin" class="form-control">
                                    </div>
                                </div>

                                <h5>Keterangan</h5>
                                <div class="form-group">
                                    <label for="notes" class="form-label">Catatan Marketing</label>
                                    <textarea name="notes" id="notes" class="form-control" style="width: 100%; height: 150px;" required></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const domisiliSelect = document.getElementById('domisili');
            const bekerjaSelect = document.getElementById('bekerja');
            const kerabatSelect = document.getElementById('kerabat_kerja');
            const golonganSelect = document.getElementById('golongan');
            const statusPinjamanSelect = document.getElementById('status_pinjaman');
            const penjaminSelect = document.getElementById('penjamin');
            const riwayatPinjamPenjaminSelect = document.getElementById('riwayat_pinjam_penjamin');

            const alamatLengkapGroup = document.getElementById('alamat-lengkap-group');
            const pekerjaanKeluargaGroup = document.getElementById('pekerjaan-keluarga-group');
            const kerabatKerjaGroup = document.getElementById('kerabat-kerja-group');
            const divYayasan = document.getElementById('div-yayasan');
            const riwayatPinjamanGroup = document.getElementById('riwayat-pinjaman-group');
            const penjaminGroup = document.getElementById('penjamin-group');
            const riwayatPenjaminGroup = document.getElementById('riwayat-penjamin-group');

            // Fungsi untuk mengatur tampilan alamat lengkap
            function toggleAlamatLengkap() {
                if (domisiliSelect.value === 'tidak sesuai ktp') {
                    alamatLengkapGroup.style.display = 'block'; // Tampilkan alamat lengkap
                } else {
                    alamatLengkapGroup.style.display = 'none'; // Sembunyikan alamat lengkap
                }
            }

            // Fungsi untuk mengatur tampilan pekerjaan keluarga
            function togglePekerjaanKeluarga() {
                if (bekerjaSelect.value === 'ya') {
                    pekerjaanKeluargaGroup.style.display = 'block'; // Tampilkan pekerjaan keluarga
                } else {
                    pekerjaanKeluargaGroup.style.display = 'none'; // Sembunyikan pekerjaan keluarga
                }
            }

            // Fungsi untuk mengatur tampilan kerabat kerja
            function toggleKerabatKerja() {
                if (kerabatSelect.value === 'ya') {
                    kerabatKerjaGroup.style.display = 'block'; // Tampilkan kerabat kerja
                } else {
                    kerabatKerjaGroup.style.display = 'none'; // Sembunyikan kerabat kerja
                }
            }

            // Fungsi untuk mengatur tampilan div yayasan
            function toggleDivYayasan() {
                if (golonganSelect.value === 'Borongan') {
                    divYayasan.style.display = 'block'; // Tampilkan div yayasan
                } else {
                    divYayasan.style.display = 'none'; // Sembunyikan div yayasan
                }
            }

            // Fungsi untuk mengatur tampilan riwayat pinjaman
            function toggleRiwayatPinjaman() {
                if (statusPinjamanSelect.value === 'lama') {
                    riwayatPinjamanGroup.style.display = 'block'; // Tampilkan riwayat pinjaman
                } else {
                    riwayatPinjamanGroup.style.display = 'none'; // Sembunyikan riwayat pinjaman
                }
            }

            // Fungsi untuk mengatur tampilan penjamin
            function togglePenjamin() {
                if (penjaminSelect.value === 'ada') {
                    penjaminGroup.style.display = 'block'; // Tampilkan penjamin
                } else {
                    penjaminGroup.style.display = 'none'; // Sembunyikan penjamin
                }
            }

            // Fungsi untuk mengatur tampilan riwayat penjamin
            function toggleRiwayatPenjamin() {
                if (riwayatPinjamPenjaminSelect.value === 'ada') {
                    riwayatPenjaminGroup.style.display = 'block'; // Tampilkan riwayat penjamin
                } else {
                    riwayatPenjaminGroup.style.display = 'none'; // Sembunyikan riwayat penjamin
                }
            }

            // Tambahkan event listener untuk dropdown
            domisiliSelect.addEventListener('change', toggleAlamatLengkap);
            bekerjaSelect.addEventListener('change', togglePekerjaanKeluarga);
            kerabatSelect.addEventListener('change', toggleKerabatKerja);
            golonganSelect.addEventListener('change', toggleDivYayasan);
            statusPinjamanSelect.addEventListener('change', toggleRiwayatPinjaman);
            penjaminSelect.addEventListener('change', togglePenjamin);
            riwayatPinjamPenjaminSelect.addEventListener('change', toggleRiwayatPenjamin);

            // Panggil fungsi untuk mengatur tampilan saat halaman dimuat
            toggleAlamatLengkap();
            togglePekerjaanKeluarga();
            toggleKerabatKerja();
            toggleDivYayasan();
            toggleRiwayatPinjaman();
            togglePenjamin();
            toggleRiwayatPenjamin();
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
