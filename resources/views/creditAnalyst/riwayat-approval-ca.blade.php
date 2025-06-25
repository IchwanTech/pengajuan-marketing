@extends('layouts.parent-layout')

@section('page-title', 'Riwayat Approval Pengajuan')

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
                                    <th>Nominal Pinjaman</th>
                                    <th>Marketing</th>
                                    <th>Status</th>
                                    <th>Waktu Approval</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($riwayat->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Belum Ada Riwayat Approval</td>
                                    </tr>
                                @else
                                    @foreach ($riwayat as $data)
                                        @if (
                                            $data->pengajuan->status !== 'aproved spv' &&
                                                $data->pengajuan->status !== 'rejected spv' &&
                                                $data->pengajuan->status !== 'pending')
                                            <tr>
                                                <td class="align-middle">{{ $loop->iteration }}</td>
                                                <td>{{ $data->nama_lengkap }}</td>
                                                <td>
                                                    Rp. {{ number_format($data->pengajuan->nominal_pinjaman, 0, ',', '.') }}
                                                </td>
                                                <td class="align-middle">
                                                    {{ $data->user->name ?? 'Marketing Tidak Ditemukan' }}
                                                </td>
                                                <td class="align-middle">
                                                    @foreach ($data->pengajuan->approval as $item)
                                                        @if ($item->status === 'approved')
                                                            <span class="badge badge-success">Approved</span>
                                                        @else
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td class="align-middle">
                                                    {{ $data->pengajuan->updated_at->translatedFormat('d F Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('creditAnalyst.detail', $data->id) }}"
                                                        class="btn btn-info btn-xs">Detail</a>
                                                </td>
                                            </tr>
                                        @endif
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
