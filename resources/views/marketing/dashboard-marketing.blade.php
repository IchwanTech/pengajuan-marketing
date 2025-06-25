{{-- @extends('layouts.parent-layout') --}}
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

        @if (Auth::user()->type == 'luar')
            {{-- Tabs untuk Dalam dan Luar --}}
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Status Pengajuan</h4>

                        {{-- Tab Navigation --}}
                        <ul class="nav nav-tabs" id="pengajuanTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="dalam-tab" data-bs-toggle="tab" href="#dalam" role="tab"
                                    aria-controls="dalam" aria-selected="true">Pengajuan Dalam</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="luar-tab" data-bs-toggle="tab" href="#luar" role="tab"
                                    aria-controls="luar" aria-selected="false">Pengajuan Luar</a>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content pt-2" id="pengajuanTabContent">
                            {{-- Dalam Tab --}}
                            <div class="tab-pane fade show active" id="dalam" role="tabpanel"
                                aria-labelledby="dalam-tab">
                                <div class="row">
                                    <div class="col-md-4 stretch-card grid-margin">
                                        <div class="card bg-gradient-info card-img-holder text-white">
                                            <div class="card-body">
                                                <img src="{{ asset('assets/images/dashboard/circle.svg') }}"
                                                    class="card-img-absolute" alt="circle-image" />
                                                <h4 class="font-weight-normal mb-3">Jumlah Pengajuan <i
                                                        class="mdi mdi-chart-line mdi-24px float-end"></i>
                                                </h4>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    <h2 class="mb-5">{{ $dashboardDalam->count() }} Pengajuan</h2>
                                                </a>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    {{-- <h6 class="card-text">Belum Approval</h6> --}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 stretch-card grid-margin">
                                        <div class="card bg-gradient-success card-img-holder text-white">
                                            <div class="card-body">
                                                <img src="{{ asset('assets/images/dashboard/circle.svg') }}"
                                                    class="card-img-absolute" alt="circle-image" />
                                                <h4 class="font-weight-normal mb-3">Pengajuan Approve <i
                                                        class="mdi mdi-check-circle mdi-24px float-end"></i>
                                                </h4>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    <h2 class="mb-5">{{ $totalApprovedHm }} Pengajuan</h2>
                                                </a>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    <h6 class="card-text">di Approve</h6>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 stretch-card grid-margin">
                                        <div class="card bg-gradient-danger card-img-holder text-white">
                                            <div class="card-body">
                                                <img src="{{ asset('assets/images/dashboard/circle.svg') }}"
                                                    class="card-img-absolute" alt="circle-image" />
                                                <h4 class="font-weight-normal mb-3">Pengajuan Reject <i
                                                        class="mdi mdi-close-circle mdi-24px float-end"></i>
                                                </h4>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    <h2 class="mb-5">{{ $totalRejectedHm }} pengajuan</h2>
                                                </a>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
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
                                                <div id="traffic-chart-legend"
                                                    class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Luar Tab --}}
                            <div class="tab-pane fade" id="luar" role="tabpanel" aria-labelledby="luar-tab">
                                <div class="row">
                                    <div class="col-md-4 stretch-card grid-margin">
                                        <div class="card bg-gradient-info card-img-holder text-white">
                                            <div class="card-body">
                                                <img src="{{ asset('assets/images/dashboard/circle.svg') }}"
                                                    class="card-img-absolute" alt="circle-image" />
                                                <h4 class="font-weight-normal mb-3">Jumlah Pengajuan <i
                                                        class="mdi mdi-chart-line mdi-24px float-end"></i>
                                                </h4>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    <h2 class="mb-5">{{ $dashboardLuar->count() }} Pengajuan</h2>
                                                </a>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    {{-- <h6 class="card-text">Belum Approval</h6> --}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 stretch-card grid-margin">
                                        <div class="card bg-gradient-success card-img-holder text-white">
                                            <div class="card-body">
                                                <img src="{{ asset('assets/images/dashboard/circle.svg') }}"
                                                    class="card-img-absolute" alt="circle-image" />
                                                <h4 class="font-weight-normal mb-3">Pengajuan Approve <i
                                                        class="mdi mdi-check-circle mdi-24px float-end"></i>
                                                </h4>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    <h2 class="mb-5">{{ $totalApprovedLuar }} Pengajuan</h2>
                                                </a>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    <h6 class="card-text">di Approve</h6>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 stretch-card grid-margin">
                                        <div class="card bg-gradient-danger card-img-holder text-white">
                                            <div class="card-body">
                                                <img src="{{ asset('assets/images/dashboard/circle.svg') }}"
                                                    class="card-img-absolute" alt="circle-image" />
                                                <h4 class="font-weight-normal mb-3">Pengajuan Reject <i
                                                        class="mdi mdi-close-circle mdi-24px float-end"></i>
                                                </h4>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    <h2 class="mb-5">{{ $totalRejectedLuar }} pengajuan</h2>
                                                </a>
                                                <a href="{{ route('marketing.data.pengajuan') }}"
                                                    class="text-white text-decoration-none">
                                                    <h6 class="card-text">di Reject</h6>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-12 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Traffic Status Approval</h4>
                                                <div class="doughnutjs-wrapper d-flex justify-content-center">
                                                    <canvas id="traffic-chart"></canvas>
                                                </div>
                                                <div id="traffic-chart-legend"
                                                    class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @elseif (Auth::user()->type == 'dalam')
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">Jumlah Pengajuan <i
                                class="mdi mdi-chart-line mdi-24px float-end"></i>
                        </h4>
                        <a href="{{ route('marketing.data.pengajuan') }}" class="text-white text-decoration-none">
                            <h2 class="mb-5">{{ $dashboardDalam->count() }} Pengajuan</h2>
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
                        <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        var totalPending = {{ $totalPendingHm }};
        var totalApproved = {{ $totalApprovedHm }};
        var totalRejected = {{ $totalRejectedHm }};
    </script>
@endsection
