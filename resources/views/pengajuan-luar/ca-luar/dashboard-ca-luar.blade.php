@extends('layouts.parent-layout')

@section('content')
    <div class="row">
        <div class="col-md-12 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h1 class="text-white">Dashboard Credit Analyst Luar</h1>
                </div>
            </div>
        </div>

        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Pengajuan Baru <i class="mdi mdi-chart-line mdi-24px float-end"></i>
                    </h4>
                    <a href="{{ route('creditAnalystLuar.pengajuan') }}" class="text-white text-decoration-none">
                        <h2 class="mb-5">{{ $dashboardLuar->count() }} Pengajuan</h2>
                    </a>
                    {{-- <a href="{{ route('creditAnalyst.approval') }}" class="text-white text-decoration-none">
                        <h6 class="card-text">Belum Approval</h6>
                    </a> --}}
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Pengajuan Approve <i
                            class="mdi mdi-check-circle mdi-24px float-end"></i>
                    </h4>
                    <a href="{{ route('creditAnalystLuar.riwayat') }}" class="text-white text-decoration-none">
                        <h2 class="mb-5">{{ $approvedCountLuar }} Pengajuan</h2>
                    </a>
                    {{-- <a href="{{ route('creditAnalyst.riwayat') }}" class="text-white text-decoration-none">
                        <h6 class="card-text">di Approve</h6>
                    </a> --}}
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Pengajuan Reject <i
                            class="mdi mdi-close-circle mdi-24px float-end"></i>
                    </h4>
                    <a href="{{ route('creditAnalystLuar.riwayat') }}" class="text-white text-decoration-none">
                        <h2 class="mb-5">{{ $rejectedCountLuar }} pengajuan</h2>
                    </a>
                    {{-- <a href="{{ route('creditAnalyst.riwayat') }}" class="text-white text-decoration-none">
                        <h6 class="card-text">di Reject</h6>
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
