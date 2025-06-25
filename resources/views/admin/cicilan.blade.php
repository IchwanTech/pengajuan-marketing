@extends('layouts.parent-layout')

@section('page-title', 'Data Cicilan')

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
                        <h2 class="card-title">Data Cicilan</h2>
                        <!-- Button to Open Modal -->
                        <button type="button" class="btn btn-primary btn-sm ms-auto mb-3" data-bs-toggle="modal"
                            data-bs-target="#uploadModal">
                            Import <i class="mdi mdi-upload"></i>
                        </button>
                        <button type="button" class="btn btn-success btn-sm ms-2 mb-3" data-bs-toggle="modal"
                            data-bs-target="#truncateModal">
                            Hapus Semua Data
                        </button>
                        <button type="button" class="btn btn-danger btn-sm ms-2 mb-3" id="bulk-delete-btn"
                            style="display: none;"><i class="fa fa-trash"></i> Hapus Cicilan</button>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>No</th>
                                    <th>ID Pinjam</th>
                                    <th>Nama Konsumen</th>
                                    <th>Tanggal Pencairan</th>
                                    <th>Pokok Pinjaman</th>
                                    {{-- <th>Tenor</th> --}}
                                    <th>Divisi</th>
                                    <th>Cicilan Perbulan</th>
                                    {{-- <th>Sisa Tenor</th> --}}
                                    <th>Sisa Pinjaman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cicilans as $cicilan)
                                    <tr data-id="{{ $cicilan->id }}">
                                        <td>
                                            @if ($cicilan->sisa_pinjaman == 0 && $cicilan->sisa_tenor == 0)
                                                <input type="checkbox" class="lunas-checkbox" value="{{ $cicilan->id }}">
                                            @endif
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $cicilan->id_pinjam }}</td>
                                        <td>
                                            <div class="mb-1">
                                                {{ $cicilan->nama_konsumen }}
                                            </div>
                                            Pinjaman Ke-{{ $cicilan->pinjaman_ke }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($cicilan->tgl_pencairan)->translatedFormat('d F Y') }}
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                Rp. {{ number_format($cicilan->pokok_pinjaman, 0, ',', '.') }}
                                            </div>
                                            <small>
                                                {{ $cicilan->jumlah_tenor_seharusnya }}
                                                {{ $cicilan->divisi == 'Borongan' ? 'Periode' : 'Bulan' }}
                                            </small>
                                        </td>
                                        <td class="text-center" id="divisi{{ $cicilan->id }}">{{ $cicilan->divisi }}</td>
                                        <td>Rp. {{ number_format($cicilan->cicilan_perbulan, 0, ',', '.') }}</td>
                                        <td id="sisa_pinjaman_container{{ $cicilan->id }}">
                                            <div class="mb-1">
                                                Rp. <span
                                                    id="sisa_pinjaman{{ $cicilan->id }}">{{ number_format($cicilan->sisa_pinjaman, 0, ',', '.') }}</span>
                                            </div>
                                            <small>
                                                Sisa <span
                                                    id="sisa_tenor{{ $cicilan->id }}">{{ $cicilan->sisa_tenor }}</span>
                                                Bulan
                                            </small>
                                        </td>
                                        <td>
                                            {{-- Edit Button dengan data attributes --}}
                                            <button type="button" class="btn btn-info btn-sm edit-btn"
                                                data-id="{{ $cicilan->id }}" data-divisi="{{ $cicilan->divisi }}"
                                                data-sisa-tenor="{{ $cicilan->sisa_tenor }}"
                                                data-sisa-pinjaman="{{ $cicilan->sisa_pinjaman }}" data-bs-toggle="modal"
                                                data-bs-target="#editModal">
                                                <i class="fa fa-edit"></i> Edit
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
                    <form action="{{ route('admin.cicilan.import') }}" method="POST" enctype="multipart/form-data">
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

    <!-- Modal -->
    <div class="modal fade" id="truncateModal" tabindex="-1" aria-labelledby="truncateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="truncateModalLabel">Konfirmasi Hapus Semua Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus semua data? Operasi ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('admin.cicilan.truncate') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Ya, Hapus Semua Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Cicilan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="divisi">Divisi</label>
                            <select name="divisi" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Divisi --</option>
                                <option value="Borongan">Borongan</option>
                                <option value="Bukan Borongan">Bukan Borongan</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="sisa_tenor">Sisa Tenor</label>
                            <input type="number" class="form-control" name="sisa_tenor" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="sisa_pinjaman">Sisa Pinjaman</label>
                            <input type="number" class="form-control" name="sisa_pinjaman" required>
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

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="sessionToast" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage">
                    <!-- Pesan akan dimasukkan lewat jQuery -->
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle click on edit button
            $('.edit-btn').click(function() {
                var id = $(this).data('id');
                var divisi = $(this).data('divisi');
                var sisaTenor = $(this).data('sisa-tenor');
                var sisaPinjaman = $(this).data('sisa-pinjaman');

                // Set form values
                $('#editModal select[name="divisi"]').val(divisi);
                $('#editModal input[name="sisa_tenor"]').val(sisaTenor);
                $('#editModal input[name="sisa_pinjaman"]').val(sisaPinjaman);

                // Update form action
                $('#updateForm').attr('action', '/admin/data-cicilan/' + id);
            });

            // Handle form submission
            $('#updateForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        $('#editModal').modal('hide');

                        // Update table data
                        var rowId = url.split('/').pop();
                        var newDivisi = form.find('select[name="divisi"]').val();
                        var newSisaPinjaman = form.find('input[name="sisa_pinjaman"]').val();
                        var newSisaTenor = form.find('input[name="sisa_tenor"]').val();

                        // Format number
                        var formattedSisaPinjaman = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(newSisaPinjaman);

                        // Update table cells
                        $('#divisi' + rowId).text(newDivisi);
                        $('#sisa_pinjaman' + rowId).text(formattedSisaPinjaman.replace('IDR',
                            'Rp.'));
                        $('#sisa_tenor' + rowId).text(newSisaTenor);

                        // Show toast
                        showToast('success', response.message);
                    },
                    error: function(xhr) {
                        showToast('error', 'Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });

            function showToast(type, message) {
                var toast = $('#sessionToast');
                toast.find('#toastMessage').text(message);
                toast.removeClass('text-bg-success text-bg-danger');

                if (type === 'success') {
                    toast.addClass('text-bg-success');
                } else {
                    toast.addClass('text-bg-danger');
                }

                new bootstrap.Toast(toast[0]).show();
            }
        });

        const selectAll = document.getElementById('select-all');
        const lunasCheckboxes = document.querySelectorAll('.lunas-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

        // Toggle semua checkbox expired
        selectAll.addEventListener('change', function() {
            lunasCheckboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkDeleteButton();
        });

        // Tampilkan tombol delete jika ada yang dicentang
        lunasCheckboxes.forEach(chk => {
            chk.addEventListener('change', toggleBulkDeleteButton);
        });

        function toggleBulkDeleteButton() {
            const anyChecked = [...lunasCheckboxes].some(cb => cb.checked);
            bulkDeleteBtn.style.display = anyChecked ? 'inline-block' : 'none';

            // Update master checkbox status
            const allChecked = [...lunasCheckboxes].every(cb => cb.checked);
            selectAll.checked = allChecked;
        }

        // Submit bulk delete
        bulkDeleteBtn.addEventListener('click', () => {
            if (!confirm('Yakin ingin menghapus voucher kadaluarsa yang dipilih?')) return;

            const selectedIds = [...lunasCheckboxes]
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            fetch('{{ route('admin.cicilan.bulkDeleteCicilan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: selectedIds
                    })
                })
                .then(res => res.json())
                .then(data => {
                    // Tampilkan toast sukses
                    $('#toastMessage').text(data.message);
                    $('#sessionToast')
                        .removeClass('text-bg-danger')
                        .addClass('text-bg-success');

                    new bootstrap.Toast($('#sessionToast')[0]).show();

                    // Hapus baris dari tabel
                    selectedIds.forEach(id => {
                        $(`tr[data-id="${id}"]`).remove();
                    });

                    // Perbarui tampilan tombol bulk delete
                    toggleBulkDeleteButton();
                })
                .catch(err => {
                    console.error(err);

                    // Tampilkan toast error
                    $('#toastMessage').text('Terjadi kesalahan saat menghapus data.');
                    $('#sessionToast')
                        .removeClass('text-bg-success')
                        .addClass('text-bg-danger');

                    new bootstrap.Toast($('#sessionToast')[0]).show();
                });
        });
    </script>

@endsection
