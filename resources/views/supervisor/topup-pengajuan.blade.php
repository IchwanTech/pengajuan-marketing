@extends('layouts.parent-layout')

@section('page-title', 'Approval Pengajuan')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center border-bottom">
                        <h6 class="card-title mb-3">Daftar Pengajuan Nasabah</h6>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-bordered nowrap" style="width:100%;">
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
                                @if ($topUp->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">Belum Ada Pengajuan</td>
                                    </tr>
                                @else
                                    @foreach ($topUp as $data)
                                        <tr class="{{ $data->is_clear ? 'table-white' : 'table-warning' }}">
                                            <td class="align-middle">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="mb-1">
                                                    {{ ucfirst($data->nama_lengkap) }}
                                                </div>
                                                <small>Pinjaman ke-{{ $data->pinjaman_ke ?? '0' }}</small>
                                            </td>
                                            <td>
                                                <div class="mb-1">
                                                    Rp. {{ number_format($data->nominal_pinjaman, 0, ',', '.') }}
                                                </div>
                                                <small>
                                                    {{ $data->tenor }} Bulan
                                                </small>
                                            </td>
                                            <td class="align-middle">
                                                {{ ucfirst($data->nama_marketing) ?? 'Marketing Tidak Ditemukan' }}
                                            </td>
                                            <td class="align-middle">
                                                <div class="mb-1">
                                                    {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}
                                                </div>
                                                <small>{{ $data->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if (!$data->is_clear)
                                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#detailModal" data-id="{{ $data->id }}"
                                                            data-nama_lengkap="{{ $data->nama_lengkap }}"
                                                            data-nominal_pinjaman="{{ $data->nominal_pinjaman }}"
                                                            data-tenor="{{ $data->tenor }}"
                                                            data-nama_marketing="{{ $data->nama_marketing }}"
                                                            data-nik="{{ $data->nik }}"
                                                            data-no_hp="{{ $data->no_hp }}"
                                                            data-pinjaman_ke="{{ $data->pinjaman_ke }}"
                                                            data-alasan_topup="{{ $data->alasan_topup }}">Lihat</button>

                                                        <form action="{{ route('supervisor.ro.selesai', $data->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success btn-sm">Selesai
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-success">Selesai diinput</span>
                                                    @endif
                                                </div>
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

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group mb-3">
                        <label for="nama_lengkap">Nama</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control">
                    </div>

                    <!-- Nomor Karyawan -->
                    <div class="form-group mb-3">
                        <label for="nik">Nomor ID Card</label>
                        <input type="text" id="nik" name="nik" class="form-control">
                    </div>

                    <!-- No HP -->
                    <div class="form-group mb-3">
                        <label for="no_hp">No HP</label>
                        <input type="text" id="no_hp" name="no_hp" class="form-control">
                    </div>

                    <!-- Nominal Pinjaman -->
                    <div class="form-group mb-3">
                        <label for="nominal_pinjaman">Nominal Pinjaman</label>
                        <input type="number" id="nominal_pinjaman" name="nominal_pinjaman" class="form-control">
                    </div>

                    <!-- Tenor -->
                    <div class="form-group mb-3">
                        <label for="tenor">Tenor (bulan)</label>
                        <input type="number" id="tenor" name="tenor" class="form-control">
                    </div>

                    <!-- Pengajuan Ke Berapa -->
                    <div class="form-group mb-3">
                        <label for="pinjaman_ke">Pengajuan Ke Berapa</label>
                        <input type="number" id="pinjaman_ke" name="pinjaman_ke" class="form-control">
                    </div>

                    <!-- Pilih Marketing -->
                    <div class="form-group mb-3">
                        <label for="marketing">Pilih Marketing</label>
                        <input type="text" id="nama_marketing" name="nama_marketing" class="form-control">
                    </div>

                    <!-- Alasan Topup -->
                    <div class="form-group mb-3">
                        <label for="alasan_topup">Alasan Topup</label>
                        <textarea name="alasan_topup" id="alasan_topup" class="form-control" cols="20" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Structure -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="sessionToast"
            class="toast align-items-center text-bg-{{ session('success') ? 'success' : 'danger' }} border-0"
            role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') ?? session('error') }}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Script to Trigger Toast -->
    @if (session('success') || session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var sessionToast = new bootstrap.Toast(document.getElementById('sessionToast'), {
                    autohide: true,
                    delay: 3000 // Toast duration in milliseconds
                });
                sessionToast.show();
            });
        </script>
    @endif

    <script>
        const DetailModal = document.getElementById('detailModal');
        DetailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');
            const nama_lengkap = button.getAttribute('data-nama_lengkap');
            const nik = button.getAttribute('data-nik');
            const no_hp = button.getAttribute('data-no_hp');
            const nominal_pinjaman = button.getAttribute('data-nominal_pinjaman');
            const tenor = button.getAttribute('data-tenor');
            const pinjaman_ke = button.getAttribute('data-pinjaman_ke');
            const nama_marketing = button.getAttribute('data-nama_marketing');
            const alasan_topup = button.getAttribute('data-alasan_topup');

            const modal = this;
            modal.querySelector('#id').value = id;
            modal.querySelector('#nama_lengkap').value = nama_lengkap;
            modal.querySelector('#nik').value = nik;
            modal.querySelector('#no_hp').value = no_hp;
            modal.querySelector('#nominal_pinjaman').value = nominal_pinjaman;
            modal.querySelector('#tenor').value = tenor;
            modal.querySelector('#pinjaman_ke').value = pinjaman_ke;
            modal.querySelector('#nama_marketing').value = nama_marketing;
            modal.querySelector('#alasan_topup').value = alasan_topup;
        });
    </script>
@endsection
