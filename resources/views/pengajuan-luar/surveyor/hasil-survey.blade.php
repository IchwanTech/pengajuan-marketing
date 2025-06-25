@extends('layouts.parent-layout')

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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center border-bottom">
                        <h6 class="card-title mb-3">Daftar Survey Pengajuan Nasabah Luar</h6>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Nominal Pinjaman</th>
                                    <th>Marketing</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Pembiayaan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengajuan as $nasabah)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucfirst($nasabah->nasabahLuar->nama_lengkap) }}</td>
                                        <td>
                                            <div class="mb-1">
                                                Rp.
                                                {{ number_format($nasabah->nominal_pinjaman, 0, ',', '.') }}
                                            </div>
                                            <small>
                                                {{ $nasabah->tenor }} Bulan
                                            </small>
                                        </td>
                                        <td>{{ $nasabah->nasabahLuar->user->name }}</td>
                                        <td>{{ $nasabah->created_at->format('d F Y') }}</td>
                                        <td>{{ $nasabah->jenis_pembiayaan }}</td>
                                        <td>{{ Str::title($nasabah->status_pengajuan) }}
                                        </td>
                                        <td>
                                            @if ($nasabah->status_pengajuan == 'perlu survey')
                                                <a href="{{ route('surveyor.form.hasil.survey', $nasabah->id) }}"
                                                    class="btn btn-primary btn-sm">Input Hasil Survey</a>
                                            @elseif ($nasabah->status_pengajuan == 'survey selesai')
                                                <a href="{{ route('surveyor.detail.hasil.survey', $nasabah->id) }}"
                                                    class="btn btn-info btn-sm">Detail</a>
                                                <a href="{{ route('surveyor.hasil.survey.edit', $nasabah->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                            @endif
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
@endsection
