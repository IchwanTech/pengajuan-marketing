@extends('layouts.parent-layout')

{{-- @section('breadcrumb-title', 'Pengajuan') --}}
@section('page-title', 'Pengajuan Nasabah')

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
                        <a href="{{ route('marketingLuar.form') }}" class="btn btn-primary btn-sm ms-auto mb-3">Tambah</a>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped nowrap" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jenis Pembiayaan</th>
                                    <th>Nominal Pinjaman</th>
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
                                                        @if ($pengajuan->status_pengajuan === 'revisi' || $pengajuan->status_pengajuan === 'revisi spv')
                                                            <span
                                                                class="badge badge-warning">{{ Str::title($pengajuan->status_pengajuan) }}</span>
                                                        @elseif ($pengajuan->status_pengajuan === 'verifikasi')
                                                            <span class="badge badge-primary">Menunggu
                                                                {{ Str::title($pengajuan->status_pengajuan) }}</span>
                                                        @elseif (
                                                            $pengajuan->status_pengajuan === 'pending' ||
                                                                $pengajuan->status_pengajuan === 'checked by spv' ||
                                                                $pengajuan->status_pengajuan === 'tidak perlu survey' ||
                                                                $pengajuan->status_pengajuan === 'survey selesai')
                                                            <span
                                                                class="badge badge-primary">{{ Str::title($pengajuan->status_pengajuan) }}</span>
                                                        @elseif ($pengajuan->status_pengajuan === 'perlu survey')
                                                            <span
                                                                class="badge badge-primary">{{ Str::title($pengajuan->status_pengajuan) }}</span>
                                                        @elseif ($pengajuan->status_pengajuan === 'aproved hm' || $pengajuan->status_pengajuan === 'approved banding hm')
                                                            <span
                                                                class="badge badge-success">{{ Str::title($pengajuan->status_pengajuan) }}</span>
                                                        @elseif ($pengajuan->status_pengajuan === 'aproved ca' || $pengajuan->status_pengajuan === 'approved banding ca')
                                                            <span
                                                                class="badge badge-success">{{ Str::title($pengajuan->status_pengajuan) }}</span>
                                                        @elseif ($pengajuan->status_pengajuan === 'banding')
                                                            <span class="badge badge-info">Sedang Banding</span>
                                                        @elseif ($pengajuan->status_pengajuan === 'rejected ca' || $pengajuan->status_pengajuan === 'rejected banding ca')
                                                            <span
                                                                class="badge badge-danger">{{ Str::title($pengajuan->status_pengajuan) }}</span>
                                                        @else
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @endif
                                                    </div>
                                                    @php
                                                        $keterangan = $pengajuan->approval
                                                            ->where('role', 'hm')
                                                            ->first();
                                                    @endphp
                                                    <button class="btn btn-gradient-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#detailKeteranganModal"
                                                        data-keterangan="{{ $keterangan->catatan ?? 'Tidak ada catatan' }}">
                                                        Keterangan
                                                    </button>
                                                </td>
                                                <td class="align-middle nowrap">
                                                    <div class="button-group d-flex gap-2">
                                                        @if (
                                                            $pengajuan->status_pengajuan === 'revisi' ||
                                                                $pengajuan->status_pengajuan === 'revisi spv' ||
                                                                $pengajuan->status_pengajuan === 'pending')
                                                            <a href="{{ route('marketingLuar.edit.pengajuan.show', $pengajuan->id) }}"
                                                                class="btn btn-outline-info btn-sm">
                                                                Edit <i class="mdi mdi-pencil"></i>
                                                            </a>
                                                        @elseif (
                                                            $pengajuan->is_banding == 0 &&
                                                                ($pengajuan->status_pengajuan == 'rejected hm' || $pengajuan->status_pengajuan == 'aproved hm'))
                                                            <div class="button-group d-flex gap-2">
                                                                <button class="btn btn-outline-warning btn-sm"
                                                                    data-bs-toggle="modal" data-bs-target="#bandingModal"
                                                                    data-id="{{ $pengajuan->id }}"
                                                                    data-id-marketing="{{ $data->id }}">
                                                                    Banding <i class="mdi mdi-alert-circle"></i>
                                                                </button>
                                                                <form
                                                                    action="{{ route('marketingLuar.clear', $pengajuan->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="submit"
                                                                        class="btn btn-outline-success btn-sm">
                                                                        Selesai
                                                                        <i class="mdi mdi-check"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                        <a href="{{ route('marketingLuar.detail.pengajuan.show', $pengajuan->id) }}"
                                                            class="btn btn-outline-info btn-sm">Detail <i
                                                                class="mdi mdi-eye"></i></a>
                                                        @if ($data->pengajuanLuar->count() >= 1 && $loop->first)
                                                            <a href="{{ route('marketingLuar.topUp', $data->id) }}"
                                                                class="btn btn-outline-primary btn-sm">Top Up <i
                                                                    class="mdi mdi-plus-circle"></i></a>
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

    <!-- Modal Alasan Banding -->
    <div class="modal fade" id="bandingModal" tabindex="-1" aria-labelledby="bandingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bandingModalLabel">Alasan Banding</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bandingForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" id="pengajuanId" name="pengajuan_id">
                        <div class="mb-3">
                            <label for="alasanBanding" class="form-label">Alasan Banding</label>
                            <textarea class="form-control" id="alasanBanding" name="alasan_banding" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bandingModal = document.getElementById('bandingModal');
            const pengajuanIdInput = document.getElementById('pengajuanId');
            const bandingForm = document.getElementById('bandingForm');

            bandingModal.addEventListener('show.bs.modal', function(event) {
                // Tombol yang memicu modal
                const button = event.relatedTarget;
                const pengajuanId = button.getAttribute('data-id');
                const marketingId = button.getAttribute('data-id-marketing');

                // Set ID pengajuan di input form
                pengajuanIdInput.value = pengajuanId;
                console.log(pengajuanId);
                console.log(marketingId);

                // Set action form ke endpoint yang sesuai
                bandingForm.action = `/marketing-luar/pengajuan-luar/banding/${pengajuanId}`;
            });
        });

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
