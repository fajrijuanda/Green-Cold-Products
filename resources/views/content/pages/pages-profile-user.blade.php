@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User Profile')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.scss'])
@endsection

<!-- Page Styles -->
@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-profile.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/pages-profile.js'])
@endsection

@section('content')

    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top">
                </div>
                <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="{{ asset('assets/img/avatars/'.Auth::user()->avatar) }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img">
                    </div>
                    <div class="flex-grow-1 mt-3 mt-lg-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4 class="mb-2 mt-lg-6">{{ Auth::user()->full_name }}</h4>
                                <ul
                                    class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class='ti ti-device-desktop ti-lg'></i><span class="fw-medium">Admin</span>
                                    </li>
                                    <li class="list-inline-item d-flex gap-2 align-items-center">
                                        <i class='ti ti-calendar ti-lg'></i>
                                        <span class="fw-medium">Joined at {{ Auth::user()->joined_at }}</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('account-settings-account') }}" class="btn btn-primary mb-1">
                                <i class='ti ti-edit ti-xs me-2'></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->

    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                                class='ti-sm ti ti-user-check me-1_5'></i> Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Navbar pills -->

    <!-- User Profile Content -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- About User -->
            <div class="card mb-6">
                <div class="card-body">
                    <small class="card-text text-uppercase text-muted small">About</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-user ti-lg"></i>
                            <span class="fw-medium mx-2">Full Name:</span>
                            <span>{{ Auth::user()->name }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-check ti-lg"></i>
                            <span class="fw-medium mx-2">Status:</span>
                            <span>{{ Auth::user()->is_active == 1 ? 'Active' : 'Inactive' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-crown ti-lg"></i>
                            <span class="fw-medium mx-2">Role:</span>
                            <span>{{ ucfirst (Auth::user()->role) }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-building ti-lg"></i>
                            <span class="fw-medium mx-2">Project:</span>
                            <span>{{ Auth::user()->project ?? 'LKI' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-2">
                            <i class="ti ti-flag ti-lg"></i>
                            <span class="fw-medium mx-2">Country:</span>
                            <span>{{ Auth::user()->country ?? 'Indonesia' }}</span>
                        </li>
                    </ul>
                    <small class="card-text text-uppercase text-muted small">Contacts</small>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-phone-call ti-lg"></i>
                            <span class="fw-medium mx-2">Contact:</span>
                            <span>{{ Auth::user()->phone_number ?? '(Not Provided)' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-map-pin ti-lg"></i>
                            <span class="fw-medium mx-2">Address:</span>
                            <span>
                                {{ trim((Auth::user()->address ?? '') . ', ' . (Auth::user()->zip_code ?? '')) ?: 'Not Set' }}
                            </span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="ti ti-mail ti-lg"></i>
                            <span class="fw-medium mx-2">Email:</span>
                            <span>{{ Auth::user()->email }}</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="col-xl-8 col-lg-7 col-md-7">
            <!-- Activity Timeline -->
            <div class="card card-action mb-6">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0">
                        <i class='ti ti-chart-bar ti-lg text-body me-4'></i>Activity Timeline
                    </h5>
                </div>
                <div class="card-body pt-3">
                    <!-- Inline CSS untuk membatasi tinggi dan membuat scroll -->
                    <ul class="timeline mb-0" style="max-height: 353px; overflow-y: auto; padding-right: 10px; padding-top: 10px;">
                        @forelse ($activities as $activity)
                            <li class="timeline-item timeline-item-transparent">
                                <!-- Tentukan warna timeline-point berdasarkan type -->
                                <span
                                    class="timeline-point 
                                    @if ($activity->type === 'login') timeline-point-success
                                    @elseif($activity->type === 'logout') timeline-point-danger
                                    @elseif($activity->type === 'update') timeline-point-warning
                                    @elseif($activity->type === 'create') timeline-point-info
                                    @elseif($activity->type === 'delete') timeline-point-dark
                                    @else timeline-point-primary @endif">
                                </span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">{{ $activity->activity }}</h6>
                                        <small class="text-muted">{{ $activity->activity_date->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-2">
                                        {{ $activity->description ?? 'No additional description available.' }}
                                    </p>
        
                                    @if ($activity->attachment)
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="badge bg-lighter rounded d-flex align-items-center">
                                                <img src="{{ asset('assets/img/icons/misc/pdf.png') }}" alt="attachment"
                                                    width="15" class="me-2">
                                                <span class="h6 mb-0 text-body">{{ $activity->attachment }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point timeline-point-warning"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">No activities yet</h6>
                                        <small class="text-muted">--</small>
                                    </div>
                                    <p class="mb-2">User has not performed any activities recently.</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>        
    </div>
@endsection
