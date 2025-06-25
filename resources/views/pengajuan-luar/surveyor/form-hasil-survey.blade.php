@extends('layouts.parent-layout')

@section('breadcrumb-title', '/ Form Hasil Survey')
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
                    <a href="{{ route('surveyor.daftar.survey') }}" class="btn btn-success btn-sm">Back</a>
                    <h4 class="card-title text-center mt-2 mb-2">Form Hasil Survey</h4>
                    <div style="width: 80px;"></div>
                </div>
                <div class="card-body">
                    <form action="{{ route('surveyor.hasil.survey', $pengajuan->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Input Nasabah Info -->
                        <div class="form-group mb-3">
                            <label for="nama_nasabah">Nama Nasabah</label>
                            <input type="text" id="nama_nasabah" name="nama_nasabah" class="form-control"
                                value="{{ Str::title($pengajuan->nasabahLuar->nama_lengkap) }}" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label for="berjumpa_siapa">Berjumpa dengan siapa?</label>
                            <input type="text" id="berjumpa_siapa" name="berjumpa_siapa" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hubungan_jumpa">Hubungan dengan nasabah?</label>
                            <input type="text" id="hubungan_jumpa" name="hubungan_jumpa" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status_rumah">Status Rumah</label>
                            <input type="text" id="status_rumah" name="status_rumah" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hasil_cekling1">Hasil Cek Lingkungan 1</label>
                            <input type="text" id="hasil_cekling1" name="hasil_cekling1" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hasil_cekling2">Hasil Cek Lingkungan 2</label>
                            <input type="text" id="hasil_cekling2" name="hasil_cekling2" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="kesimpulan">Kesimpulan</label>
                            <textarea name="kesimpulan" id="kesimpulan" cols="30" rows="10" class="form-control"></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="rekomendasi">Rekomendasi</label>
                            <textarea name="rekomendasi" id="rekomendasi" cols="30" rows="10" class="form-control"></textarea>
                        </div>

                        <!-- Upload Foto Dinamis -->
                        <div class="form-group mb-3">
                            <label for="foto_survey[]">Upload Foto Rumah</label>
                            <div id="foto-rumah-container">
                                <div class="input-group mb-2">
                                    <input type="file" name="foto_survey[]" class="form-control" required>
                                    <button type="button" class="btn btn-danger remove-photo"
                                        style="display: none;">Hapus</button>
                                </div>
                            </div>
                            <button type="button" id="add-photo" class="btn btn-success">Tambah Foto</button>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            {{-- <a href="{{ route('luar.ca-luar.index') }}" class="btn btn-secondary">Batal</a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-photo').addEventListener('click', function() {
            const container = document.getElementById('foto-rumah-container');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `
                <input type="file" name="foto_survey[]" class="form-control" required>
                <button type="button" class="btn btn-danger remove-photo">Hapus</button>
            `;
            container.appendChild(div);

            div.querySelector('.remove-photo').addEventListener('click', function() {
                div.remove();
            });
        });
    </script>
@endsection
