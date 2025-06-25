@extends('layouts.parent-layout')

@section('page-title')
    <a href="{{ url()->previous() }}" style="text-decoration: none" class="text-dark">Data Pengajuan</a>
@endsection
@section('breadcrumb-title', '/ Form Edit')

@section('content')

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
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
                        <form id="loanForm" action="{{ route('superAdmin.list.pengajuan.update', $nasabah->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Step 1: Informasi Nasabah -->
                            <div class="form-step form-step-active">
                                <h5 class="mb-3">Informasi Nasabah</h5>
                                <div class="form-group">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control"
                                        value="{{ $nasabah->nama_lengkap }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_ktp" class="form-label">Nomor KTP</label>
                                    <input type="text" id="no_ktp" name="no_ktp" class="form-control"
                                        value="{{ $nasabah->no_ktp }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki"
                                            {{ $nasabah->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                        </option>
                                        <option value="Perempuan"
                                            {{ $nasabah->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                        </option>
                                    </select>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control"
                                            value="{{ $nasabah->tempat_lahir }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control"
                                            value="{{ $nasabah->tanggal_lahir }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status_nikah" class="form-label">Status Pernikahan</label>
                                    <select name="status_nikah" id="status_nikah" class="form-select" required>
                                        <option value="">Pilih Status Pernikahan</option>
                                        <option value="Belum Menikah"
                                            {{ $nasabah->status_nikah == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah
                                        </option>
                                        <option value="Menikah"
                                            {{ $nasabah->status_nikah == 'Menikah' ? 'selected' : '' }}>
                                            Menikah</option>
                                        <option value="Janda/Duda"
                                            {{ $nasabah->status_nikah == 'Janda/Duda' ? 'selected' : '' }}>Janda/Duda
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="no_hp" class="form-label">Nomor HP/WA</label>
                                        <input type="text" id="no_hp" name="no_hp" class="form-control"
                                            value="{{ $nasabah->no_hp }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{ $nasabah->email ?? 'Tidak ada email' }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="foto_ktp" class="form-label">Foto KTP</label>
                                        @if ($nasabah->foto_ktp)
                                            <div class="mb-2">
                                                <small>File saat ini: {{ $nasabah->foto_ktp }}</small>
                                            </div>
                                        @endif
                                        <input type="file" id="foto_ktp" name="foto_ktp" class="form-control"
                                            accept="image/*">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="foto_kk" class="form-label">Foto Kartu Keluarga</label>
                                        @if ($nasabah->foto_kk)
                                            <div class="mb-2">
                                                <small>File saat ini: {{ $nasabah->foto_kk }}</small>
                                            </div>
                                        @endif
                                        <input type="file" id="foto_kk" name="foto_kk" class="form-control"
                                            accept="image/*">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
                                    </div>
                                </div>

                                {{-- <div class="text-end">
                                    <button type="button" class="btn btn-primary next-btn">Next</button>
                                </div> --}}
                            </div>

                            <div class="form-step">
                                <h5>Alamat Nasabah</h5>
                                <div class="form-group">
                                    <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                                    <input type="text" id="alamat_ktp" name="alamat_ktp" class="form-control"
                                        value="{{ $nasabah->alamat->alamat_ktp }}" required>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="kelurahan" class="form-label">Kelurahan</label>
                                        <input type="text" id="kelurahan" name="kelurahan" class="form-control"
                                            value="{{ $nasabah->alamat->kelurahan }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="rt_rw" class="form-label">RT/RW</label>
                                        <input type="text" id="rt_rw" name="rt_rw" class="form-control"
                                            value="{{ $nasabah->alamat->rt_rw }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="kecamatan" class="form-label">Kecamatan</label>
                                    <input type="text" id="kecamatan" name="kecamatan" class="form-control"
                                        value="{{ $nasabah->alamat->kecamatan }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="kota" class="form-label">Kabupaten/Kota</label>
                                    <input type="text" id="kota" name="kota" class="form-control"
                                        value="{{ $nasabah->alamat->kota }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <input type="text" id="provinsi" name="provinsi" class="form-control"
                                        value="{{ $nasabah->alamat->provinsi }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="domisili" class="form-label">Alamat Domisili</label>
                                    <select id="domisili" name="domisili" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="sesuai ktp"
                                            {{ $nasabah->alamat->domisili == 'sesuai ktp' ? 'selected' : '' }}>Sesuai KTP
                                        </option>
                                        <option value="tidak sesuai ktp"
                                            {{ $nasabah->alamat->domisili == 'tidak sesuai ktp' ? 'selected' : '' }}>Tidak
                                            Sesuai KTP</option>
                                    </select>
                                </div>
                                <div class="form-group" id="alamat-lengkap-group" style="display: none;">
                                    <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                                    <textarea name="alamat_lengkap" id="alamat_lengkap" rows="4" class="form-control">{{ $nasabah->alamat->alamat_lengkap }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="status_rumah" class="form-label">Status Rumah</label>
                                    <select id="status_rumah" name="status_rumah" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="sewa"
                                            {{ $nasabah->alamat->status_rumah == 'sewa' ? 'selected' : '' }}>Sewa</option>
                                        <option value="kontrak"
                                            {{ $nasabah->alamat->status_rumah == 'kontrak' ? 'selected' : '' }}>Kontrak
                                        </option>
                                        <option value="milik orang tua"
                                            {{ $nasabah->alamat->status_rumah == 'milik orang tua' ? 'selected' : '' }}>
                                            Milik Orang Tua</option>
                                        <option value="pribadi"
                                            {{ $nasabah->alamat->status_rumah == 'pribadi' ? 'selected' : '' }}>Milik
                                            Pribadi</option>
                                    </select>
                                </div>
                                {{-- <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div> --}}
                            </div>

                            <div class="form-step">
                                <h5>Keluarga Nasabah</h5>
                                <div class="form-group">
                                    <label for="hubungan" class="form-label">Hubungan Keluarga</label>
                                    <select id="hubungan" name="hubungan" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="orang tua"
                                            {{ $nasabah->keluarga->hubungan == 'orang tua' ? 'selected' : '' }}>Orang Tua
                                        </option>
                                        <option value="suami"
                                            {{ $nasabah->keluarga->hubungan == 'suami' ? 'selected' : '' }}>
                                            Suami</option>
                                        <option value="istri"
                                            {{ $nasabah->keluarga->hubungan == 'istri' ? 'selected' : '' }}>
                                            Istri</option>
                                        <option value="anak"
                                            {{ $nasabah->keluarga->hubungan == 'anak' ? 'selected' : '' }}>Anak</option>
                                        <option value="lainnya"
                                            {{ $nasabah->keluarga->hubungan == 'lainnya' ? 'selected' : '' }}>Lainnya
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_keluarga" class="form-label">Nama Keluarga</label>
                                    <input type="text" id="nama_keluarga" name="nama_keluarga" class="form-control"
                                        value="{{ $nasabah->keluarga->nama }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="bekerja" class="form-label">Apakah Bekerja ?</label>
                                    <select id="bekerja" name="bekerja" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="ya"
                                            {{ $nasabah->keluarga->bekerja == 'ya' ? 'selected' : '' }}>Bekerja</option>
                                        <option value="tidak"
                                            {{ $nasabah->keluarga->bekerja == 'tidak' ? 'selected' : '' }}>Tidak Bekerja
                                        </option>
                                    </select>
                                </div>
                                <div id="pekerjaan-keluarga-group" style="display: none;">
                                    <div class="form-group">
                                        <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                                        <input type="text" id="nama_perusahaan" name="nama_perusahaan"
                                            class="form-control" value="{{ $nasabah->keluarga->nama_perusahaan ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="jabatan" class="form-label">Jabatan</label>
                                        <input type="text" id="jabatan" name="jabatan" class="form-control"
                                            value="{{ $nasabah->keluarga->jabatan ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="penghasilan" class="form-label">Penghasilan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-gradient-primary text-white">Rp.</span>
                                            </div>
                                            <input type="number" id="penghasilan" name="penghasilan"
                                                class="form-control" value="{{ $nasabah->keluarga->penghasilan ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_kerja" class="form-label">Alamat Kerja</label>
                                        <input type="text" id="alamat_kerja" name="alamat_kerja" class="form-control"
                                            value="{{ $nasabah->keluarga->alamat_kerja ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="no_hp_keluarga" class="form-label">No HP/WA</label>
                                    <input type="text" id="no_hp_keluarga" name="no_hp_keluarga" class="form-control"
                                        value="{{ $nasabah->keluarga->no_hp ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="kerabat_kerja" class="form-label">Memiliki Kerabat yg Kerja di Perusahaan
                                        yang sama ?</label>
                                    <select id="kerabat_kerja" name="kerabat_kerja" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="ya"
                                            {{ $nasabah->kerabat->kerabat_kerja == 'ya' ? 'selected' : '' }}>Ya</option>
                                        <option value="tidak"
                                            {{ $nasabah->kerabat->kerabat_kerja == 'tidak' ? 'selected' : '' }}>Tidak
                                        </option>
                                    </select>
                                </div>
                                <div id="kerabat-kerja-group" style="display: none;">
                                    <div class="form-group">
                                        <label for="nama_kerabat" class="form-label">Nama Kerabat</label>
                                        <input type="text" id="nama_kerabat" name="nama_kerabat" class="form-control"
                                            value="{{ $nasabah->kerabat->nama ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat_kerabat" class="form-label">Alamat Kerabat</label>
                                        <textarea name="alamat_kerabat" id="alamat_kerabat" rows="4" class="form-control">{{ $nasabah->kerabat->alamat ?? '' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_hp_kerabat" class="form-label">No HP/WA</label>
                                        <input type="text" id="no_hp_kerabat" name="no_hp_kerabat"
                                            class="form-control" value="{{ $nasabah->kerabat->no_hp ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="status_hubungan_kerabat" class="form-label">Status Hubungan</label>
                                        <input type="text" id="status_hubungan_kerabat" name="status_hubungan_kerabat"
                                            class="form-control" value="{{ $nasabah->kerabat->status_hubungan ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_perusahaan_kerabat" class="form-label">Perusahaan</label>
                                        <input type="text" id="nama_perusahaan_kerabat" name="nama_perusahaan_kerabat"
                                            class="form-control" value="{{ $nasabah->kerabat->nama_perusahaan ?? '' }}">
                                    </div>
                                </div>

                                {{-- <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div> --}}
                            </div>

                            <!-- Step 2: Informasi Pekerjaan -->
                            <div class="form-step">
                                <h5>Informasi Pekerjaan</h5>
                                <div class="form-group">
                                    <label for="perusahaan" class="form-label">Perusahaan</label>
                                    <select id="perusahaan" name="perusahaan" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="FOTS"
                                            {{ $nasabah->pekerjaan->perusahaan == 'FOTS' ? 'selected' : '' }}>PT. Fresh On
                                            Time Seafood</option>
                                        <option value="ILW"
                                            {{ $nasabah->pekerjaan->perusahaan == 'ILW' ? 'selected' : '' }}>PT.
                                            International Leather Works</option>
                                        <option value="ANI"
                                            {{ $nasabah->pekerjaan->perusahaan == 'ANI' ? 'selected' : '' }}>PT. Andalan
                                            Nelayan Indonesia</option>
                                        <option value="TSII"
                                            {{ $nasabah->pekerjaan->perusahaan == 'TSII' ? 'selected' : '' }}>PT.
                                            Tobasurimi Indonusantara</option>
                                        <option value="UBL"
                                            {{ $nasabah->pekerjaan->perusahaan == 'UBL' ? 'selected' : '' }}>PT.
                                            Unggul Budi Lestari</option>
                                        <option value="Lainnya"
                                            {{ $nasabah->pekerjaan->perusahaan == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                        </option>
                                    </select>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-7">
                                        <label for="divisi" class="form-label">Divisi</label>
                                        <input type="text" id="divisi" name="divisi" class="form-control"
                                            value="{{ $nasabah->pekerjaan->divisi ?? '' }}" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="lama_kerja" class="form-label">Lama Kerja</label>
                                        <div class="row">
                                            <div class="col-md-6 d-flex align-items-center">
                                                <input type="number" name="lama_kerja_tahun" id="lama_kerja_tahun"
                                                    class="form-control me-1"
                                                    value="{{ $nasabah->pekerjaan->lama_kerja_tahun ?? '0' }}" required>
                                                <label for="lama_kerja_tahun" class="form-label mb-0 ms-2">Tahun</label>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center">
                                                <input type="number" name="lama_kerja_bulan" id="lama_kerja_bulan"
                                                    class="form-control me-1"
                                                    value="{{ $nasabah->pekerjaan->lama_kerja_bulan ?? '0' }}" required>
                                                <label for="lama_kerja_bulan" class="form-label mb-0 ms-2">Bulan</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="golongan" class="form-label">Golongan</label>
                                    <select id="golongan" name="golongan" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="HO"
                                            {{ $nasabah->pekerjaan->golongan == 'HO' ? 'selected' : '' }}>HO</option>
                                        <option value="HT"
                                            {{ $nasabah->pekerjaan->golongan == 'HT' ? 'selected' : '' }}>HT</option>
                                        <option value="HS"
                                            {{ $nasabah->pekerjaan->golongan == 'HS' ? 'selected' : '' }}>HS</option>
                                        <option value="HR"
                                            {{ $nasabah->pekerjaan->golongan == 'HR' ? 'selected' : '' }}>HR</option>
                                        <option value="HG"
                                            {{ $nasabah->pekerjaan->golongan == 'HG' ? 'selected' : '' }}>HG</option>
                                        <option value="HL"
                                            {{ $nasabah->pekerjaan->golongan == 'HL' ? 'selected' : '' }}>HL</option>
                                        <option value="Borongan"
                                            {{ $nasabah->pekerjaan->golongan == 'Borongan' ? 'selected' : '' }}>Borongan
                                        </option>
                                        <option value="Lainnya"
                                            {{ $nasabah->pekerjaan->golongan == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" id="div-yayasan" style="display: none;">
                                    <label for="yayasan" class="form-label">Yayasan/Kantor</label>
                                    <input type="text" name="yayasan" id="yayasan" class="form-control"
                                        value="{{ $nasabah->pekerjaan->yayasan ?? '' }}">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="nama_atasan" class="form-label">Nama Atasan</label>
                                        <input type="text" name="nama_atasan" id="nama_atasan" class="form-control"
                                            value="{{ $nasabah->pekerjaan->nama_atasan ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="nama_hrd" class="form-label">Nama HRD</label>
                                        <input type="text" name="nama_hrd" id="nama_hrd" class="form-control"
                                            value="{{ $nasabah->pekerjaan->nama_hrd ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="absensi" class="form-label">Absensi</label>
                                    <input type="text" name="absensi" id="absensi" class="form-control"
                                        value="{{ $nasabah->pekerjaan->absensi ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="foto_id_card" class="form-label">Foto ID Card</label>
                                    @if ($nasabah->foto_id_card)
                                        <div class="mb-2">
                                            <small>File saat ini: {{ $nasabah->foto_id_card }}</small>
                                        </div>
                                    @endif
                                    <input type="file" id="foto_id_card" name="foto_id_card" class="form-control"
                                        accept="image/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
                                </div>
                                {{-- <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div> --}}
                            </div>

                            <!-- Step 2: Pengajuan Pinjaman -->
                            <div class="form-step">
                                <h5>Informasi Pengajuan Pinjaman</h5>
                                <div class="form-group">
                                    <label for="status_pinjaman">Status Pinjaman</label>
                                    <select name="status_pinjaman" id="status_pinjaman" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="baru"
                                            {{ $nasabah->pengajuan->status_pinjaman == 'baru' ? 'selected' : '' }}>Baru
                                        </option>
                                        <option value="lama"
                                            {{ $nasabah->pengajuan->status_pinjaman == 'lama' ? 'selected' : '' }}>Lama
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nominal_pinjaman" class="form-label">Nominal Pinjaman</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-primary text-white">Rp.</span>
                                        </div>
                                        <input type="number" id="nominal_pinjaman" name="nominal_pinjaman"
                                            class="form-control"
                                            value="{{ $nasabah->pengajuan->nominal_pinjaman ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tenor" class="form-label">Jangka Waktu</label>
                                    <input type="number" name="tenor" id="tenor" class="form-control"
                                        value="{{ $nasabah->pengajuan->tenor ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="keperluan" class="form-label">Keperluan</label>
                                    <input type="text" id="keperluan" name="keperluan" class="form-control"
                                        value="{{ $nasabah->pengajuan->keperluan ?? '' }}" required>
                                </div>
                                <div id="riwayat-pinjaman-group" style="display: none;">
                                    <h5>Riwayat Pinjaman</h5>
                                    <div class="form-group">
                                        <label for="pinjaman_ke">Pinjaman Ke</label>
                                        <input type="number" name="pinjaman_ke" id="pinjaman_ke" class="form-control"
                                            value="{{ $nasabah->pengajuan->pinjaman_ke ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="riwayat_nominal">Nominal Pinjaman</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-gradient-primary text-white">Rp.</span>
                                            </div>
                                            <input type="number" name="riwayat_nominal" id="riwayat_nominal"
                                                class="form-control"
                                                value="{{ $nasabah->pengajuan->riwayat_nominal ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="riwayat_tenor">Jangka Waktu</label>
                                        <input type="number" name="riwayat_tenor" id="riwayat_tenor"
                                            class="form-control" value="{{ $nasabah->pengajuan->riwayat_tenor ?? '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="sisa_pinjaman">Sisa Pinjaman</label>
                                        <input type="number" name="sisa_pinjaman" id="sisa_pinjaman"
                                            class="form-control" value="{{ $nasabah->pengajuan->sisa_pinjaman ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foto_rekening" class="form-label">Foto Buku Rekening</label>
                                    @if ($nasabah->foto_rekening)
                                        <div class="mb-2">
                                            <small>File saat ini: {{ $nasabah->foto_rekening }}</small>
                                        </div>
                                    @endif
                                    <input type="file" id="foto_rekening" name="foto_rekening" class="form-control"
                                        accept="image/*">
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
                                </div>

                                <div class="form-group"><label for="no_rekening" class="form-label">No
                                        Rekening</label>
                                    <input type="text" name="no_rekening" id="no_rekening" class="form-control"
                                        value="{{ $nasabah->pengajuan->no_rekening ?? '' }}">
                                </div>

                                {{-- <div class="text-end">
                                    <button type="button" class="btn btn-secondary prev-btn">Previous</button>
                                    <button type="submit" class="btn btn-primary next-btn">Next</button>
                                </div> --}}
                            </div>

                            <div class="form-step">
                                <h5>Informasi Jaminan Pinjaman</h5>
                                <div class="form-group">
                                    <label for="jaminan_hrd" class="form-label">Jaminan di HRD</label>
                                    <input type="text" id="jaminan_hrd" name="jaminan_hrd" class="form-control"
                                        value="{{ $nasabah->jaminan->jaminan_hrd ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="jaminan_cg" class="form-label">Jaminan di Cash Gampang</label>
                                    <input type="text" name="jaminan_cg" id="jaminan_cg" class="form-control"
                                        value="{{ $nasabah->jaminan->jaminan_cg ?? '' }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="penjamin" class="form-label">Memiliki Penjamin</label>
                                    <select name="penjamin" id="penjamin" class="form-select" required>
                                        <option value="">Pilih</option>
                                        <option value="ada"
                                            {{ $nasabah->jaminan->penjamin == 'ada' ? 'selected' : '' }}>Ada</option>
                                        <option value="tidak"
                                            {{ $nasabah->jaminan->penjamin == 'tidak' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                </div>
                                <div id="penjamin-group">
                                    <div class="form-group mb-3">
                                        <label for="nama_penjamin" class="form-label">Nama Penjamin</label>
                                        <input type="text" id="nama_penjamin" name="nama_penjamin"
                                            class="form-control" value="{{ $nasabah->jaminan->nama_penjamin ?? '' }}">
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-7">
                                            <label for="bagian" class="form-label">Divisi</label>
                                            <input type="text" id="bagian" name="bagian" class="form-control"
                                                value="{{ $nasabah->jaminan->bagian ?? '' }}">
                                        </div>
                                        <div class="col-md-5">
                                            <label for="lama_kerja_penjamin" class="form-label">Lama Kerja</label>
                                            <input type="text" name="lama_kerja_penjamin" id="lama_kerja_penjamin"
                                                class="form-control me-1"
                                                value="{{ $nasabah->jaminan->lama_kerja_penjamin ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="absensi_penjamin">Absensi</label>
                                        <input type="text" name="absensi_penjamin" id="absensi_penjamin"
                                            class="form-control" value="{{ $nasabah->jaminan->absensi ?? '' }}">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="foto_ktp_penjamin" class="form-label">Foto KTP
                                                Penjamin</label>
                                            @if ($nasabah->foto_ktp_penjamin)
                                                <div class="mb-2">
                                                    <small>File saat ini: {{ $nasabah->foto_ktp_penjamin }}</small>
                                                </div>
                                            @endif
                                            <input type="file" id="foto_ktp_penjamin" name="foto_ktp_penjamin"
                                                class="form-control" accept="image/*">
                                            <small class="text-muted">Kosongkan jika tidak ingin mengganti
                                                file.</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="foto_id_card_penjamin" class="form-label">Foto ID Card
                                                Penjamin</label>
                                            @if ($nasabah->foto_id_card_penjamin)
                                                <div class="mb-2">
                                                    <small>File saat ini: {{ $nasabah->foto_id_card_penjamin }}</small>
                                                </div>
                                            @endif
                                            <input type="file" id="foto_id_card_penjamin" name="foto_id_card_penjamin"
                                                class="form-control" accept="image/*">
                                            <small class="text-muted">Kosongkan jika tidak ingin mengganti
                                                file.</small>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="riwayat_pinjam_penjamin" class="form-label">Memiliki Riwayat
                                            Pinjaman</label>
                                        <select name="riwayat_pinjam_penjamin" id="riwayat_pinjam_penjamin"
                                            class="form-select">
                                            <option value="">Pilih</option>
                                            <option value="ada"
                                                {{ $nasabah->jaminan->riwayat_pinjam_penjamin == 'ada' ? 'selected' : '' }}>
                                                Ada</option>
                                            <option value="tidak"
                                                {{ $nasabah->jaminan->riwayat_pinjam_penjamin == 'tidak' ? 'selected' : '' }}>
                                                Tidak</option>
                                        </select>
                                    </div>
                                    <div id="riwayat-penjamin-group">
                                        <div class="form-group">
                                            <label for="riwayat_nominal_penjamin">Nominal Pinjaman</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span
                                                        class="input-group-text bg-gradient-primary text-white">Rp.</span>
                                                </div>
                                                <input type="number" name="riwayat_nominal_penjamin"
                                                    id="riwayat_nominal_penjamin" class="form-control"
                                                    value="{{ $nasabah->jaminan->riwayat_nominal_penjamin ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="riwayat_tenor_penjamin">Tenor Pinjaman</label>
                                            <input type="number" name="riwayat_tenor_penjamin"
                                                id="riwayat_tenor_penjamin" class="form-control"
                                                value="{{ $nasabah->jaminan->riwayat_tenor_penjamin ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="sisa_pinjaman_penjamin">Sisa Pinjaman</label>
                                            <input type="number" name="sisa_pinjaman_penjamin"
                                                id="sisa_pinjaman_penjamin" class="form-control"
                                                value="{{ $nasabah->jaminan->sisa_pinjaman_penjamin ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="jaminan_cg_penjamin">Jaminan di Cash Gampang</label>
                                            <input type="text" name="jaminan_cg_penjamin" id="jaminan_cg_penjamin"
                                                class="form-control"
                                                value="{{ $nasabah->jaminan->jaminan_cg_penjamin ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status_hubungan_penjamin">Status Hubungan</label>
                                        <input type="text" name="status_hubungan_penjamin"
                                            id="status_hubungan_penjamin" class="form-control"
                                            value="{{ $nasabah->jaminan->status_hubungan_penjamin ?? '' }}">
                                    </div>
                                </div>

                                <h5>Keterangan</h5>
                                <div class="form-group">
                                    <label for="notes" class="form-label">Catatan Marketing</label>
                                    <textarea name="notes" id="notes" class="form-control" style="width: 100%; height: 150px;" required>{{ $nasabah->pengajuan->notes }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="status" class="form-label">Status Pengajuan</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Pilih Status</option>
                                        <option value="pending"
                                            {{ $nasabah->pengajuan->status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="aproved spv"
                                            {{ $nasabah->pengajuan->status == 'aproved spv' ? 'selected' : '' }}>Approved
                                            SPV</option>
                                        <option value="rejected spv"
                                            {{ $nasabah->pengajuan->status == 'rejected spv' ? 'selected' : '' }}>Rejected
                                            SPV</option>
                                        <option value="aproved ca"
                                            {{ $nasabah->pengajuan->status == 'aproved ca' ? 'selected' : '' }}>Approved
                                            CA</option>
                                        <option value="rejected ca"
                                            {{ $nasabah->pengajuan->status == 'rejected ca' ? 'selected' : '' }}>Rejected
                                            CA</option>
                                        <option value="aproved head"
                                            {{ $nasabah->pengajuan->status == 'aproved head' ? 'selected' : '' }}>
                                            Approved Head</option>
                                        <option value="rejected head"
                                            {{ $nasabah->pengajuan->status == 'rejected head' ? 'selected' : '' }}>
                                            Rejected Head</option>
                                        <option value="banding"
                                            {{ $nasabah->pengajuan->status == 'banding' ? 'selected' : '' }}>Banding
                                        </option>
                                        <option value="approved banding ca"
                                            {{ $nasabah->pengajuan->status == 'approved banding ca' ? 'selected' : '' }}>
                                            Approved Banding CA</option>
                                        <option value="rejected banding ca"
                                            {{ $nasabah->pengajuan->status == 'rejected banding ca' ? 'selected' : '' }}>
                                            Rejected Banding CA</option>
                                        <option value="approved banding head"
                                            {{ $nasabah->pengajuan->status == 'approved banding head' ? 'selected' : '' }}>
                                            Approved Banding Head</option>
                                        <option value="rejected banding head"
                                            {{ $nasabah->pengajuan->status == 'rejected banding head' ? 'selected' : '' }}>
                                            Rejected Banding Head</option>
                                    </select>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Izinkan Mengedit</label>
                                    <div class="col-sm-2">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="enable_edit"
                                                    value="1" {{ $nasabah->enable_edit ? 'checked' : '' }}>
                                                Ya
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="enable_edit"
                                                    value="0" {{ !$nasabah->enable_edit ? 'checked' : '' }}>
                                                Tidak
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div class="text-first">
                                        <a href="{{ route('superAdmin.list.pengajuan') }}"
                                            class="btn btn-secondary rounded">Kembali</a>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-gradient-success rounded">Update</button>
                                    </div>
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
    </script>

@endsection
