@extends('layouts.parent-layout')

@section('content')
    <div class="row">
        <div class="col-md-12 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h1 class="text-white">Dashboard Marketing</h1>
                </div>
            </div>
        </div>
        <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image" />
                    <h4 class="font-weight-normal mb-3">Jumlah Pengajuan <i
                            class="mdi mdi-chart-line mdi-24px float-end"></i>
                    </h4>
                    <a href="{{ route('marketing.data.pengajuan') }}" class="text-white text-decoration-none">
                        <h2 class="mb-5">{{ $dashboard->count() }} Pengajuan</h2>
                    </a>
                    <a href="{{ route('marketing.data.pengajuan') }}" class="text-white text-decoration-none">
                        {{-- <h6 class="card-text">Belum Approval</h6> --}}
                    </a>
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
                    <a href="{{ route('marketing.data.pengajuan') }}" class="text-white text-decoration-none">
                        <h2 class="mb-5">{{ $totalApprovedHm }} Pengajuan</h2>
                    </a>
                    <a href="{{ route('marketing.data.pengajuan') }}" class="text-white text-decoration-none">
                        <h6 class="card-text">di Approve</h6>
                    </a>
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
                    <a href="{{ route('marketing.data.pengajuan') }}" class="text-white text-decoration-none">
                        <h2 class="mb-5">{{ $totalRejectedHm }} pengajuan</h2>
                    </a>
                    <a href="{{ route('marketing.data.pengajuan') }}" class="text-white text-decoration-none">
                        <h6 class="card-text">di Reject</h6>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Traffic Status Approval</h4>
                    <div class="doughnutjs-wrapper d-flex justify-content-center">
                        <canvas id="traffic-chart"></canvas>
                    </div>
                    <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var totalPending = {{ $totalPendingHm }};
        var totalApproved = {{ $totalApprovedHm }};
        var totalRejected = {{ $totalRejectedHm }};
    </script>
@endsection
