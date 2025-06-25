@extends('layouts.parent-layout')

@section('breadcrumb-title', '/ Form Approval')
@section('page-title', 'Pengajuan Nasabah Luar')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @if ($pengajuan->status_pengajuan == 'survey selesai')
                        <a href="{{ url()->previous() }}" class="btn btn-success btn-sm">Back</a>
                    @endif
                    <h4 class="card-title text-center mt-2 mb-2">Form Approval Pengajuan Pinjaman</h4>
                    <div style="width: 80px;"></div>
                </div>
                <div class="card-body">
                    <form action="{{ route('creditAnalystLuar.approval', $pengajuan->id) }}" method="POST">
                        @csrf
                        <!-- Input Nasabah Info -->
                        <div class="form-group mb-3">
                            <label for="nama_nasabah">Nama Nasabah</label>
                            <input type="text" id="nama_nasabah" name="nama_nasabah" class="form-control"
                                value="{{ $pengajuan->nasabahLuar->nama_lengkap }}" readonly>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label for="nominal_pinjaman">Nominal Pengajuan</label>
                                <input type="text" id="nominal_pinjaman" name="nominal_pinjaman" class="form-control"
                                    value="Rp. {{ number_format($pengajuan->nominal_pinjaman, 0, ',', '.') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="tenor_pengajuan">Tenor Pengajuan</label>
                                <input type="text" id="tenor_pengajuan" name="tenor_pengajuan" class="form-control"
                                    value="{{ $pengajuan->tenor }} Bulan" readonly>
                            </div>
                        </div>

                        <!-- Input Analisa -->
                        <div class="form-group mb-3">
                            <label for="analisa">Analisa</label>
                            <textarea name="analisa" id="analisa" class="form-control" rows="5" required>{{ old('analisa') }}</textarea>
                            @error('analisa')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Pilihan Survey -->
                        <div class="form-group mb-3">
                            <label for="survey_required">Pilih approval pengajuan nasabah</label>
                            <div class="form-check">
                                <label for="approve_yes" class="form-check-label">
                                    <input type="radio" id="approve_yes" name="is_approve" value="1"
                                        class="form-check-input" {{ old('is_approve') == '1' ? 'checked' : '' }} required>
                                    Approve
                                </label>
                            </div>
                            <div class="form-check">
                                <label for="approve_no" class="form-check-label">
                                    <input type="radio" id="approve_no" name="is_approve" value="0"
                                        class="form-check-input" {{ old('is_approve') == '0' ? 'checked' : '' }} required>
                                    Reject
                                </label>
                            </div>
                            @error('is_survey')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div id="approve-section" style="display: none;">
                            <div class="form-group mb-3">
                                <label for="nominal_pinjaman" class="form-label">Nominal Pinjaman</label>
                                <input type="number" id="nominal_pinjaman" name="nominal_pinjaman" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="tenor" class="form-label">Jangka Waktu</label>
                                <input type="number" name="tenor" id="tenor" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="catatan_approve">Catatan Approve</label>
                                <textarea name="catatan_approve" id="catatan_approve" class="form-control" cols="30" rows="10"></textarea>
                            </div>
                        </div>

                        <div id="reject-section" style="display: none;">
                            <div class="form-group mb-3">
                                <label for="catatan_reject">Alasan Reject</label>
                                <textarea name="catatan_reject" id="catatan_reject" class="form-control" cols="30" rows="10"></textarea>
                            </div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const approveRadio = document.getElementById('approve_yes');
            const rejectRadio = document.getElementById('approve_no');
            const approveSection = document.getElementById('approve-section');
            const rejectSection = document.getElementById('reject-section');

            function toggleSections() {
                if (approveRadio.checked) {
                    approveSection.style.display = 'block';
                    rejectSection.style.display = 'none';
                } else if (rejectRadio.checked) {
                    approveSection.style.display = 'none';
                    rejectSection.style.display = 'block';
                }
            }

            toggleSections();

            approveRadio.addEventListener('change', toggleSections);
            rejectRadio.addEventListener('change', toggleSections);
        })
    </script>
@endsection
