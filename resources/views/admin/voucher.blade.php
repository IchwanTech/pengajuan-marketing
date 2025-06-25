@extends('layouts.parent-layout')

@section('page-title', 'Voucher')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center border-bottom">
                        <h2 class="card-title">Data Voucher</h2>
                        <!-- Button to Open Modal -->
                        <button type="button" class="btn btn-primary btn-sm ms-auto mb-3" data-bs-toggle="modal"
                            data-bs-target="#uploadModal">
                            Import <i class="mdi mdi-upload"></i>
                        </button>
                        <button type="button" class="btn btn-success btn-sm ms-2 mb-3" data-bs-toggle="modal"
                            data-bs-target="#manualModal">
                            Tambah Data Manual
                        </button>

                        <!-- Bulk Delete Button -->
                        <button id="bulk-delete-btn" class="btn btn-danger btn-sm ms-2 mb-3" style="display: none;">
                            Hapus Voucher
                        </button>
                    </div>

                    <div class="table-responsive mt-2">
                        <table id="voucher" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" id="select-all-expired">
                                    </th>
                                    <th class="text-center">No</th>
                                    <th>Nama Nasabah</th>
                                    <th class="text-center">Kode Voucher</th>
                                    <th class="text-center">Tipe Voucher</th>
                                    <th class="text-center">Voucher</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $today = \Carbon\Carbon::today();
                                @endphp
                                @foreach ($vouchers as $voucher)
                                    <tr data-id="{{ $voucher->id }}">
                                        <!-- Checkbox -->
                                        <td class="text-center nowrap">
                                            @if (\Carbon\Carbon::parse($voucher->kadaluarsa)->lt($today))
                                                <input type="checkbox" class="expired-checkbox" value="{{ $voucher->id }}">
                                            @endif
                                        </td>
                                        <td class="text-center nowrap">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <div class="mb-1" id="nama-{{ $voucher->id }}">
                                                {{ $voucher->nama }}
                                            </div>
                                            <small id="nik-{{ $voucher->id }}">NIK : {{ $voucher->nik }}</small>
                                        </td>
                                        <td class="text-center nowrap">
                                            <div class="mb-1" id="kode_voucher-{{ $voucher->id }}">
                                                {{ $voucher->kode_voucher }}
                                            </div>
                                            <small>
                                                Kadaluarsa :
                                                <span id="kadaluarsa-{{ $voucher->id }}">
                                                    {{ \Carbon\Carbon::parse($voucher->kadaluarsa)->translatedFormat('d F Y') }}
                                                </span>
                                            </small>
                                        </td>
                                        <td class="text-center nowrap" id="type-{{ $voucher->id }}">
                                            {{ Str::title($voucher->type) ?? 'Tidak Ada Data' }}
                                        </td>
                                        <td class="text-center nowrap">
                                            @if ($voucher->voucher)
                                                <a id="voucher-link-{{ $voucher->id }}"
                                                    href="{{ asset('storage/vouchers/' . $voucher->voucher) }}"
                                                    target="_blank">
                                                    <img id="voucher-img-{{ $voucher->id }}"
                                                        src="{{ asset('storage/vouchers/' . $voucher->voucher) }}"
                                                        style="max-width: 100%; height: auto; border-radius: 0;"
                                                        alt="Voucher">
                                                </a>
                                            @else
                                                <form id="form-{{ $voucher->id }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="file" name="voucher" id="file-{{ $voucher->id }}"
                                                        class="d-none" onchange="uploadVoucher({{ $voucher->id }})">
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="document.getElementById('file-{{ $voucher->id }}').click();">
                                                        Upload Voucher
                                                    </button>
                                                </form>
                                                <div id="loading-{{ $voucher->id }}" style="display: none;">
                                                    <span>Uploading...</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center nowrap">
                                            @if ($voucher->type == 'Produksi')
                                                @if ($voucher->is_claim == '0')
                                                    <form action="{{ route('admin.voucher.claim', $voucher->id) }}"
                                                        method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm"
                                                            onclick="return confirm('Apakah Anda yakin ingin klaim voucher ini?')">Klaim</button>
                                                    </form>
                                                @else
                                                    <span class="badge badge-success">Sudah diklaim</span>
                                                @endif
                                            @else
                                                <span class="badge badge-danger">Tidak bisa klaim</span>
                                            @endif
                                        </td>
                                        <td class="text-center nowrap">
                                            <!-- Tombol Edit -->
                                            <button type="button" class="btn btn-info btn-sm edit-btn"
                                                data-id="{{ $voucher->id }}" data-nama="{{ $voucher->nama }}"
                                                data-nik="{{ $voucher->nik }}"
                                                data-kode_voucher="{{ $voucher->kode_voucher }}"
                                                data-kadaluarsa="{{ $voucher->kadaluarsa }}"
                                                data-type="{{ $voucher->type }}" data-saldo="{{ $voucher->saldo }}"
                                                data-voucher="{{ $voucher->voucher }}" data-bs-toggle="modal"
                                                data-bs-target="#editModal">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <!-- Tombol Hapus -->
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $voucher->id }}" data-nama="{{ $voucher->nama }}"
                                                data-kode_voucher="{{ $voucher->kode_voucher }}" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Data Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Upload -->
                    <form action="{{ route('admin.voucher.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="file" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Input Manual -->
    <div class="modal fade" id="manualModal" tabindex="-1" aria-labelledby="manualModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualModalLabel">Input Data Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Input -->
                    <form action="{{ route('admin.voucher.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="kode_voucher" class="form-label">Kode Voucher</label>
                            <input type="text" class="form-control" id="kode_voucher" name="kode_voucher" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="kadaluarsa" class="form-label">Kadaluarsa</label>
                            <input type="date" class="form-control" id="kadaluarsa" name="kadaluarsa" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="type" class="form-label">Tipe</label>
                            <select class="form-select" id="type" name="type" onchange="toggleSaldoInput()"
                                required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Doorprize">Doorprize</option>
                                <option value="Produksi">Produksi</option>
                            </select>
                        </div>
                        <div class="form-group mb-3" id="saldoGroup" style="display: none;">
                            <label for="saldo" class="form-label">Saldo</label>
                            <input type="number" class="form-control" id="saldo" name="saldo">
                        </div>
                        <div class="form-group mb-3">
                            <label for="voucher" class="form-label">Upload Voucher</label>
                            <input type="file" class="form-control" id="voucher" name="voucher">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Single Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="editNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="nama" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="editNik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="editNik" name="nik" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="editKodeVoucher" class="form-label">Kode Voucher</label>
                            <input type="text" class="form-control" id="editKodeVoucher" name="kode_voucher"
                                required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="editKadaluarsa" class="form-label">Kadaluarsa</label>
                            <input type="date" class="form-control" id="editKadaluarsa" name="kadaluarsa" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="editType" class="form-label">Tipe</label>
                            <select class="form-select" id="editType" name="type" onchange="toggleSaldoInputEdit()"
                                required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Doorprize">Doorprize</option>
                                <option value="Produksi">Produksi</option>
                            </select>
                        </div>
                        <div class="form-group mb-3" id="editSaldoGroup" style="display: none;">
                            <label for="editSaldo" class="form-label">Saldo</label>
                            <input type="number" class="form-control" id="editSaldo" name="saldo">
                        </div>
                        <div class="form-group mb-3">
                            <label for="editVoucher" class="form-label">Upload Voucher</label>
                            <input type="file" class="form-control" id="editVoucher" name="voucher">
                            <small class="form-text mt-1" id="currentVoucher"></small>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Single Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus voucher <strong id="deleteVoucherName"></strong> dengan kode
                        <strong id="deleteVoucherCode"></strong>?
                    </p>
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

    <!-- Tambahkan elemen toast -->
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div id="sessionToast" class="toast text-bg-success border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const selectAllExpired = document.getElementById('select-all-expired');
        const expiredCheckboxes = document.querySelectorAll('.expired-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        // Toggle semua checkbox expired
        selectAllExpired.addEventListener('change', function() {
            expiredCheckboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkDeleteButton();
        });

        // Tampilkan tombol delete jika ada yang dicentang
        expiredCheckboxes.forEach(chk => {
            chk.addEventListener('change', toggleBulkDeleteButton);
        });

        function toggleBulkDeleteButton() {
            const anyChecked = [...expiredCheckboxes].some(cb => cb.checked);
            bulkDeleteBtn.style.display = anyChecked ? 'inline-block' : 'none';

            // Update master checkbox status
            const allChecked = [...expiredCheckboxes].every(cb => cb.checked);
            selectAllExpired.checked = allChecked;
        }

        // Submit bulk delete
        bulkDeleteBtn.addEventListener('click', () => {
            if (!confirm('Yakin ingin menghapus voucher kadaluarsa yang dipilih?')) return;

            const selectedIds = [...expiredCheckboxes]
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            fetch('{{ route('admin.voucher.bulkDeleteExpired') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: selectedIds
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    location.reload();
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menghapus data.');
                });
        });

        // Handle Edit Button Click
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const form = document.getElementById('updateForm');
                form.action = `/admin/voucher/${id}`;

                // Populate form fields
                document.getElementById('editNama').value = this.dataset.nama;
                document.getElementById('editNik').value = this.dataset.nik;
                document.getElementById('editKodeVoucher').value = this.dataset.kode_voucher;
                document.getElementById('editKadaluarsa').value = this.dataset.kadaluarsa;
                document.getElementById('editType').value = this.dataset.type;
                document.getElementById('editSaldo').value = this.dataset.saldo;
                document.getElementById('currentVoucher').textContent =
                    this.dataset.voucher ? `File saat ini: ${this.dataset.voucher}` : '';

                // Toggle saldo input
                toggleSaldoInputEdit();
            });
        });

        // Handle Delete Button Click
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const form = document.getElementById('deleteForm');
                form.action = `/admin/voucher/${id}`;
                document.getElementById('deleteVoucherName').textContent = this.dataset.nama;
                document.getElementById('deleteVoucherCode').textContent = this.dataset.kode_voucher;
            });
        });

        // Handle Edit Form Submission
        $('#updateForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData(form[0]);
            const url = form.attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#editModal').modal('hide');
                    updateTableRow(response);
                    showToast('Voucher berhasil diperbarui!', 'success');
                },
                error: function(xhr) {
                    showToast('Terjadi kesalahan: ' + xhr.responseText, 'error');
                }
            });
        });

        // Handle Delete Form Submission
        $('#deleteForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    $(`tr[data-id="${response.id}"]`).remove();
                    showToast('Voucher berhasil dihapus!', 'success');
                },
                error: function(xhr) {
                    showToast('Terjadi kesalahan: ' + xhr.responseText, 'error');
                }
            });
        });

        function toggleSaldoInputEdit() {
            const type = document.getElementById('editType').value;
            document.getElementById('editSaldoGroup').style.display =
                type === 'Produksi' ? 'block' : 'none';
        }

        function updateTableRow(data) {
            const row = $(`tr[data-id="${data.id}"]`);
            const dateOptions = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };

            // Update Nama & NIK
            row.find('td:eq(1)').html(`
                <div class="mb-1" id="nama-${data.id}">${data.nama}</div>
                <small id="nik-${data.id}">NIK : ${data.nik}</small>
            `);

            // Update Kode Voucher & Expired
            row.find('td:eq(2)').html(`
                <div class="mb-1" id="kode_voucher-${data.id}">${data.kode_voucher}</div>
                <small>Kadaluarsa : 
                    <span id="kadaluarsa-${data.id}">
                        ${new Date(data.kadaluarsa).toLocaleDateString('id-ID', dateOptions)}
                    </span>
                </small>
            `);

            // Update Tipe
            row.find('td:eq(3)').html(`
        ${data.type.charAt(0).toUpperCase() + data.type.slice(1)}
    `);

            // Update Voucher
            let voucherHtml = '';
            if (data.voucher_url) {
                voucherHtml = `
                    <a id="voucher-link-${data.id}" href="${data.voucher_url}" target="_blank">
                        <img id="voucher-img-${data.id}" 
                            src="${data.voucher_url}" 
                            style="max-width: 100%; height: auto; border-radius: 0;" 
                            alt="Voucher">
                    </a>`;
            } else {
                voucherHtml = `
                    <form id="form-${data.id}">
                        <input type="file" name="voucher" id="file-${data.id}" 
                            class="d-none" onchange="uploadVoucher(${data.id})">
                        <button type="button" class="btn btn-success btn-sm"
                            onclick="document.getElementById('file-${data.id}').click()">
                            Upload Voucher
                        </button>
                    </form>
                    <div id="loading-${data.id}" style="display: none;">
                        <span>Uploading...</span>
                    </div>`;
            }
            row.find('td:eq(4)').html(voucherHtml);

            // Update Status
            let statusHtml = '';
            if (data.type === 'Produksi') {
                statusHtml = data.is_claim == '0' ?
                    `<form action="/admin/voucher/${data.id}/claim" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Klaim</button>
                    </form>` :
                    '<span class="badge badge-success">Sudah diklaim</span>';
            } else {
                statusHtml = '<span class="badge badge-danger">Tidak bisa klaim</span>';
            }
            row.find('td:eq(5)').html(statusHtml);
        }

        function showToast(message, type) {
            const toast = $('#sessionToast');
            const toastMessage = $('#toastMessage');
            toastMessage.text(message);
            toast.removeClass('text-bg-success text-bg-danger');
            toast.addClass(type === 'success' ? 'text-bg-success' : 'text-bg-danger');
            toast.toast('show');
        }

        function uploadVoucher(voucherId) {
            const fileInput = document.getElementById(`file-${voucherId}`);
            const loadingIndicator = document.getElementById(`loading-${voucherId}`);
            const formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('voucher', fileInput.files[0]);

            // Tampilkan loading
            if (loadingIndicator) {
                loadingIndicator.style.display = 'block';
            }

            fetch(`{{ route('admin.voucher.upload', '') }}/${voucherId}`, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (loadingIndicator) {
                        loadingIndicator.style.display = 'none';
                    }

                    if (data.success) {
                        // Update row di tabel
                        const row = document.querySelector(`tr[data-id="${data.id}"]`);
                        if (row) {
                            // Update kolom voucher
                            const voucherCell = row.querySelector('td:nth-child(5)');
                            voucherCell.innerHTML = `
                    <a href="${data.voucher_url}?${Date.now()}" target="_blank">
                        <img src="${data.voucher_url}?${Date.now()}" 
                             style="max-width: 100%; height: auto; border-radius: 0;" 
                             alt="Voucher">
                    </a>
                `;

                            // Update kolom status
                            const statusCell = row.querySelector('td:nth-child(6)');
                            if (data.type === 'Produksi') {
                                statusCell.innerHTML = data.is_claim == '0' ?
                                    `<form action="/admin/voucher/${data.id}/claim" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">Klaim</button>
                        </form>` :
                                    '<span class="badge badge-success">Sudah diklaim</span>';
                            }
                        }
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    if (loadingIndicator) {
                        loadingIndicator.style.display = 'none';
                    }
                    showToast('Error: ' + error.message, 'error');
                });
        }

        function toggleSaldoInput() {
            const type = document.getElementById('type').value;
            const saldoGroup = document.getElementById('saldoGroup');
            saldoGroup.style.display = type === 'Produksi' ? 'block' : 'none';
        }

        function toggleSaldoInputEdit() {
            const type = document.getElementById('editType').value;
            document.getElementById('editSaldoGroup').style.display =
                type === 'Produksi' ? 'block' : 'none';
        }

        function submitForm(voucherId) {
            document.getElementById('form-' + voucherId).submit();
        }
    </script>

@endsection
