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
                    <div class="d-flex align-items-center border-bottom mb-3">
                        <h6 class="card-title mb-3">Daftar Pengajuan Nasabah Luar</h6>
                    </div>
                    <form method="GET" action="{{ route('headMarketing.data.riwayat.luar') }}" class="mb-4">
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
                                    @for ($y = now()->year; $y >= now()->year - 10; $y--)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped nowrap" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jenis Pembiayaan</th>
                                    <th>Nominal Pinjaman</th>
                                    <th>Marketing</th>
                                    <th>Status Approval</th>
                                    <th>Tanggal Approval</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($pengajuan)
                                    @php $index = 1; @endphp
                                    @foreach ($pengajuan as $data)
                                        @foreach ($data->pengajuanLuar as $pengajuan)
                                            <tr class="align-middle nowrap">
                                                <td class="align-middle nowrap">{{ $index }}</td>
                                                <td class="align-middle nowrap">
                                                    {{ $data->nama_lengkap }}
                                                    @if ($data->pengajuanLuar->count() > 1 && !$loop->first)
                                                        <br>
                                                        <small>Repeat Order</small>
                                                    @endif
                                                </td>
                                                <td class="align-middle nowrap">{{ $pengajuan->jenis_pembiayaan }}</td>
                                                <td class="align-middle nowrap">
                                                    <div class="mb-1">
                                                        Rp. {{ number_format($pengajuan->nominal_pinjaman, 0, ',', '.') }}
                                                    </div>
                                                    <small>{{ $pengajuan->tenor }} Bulan</small>
                                                </td>
                                                <td class="align-middle nowrap">
                                                    <div class="mb-1">
                                                        {{ Str::title($data->user->name) ?? 'Marketing Tidak Ditemukan' }}
                                                    </div>
                                                    <small>
                                                        {{ $pengajuan->created_at->translatedFormat('d F Y') }}
                                                    </small>
                                                </td>
                                                <td class="align-middle nowrap">
                                                    @if ($pengajuan->status_pengajuan == 'aproved hm' || $pengajuan->status_pengajuan == 'approved banding hm')
                                                        <span class="badge bg-success">Approved</span>
                                                    @else
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle nowrap">
                                                    @if ($pengajuan->approval)
                                                        {{ $pengajuan->approval->firstWhere('role', 'hm')->created_at->translatedFormat('d F Y') }}
                                                    @else
                                                        Tidak ada
                                                    @endif
                                                </td>
                                                <td class="align-middle nowrap">
                                                    <a href="{{ route('headMarketing.detail.luar', $pengajuan->id) }}"
                                                        class="btn btn-info btn-sm">Detail</a>
                                                </td>
                                            </tr>
                                            @php $index++; @endphp
                                        @endforeach
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">Belum Ada Pengajuan</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
