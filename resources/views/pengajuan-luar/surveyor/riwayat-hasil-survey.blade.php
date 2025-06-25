@extends('layouts.parent-layout')

@section('page-title', 'Riwayat Hasil Survey')

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
                        <h6 class="card-title mb-3">Riwayat Hasil Survey</h6>
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
                                @foreach ($nasabah as $pengajuan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucfirst($pengajuan->nasabahLuar->nama_lengkap) }}</td>
                                        <td>
                                            <div class="mb-1">
                                                Rp.
                                                {{ number_format($pengajuan->nominal_pinjaman, 0, ',', '.') }}
                                            </div>
                                            <small>
                                                {{ $pengajuan->tenor }} Bulan
                                            </small>
                                        </td>
                                        <td>{{ $pengajuan->nasabahLuar->user->name }}</td>
                                        <td>{{ $pengajuan->created_at->format('d F Y') }}</td>
                                        <td>{{ $pengajuan->jenis_pembiayaan }}</td>
                                        <td>{{ Str::title($pengajuan->status_pengajuan) }}
                                        </td>
                                        <td>
                                            <a href="{{ route('surveyor.detail.hasil.survey', $pengajuan->id) }}"
                                                class="btn btn-primary btn-sm">Detail</a>
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
