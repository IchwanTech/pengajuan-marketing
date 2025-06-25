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
                    <div class="d-flex align-items-center border-bottom mb-3">
                        <h6 class="card-title mb-3">Daftar Pengajuan Nasabah</h6>
                    </div>
                    <form method="GET" action="{{ route('headMarketing.riwayat') }}" class="mb-4">
                        <div class="row">
                            <!-- Filter Waktu -->
                            <div class="col-md-3">
                                <label for="filter_time" class="form-label">Pilih Waktu</label>
                                <select name="filter_time" id="filter_time" class="form-control">
                                    <option value="">Semua Waktu</option>
                                    <option value="today" {{ request('filter_time') == 'today' ? 'selected' : '' }}>Hari
                                        Ini</option>
                                    <option value="this_month"
                                        {{ request('filter_time') == 'this_month' ? 'selected' : '' }}>Bulan Ini
                                    </option>
                                    <option value="this_year" {{ request('filter_time') == 'this_year' ? 'selected' : '' }}>
                                        Tahun Ini
                                    </option>
                                </select>
                            </div>

                            <!-- Filter Bulan -->
                            <div class="col-md-3">
                                <label for="month" class="form-label">Pilih Bulan</label>
                                <select name="month" id="month" class="form-control">
                                    <option value="">Semua Bulan</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        @php
                                            $namaBulan = \Carbon\Carbon::createFromFormat('m', $m)->translatedFormat(
                                                'F',
                                            );
                                        @endphp
                                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                            {{ $namaBulan }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Filter Tahun -->
                            <div class="col-md-3">
                                <label for="year" class="form-label">Pilih Tahun</label>
                                <select name="year" id="year" class="form-control">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($availableYears as $year)
                                        <option value="{{ $year }}"
                                            {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Nominal Pinjaman</th>
                                    <th>Marketing</th>
                                    <th>Status</th>
                                    <th>Tanggal Approval</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($riwayat->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center">Belum Ada Riwayat Approval</td>
                                    </tr>
                                @else
                                    @foreach ($riwayat as $data)
                                        <tr>
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td>{{ $data->nama_lengkap }}</td>
                                            <td>
                                                Rp. {{ number_format($data->pengajuan->nominal_pinjaman, 0, ',', '.') }}
                                            </td>
                                            <td class="align-middle">
                                                {{ Str::title($data->user->name) ?? 'Marketing Tidak Ditemukan' }}
                                            </td>
                                            <td class="align-middle">
                                                @if ($data->pengajuan->status === 'aproved head' || $data->pengajuan->status === 'approved banding head')
                                                    <span class="badge badge-success">Approved</span>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @if ($data->pengajuan)
                                                    @foreach ($data->pengajuan->approval as $item)
                                                        @if ($item->role === 'hm')
                                                            {{ $item->created_at->translatedFormat('d F Y') }}
                                                        @endif
                                                    @endforeach
                                                @else
                                                    Tidak ada pengajuan
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('headMarketing.detail', $data->id) }}"
                                                    class="btn btn-info btn-sm">Detail</a>
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
