@extends('layouts.parent-layout')

@section('breadcrumb-title', '/ Detail Hasil Survey')
@section('page-title', 'Daftar Survey Pengajuan Nasabah Luar')

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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-success btn-sm">Back</a>
                    <h4 class="card-title text-center mt-2 mb-2">Detail Hasil Survey</h4>
                    <div style="width: 80px;"></div>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="nama_nasabah">Nama Nasabah</label>
                        <input type="text" id="nama_nasabah" name="nama_nasabah" class="form-control"
                            value="{{ Str::title($pengajuan->nasabahLuar->nama_lengkap) }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="berjumpa_siapa">Berjumpa dengan siapa?</label>
                        <input type="text" id="berjumpa_siapa" name="berjumpa_siapa" class="form-control"
                            value="{{ $pengajuan->hasilSurvey->berjumpa_siapa ?? 'Tidak Ada' }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="hubungan_jumpa">Hubungan dengan nasabah?</label>
                        <input type="text" id="hubungan_jumpa" name="hubungan_jumpa" class="form-control"
                            value="{{ $pengajuan->hasilSurvey->hubungan ?? 'Tidak Ada' }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status_rumah">Status Rumah</label>
                        <input type="text" id="status_rumah" name="status_rumah" class="form-control"
                            value="{{ $pengajuan->hasilSurvey->status_rumah ?? 'Tidak Ada' }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="hasil_cekling1">Hasil Cek Lingkungan 1</label>
                        <input type="text" id="hasil_cekling1" name="hasil_cekling1" class="form-control"
                            value="{{ $pengajuan->hasilSurvey->hasil_cekling1 ?? 'Tidak Ada' }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="hasil_cekling2">Hasil Cek Lingkungan 2</label>
                        <input type="text" id="hasil_cekling2" name="hasil_cekling2" class="form-control"
                            value="{{ $pengajuan->hasilSurvey->hasil_cekling2 ?? 'Tidak Ada' }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="kesimpulan">Kesimpulan</label>
                        <textarea name="kesimpulan" id="kesimpulan" cols="30" rows="10" class="form-control" readonly>{{ $pengajuan->hasilSurvey->kesimpulan ?? 'Tidak Ada' }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="rekomendasi">Rekomendasi</label>
                        <textarea name="rekomendasi" id="rekomendasi" cols="30" rows="10" class="form-control" readonly>{{ $pengajuan->hasilSurvey->rekomendasi ?? 'Tidak Ada' }}</textarea>
                    </div>

                    @php
                        $namaNasabah = str_replace(' ', '-', strtolower($pengajuan->nasabahLuar->nama_lengkap));
                        $folderNasabah = $namaNasabah . '-' . $pengajuan->nasabahLuar->id;
                    @endphp

                    <dvi class="form-group mb-3">
                        <div class="row">
                            @forelse ($pengajuan->hasilSurvey->fotoHasilSurvey ?? [] as $item)
                                <div class="col-md-3 mb-3">
                                    <a href="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/hasil_survey/' . $item->foto_survey) }}"
                                        target="_blank">
                                        <img src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/hasil_survey/' . $item->foto_survey) }}"
                                            alt="Foto Survey" class="img-thumbnail"
                                            style="max-width: 100%; height: auto;"><br>
                                        <small>{{ $item->foto_survey }}</small>
                                    </a>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </dvi>
                </div>
            </div>
        </div>
    </div>
@endsection
