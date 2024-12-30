@extends('layouts/layoutMaster')

@section('title', 'Projects')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/apex-charts/apex-charts.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-select-bs5/select.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/bs-stepper/bs-stepper.scss', 'resources/assets/vendor/libs/flatpickr/flatpickr.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/app-logistics-dashboard.scss', 'resources/assets/vendor/scss/pages/app-calendar.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/bs-stepper/bs-stepper.js', 'resources/assets/vendor/libs/flatpickr/flatpickr.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/app-logistics-dashboard.js', 'resources/assets/js/modal-create-app.js'])
@endsection

@section('content')
    <div class="row g-6">
        <!-- Card Border Shadow -->
        <!-- Total Projects -->
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ti ti-clipboard-list ti-28px"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $totalProjects }}</h4>
                    </div>
                    <p class="mb-1">Total Projects</p>
                    <p class="mb-0">
                        <span
                            class="text-heading fw-medium me-2">{{ $totalProjectsChange >= 0 ? '+' : '' }}{{ $totalProjectsChange }}%</span>
                        <small class="text-muted">than last week</small>
                    </p>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ti ti-check ti-28px"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $activeProjects }}</h4>
                    </div>
                    <p class="mb-1">Active Projects</p>
                    <p class="mb-0">
                        <span
                            class="text-heading fw-medium me-2">{{ $activeProjectsChange >= 0 ? '+' : '' }}{{ $activeProjectsChange }}%</span>
                        <small class="text-muted">than last week</small>
                    </p>
                </div>
            </div>
        </div>

        <!-- Inactive Projects -->
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-danger h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="ti ti-ban ti-28px"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $inactiveProjects }}</h4>
                    </div>
                    <p class="mb-1">Inactive Projects</p>
                    <p class="mb-0">
                        <span
                            class="text-heading fw-medium me-2">{{ $inactiveProjectsChange >= 0 ? '+' : '' }}{{ $inactiveProjectsChange }}%</span>
                        <small class="text-muted">than last week</small>
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ti ti-users ti-28px"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $totalCustomers }}</h4>
                    </div>
                    <p class="mb-1">Total Customers</p>
                    <p class="mb-0">
                        <span class="text-muted">Unique customers</span>
                    </p>
                </div>
            </div>
        </div>


        <!--/ Card Border Shadow -->

        <!-- On route vehicles Table -->

        <div class="col-12 order-5">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Projects</h5>
                    </div>

                    <div class="dropdown">
                        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createApp">Add Project</button> --}}
                        <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button"
                            id="routeVehicles" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical ti-md text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
                            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                            <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table class="dt-route-vehicles table table-sm">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Project ID</th>
                                <th>Project Name</th>
                                <th>Location</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Delivery Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!--/ On route vehicles Table -->
    </div>

    <!-- All Modals -->
    @include('_partials/_modals/modal-create-app')

@endsection
