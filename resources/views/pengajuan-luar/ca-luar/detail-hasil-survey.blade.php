@extends('layouts.parent-layout')

@section('breadcrumb-title', '/ Detail Hasil Survey Pengajuan')
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
    <main id="main" class="main">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <a href="{{ url()->previous() }}" class="btn btn-success btn-sm">Back</a>
                        <h4 class="card-title text-center mt-2 mb-2">Form Detail Hasil Survey Pengajuan Pinjaman</h4>
                        <div style="width: 80px;"></div>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="nama_nasabah">Nama Nasabah</label>
                            <input type="text" id="nama_nasabah" name="nama_nasabah" class="form-control"
                                value="{{ Str::title($nasabah->nasabahLuar->nama_lengkap) }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="berjumpa_siapa">Berjumpa dengan siapa?</label>
                            <input type="text" id="berjumpa_siapa" name="berjumpa_siapa" class="form-control"
                                value="{{ $nasabah->hasilSurvey->berjumpa_siapa ?? 'Tidak Ada' }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hubungan_jumpa">Hubungan dengan nasabah?</label>
                            <input type="text" id="hubungan_jumpa" name="hubungan_jumpa" class="form-control"
                                value="{{ $nasabah->hasilSurvey->hubungan ?? 'Tidak Ada' }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status_rumah">Status Rumah</label>
                            <input type="text" id="status_rumah" name="status_rumah" class="form-control"
                                value="{{ $nasabah->hasilSurvey->status_rumah ?? 'Tidak Ada' }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hasil_cekling1">Hasil Cek Lingkungan 1</label>
                            <input type="text" id="hasil_cekling1" name="hasil_cekling1" class="form-control"
                                value="{{ $nasabah->hasilSurvey->hasil_cekling1 ?? 'Tidak Ada' }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hasil_cekling2">Hasil Cek Lingkungan 2</label>
                            <input type="text" id="hasil_cekling2" name="hasil_cekling2" class="form-control"
                                value="{{ $nasabah->hasilSurvey->hasil_cekling2 ?? 'Tidak Ada' }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="kesimpulan">Kesimpulan</label>
                            <textarea name="kesimpulan" id="kesimpulan" cols="30" rows="10" class="form-control" readonly>{{ $nasabah->hasilSurvey->kesimpulan ?? 'Tidak Ada' }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="rekomendasi">Rekomendasi</label>
                            <textarea name="rekomendasi" id="rekomendasi" cols="30" rows="10" class="form-control" readonly>{{ $nasabah->hasilSurvey->rekomendasi ?? 'Tidak Ada' }}</textarea>
                        </div>

                        @php
                            $namaNasabah = str_replace(' ', '-', strtolower($nasabah->nasabahLuar->nama_lengkap));
                            $folderNasabah = $namaNasabah . '-' . $nasabah->nasabahLuar->id;
                        @endphp

                        <dvi class="form-group mb-3">
                            <div class="row">
                                @forelse ($nasabah->hasilSurvey->fotoHasilSurvey ?? [] as $item)
                                    <div class="col-md-3 mb-3">
                                        <img src="{{ asset('storage/dokumen_pendukung_luar/' . $folderNasabah . '/hasil_survey/' . $item->foto_survey) }}"
                                            alt="Foto Survey" class="img-thumbnail"
                                            style="max-width: 100%; height: auto;"><br>
                                        <small>{{ $item->foto_survey ?? '' }}</small>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </dvi>

                        <div class="text-end">
                            <a href="{{ route('creditAnalystLuar.approval.form', $nasabah->id) }}"
                                class="btn btn-primary">Next</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
