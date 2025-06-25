<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manajemen Pengajuan Marketing</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- DataTables Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
</head>

<body>
    @php
        $usertype = Auth::user()->usertype;
        $type = Auth::user()->type;
        $routeName = '';

        switch ($usertype) {
            case 'marketing':
                $routeName = 'marketing.dashboard';
                break;
            case 'supervisor':
                $routeName = 'supervisor.dashboard';
                break;
            case 'credit':
                $routeName = 'creditAnalyst.dashboard';
                break;
            case 'head':
                $routeName = 'headMarketing.dashboard';
                break;
            case 'admin':
                $routeName = 'admin.dashboard';
                break;
            case 'root':
                $routeName = 'superAdmin.dashboard';
                break;
            case 'surveyor':
                $routeName = 'surveyor.dashboard';
                break;
        }

        $isActive = request()->routeIs($routeName) ? 'active' : '';
    @endphp
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo" href="{{ route($routeName) }}">
                    Pengajuan
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ route($routeName) }}">
                    <img src="{{ asset('favicon.ico') }}" alt="logo" style="width: fit-content" />
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <div class="search-field d-none d-md-block">
                    <div class="d-flex align-items-center h-100">
                        <div class="current-time">
                            <span id="datetime" class="text-black"></span>
                        </div>
                    </div>
                </div>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
                                <i class="mdi mdi-account me-2 text-success"></i>
                                Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <!-- Trigger modal -->
                            <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal"
                                data-bs-target="#logoutModal">
                                <i class="mdi mdi-logout me-2 text-primary"></i> Signout
                            </a>
                        </div>
                    </li>
                    @if ($usertype == 'marketing')
                        <li class="nav-item dropdown">
                            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#"
                                data-bs-toggle="dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                @php
                                    $user = Auth::user();

                                    // Ambil notifikasi yang belum dibaca
                                    $unreadNotifications = \App\Models\NotifactionMarketing::where(function (
                                        $query,
                                    ) use ($user) {
                                        $query
                                            ->whereHas('pengajuan.nasabah', function ($subQuery) use ($user) {
                                                $subQuery->where('marketing_id', $user->id);
                                            })
                                            ->orWhereHas('pengajuanLuar.nasabahLuar', function ($subQuery) use ($user) {
                                                $subQuery->where('marketing_id', $user->id);
                                            });
                                    })
                                        ->where('read', false) // Pastikan tipe data kolom 'read' sesuai (boolean/0/1)
                                        ->count();
                                @endphp
                                @if ($unreadNotifications > 0)
                                    <span class="count-symbol bg-danger"></span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                                aria-labelledby="notificationDropdown">
                                <h6 class="p-3 mb-0">Notifications</h6>
                                <div class="dropdown-divider"></div>
                                @php
                                    $notifications = \App\Models\NotifactionMarketing::where(function ($query) use (
                                        $user,
                                    ) {
                                        $query
                                            ->whereHas('pengajuan.nasabah', function ($subQuery) use ($user) {
                                                $subQuery->where('marketing_id', $user->id);
                                            })
                                            ->orWhereHas('pengajuanLuar.nasabahLuar', function ($subQuery) use ($user) {
                                                $subQuery->where('marketing_id', $user->id);
                                            });
                                    })
                                        ->where('read', false)
                                        ->orderBy('created_at', 'desc')
                                        ->take(3)
                                        ->get();
                                @endphp
                                @forelse ($notifications as $notification)
                                    <a class="dropdown-item preview-item bg-light"
                                        href="{{ route('marketing.notifikasi') }}">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">
                                                {{ $notification->created_at->diffForHumans() }}</h6>
                                            <p class="text-gray ellipsis mb-0">{{ $notifications[0]->pesan }}
                                            </p>
                                        </div>
                                    </a>
                                @empty
                                    <a class="dropdown-item preview-item">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">Hari Ini</h6>
                                            <p class="text-gray ellipsis mb-0">Tidak ada notifikasi
                                            </p>
                                        </div>
                                    </a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('marketing.notifikasi') }}" class="text-decoration-none text-black">
                                    <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                                </a>
                            </div>
                        </li>
                    @elseif ($usertype == 'supervisor')
                        <li class="nav-item dropdown">
                            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown"
                                href="#" data-bs-toggle="dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                @php
                                    $unreadNotifications = 0;
                                    if ($type == 'dalam') {
                                        $unreadNotifications = \App\Models\NotifactionSupervisor::where(
                                            'read',
                                            false,
                                        )->count();
                                    } elseif ($type == 'luar') {
                                        $unreadNotifications = \App\Models\Luar\NotificationSPV::where(
                                            'read',
                                            false,
                                        )->count();
                                    }
                                @endphp
                                @if ($unreadNotifications > 0)
                                    <span class="count-symbol bg-danger"></span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                                aria-labelledby="notificationDropdown">
                                <h6 class="p-3 mb-0">Notifications</h6>
                                <div class="dropdown-divider"></div>
                                @php
                                    $notifications = [];
                                    if ($type == 'dalam') {
                                        $notifications = \App\Models\NotifactionSupervisor::where('read', false)
                                            ->orderBy('created_at', 'desc')
                                            ->take(3)
                                            ->get();
                                    } elseif ($type == 'luar') {
                                        $notifications = \App\Models\Luar\NotificationSPV::where('read', false)
                                            ->orderBy('created_at', 'desc')
                                            ->take(3)
                                            ->get();
                                    }
                                @endphp
                                @forelse ($notifications as $notification)
                                    <a class="dropdown-item preview-item"
                                        href="{{ route('supervisor.notifikasi') }}">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">
                                                {{ $notification->created_at->diffForHumans() }}</h6>
                                            <p class="text-gray ellipsis mb-0">{{ $notifications[0]->pesan }}
                                            </p>
                                        </div>
                                    </a>
                                @empty
                                    <a class="dropdown-item preview-item">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">Hari Ini</h6>
                                            <p class="text-gray ellipsis mb-0">Tidak ada notifikasi
                                            </p>
                                        </div>
                                    </a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('supervisor.notifikasi') }}"
                                    class="text-decoration-none text-black">
                                    <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                                </a>
                            </div>
                        </li>
                    @elseif ($usertype == 'credit')
                        <li class="nav-item dropdown">
                            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown"
                                href="#" data-bs-toggle="dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                @php
                                    $unreadNotifications = 0;
                                    if ($type == 'dalam') {
                                        $unreadNotifications = \App\Models\NotifactionCreditAnalyst::where(
                                            'read',
                                            false,
                                        )->count();
                                    } elseif ($type == 'luar') {
                                        $unreadNotifications = \App\Models\Luar\NotificationCA::where(
                                            'read',
                                            false,
                                        )->count();
                                    }
                                @endphp
                                @if ($unreadNotifications > 0)
                                    <span class="count-symbol bg-danger"></span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                                aria-labelledby="notificationDropdown">
                                <h6 class="p-3 mb-0">Notifications</h6>
                                <div class="dropdown-divider"></div>
                                @php
                                    $notifications = [];
                                    if ($type == 'dalam') {
                                        $notifications = \App\Models\NotifactionCreditAnalyst::where('read', false)
                                            ->orderBy('created_at', 'desc')
                                            ->take(3)
                                            ->get();
                                    } elseif ($type == 'luar') {
                                        $notifications = \App\Models\Luar\NotificationCA::where('read', false)
                                            ->orderBy('created_at', 'desc')
                                            ->take(3)
                                            ->get();
                                    }

                                @endphp
                                @forelse ($notifications as $notification)
                                    <a class="dropdown-item preview-item"
                                        href="{{ route('creditAnalyst.notifikasi') }}">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">
                                                {{ $notification->created_at->diffForHumans() }}</h6>
                                            <p class="text-gray ellipsis mb-0">{{ $notifications[0]->pesan }}
                                            </p>
                                        </div>
                                    </a>
                                @empty
                                    <a class="dropdown-item preview-item">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">Hari Ini</h6>
                                            <p class="text-gray ellipsis mb-0">Tidak ada notifikasi
                                            </p>
                                        </div>
                                    </a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('creditAnalyst.notifikasi') }}"
                                    class="text-decoration-none text-black">
                                    <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                                </a>
                            </div>
                        </li>
                    @elseif ($usertype == 'head')
                        <li class="nav-item dropdown">
                            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown"
                                href="#" data-bs-toggle="dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                @php
                                    $unreadNotifications = \App\Models\NotifactionHeadMarketing::where(
                                        'read',
                                        false,
                                    )->count();
                                @endphp
                                @if ($unreadNotifications > 0)
                                    <span class="count-symbol bg-danger"></span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                                aria-labelledby="notificationDropdown">
                                <h6 class="p-3 mb-0">Notifications</h6>
                                <div class="dropdown-divider"></div>
                                @php
                                    $notifications = \App\Models\NotifactionHeadMarketing::where('read', false)
                                        ->orderBy('created_at', 'desc')
                                        ->take(3)
                                        ->get();
                                @endphp
                                @forelse ($notifications as $notification)
                                    <a class="dropdown-item preview-item"
                                        href="{{ route('headMarketing.notifikasi') }}">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">
                                                {{ $notification->created_at->diffForHumans() }}</h6>
                                            <p class="text-gray ellipsis mb-0">{{ $notifications[0]->pesan }}
                                            </p>
                                        </div>
                                    </a>
                                @empty
                                    <a class="dropdown-item preview-item">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">Hari Ini</h6>
                                            <p class="text-gray ellipsis mb-0">Tidak ada notifikasi
                                            </p>
                                        </div>
                                    </a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('headMarketing.notifikasi') }}"
                                    class="text-decoration-none text-black">
                                    <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                                </a>
                            </div>
                        </li>
                    @elseif ($usertype == 'surveyor')
                        <li class="nav-item dropdown">
                            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown"
                                href="#" data-bs-toggle="dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                @php
                                    $unreadNotifications = \App\Models\Luar\NotificationSurveyor::where(
                                        'read',
                                        false,
                                    )->count();
                                @endphp
                                @if ($unreadNotifications > 0)
                                    <span class="count-symbol bg-danger"></span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list"
                                aria-labelledby="notificationDropdown">
                                <h6 class="p-3 mb-0">Notifications</h6>
                                <div class="dropdown-divider"></div>
                                @php
                                    $notifications = \App\Models\Luar\NotificationSurveyor::where('read', false)
                                        ->orderBy('created_at', 'desc')
                                        ->take(3)
                                        ->get();
                                @endphp
                                @forelse ($notifications as $notification)
                                    <a class="dropdown-item preview-item" href="{{ route('surveyor.notifikasi') }}">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">
                                                {{ $notification->created_at->diffForHumans() }}</h6>
                                            <p class="text-gray ellipsis mb-0">{{ $notifications[0]->pesan }}
                                            </p>
                                        </div>
                                    </a>
                                @empty
                                    <a class="dropdown-item preview-item">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-information"></i>
                                            </div>
                                        </div>
                                        <div
                                            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                            <h6 class="preview-subject font-weight-normal mb-1">Hari Ini</h6>
                                            <p class="text-gray ellipsis mb-0">Tidak ada notifikasi
                                            </p>
                                        </div>
                                    </a>
                                @endforelse
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('surveyor.notifikasi') }}" class="text-decoration-none text-black">
                                    <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                                </a>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item nav-settings d-none d-lg-block">
                        <a class="nav-link" href="#">
                            <i class="mdi mdi-format-line-spacing"></i>
                        </a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="{{ route($routeName) }}" class="nav-link">
                            <div class="nav-profile-image">
                                <img src="{{ asset('favicon.ico') }}" alt="profile" style="width: fit-content" />
                                <span class="login-status online"></span>
                                <!--change to offline or busy as needed-->
                            </div>
                            <div class="nav-profile-text d-flex flex-column">
                                <span class="text-secondary text-small mb-2">Selamat Datang</span>
                                <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
                            </div>
                            <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $isActive }}" href="{{ route($routeName) }}">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    @if ($usertype == 'marketing')
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('marketing.data.pengajuan') ? 'active' : '' }}"
                                href="{{ route('marketing.data.pengajuan') }}">
                                <span class="menu-title">Pengajuan Dalam</span>
                                <i class="mdi mdi-file-document menu-icon"></i>
                            </a>
                        </li>
                        @if ($type == 'luar')
                            <li class="nav-item {{ Route::is('marketingLuar.pengajuan') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('marketingLuar.pengajuan') }}">
                                    <span class="menu-title">Pengajuan Luar</span>
                                    <i class="mdi mdi-history menu-icon"></i>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item {{ Route::is('marketing.notifikasi') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('marketing.notifikasi') }}">
                                <span class="menu-title">Notifikasi</span>
                                <i class="mdi mdi-bell menu-icon"></i>
                            </a>
                        </li>
                    @elseif ($usertype == 'supervisor')
                        @if ($type == 'dalam')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('supervisor.approval') ? 'active' : '' }}"
                                    href="{{ route('supervisor.approval') }}">
                                    <span class="menu-title">Approval Supervisor</span>
                                    <i class="mdi mdi-checkbox-marked-outline menu-icon"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('supervisor.riwayat') ? 'active' : '' }}"
                                    href="{{ route('supervisor.riwayat') }}">
                                    <span class="menu-title">Riwayat Approval</span>
                                    <i class="mdi mdi-history menu-icon"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('supervisor.ro') ? 'active' : '' }}"
                                    href="{{ route('supervisor.ro') }}">
                                    <span class="menu-title">Pengajuan RO</span>
                                    <i class="mdi mdi-history menu-icon"></i>
                                </a>
                            </li>
                        @elseif ($type == 'luar')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('supervisor.luar') ? 'active' : '' }}"
                                    href="{{ route('supervisor.luar') }}">
                                    <span class="menu-title">Pengajuan Luar</span>
                                    <i class="mdi mdi-checkbox-marked-outline menu-icon"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('supervisor.riwayat.pengajuan.luar') ? 'active' : '' }}"
                                    href="{{ route('supervisor.riwayat.pengajuan.luar') }}">
                                    <span class="menu-title">Riwayat Check</span>
                                    <i class="mdi mdi-history menu-icon"></i>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('supervisor.notifikasi') ? 'active' : '' }}"
                                href="{{ route('supervisor.notifikasi') }}">
                                <span class="menu-title">Notifikasi</span>
                                <i class="mdi mdi-bell menu-icon"></i>
                            </a>
                        </li>
                    @elseif ($usertype == 'credit')
                        @if ($type == 'dalam')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('creditAnalyst.approval') ? 'active' : '' }}"
                                    href="{{ route('creditAnalyst.approval') }}">
                                    <span class="menu-title">Approval Credit Analyst</span>
                                    <i class="mdi mdi-checkbox-marked-outline menu-icon"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('creditAnalyst.riwayat') ? 'active' : '' }}"
                                    href="{{ route('creditAnalyst.riwayat') }}">
                                    <span class="menu-title">Riwayat Approval</span>
                                    <i class="mdi mdi-history menu-icon"></i>
                                </a>
                            </li>
                        @elseif ($type == 'luar')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('creditAnalystLuar.pengajuan') ? 'active' : '' }}"
                                    href="{{ route('creditAnalystLuar.pengajuan') }}">
                                    <span class="menu-title">Approval Pengajuan Luar</span>
                                    <i class="mdi mdi-checkbox-marked-outline menu-icon"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('creditAnalystLuar.riwayat') ? 'active' : '' }}"
                                    href="{{ route('creditAnalystLuar.riwayat') }}">
                                    <span class="menu-title">Riwayat Approval</span>
                                    <i class="mdi mdi-history menu-icon"></i>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('creditAnalyst.notifikasi') ? 'active' : '' }}"
                                href="{{ route('creditAnalyst.notifikasi') }}">
                                <span class="menu-title">Notifikasi</span>
                                <i class="mdi mdi-bell menu-icon"></i>
                            </a>
                        </li>
                    @elseif ($usertype == 'head')
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#pengajuanDalam"
                                aria-expanded="false" aria-controls="pengajuanDalam">
                                <span class="menu-title">Pengajuan Dalam</span>
                                <i class="menu-arrow"></i>
                                <i class="mdi mdi-text-box-outline menu-icon"></i>
                            </a>
                            <div class="collapse" id="pengajuanDalam">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('headMarketing.approval') ? 'active' : '' }}"
                                            href="{{ route('headMarketing.approval') }}">
                                            <span class="menu-title">Approval Pengajuan Dalam</span>
                                            <i class="mdi mdi-checkbox-marked-outline menu-icon"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('headMarketing.riwayat') ? 'active' : '' }}"
                                            href="{{ route('headMarketing.riwayat') }}">
                                            <span class="menu-title">Riwayat Approval Dalam</span>
                                            <i class="mdi mdi-history menu-icon"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#pengajuanLuar"
                                aria-expanded="false" aria-controls="pengajuanLuar">
                                <span class="menu-title">Pengajuan Luar</span>
                                <i class="menu-arrow"></i>
                                <i class="mdi mdi-earth menu-icon"></i>
                            </a>
                            <div class="collapse" id="pengajuanLuar">
                                <ul class="nav flex-column sub-menu">
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('headMarketing.data.pengajuan.luar') ? 'active' : '' }}"
                                            href="{{ route('headMarketing.data.pengajuan.luar') }}">
                                            <span class="menu-title">Approval Pengajuan Luar</span>
                                            <i class="mdi mdi-checkbox-marked-outline menu-icon"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ Route::is('headMarketing.data.riwayat.luar') ? 'active' : '' }}"
                                            href="{{ route('headMarketing.data.riwayat.luar') }}">
                                            <span class="menu-title">Riwayat Approval Luar</span>
                                            <i class="mdi mdi-history menu-icon"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('headMarketing.tracking') ? 'active' : '' }}"
                                href="{{ route('headMarketing.tracking') }}">
                                <span class="menu-title">Data Marketing</span>
                                <i class="mdi mdi-file-document-check-outline menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('headMarketing.notifikasi') ? 'active' : '' }}"
                                href="{{ route('headMarketing.notifikasi') }}">
                                <span class="menu-title">Notifikasi</span>
                                <i class="mdi mdi-bell menu-icon"></i>
                            </a>
                        </li>
                    @elseif ($usertype == 'admin')
                        <li class="nav-item {{ Route::is('admin.data.pengajuan') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.data.pengajuan') }}">
                                <span class="menu-title">Data Pengajuan Dalam</span>
                                <i class="mdi mdi-history menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.data.pengajuan.luar') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.data.pengajuan.luar') }}">
                                <span class="menu-title">Data Pengajuan Luar</span>
                                <i class="mdi mdi-history menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.tracking') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.tracking') }}">
                                <span class="menu-title">Data Marketing</span>
                                <i class="mdi mdi-account-multiple menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.voucher') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.voucher') }}">
                                <span class="menu-title">Voucher</span>
                                <i class="mdi mdi-tag menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.data.cicilan') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.data.cicilan') }}">
                                <span class="menu-title">Data Cicilan</span>
                                <i class="mdi mdi-receipt-text menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('admin.surat.kontrak') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.surat.kontrak') }}">
                                <span class="menu-title">Surat Kontrak</span>
                                <i class="mdi mdi-file-pdf-box menu-icon"></i>
                            </a>
                        </li>
                    @elseif ($usertype == 'root')
                        <li
                            class="nav-item {{ Route::is('superAdmin.list.pengajuan') || Route::is('superAdmin.list.pengajuan.edit') || Route::is('superAdmin.list.pengajuan.detail') || Route::is('superAdmin.list.approval') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('superAdmin.list.pengajuan') }}">
                                <span class="menu-title">List Data Pengajuan</span>
                                <i class="mdi mdi-history menu-icon"></i>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ Route::is('superAdmin.data.pengajuan.luar') || Route::is('superAdmin.data.pengajuan.luar.detail') || Route::is('superAdmin.data.pengajuan.luar.edit') || Route::is('superAdmin.list.approval.luar') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('superAdmin.data.pengajuan.luar') }}">
                                <span class="menu-title">List Data Pengajuan Luar</span>
                                <i class="mdi mdi-history menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('superAdmin.list.user') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('superAdmin.list.user') }}">
                                <span class="menu-title">List User</span>
                                <i class="mdi mdi-file-document-check-outline menu-icon"></i>
                            </a>
                        </li>
                    @elseif ($usertype == 'surveyor')
                        <li class="nav-item {{ Route::is('surveyor.daftar.survey') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('surveyor.daftar.survey') }}">
                                <span class="menu-title">Daftar Survey Nasabah</span>
                                <i class="mdi mdi-file-document-check-outline menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::is('surveyor.riwayat.survey') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('surveyor.riwayat.survey') }}">
                                <span class="menu-title">Riwayat Survey</span>
                                <i class="mdi mdi-history menu-icon"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('surveyor.notifikasi') ? 'active' : '' }}"
                                href="{{ route('surveyor.notifikasi') }}">
                                <span class="menu-title">Notifikasi</span>
                                <i class="mdi mdi-bell menu-icon"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="page-header">
                        <h3 class="page-title">
                            <span class="page-title-icon bg-gradient-primary text-white me-2">
                                <a href="{{ route($routeName) }}">
                                    <i class="mdi mdi-home text-white"></i>
                                </a>
                            </span> @yield('page-title', 'Dashboard')
                            @yield('breadcrumb-title', '')
                        </h3>
                    </div>
                    @yield('content')
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2024 <a
                                href="https://www.cashgampang.com/" target="_blank">Cash Gampang</a>. All rights
                            reserved.</span>
                        {{-- <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made
                            with <i class="mdi mdi-heart text-danger"></i></span> --}}
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <!-- End custom js for this page -->

    <!-- DataTables Bootstrap 5 -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
    @stack('script')
    <script>
        function updateDateTime() {
            const datetimeElement = document.getElementById('datetime');
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                // hour: '2-digit',
                // minute: '2-digit',
                // second: '2-digit'
            };
            datetimeElement.textContent = now.toLocaleDateString('id-ID', options);
        }

        // Update time immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);

        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: false, // Membuat tabel responsif
                scrollX: true,
                pagingType: "simple_numbers", // Menggunakan pagination dengan First, Previous, Next, Last
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered: "(disaring dari total _MAX_ entri)",
                    paginate: {
                        first: "<<", // Tombol pertama
                        last: ">>", // Tombol terakhir
                        next: ">",
                        previous: "<"
                    },
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data tersedia di tabel"
                }
            });
            $('#myTable1').DataTable({
                responsive: false, // Membuat tabel responsif
                scrollX: true,
                pagingType: "simple_numbers", // Menggunakan pagination dengan First, Previous, Next, Last
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered: "(disaring dari total _MAX_ entri)",
                    paginate: {
                        first: "<<", // Tombol pertama
                        last: ">>", // Tombol terakhir
                        next: ">",
                        previous: "<"
                    },
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data tersedia di tabel"
                }
            });
            $('#voucher').DataTable({
                scrollX: true,
                pagingType: "simple_numbers", // Menggunakan pagination dengan First, Previous, Next, Last
                lengthMenu: [
                    [50, 100, -1],
                    [50, 100, "Semua"]
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                    infoFiltered: "(disaring dari total _MAX_ entri)",
                    paginate: {
                        first: "<<", // Tombol pertama
                        last: ">>", // Tombol terakhir
                        next: ">",
                        previous: "<"
                    },
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data tersedia di tabel"
                }
            });
        });
    </script>
</body>

</html>
