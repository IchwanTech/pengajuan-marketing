@extends('layouts.parent-layout')

@section('page-title', 'Approval Pengajuan')

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
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Nominal Pinjaman</th>
                                    <th>Marketing</th>
                                    <th>Waktu Pengajuan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($pengajuan->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">Belum Ada Pengajuan</td>
                                    </tr>
                                @else
                                    @foreach ($pengajuan as $data)
                                        <tr>
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="mb-1">
                                                    {{ ucfirst($data->nama_lengkap) }}
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
                                                {{ ucfirst($data->user->name) ?? 'Marketing Tidak Ditemukan' }}
                                            </td>
                                            <td class="align-middle">
                                                @if ($data->pengajuan->is_banding == 1 || $data->pengajuan->is_banding == 0)
                                                    <div class="mb-1">
                                                        {{ \Carbon\Carbon::parse($data->pengajuan->created_at)->translatedFormat('d F Y') }}
                                                    </div>
                                                    <small>{{ $data->pengajuan->created_at->diffForHumans() }}</small>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('supervisor.approval.detail', $data->id) }}"
                                                    class="btn btn-gradient-info btn-sm">Detail</a>
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
