@extends('layouts.parent-layout')

@section('breadcrumb-title', '/ Form Survey')
@section('page-title', 'Pengajuan Nasabah Luar')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('creditAnalystLuar.survey', $pengajuan->id) }}" method="POST">
                        @csrf

                        <!-- Input Nasabah Info -->
                        <div class="form-group mb-3">
                            <label for="nama_nasabah">Nama Nasabah</label>
                            <input type="text" id="nama_nasabah" name="nama_nasabah" class="form-control"
                                value="{{ $pengajuan->nasabahLuar->nama_lengkap }}" disabled>
                        </div>

                        <!-- Pilihan Survey -->
                        <div class="form-group mb-3">
                            <label for="survey_required">Apakah Nasabah Harus Disurvey?</label>
                            <div class="form-check">
                                <label for="survey_yes" class="form-check-label">
                                    <input type="radio" id="survey_yes" name="is_survey" value="1"
                                        class="form-check-input" {{ old('is_survey') == '1' ? 'checked' : '' }} required>
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <label for="survey_no" class="form-check-label">
                                    <input type="radio" id="survey_no" name="is_survey" value="0"
                                        class="form-check-input" {{ old('is_survey') == '0' ? 'checked' : '' }} required>

                                    Tidak
                                </label>
                            </div>
                            @error('is_survey')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
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
@endsection
