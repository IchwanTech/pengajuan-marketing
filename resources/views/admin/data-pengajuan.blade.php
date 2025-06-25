@extends('layouts.parent-layout')

@section('page-title', 'Data Pengajuan')

@section('content')

    <style>
        th.status-header,
        td.status-cell {
            max-width: 50px;
            text-align: center;
            vertical-align: middle;
        }
    </style>

    <!-- Alert Messages -->
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
                    <!-- Title -->
                    <div class="d-flex align-items-center border-bottom mb-3">
                        <h6 class="card-title">Daftar Pengajuan Nasabah</h6>
                    </div>

                    <form method="GET" action="{{ route('admin.data.pengajuan') }}" class="mb-4">
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

                    <!-- Table -->
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">No</th>
                                    <th rowspan="2" class="align-middle">Nama Nasabah</th>
                                    <th rowspan="2" class="align-middle">Perusahaan</th>
                                    <th rowspan="2" class="align-middle">Nominal Pinjaman</th>
                                    <th rowspan="2" class="align-middle">Keterangan</th>
                                    <th rowspan="2" class="align-middle">Marketing</th>
                                    <th colspan="3" class="text-center">Status</th>
                                    <th rowspan="2" class="text-center align-middle">Aksi</th>
                                </tr>
                                <tr>
                                    <th class="status-header">SPV</th>
                                    <th class="status-header">CA</th>
                                    <th class="status-header">HM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayat as $data)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="mb-1">{{ $data->nama_lengkap }}</div>
                                            <small>
                                                {{ $data->pengajuan->status_pinjaman === 'baru'
                                                    ? 'Pinjaman Baru'
                                                    : 'Pinjaman ke-' . ($data->pengajuan->pinjaman_ke ?? '0') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                PT. {{ $data->pekerjaan->perusahaan }}
                                            </div>
                                            @if ($data->pekerjaan->golongan == 'Borongan')
                                                <small>{{ $data->pekerjaan->golongan }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="mb-1">Rp.
                                                {{ number_format($data->pengajuan->nominal_pinjaman, 0, ',', '.') }}
                                            </div>
                                            <small>
                                                {{ $data->pekerjaan->golongan == 'Borongan'
                                                    ? $data->pengajuan->tenor . ' Periode'
                                                    : $data->pengajuan->tenor . ' Bulan' }}
                                            </small>
                                        </td>
                                        @php
                                            $hmKeterangan = $data->pengajuan->approval->where('role', 'hm')->last();
                                        @endphp
                                        <td class="align-middle">
                                            @if ($hmKeterangan->status ?? null)
                                                @if ($hmKeterangan->status == 'approved')
                                                    <button class="btn btn-gradient-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#detailKeteranganModal"
                                                        data-keterangan="{{ $hmKeterangan->keterangan }}">
                                                        Keterangan
                                                    </button>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            @else
                                                <span class="badge badge-primary">Menunggu Approval</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <div class="mb-1">
                                                {{ Str::title($data->user->name) ?? 'Marketing Tidak Ditemukan' }}
                                            </div>
                                            <small>
                                                {{ $data->pengajuan->created_at->translatedFormat('d F Y') }}
                                            </small>
                                        </td>
                                        <td class="status-cell">
                                            @php
                                                $spvStatus = $data->pengajuan->approval
                                                    ->where('role', 'spv')
                                                    ->pluck('status')
                                                    ->last();
                                            @endphp
                                            {!! $spvStatus === 'approved'
                                                ? '<i class="mdi mdi-check-circle text-success"></i>'
                                                : ($spvStatus === 'rejected'
                                                    ? '<i class="mdi mdi-close-circle text-danger"></i>'
                                                    : '<i class="mdi mdi-clock-outline text-info"></i>') !!}
                                        </td>
                                        <td class="status-cell">
                                            @php
                                                // Ambil data approval CA terakhir
                                                $lastCAApproval = $data->pengajuan->approval
                                                    ->where('role', 'ca')
                                                    ->last();

                                                $ca =
                                                    $lastCAApproval && $lastCAApproval->user
                                                        ? explode(' ', trim($lastCAApproval->user->name))[0]
                                                        : 'Unknown';

                                                // Status approval
                                                $caStatus = $lastCAApproval ? $lastCAApproval->status : null;
                                            @endphp

                                            {!! $caStatus === 'approved'
                                                ? '<i class="mdi mdi-check-circle text-success"></i>'
                                                : ($caStatus === 'rejected'
                                                    ? '<i class="mdi mdi-close-circle text-danger"></i>'
                                                    : '<i class="mdi mdi-clock-outline text-info"></i>') !!}

                                            <br>
                                            <div class="mt-1">
                                                {{ $ca }}
                                            </div>
                                        </td>
                                        <td class="status-cell">
                                            @php
                                                $hmStatus = $data->pengajuan->approval
                                                    ->where('role', 'hm')
                                                    ->pluck('status')
                                                    ->last();
                                            @endphp
                                            {!! $hmStatus === 'approved'
                                                ? '<i class="mdi mdi-check-circle text-success"></i>'
                                                : ($hmStatus === 'rejected'
                                                    ? '<i class="mdi mdi-close-circle text-danger"></i>'
                                                    : '<i class="mdi mdi-clock-outline text-info"></i>') !!}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.data.pengajuan.detail', $data->id) }}"
                                                class="btn btn-info btn-xs">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Belum Ada Riwayat Approval</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailKeteranganModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Detail Keterangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalKeterangan">Memuat...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailKeteranganModal = document.getElementById('detailKeteranganModal');
            const modalKeterangan = document.getElementById('modalKeterangan');

            detailKeteranganModal.addEventListener('show.bs.modal', function(event) {
                // Ambil button yang memicu modal
                const button = event.relatedTarget;

                // Ambil data keterangan dari atribut data
                const keterangan = button.getAttribute('data-keterangan');

                // Tampilkan keterangan di modal
                modalKeterangan.textContent = keterangan;
            });
        });
    </script>

@endsection
