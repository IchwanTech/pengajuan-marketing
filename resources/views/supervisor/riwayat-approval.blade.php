@extends('layouts.parent-layout')

@section('page-title', 'Riwayat Approval')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center border-bottom">
                        <h6 class="card-title mb-3">Daftar Pengajuan Nasabah</h6>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped" style="width 100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Nominal Pinjaman</th>
                                    <th>Marketing</th>
                                    <th>Status</th>
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
                                        <tr>
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td>{{ $data->nama_lengkap }}</td>
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
                                                {{ Str::title($data->user->name) ?? 'Marketing Tidak Ditemukan' }}
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
                                            <td>
                                                <a href="{{ route('supervisor.detail', $data->id) }}"
                                                    class="btn btn-info btn-xs">Detail</a>
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
