@extends('layouts.parent-layout')

@section('page-title', 'Approval Credit Analyst')

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
                        <h6 class="card-title mb-3">Daftar Pengajuan Nasabah Luar</h6>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Pembiayaan</th>
                                    <th>Nominal Pinjaman</th>
                                    <th>Marketing</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($pengajuan)
                                    @php $index = 1; @endphp
                                    @foreach ($pengajuan as $item)
                                        @foreach ($item->pengajuanLuar as $pengajuan)
                                            <tr>
                                                <td>{{ $index }}</td>
                                                <td>
                                                    {{ $item->nama_lengkap }}
                                                    @if ($item->total_pengajuan > 1)
                                                        <br>
                                                        <small>Repeat Order</small>
                                                    @endif
                                                </td>
                                                <td>{{ $pengajuan->jenis_pembiayaan }}</td>
                                                <td>
                                                    <div class="mb-1">
                                                        Rp. {{ number_format($pengajuan->nominal_pinjaman, 0, ',', '.') }}
                                                    </div>
                                                    <small>
                                                        {{ $pengajuan->tenor }} Bulan
                                                    </small>
                                                </td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $pengajuan->created_at->translatedFormat('d F Y') }}</td>
                                                <td>
                                                    @if (
                                                        $pengajuan->status_pengajuan == 'survey selesai' ||
                                                            $pengajuan->status_pengajuan == 'perlu survey' ||
                                                            $pengajuan->status_pengajuan == 'tidak perlu survey')
                                                        <span
                                                            class="badge bg-info">{{ Str::title($pengajuan->status_pengajuan) }}</span>
                                                    @elseif ($pengajuan->status_pengajuan == 'banding')
                                                        <span class="badge bg-warning">Banding</span>
                                                    @elseif (
                                                        $pengajuan->status_pengajuan == 'pending' ||
                                                            $pengajuan->status_pengajuan == 'checked by spv' ||
                                                            $pengajuan->status_pengajuan == 'revisi' ||
                                                            $pengajuan->status_pengajuan == 'verifikasi')
                                                        <span
                                                            class="badge bg-primary">{{ Str::title($pengajuan->status_pengajuan) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($pengajuan->status_pengajuan == 'banding')
                                                        <a href="{{ route('creditAnalystLuar.detail-banding', $pengajuan->id) }}"
                                                            class="btn btn-info btn-sm">Detail Banding</a>
                                                    @else
                                                        <a href="{{ route('creditAnalystLuar.detail.pengajuan.show', $pengajuan->id) }}"
                                                            class="btn btn-primary btn-sm">Detail</a>
                                                    @endif
                                                    @if ($pengajuan->status_pengajuan == 'survey selesai')
                                                        <a href="{{ route('creditAnalystLuar.detail.hasil.survey', $pengajuan->id) }}"
                                                            class="btn btn-info btn-sm">Hasil Survey</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $index++; @endphp
                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
