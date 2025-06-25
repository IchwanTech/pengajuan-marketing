@extends('layouts.parent-layout')

@section('page-title', 'List User')

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
                    <div class="d-flex align-items-center border-bottom mb-3">
                        <h6 class="card-title">List Data User</h6>
                        <button class="btn btn-gradient-success btn-sm ms-auto mb-3" onclick="showAddUserModal()">
                            Tambah User</i>
                        </button>
                    </div>

                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-center">Kode Inisial</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($user)
                                    @foreach ($user as $data)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                {{ Str::title($data->name) ?? 'User Tidak Ditemukan' }}
                                                @if ($data->usertype == 'marketing')
                                                    <div class="mt-2">
                                                        <small>{{ $data->total_approved_hm }} / {{ $data->total_nasabah }}
                                                            Nasabah</small>
                                                        @if ($data->type == 'luar')
                                                            <br>
                                                            <small>{{ $data->total_approved_ca }} /
                                                                {{ $data->total_nasabah_luar }} Nasabah</small>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                {{ Str::ucfirst($data->email) ?? 'Email Tidak Ditemukan' }}
                                            </td>
                                            <td>
                                                <div class="mb-1">
                                                    @if ($data->usertype == 'head')
                                                        Head Marketing
                                                    @elseif ($data->usertype == 'credit')
                                                        Credit Analyst
                                                    @else
                                                        {{ Str::title($data->usertype) ?? 'Role Tidak Ditemukan' }}
                                                    @endif
                                                </div>
                                                <small>
                                                    {{ Str::ucfirst($data->type) ?? 'Type Tidak Ditemukan' }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="mb-1">
                                                    {{ $data->kode_marketing ?? 'Kode Tidak Ditemukan' }}
                                                </div>
                                                <small>
                                                    {{ $data->is_active == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn btn-group">
                                                    {{-- <button class="btn btn-gradient-primary btn-sm"
                                                        onclick="showEditModal('{{ route('superAdmin.list.user.update', $data->id) }}', '{{ $data->name }}', '{{ $data->email }}', '{{ $data->usertype }}', '{{ $data->type }}', '{{ $data->kode_marketing }}', '{{ $data->is_active }}')">
                                                        Edit <i class="mdi mdi-pencil"></i>
                                                    </button> --}}
                                                    <button class="btn btn-gradient-primary btn-sm"
                                                        data-action="{{ route('superAdmin.list.user.update', $data->id) }}"
                                                        data-name="{{ $data->name }}" data-email="{{ $data->email }}"
                                                        data-usertype="{{ $data->usertype }}"
                                                        data-type="{{ $data->type }}"
                                                        data-kode-marketing="{{ $data->kode_marketing }}"
                                                        data-is-active="{{ $data->is_active }}"
                                                        onclick="showEditModalFromButton(this)">
                                                        Edit <i class="mdi mdi-pencil"></i>
                                                    </button>

                                                    <button class="btn btn-gradient-danger btn-sm"
                                                        onclick="showDeleteModal('{{ route('superAdmin.list.user.delete', $data->id) }}')">Hapus
                                                        <i class="mdi mdi-trash-can"></i></button>
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

    <!-- Modal Tambah User -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addUserForm" action="{{ route('superAdmin.list.user.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Tambah User Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="addName" class="form-label">Nama</label>
                            <input type="text" name="name" id="addName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="addEmail" class="form-label">Email</label>
                            <input type="email" name="email" id="addEmail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="addPassword" class="form-label">Password</label>
                            <input type="password" name="password" id="addPassword" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="addUsertype" class="form-label">Usertype</label>
                            <select class="form-select" id="addUsertype" name="usertype" required>
                                <option value="">-- Pilih Usertype --</option>
                                <option value="head">Head Marketing</option>
                                <option value="credit">Credit Analyst</option>
                                <option value="admin">Admin</option>
                                <option value="marketing">Marketing</option>
                                <option value="surveyor">Surveyor</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addType" class="form-label">Type</label>
                            <select class="form-select" id="addType" name="type">
                                <option value="">-- Pilih Type --</option>
                                <option value="dalam">Dalam</option>
                                <option value="luar">Luar</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addKodeMarketing" class="form-label">Kode Inisial</label>
                            <input type="text" name="kode_marketing" id="addKodeMarketing" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="addStatus" class="form-label">Status</label>
                            <select class="form-select" id="addStatus" name="is_active">
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password (Opsional)</label>
                            <input type="password" name="password" id="editPassword" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editUsertype" class="form-label">Usertype</label>
                            <select class="form-select" id="editUsertype" name="usertype">
                                <option value="">-- Pilih Usertype --</option>
                                <option value="head">Head Marketing</option>
                                <option value="credit">Credit Analyst</option>
                                <option value="admin">Admin</option>
                                <option value="marketing">Marketing</option>
                                <option value="surveyor">Surveyor</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editType" class="form-label">type</label>
                            <select class="form-select" id="editType" name="type">
                                <option value="">-- Pilih Type --</option>
                                <option value="dalam">Dalam</option>
                                <option value="luar">Luar</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editKodeMarketing" class="form-label">Kode Inisial</label>
                            <input type="text" name="kode_marketing" id="editKodeMarketing" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="is_active">
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddUserModal() {
            const addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            addUserModal.show();
        }

        function showEditModalFromButton(button) {
            showEditModal(
                button.dataset.action,
                button.dataset.name,
                button.dataset.email,
                button.dataset.usertype,
                button.dataset.type,
                button.dataset.kodeMarketing,
                button.dataset.isActive
            );
        }

        function showEditModal(action, name, email, usertype, type, kode_marketing, is_active) {
            const form = document.getElementById('editForm');
            form.action = action;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPassword').value = ''; // Reset password input
            document.getElementById('editUsertype').value = usertype;
            document.getElementById('editType').value = type;
            document.getElementById('editKodeMarketing').value = kode_marketing ?? '';
            document.getElementById('editStatus').value = is_active;

            const editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }

        function showDeleteModal(action) {
            const form = document.getElementById('deleteForm');
            form.action = action;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }
    </script>

@endsection
