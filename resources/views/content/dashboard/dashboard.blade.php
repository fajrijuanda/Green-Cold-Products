@extends('layouts/layoutMaster')

@php
    $configData = Helper::appClasses();
@endphp

@section('title', 'Dashboard')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/plyr/plyr.scss', 'resources/assets/vendor/libs/spinkit/spinkit.scss'])
@endsection

@section('page-style')
    @vite('resources/assets/vendor/scss/pages/app-academy.scss')
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/plyr/plyr.js'])
@endsection

@section('page-script')
    @vite('resources/assets/js/app-academy-course.js')
@endsection

@section('content')
    <div class="app-academy">
        <div class="card p-0 mb-6">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between p-0 pt-6">
                <div class="app-academy-md-25 card-body py-0 pt-6 ps-12">
                    <img src="{{ asset('assets/img/illustrations/bulb-' . $configData['style'] . '.png') }}"
                        class="img-fluid app-academy-img-height scaleX-n1-rtl" alt="Bulb in hand"
                        data-app-light-img="illustrations/bulb-light.png" data-app-dark-img="illustrations/bulb-dark.png"
                        height="90" />
                </div>
                <div class="app-academy-md-50 card-body d-flex align-items-md-center flex-column text-md-center mb-6 py-6">
                    <span class="card-title mb-4 lh-lg px-md-12 h4 text-heading">
                        Manage your products<br> efficiently.
                        <span class="text-primary text-nowrap">All in one place</span>.
                    </span>
                    <p class="mb-4 px-0 px-md-2">
                        Keep track of your Products with ease using our reliable tools for<br> product management, stock
                        monitoring, and QR code generation.
                    </p>
                    <div class="d-flex align-items-center justify-content-between app-academy-md-80">
                        <input id="searchInput" type="search" placeholder="Find your product" class="form-control me-4" />
                        <button id="searchButton" type="button" class="btn btn-primary btn-icon">
                            <i class="ti ti-search ti-md"></i>
                        </button>
                    </div>
                </div>
                <div class="app-academy-md-25 d-flex align-items-end justify-content-end">
                    <img src="{{ asset('assets/img/illustrations/pencil-rocket.png') }}" alt="pencil rocket" height="188"
                        class="scaleX-n1-rtl" />
                </div>
            </div>
        </div>

        <div class="card mb-6">
            <div class="card-header d-flex flex-wrap justify-content-between gap-4">
                <div class="card-title mb-0 me-1">
                    <h5 class="mb-0">Products</h5>
                    <p class="mb-0">Total {{ $products->count() }} products in this page</p>
                </div>
                <div class="d-flex justify-content-md-end align-items-center column-gap-6">
                    <select class="form-select select2" id="categoryFilter">
                        <option value="">All Products</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->slug }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button id="resetButton" type="button" class="btn btn-secondary">Reset</button>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-6 mb-6" id="productContainer">
                    @include('content.dashboard._product-list', ['products' => $products])
                </div>
                <div class="pagination-container">
                    @include('content.dashboard._pagination', ['products' => $products])
                </div>

            </div>
        </div>
    </div>
@endsection
