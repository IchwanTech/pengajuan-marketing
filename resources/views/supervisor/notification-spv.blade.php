@extends('layouts.parent-layout')

@section('page-title', 'Notifikasi Supervisor')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between border-bottom">
                        <h6 class="card-title mb-3">Notifikasi</h6>
                        <form action="{{ route('supervisor.notifikasi.read-all') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">
                                Tandai Semua sebagai Dibaca
                            </button>
                        </form>
                    </div>
                    <div class="table-responsive mt-2">
                        <table id="myTable" class="table table-bordered nowrap" style="width:100%;">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Pesan</th> <!-- Header untuk kolom Pesan -->
                                    <th>Status</th> <!-- Header untuk kolom Status -->
                                    <th>Aksi</th> <!-- Header untuk kolom Aksi -->
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notif as $item)
                                    <tr class="{{ $item->read ? 'table-white' : 'table-warning' }}">
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-wrap">
                                            <div class="mb-2">
                                                {{ $item->pesan }}
                                            </div>
                                            <small>{{ $item->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>{{ $item->read ? 'Dibaca' : 'Belum Dibaca' }}</td>
                                        <td>
                                            @if (!$item->read)
                                                <!-- Tombol Mark as Read -->
                                                <form action="{{ route('supervisor.notifikasi.read', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        Tandai sebagai Dibaca
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-success">Sudah Dibaca</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada notifikasi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Structure -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="sessionToast"
            class="toast align-items-center text-bg-{{ session('success') ? 'success' : 'danger' }} border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') ?? session('error') }}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
@endsection
