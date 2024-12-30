@extends('layouts/layoutMaster')

@section('title', 'Product List')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 
    'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 
    'resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/app-ecommerce-product-list.js'])
@endsection

@section('content')
    <!-- Product List Widget -->
    <div class="card mb-6">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">
                    <!-- Total Products -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                            <div>
                                <p class="mb-1">Total Products</p>
                                <h4 class="mb-1">{{ $products->count() }}</h4>
                                <p class="mb-0">
                                    <span class="badge bg-label-{{ $totalProductsChange >= 0 ? 'success' : 'danger' }}">
                                        {{ $totalProductsChange >= 0 ? '+' : '' }}{{ $totalProductsChange }}%
                                    </span>
                                </p>                            </div>
                            <span class="avatar me-sm-6">
                                <span class="avatar-initial rounded">
                                    <i class="ti-28px ti ti-box text-heading"></i>
                                </span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6">
                    </div>

                    <!-- Active Products -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                            <div>
                                <p class="mb-1">Active Products</p>
                                <h4 class="mb-1">{{ $activeProducts }}</h4>
                                <p class="mb-0">
                                    <span class="badge bg-label-{{ $activeProductsChange >= 0 ? 'success' : 'danger' }}">
                                        {{ $activeProductsChange >= 0 ? '+' : '' }}{{ $activeProductsChange }}%
                                    </span>
                                </p>
                            </div>
                            <span class="avatar p-2 me-lg-6">
                                <span class="avatar-initial rounded">
                                    <i class="ti-28px ti ti-check text-heading"></i>
                                </span>
                            </span>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none">
                    </div>

                    <!-- Inactive Products -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                            <div>
                                <p class="mb-1">Inactive Products</p>
                                <h4 class="mb-1">{{ $inactiveProducts }}</h4>
                                <p class="mb-0">
                                    <span class="badge bg-label-{{ $inactiveProductsChange >= 0 ? 'success' : 'danger' }}">
                                        {{ $inactiveProductsChange >= 0 ? '+' : '' }}{{ $inactiveProductsChange }}%
                                    </span>
                                </p>
                            </div>
                            <span class="avatar p-2 me-sm-6">
                                <span class="avatar-initial rounded">
                                    <i class="ti-28px ti ti-eye-off text-heading"></i>
                                </span>
                            </span>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1">Categories</p>
                                <h4 class="mb-1">{{ $categoriesCount }}</h4>
                                <p class="mb-0"><span class="me-2">All categories</span></p>
                            </div>
                            <span class="avatar p-2">
                                <span class="avatar-initial rounded">
                                    <i class="ti-28px ti ti-category text-heading"></i>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Product List Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Filter</h5>
            <div class="d-flex justify-content-between align-items-center row pt-4 gap-6 gap-md-0">
                <div class="col-md-4 product_status"></div>
                <div class="col-md-4 product_category"></div>
                <div class="col-md-4 product_stock"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-products table">
                <thead class="border-top">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>product</th>
                        <th>category</th>
                        <th>project</th>
                        <th>created by</th>
                        <th>status</th>
                        <th>actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- QR Code -->
    <!-- QR Code -->
    {{-- <h5 class="pb-1 mb-6">QR Code</h5>
    <div class="row mb-0 g-6">
        <!-- QR Code di Kiri -->
        <div class="col-md-5">
            <div class="card" style="width: 235 px; height:  113 px; padding: 0; display: flex; align-items: center;">
                <div class="row g-0" style="width: 100%; height: 100%;">
                    <!-- Gambar QR Code -->
                    <div class="col-md-3 d-flex flex-column align-items-center justify-content-center" style="padding: -10px; margin-right:-6px;">
                        <img class="img-fluid w-100" src="{{ asset('assets/img/qr-codes/ducting-lurus.png') }}"
                            alt="QR Code" style="height: 123px; object-fit: contain;" />
                        <span class="mt-2 text-center" style="font-weight: bold; font-size: 12px;">Product by</span>
                        <span class="mt-2 text-center" style="font-weight: bold; font-size: 12px;">PT. Green Cold</span>
                    </div>
                    <!-- Konten Tabel -->
                    <div class="col-md-9" style="padding: 0px;">
                        <div class="card-body p-1" style="height: 100%; width:101%; display: flex; align-items: center;">
                            <table class="table table-bordered" style="font-size: 10px; font-weight:bold; padding:0px;">
                                <tbody>
                                    <tr>
                                        <td colspan="1">LKI-MRT XX</td>
                                        <td colspan="1" style="border-right: none;">MRT CP203
                                        </td>
                                        <td style="border-left: none; border-right: none;"></td>
                                        <td style="border-left: none;">ECS-DUCT SF01 & TS01</td>
                                    </tr>
                                    <tr>
                                        <td>Size:</td>
                                        <td style="border-right: none;">200 X 200 mm
                                        </td>
                                        <td style="border-left: none; border-right: none;"></td>
                                        <td style="border-left: none;">DO:</td>
                                    </tr>
                                    <tr>
                                        <td>Type:</td>
                                        <td colspan="2" style="border-right:none;">DUCT REDUCER SA</td>
                                        <td class="text-center" style="border-left:none;" colspan="1">
                                            @include('_partials.macros', [
                                                'height' => 30,
                                            ])</td>
                                    </tr>
                                    <tr>
                                        <td>Length:</td>
                                        <td colspan="2">250 mm</td>
                                        <td class="text-lg-center">Cust: PT. SHINRYO</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md" hidden>
            <div class="card" style="width: 235 px; height:  113 px; padding: 0; display: flex; align-items: center;">
                <div class="row g-0" style="width: 100%; height: 100%;">
                    <!-- Gambar QR Code -->
                    <div class="col-md-3 d-flex flex-column align-items-center justify-content-center" style="padding: 0; margin:0;">
                        <img class="img-fluid w-100" src="{{ asset('assets/img/qr-codes/ducting-lurus.png') }}"
                            alt="QR Code" style="height: 133px; object-fit: contain;" />
                        <span class="mt-2 text-center" style="font-weight: bold; font-size: 12px;">Product by</span>
                        <span class="mt-2 text-center" style="font-weight: bold; font-size: 12px;">PT. Green Cold</span>
                    </div>
                    <!-- Konten Tabel -->
                    <div class="col-md-9" style="padding: 0;">
                        <div class="card-body p-1" style="height: 100%; display: flex; align-items: center;">
                            <table class="table table-bordered" style="font-size: 11px; font-weight:bold">
                                <tbody>
                                    <tr>
                                        <td colspan="1">LKI-MRT XX</td>
                                        <td colspan="1" style="border-right: none;">MRT CP203
                                        </td>
                                        <td style="border-left: none; border-right: none;"></td>
                                        <td style="border-left: none;">ECS-DUCT SF01 & TS01</td>
                                    </tr>
                                    <tr>
                                        <td>Size:</td>
                                        <td style="border-right: none;">200 X 200 mm
                                        </td>
                                        <td style="border-left: none; border-right: none;"></td>
                                        <td style="border-left: none;">DO:</td>
                                    </tr>
                                    <tr>
                                        <td>Type:</td>
                                        <td colspan="2" style="border-right:none;">DUCT REDUCER SA</td>
                                        <td class="text-center" style="border-left:none;" colspan="1">
                                            @include('_partials.macros', [
                                                'height' => 30,
                                            ])</td>
                                    </tr>
                                    <tr>
                                        <td>Length:</td>
                                        <td colspan="2">250 mm</td>
                                        <td class="text-lg-center">Cust: PT. SHINRYO</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-0 g-6">
        <!-- QR Code di Kiri -->
        <div class="col-md">
            <div class="card" style="width: 235 px; height:  113 px; padding: 0; display: flex; align-items: center;">
                <div class="row g-0" style="width: 100%; height: 100%;">
                    <!-- Gambar QR Code -->
                    <div class="col-md-3 d-flex flex-column align-items-center justify-content-center" style="padding: 0; margin:0;">
                        <img class="img-fluid w-100" src="{{ asset('assets/img/qr-codes/ducting-lurus.png') }}"
                            alt="QR Code" style="height: 133px; object-fit: contain;" />
                        <span class="mt-2 text-center" style="font-weight: bold; font-size: 12px;">Product by</span>
                        <span class="mt-2 text-center" style="font-weight: bold; font-size: 12px;">PT. Green Cold</span>
                    </div>
                    <!-- Konten Tabel -->
                    <div class="col-md-9" style="padding: 0;">
                        <div class="card-body p-1" style="height: 100%; display: flex; align-items: center;">
                            <table class="table table-bordered" style="font-size: 11px; font-weight:bold">
                                <tbody>
                                    <tr>
                                        <td colspan="1">LKI-MRT XX</td>
                                        <td colspan="1" style="border-right: none;">MRT CP203
                                        </td>
                                        <td style="border-left: none; border-right: none;"></td>
                                        <td style="border-left: none;">ECS-DUCT SF01 & TS01</td>
                                    </tr>
                                    <tr>
                                        <td>Size:</td>
                                        <td style="border-right: none;">200 X 200 mm
                                        </td>
                                        <td style="border-left: none; border-right: none;"></td>
                                        <td style="border-left: none;">DO:</td>
                                    </tr>
                                    <tr>
                                        <td>Type:</td>
                                        <td colspan="2" style="border-right:none;">DUCT REDUCER SA</td>
                                        <td class="text-center" style="border-left:none;" colspan="1">
                                            @include('_partials.macros', [
                                                'height' => 30,
                                            ])</td>
                                    </tr>
                                    <tr>
                                        <td>Length:</td>
                                        <td colspan="2">250 mm</td>
                                        <td class="text-lg-center">Cust: PT. SHINRYO</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card" style="width: 235 px; height:  113 px; padding: 0; display: flex; align-items: center;">
                <div class="row g-0" style="width: 100%; height: 100%;">
                    <!-- Gambar QR Code -->
                    <div class="col-md-3 d-flex flex-column align-items-center justify-content-center" style="padding: 0; margin:0;">
                        <img class="img-fluid w-100" src="{{ asset('assets/img/qr-codes/ducting-lurus.png') }}"
                            alt="QR Code" style="height: 133px; object-fit: contain;" />
                        <span class="mt-2 text-center" style="font-weight: bold; font-size: 12px;">Product by</span>
                        <span class="mt-2 text-center" style="font-weight: bold; font-size: 12px;">PT. Green Cold</span>
                    </div>
                    <!-- Konten Tabel -->
                    <div class="col-md-9" style="padding: 0;">
                        <div class="card-body p-1" style="height: 100%; display: flex; align-items: center;">
                            <table class="table table-bordered" style="font-size: 11px; font-weight:bold">
                                <tbody>
                                    <tr>
                                        <td colspan="1">LKI-MRT XX</td>
                                        <td colspan="1" style="border-right: none;">MRT CP203
                                        </td>
                                        <td style="border-left: none; border-right: none;"></td>
                                        <td style="border-left: none;">ECS-DUCT SF01 & TS01</td>
                                    </tr>
                                    <tr>
                                        <td>Size:</td>
                                        <td style="border-right: none;">200 X 200 mm
                                        </td>
                                        <td style="border-left: none; border-right: none;"></td>
                                        <td style="border-left: none;">DO:</td>
                                    </tr>
                                    <tr>
                                        <td>Type:</td>
                                        <td colspan="2" style="border-right:none;">DUCT REDUCER SA</td>
                                        <td class="text-center" style="border-left:none;" colspan="1">
                                            @include('_partials.macros', [
                                                'height' => 30,
                                            ])</td>
                                    </tr>
                                    <tr>
                                        <td>Length:</td>
                                        <td colspan="2">250 mm</td>
                                        <td class="text-lg-center">Cust: PT. SHINRYO</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!--/ QR Code -->
@endsection
