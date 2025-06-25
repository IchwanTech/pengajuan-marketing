@extends('layouts.parent-layout')

@section('page-title', 'Riwayat Approval Pengajuan Luar')

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
                        <h6 class="card-title mb-3">Daftar Pengajuan Nasabah</h6>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jenis Pembiayaan</th>
                                    <th>Nominal Pinjaman</th>
                                    <th>Marketing</th>
                                    <th>Status</th>
                                    <th>Waktu Approval</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($pengajuan->isNotEmpty())
                                    @php $index = 1; @endphp
                                    @foreach ($pengajuan as $item)
                                        @php
                                            $nasabah = $item->nasabahLuar;
                                            $approvalCA = $item->approval->where('role', 'ca')->last();
                                            $pengajuanDiproses = $nasabah->pengajuanLuar
                                                ->where('approval', '!=', null)
                                                ->count();
                                        @endphp
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>
                                                {{ $nasabah->nama_lengkap }}
                                                @if ($pengajuanDiproses > 1 && !$loop->first)
                                                    <br>
                                                    <small>Repeat Order</small>
                                                @endif
                                            </td>
                                            <td>{{ $item->jenis_pembiayaan }}</td>
                                            <td>
                                                <div class="mb-1">
                                                    Rp. {{ number_format($item->nominal_pinjaman, 0, ',', '.') }}
                                                </div>
                                                <small>{{ $item->tenor . ' Bulan' }}</small>
                                            </td>
                                            <td>{{ $nasabah->user->name }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $approvalCA->status == 'rejected' ? 'bg-danger' : 'bg-success' }}">
                                                    {{ Str::title($approvalCA->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $approvalCA->created_at->translatedFormat('d F Y') }} <br>
                                                Pukul {{ $approvalCA->created_at->translatedFormat('H:i') }} WIB
                                            </td>
                                            <td>
                                                <a href="{{ route('creditAnalystLuar.detail-approval', $item->id) }}"
                                                    class="btn btn-primary btn-sm">Detail</a>
                                            </td>
                                        </tr>
                                        @php $index++; @endphp
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
