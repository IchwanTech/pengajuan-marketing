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
                        <a href="{{ route('marketing.form') }}" class="btn btn-primary btn-sm ms-auto mb-3">Tambah</a>
                        {{-- <button class="btn btn-primary btn-sm ms-auto" >Tambah</button> --}}
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped nowrap" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Nominal Pinjaman</th>
                                    <th>Status Approval</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($pengajuan->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center nowrap">Belum Ada Pengajuan</td>
                                    </tr>
                                @else
                                    @foreach ($pengajuan as $data)
                                        <tr class="align-middle nowrap">
                                            <td class="align-middle nowrap">{{ $loop->iteration }}</td>
                                            <td class="align-middle nowrap">{{ $data->nama_lengkap }}</td>
                                            <td class="align-middle nowrap">
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
                                            <td class="align-middle nowrap">
                                                <div class="mb-1">
                                                    @if (
                                                        $data->pengajuan->status == 'aproved head' ||
                                                            $data->pengajuan->status == 'aproved ca' ||
                                                            $data->pengajuan->status == 'aproved spv' ||
                                                            $data->pengajuan->status == 'approved banding ca' ||
                                                            $data->pengajuan->status == 'approved banding head')
                                                        <span class="badge badge-success">
                                                            {{ Str::title($data->pengajuan->status) }}
                                                        </span>
                                                    @else
                                                        <span
                                                            class="badge badge-danger">{{ Str::title($data->pengajuan->status) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @php
                                                    $statuses = [
                                                        'aproved spv' => 0,
                                                        'rejected spv' => 0,
                                                        'aproved ca' => 1,
                                                        'rejected ca' => 1,
                                                        'approved banding ca' => 3,
                                                        'rejected banding ca' => 3,
                                                        'aproved head' => 2,
                                                        'rejected head' => 2,
                                                        'approved banding head' => 4,
                                                        'rejected banding head' => 4,
                                                    ];

                                                    // Default keterangan
                                                    $keterangan = 'Keterangan tidak tersedia';

                                                    // Cek status pengajuan dan ambil keterangan
                                                    if (
                                                        isset($data->pengajuan->status) &&
                                                        isset($statuses[$data->pengajuan->status])
                                                    ) {
                                                        $index = $statuses[$data->pengajuan->status];
                                                        $approval = $data->pengajuan->approval[$index] ?? null;

                                                        if ($approval) {
                                                            $keterangan =
                                                                $data->pengajuan->status == 'aproved head' ||
                                                                $data->pengajuan->status == 'rejected head'
                                                                    ? Str::title(
                                                                        $approval->keterangan ??
                                                                            'Keterangan tidak tersedia',
                                                                    )
                                                                    : Str::title(
                                                                        $approval->status ??
                                                                            'Keterangan tidak tersedia',
                                                                    );
                                                        }
                                                    }
                                                @endphp

                                                @if (isset($data->pengajuan->status) && in_array($data->pengajuan->status, array_keys($statuses)))
                                                    <button class="btn btn-gradient-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#detailKeteranganModal"
                                                        data-keterangan="{{ $keterangan }}">
                                                        Keterangan
                                                    </button>

                                                    {{-- @if (in_array($data->pengajuan->status, ['aproved spv', 'rejected spv', 'aproved ca', 'rejected ca', 'aproved head', 'rejected head', 'approved banding ca', 'rejected banding ca', 'approved banding head', 'rejected banding head']))
                                                    <button class="btn btn-gradient-info btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#detailKeteranganModal"
                                                        data-keterangan="{{ $data->pengajuan->status == 'aproved spv' || $data->pengajuan->status == 'rejected spv'
                                                            ? Str::title($data->pengajuan->approval[0]->status)
                                                            : ($data->pengajuan->status == 'aproved ca' || $data->pengajuan->status == 'rejected ca'
                                                                ? Str::title($data->pengajuan->approval[1]->status)
                                                                : ($data->pengajuan->status == 'approved banding ca' || $data->pengajuan->status == 'rejected banding ca'
                                                                    ? Str::title($data->pengajuan->approval[3]->status) // Keterangan untuk banding CA
                                                                    : ($data->pengajuan->status == 'aproved head' || $data->pengajuan->status == 'rejected head'
                                                                        ? Str::title($data->pengajuan->approval[2]->keterangan)
                                                                        : (isset($data->pengajuan->approval[4])
                                                                            ? $data->pengajuan->approval[4]->keterangan
                                                                            : 'Keterangan tidak tersedia')))) }}">
                                                        Keterangan
                                                    </button> --}}
                                                    <!--</br>-->
                                                    <!--<small>{{ $data->pengajuan->created_at->diffForHumans() }}</small>-->
                                                @elseif ($data->pengajuan->status == 'pending')
                                                    <a href="{{ route('marketing.pengajuan.edit', $data->id) }}"
                                                        class="btn btn-gradient-info btn-sm">
                                                        Edit <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                @else
                                                    <span class="badge badge-gradient-info">Sedang Banding</span>
                                                @endif
                                            </td>
                                            <td class="align-middle nowrap">
                                                @if ($data->enable_edit == 1)
                                                    <a href="{{ route('marketing.pengajuan.edit', $data->id) }}"
                                                        class="btn btn-gradient-info btn-sm">
                                                        Edit <i class="mdi mdi-pencil"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('marketing.detail.pengajuan.show', $data->id) }}"
                                                        class="btn btn-gradient-info btn-sm">Detail</a>
                                                @endif
                                            </td>
                                            <td class="align-middle nowrap">
                                                @if (
                                                    $data->pengajuan->is_banding == 0 &&
                                                        ($data->pengajuan->status == 'aproved head' || $data->pengajuan->status == 'rejected head'))
                                                    <div class="button-group d-flex gap-2">
                                                        <button type="button" class="btn btn-gradient-danger btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#bandingModal"
                                                            data-id="{{ $data->pengajuan->id }}"
                                                            data-id-marketing="{{ $data->marketing_id }}" data->
                                                            Banding
                                                        </button>
                                                        <form
                                                            action="{{ route('marketing.data.pengajuan.clear', $data->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-gradient-success btn-sm">
                                                                Selesai
                                                            </button>
                                                        </form>
                                                    </div>
                                                @elseif ($data->pengajuan->is_banding == 1)
                                                    <span class="badge badge-info">Sedang Banding</span>
                                                @elseif (
                                                    $data->pengajuan->is_banding == 2 ||
                                                        $data->pengajuan->status == 'approved banding head' ||
                                                        $data->pengajuan->status == 'rejected banding head')
                                                    <span class="badge badge-success">Sudah Selesai</span>
                                                @else
                                                    <span class="badge badge-primary">Menunggu Approval</span>
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
                bandingForm.action = `/marketing/data-pengajuan/banding/${pengajuanId}`;
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
