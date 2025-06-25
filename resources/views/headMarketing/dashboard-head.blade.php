@extends('layouts.parent-layout')

@section('content')
    <div class="row">
        <div class="col-md-12 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h1 class="text-white">Dashboard Head of Marketing</h1>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-primary card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="text-white">Total Marketing <i class="mdi mdi-account mdi-24px float-end"></i></h4>
                    <a href="{{ route('headMarketing.tracking') }}" class="text-white text-decoration-none">
                        <h2 class="text-white">{{ $totalMarketing }} Marketing</h2>
                    </a>
                </div>
            </div>
        </div> --}}
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-4">Pengajuan Baru <i class="mdi mdi-chart-line mdi-24px float-end"></i>
                    </h4>
                    <a href="{{ route('headMarketing.approval') }}" class="text-white text-decoration-none">
                        <h3>{{ $dashboard->count() }} Pengajuan Dalam</h3>
                    </a>
                    <a href="{{ route('headMarketing.data.pengajuan.luar') }}" class="text-white text-decoration-none">
                        <h3 class="mb-4">{{ $dashboardLuar->count() }} Pengajuan Luar</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-4">Pengajuan Approve <i
                            class="mdi mdi-check-circle mdi-24px float-end"></i>
                    </h4>
                    <a href="{{ route('headMarketing.riwayat') }}" class="text-white text-decoration-none">
                        <h3>{{ $approvedCount }} Pengajuan Dalam</h3>
                    </a>
                    <a href="{{ route('headMarketing.data.riwayat.luar') }}" class="text-white text-decoration-none">
                        <h3 class="mb-4">{{ $approvedCountLuar }} Pengajuan Luar</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-4">Pengajuan Reject <i
                            class="mdi mdi-close-circle mdi-24px float-end"></i>
                    </h4>
                    <a href="{{ route('headMarketing.riwayat') }}" class="text-white text-decoration-none">
                        <h3>{{ $rejectedCount }} pengajuan Dalam</h3>
                    </a>
                    <a href="{{ route('headMarketing.data.riwayat.luar') }}" class="text-white text-decoration-none">
                        <h3 class="mb-4">{{ $rejectedCountLuar }} pengajuan Luar</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
