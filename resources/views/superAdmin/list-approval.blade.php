@extends('layouts.parent-layout')

@section('page-title')
    <a href="{{ url()->previous() }}" style="text-decoration: none" class="text-dark">Data Pengajuan</a>
@endsection
@section('breadcrumb-title', '/ Approval Pengajuan')

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
                        <h6 class="card-title mb-3">Approval Pengajuan</h6>
                    </div>
                    <div>
                        <p class="mb-0 mt-3">Nama Nasabah : {{ $nasabah->nama_lengkap }}</p>
                        <p class="mb-0">Status Pengajuan : {{ Str::title($nasabah->pengajuan->status) }}</p>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped nowrap" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Approval</th>
                                    <th>Status Approval</th>
                                    <th>Waktu Approval</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nasabah->pengajuan->approval as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $data->user->name }}
                                            @if ($data->is_banding == '1')
                                                <div class="mt-2">
                                                    <small class="text-danger">Banding</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('superAdmin.list.approval.update', $data->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select class="form-select form-select-sm" name="status"
                                                    onchange="this.form.submit()">
                                                    <option value="approved"
                                                        {{ $data->status == 'approved' ? 'selected' : '' }}>
                                                        Approved
                                                    </option>
                                                    <option value="rejected"
                                                        {{ $data->status == 'rejected' ? 'selected' : '' }}>
                                                        Rejected
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                {{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('d F Y') }}
                                            </div>
                                            <small>{{ $data->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-gradient-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#detailKeteranganModal"
                                                data-keterangan="{{ $data->keterangan }}">
                                                Keterangan
                                            </button>
                                        </td>
                                        <td>
                                            <div class="btn btn-group">
                                                <button type="button" class="btn btn-gradient-danger btn-sm"
                                                    onclick="showDeleteModal('{{ route('superAdmin.list.approval.delete', $data->id) }}')">Hapus</button>
                                                <button class="btn btn-gradient-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editKeteranganModal"
                                                    data-keterangan="{{ $data->keterangan }}"
                                                    data-action="{{ route('superAdmin.list.approval.update', $data->id) }}">
                                                    Edit
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-first">
                        <a href="{{ route('superAdmin.list.pengajuan') }}"
                            class="btn btn-secondary btn-sm rounded">Kembali</a>
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
                    <!--<p id="modalKeterangan">Memuat...</p>-->
                    <textarea id="modalKeterangan" class="form-control" style="width: 100%; height: 150px;" readonly></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Keterangan -->
    <div class="modal fade" id="editKeteranganModal" tabindex="-1" aria-labelledby="editKeteranganModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKeteranganModalLabel">Edit Keterangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKeteranganForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                style="width: 100%; height: 150px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showDeleteModal(action) {
            const form = document.getElementById('deleteForm');
            form.action = action;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const detailKeteranganModal = document.getElementById('detailKeteranganModal');
            const modalKeteranganInput = document.getElementById('modalKeterangan');

            detailKeteranganModal.addEventListener('show.bs.modal', function(event) {
                // Ambil button yang memicu modal
                const button = event.relatedTarget;

                // Ambil data keterangan dari atribut data
                const keterangan = button.getAttribute('data-keterangan');

                // Tampilkan keterangan di input modal
                modalKeteranganInput.value = keterangan;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const editKeteranganModal = document.getElementById('editKeteranganModal');
            const keteranganInput = document.getElementById('keterangan');
            const editForm = document.getElementById('editKeteranganForm');

            editKeteranganModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const keterangan = button.getAttribute('data-keterangan');
                const action = button.getAttribute('data-action');

                keteranganInput.value = keterangan;
                editForm.action = action;
            });
        });
    </script>
@endsection
