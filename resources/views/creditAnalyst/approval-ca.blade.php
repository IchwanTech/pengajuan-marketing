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
                        <h6 class="card-title mb-3">Daftar Pengajuan Nasabah</h6>
                    </div>
                    <div class="table-responsive mt-2">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Nasabah</th>
                                    <th rowspan="2">Nominal Pinjaman</th>
                                    <th rowspan="2">Marketing</th>
                                    <th rowspan="2">Tanggal Pengajuan</th>
                                    <th colspan="2" class="text-center no-underline">Status</th>
                                    {{-- <th rowspan="2">Notes</th> --}}
                                    <th rowspan="2">Aksi</th>
                                </tr>
                                <tr>
                                    <!-- Sub-kolom untuk Status -->
                                    <th class="text-center">MKT</th>
                                    <th class="text-center">SPV</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($pengajuan->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center">Belum Ada Pengajuan</td>
                                    </tr>
                                @else
                                    @foreach ($pengajuan as $data)
                                        <tr>
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="mb-1">
                                                    {{ $data->nama_lengkap }}
                                                </div>
                                                @if ($data->pengajuan->status_pinjaman === 'baru')
                                                    <small>Pinjaman Baru</small>
                                                @else
                                                    <small>Pinjaman ke-{{ $data->pengajuan->pinjaman_ke ?? '0' }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="mb-1">
                                                    Rp. {{ number_format($data->pengajuan->nominal_pinjaman, 0, ',', '.') }}
                                                </div>
                                                <small>
                                                    @if ($data->pekerjaan->golongan == 'Borongan')
                                                        {{ $data->pengajuan->tenor }} Periode
                                                    @else
                                                        {{ $data->pengajuan->tenor }} Bulan
                                                    @endif
                                                </small>
                                            </td>
                                            <td class="align-middle">
                                                <div class="mb-1">
                                                    {{ ucfirst($data->user->name) ?? 'Marketing Tidak Ditemukan' }}
                                                </div>
                                                @if ($data->pengajuan->status == 'banding')
                                                    <small class="text-danger">Banding</small>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div class="mb-1">
                                                    {{ \Carbon\Carbon::parse($data->pengajuan->created_at)->translatedFormat('d F Y') }}
                                                </div>
                                                <small>{{ $data->pengajuan->created_at->diffForHumans() }}</small>
                                            </td>
                                            {{-- <td class="align-middle">
                                                {{ ucfirst(strtolower(explode(' ', $data->pengajuan->status)[0])) }}
                                                {{ strtoupper(explode(' ', $data->pengajuan->status)[1]) }}
                                            </td> --}}
                                            <td class="align-middle text-center">
                                                <i class="mdi mdi-check-circle mdi-icon text-success"></i>
                                            </td>
                                            <td class="align-middle text-center">
                                                @foreach ($data->pengajuan->approval->where('role', 'spv') as $approvalSpv)
                                                    {!! $approvalSpv->status == 'approved'
                                                        ? '<i class="mdi mdi-check-circle text-success"></i>'
                                                        : '<i class="mdi mdi-close-circle text-danger"></i>' !!}
                                                @endforeach
                                            </td>
                                            <td class="align-middle">
                                                @if ($data->pengajuan->status == 'banding')
                                                    <a href="{{ route('creditAnalyst.approval.detail-banding', $data->id) }}"
                                                        class="btn btn-gradient-info btn-sm">Detail</a>
                                                @else
                                                    <a href="{{ route('creditAnalyst.approval.detail', $data->id) }}"
                                                        class="btn btn-gradient-info btn-sm">Detail</a>
                                                @endif
                                            </td>
                                        </tr>
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
