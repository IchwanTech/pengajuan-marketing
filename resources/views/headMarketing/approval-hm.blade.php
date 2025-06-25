@extends('layouts.parent-layout')

@section('page-title', 'Approval Head Marketing')

@section('content')
    <style>
        th.status-header,
        td.status-cell {
            max-width: 50px;
            /* Lebar maksimum untuk kolom status */
            text-align: center;
            vertical-align: middle;
        }
    </style>

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
                                    <th rowspan="2" class="text-center">No</th>
                                    <th rowspan="2">Nama Nasabah</th>
                                    <th rowspan="2">Perusahaan</th>
                                    <th rowspan="2">Nominal Pinjaman</th>
                                    <th rowspan="2">Marketing</th>
                                    <th rowspan="2">Waktu Pengajuan</th>
                                    <th colspan="3" class="text-center no-underline">Status</th>
                                    <th rowspan="2" class="text-center">Aksi</th>
                                </tr>
                                <tr>
                                    <!-- Sub-kolom untuk Status -->
                                    <th class="status-header">MKT</th>
                                    <th class="status-header">SPV</th>
                                    <th class="status-header">CA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($pengajuan->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center">Belum Ada Pengajuan</td>
                                    </tr>
                                @else
                                    @foreach ($pengajuan as $data)
                                        <tr>
                                            <td class="align-middle text-center">{{ $loop->iteration }}</td>
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
                                            <td>PT. {{ $data->pekerjaan->perusahaan }}</td>
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
                                                    {{ Str::title($data->user->name) ?? 'Marketing Tidak Ditemukan' }}
                                                </div>
                                                @if ($data->pengajuan->status == 'approved banding ca' || $data->pengajuan->status == 'rejected banding ca')
                                                    <small class="text-danger">Banding</small>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if ($data->pengajuan->is_banding == 1 || $data->pengajuan->is_banding == 0)
                                                    <div class="mb-1">
                                                        {{ \Carbon\Carbon::parse($data->pengajuan->created_at)->translatedFormat('d F Y') }}
                                                    </div>
                                                    <small>{{ $data->pengajuan->created_at->diffForHumans() }}</small>
                                                @endif
                                            </td>
                                            <td class="status-cell">
                                                <i class="mdi mdi-check-circle text-success"></i>
                                            </td>

                                            <!-- Status SPV -->
                                            <td class="status-cell">
                                                @foreach ($data->pengajuan->approval->where('role', 'spv') as $approvalSpv)
                                                    {!! $approvalSpv->status == 'approved'
                                                        ? '<i class="mdi mdi-check-circle text-success"></i>'
                                                        : '<i class="mdi mdi-close-circle text-danger"></i>' !!}
                                                @endforeach
                                            </td>

                                            <!-- Status CA -->
                                            <td class="status-cell">
                                                @foreach ($data->pengajuan->approval->where('role', 'ca') as $approvalCA)
                                                    @if ($approvalCA->is_banding == 1)
                                                        {!! $approvalCA->status == 'approved'
                                                            ? '<i class="mdi mdi-check-circle text-warning"></i>'
                                                            : '<i class="mdi mdi-close-circle text-danger"></i>' !!}
                                                    @else
                                                        {!! $approvalCA->status == 'approved'
                                                            ? '<i class="mdi mdi-check-circle text-success"></i>'
                                                            : '<i class="mdi mdi-close-circle text-danger"></i>' !!}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="align-middle">
                                                @if ($data->pengajuan->status == 'approved banding ca' || $data->pengajuan->status == 'rejected banding ca')
                                                    <a href="{{ route('headMarketing.approval.detail-banding', $data->id) }}"
                                                        class="btn btn-gradient-info btn-sm">Detail Banding</a>
                                                @else
                                                    <a href="{{ route('headMarketing.approval.detail', $data->id) }}"
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
