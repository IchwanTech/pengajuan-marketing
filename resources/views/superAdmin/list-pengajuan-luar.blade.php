@extends('layouts.parent-layout')

@section('page-title', 'Data Pengajuan Luar')

@push('styles')
    <style>
        th.status-header,
        td.status-cell {
            max-width: 50px;
            text-align: center;
            vertical-align: middle;
        }

        .btn-group-actions {
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')

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
                        <h6 class="card-title">Daftar Pengajuan Nasabah Luar</h6>
                    </div>

                    <!-- Filter Form -->
                    @include('superAdmin.partials.filter-form')

                    <!-- Table -->
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped nowrap" style="width:100%;">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">No</th>
                                    <th rowspan="2" class="align-middle">Nama Nasabah</th>
                                    <th rowspan="2" class="align-middle">Jenis Pembiayaan</th>
                                    <th rowspan="2" class="align-middle">Nominal</th>
                                    <th rowspan="2" class="align-middle">Marketing</th>
                                    <th colspan="2" class="text-center">Status</th>
                                    <th rowspan="2" class="text-center align-middle nowrap">Aksi</th>
                                    <th rowspan="2" class="text-center align-middle">Approval</th>
                                </tr>
                                <tr>
                                    <th class="status-header">CA</th>
                                    <th class="status-header">HM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengajuan as $index => $data)
                                    @foreach ($data->pengajuanLuar as $pengajuanItem)
                                        <tr class="align-middle nowrap">
                                            <td class="align-middle nowrap">{{ $loop->parent->iteration }}</td>
                                            <td class="align-middle nowrap">
                                                {{ $data->nama_lengkap }}
                                                @if ($data->pengajuanLuar->count() > 1 && !$loop->first)
                                                    <br><small class="text-muted">Repeat Order</small>
                                                @endif
                                            </td>
                                            <td class="align-middle nowrap">{{ $pengajuanItem->jenis_pembiayaan }}</td>
                                            <td class="align-middle nowrap">
                                                <div class="mb-1">
                                                    Rp. {{ number_format($pengajuanItem->nominal_pinjaman, 0, ',', '.') }}
                                                </div>
                                                <small class="text-muted">{{ $pengajuanItem->tenor }} Bulan</small>
                                            </td>
                                            <td class="align-middle nowrap">
                                                {{ Str::title($data->user->name ?? 'Marketing Tidak Ditemukan') }}
                                            </td>

                                            @php
                                                $approvals = $pengajuanItem->approval ?? collect();
                                                $caStatus = $approvals->where('role', 'ca')->pluck('status')->last();
                                                $hmStatus = $approvals->where('role', 'hm')->pluck('status')->last();
                                            @endphp

                                            <td class="status-cell">
                                                @include('superAdmin.partials.status-icon', [
                                                    'status' => $caStatus,
                                                ])
                                            </td>
                                            <td class="status-cell">
                                                @include('superAdmin.partials.status-icon', [
                                                    'status' => $hmStatus,
                                                ])
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="btn-group btn-group-actions" role="group">
                                                    <button type="button" class="btn btn-gradient-danger btn-sm"
                                                        onclick="showDeleteModal('{{ route('superAdmin.data.pengajuan.luar.delete', $pengajuanItem->id) }}')">
                                                        <i class="mdi mdi-trash-can"></i>
                                                    </button>
                                                    <a href="{{ route('superAdmin.data.pengajuan.luar.edit', $pengajuanItem->id) }}"
                                                        class="btn btn-gradient-warning btn-sm">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('superAdmin.data.pengajuan.luar.detail', $pengajuanItem->id) }}"
                                                        class="btn btn-gradient-success btn-sm">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="align-middle nowrap">
                                                <a href="{{ route('superAdmin.list.approval.luar', $pengajuanItem->id) }}"
                                                    class="btn btn-gradient-info btn-sm">Approval</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="mdi mdi-database-outline mdi-24px"></i>
                                                <p class="mt-2">Tidak ada data pengajuan</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('superAdmin.partials.delete-modal')
    @include('superAdmin.partials.detail-modal')

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Detail modal handler
            const detailModal = document.getElementById('detailKeteranganModal');
            const modalKeterangan = document.getElementById('modalKeterangan');

            if (detailModal) {
                detailModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const keterangan = button.getAttribute('data-keterangan');
                    modalKeterangan.textContent = keterangan;
                });
            }
        });

        function showDeleteModal(action) {
            const form = document.getElementById('deleteForm');
            form.action = action;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>
@endpush
