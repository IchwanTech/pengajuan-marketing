@extends('layouts.parent-layout')

{{-- @section('breadcrumb-title', 'Pengajuan') --}}
@section('page-title', 'Data Pengajuan Luar')

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

                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped nowrap" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jenis Pembiayaan</th>
                                    <th>Nominal Pinjaman</th>
                                    <th>Marketing</th>
                                    <th>Waktu Pengajuan</th>
                                    <th>Status Approval</th>
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
                                                </td>
                                                <td class="align-middle nowrap">
                                                    <div class="mb-1">
                                                        {{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}
                                                    </div>
                                                    <small>{{ $pengajuan->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td class="align-middle nowrap">
                                                    <div class="mb-1">
                                                        @if ($pengajuan->status_pengajuan === 'aproved ca' || $pengajuan->status_pengajuan === 'approved banding ca')
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif ($pengajuan->status_pengajuan === 'rejected ca' || $pengajuan->status_pengajuan === 'rejected banding ca')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="align-middle nowrap">
                                                    <div class="button-group d-flex gap-2">
                                                        @if ($pengajuan->status_pengajuan === 'approved banding ca' || $pengajuan->status_pengajuan === 'rejected banding ca')
                                                            <a href="{{ route('headMarketing.detail.banding-luar', $pengajuan->id) }}"
                                                                class="btn btn-outline-info btn-sm">Detail Banding</a>
                                                        @else
                                                            <a href="{{ route('headMarketing.detail.pengajuan.show', $pengajuan->id) }}"
                                                                class="btn btn-outline-info btn-sm">Detail <i
                                                                    class="mdi mdi-eye"></i></a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $index++; @endphp
                                        @endforeach
                                    @endforeach
                                @endif
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

    {{-- </main> --}}
@endsection
