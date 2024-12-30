@extends('layouts/layoutMaster')

@section('title', 'Product Detail')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/app-ecommerce-product-list.js'])
@endsection

@section('content')
<div data-bs-spy="scroll" class="scrollspy-example">
    <!-- Contact Us: Start -->
    <section id="landingContact" class="section-py bg-body landing-contact">
        <div class="container" id="landing">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Product Detail
                    </li>
                </ol>
            </nav>
            
            
            <div class="text-center mb-4">
                <span class="badge bg-label-primary">Product Detail</span>
            </div>
            <h4 class="text-center mb-1">
                <span class="position-relative fw-extrabold z-1">
                    {{ $product->product_name }}
                    {{-- <img src="{{ asset('assets/img/front-pages/icons/section-title-icon.png') }}" alt="icon"
                        class="section-title-img position-absolute object-fit-contain bottom-0 z-n1"> --}}
                </span>
            </h4>
            <p class="text-center mb-12 pb-md-4">{{ strip_tags($product->description) }}
            </p>
            <div class="row g-6">
                <!-- Updated Image Section -->
                <div class="col-lg-5">
                    <div class="contact-img-box position-relative border p-2 h-100 hero-section" id="hero-animation">
                        {{-- <img src="{{ asset('assets/img/front-pages/icons/contact-border.png') }}" alt="contact border"
                            class="contact-border-img position-absolute d-none d-lg-block scaleX-n1-rtl hero-dashboard-img" /> --}}
                        <img src="{{ asset('assets/img/products/' . $product->image) }}"
                            alt="{{ $product->product_name }}"
                            class="contact-img w-100 scaleX-n1-rtl hero-dashboard-img product-image" style="max-height: 500px;">
                        <div class="p-4 pb-2 hero-dashboard-img">
                            <div class="row g-4">
                                <div class="col-md-6 col-lg-12 col-xl-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-label-primary rounded p-1_5 me-3"><i
                                                class="ti ti-mail ti-lg"></i></div>
                                        <div>
                                            <p class="mb-0">Email</p>
                                            <h6 class="mb-0"><a href="mailto:greencoldproducts@gmail.com"
                                                    class="text-heading">greencoldproducts@gmail.com</a></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-12 col-xl-6">
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-label-success rounded p-1_5 me-3"><i
                                                class="ti ti-phone-call ti-lg"></i></div>
                                        <div>
                                            <p class="mb-0">Phone</p>
                                            <h6 class="mb-0"><a href="tel:+1234-568-963" class="text-heading">+1234
                                                    568 963</a></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Updated col-lg-7 Section -->
                <div class="col-lg-7">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="mb-4 text-center">Product Information</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Attribute</th>
                                            <th class="text-center">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Ducting Type -->
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="badge bg-label-success rounded-circle p-2 me-2">
                                                        <i class="ti ti-tag ti-lg"></i>
                                                    </div>
                                                    <span>Ducting Type</span>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ $product->product_name }}</td>
                                        </tr>
                                        <!-- Specification -->
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="badge bg-label-info rounded-circle p-2 me-2">
                                                        <i class="ti ti-category ti-lg"></i>
                                                    </div>
                                                    <span>Specification</span>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ $product->category->name ?? 'Uncategorized' }}
                                            </td>
                                        </tr>
                                        <!-- Size -->
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="badge bg-label-warning rounded-circle p-2 me-2">
                                                        <i class="ti ti-ruler ti-lg"></i>
                                                    </div>
                                                    <span>Size</span>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ $product->size ?? 'N/A' }}</td>
                                        </tr>
                                        <!-- Length -->
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="badge bg-label-primary rounded-circle p-2 me-2">
                                                        <i class="ti ti-arrows-horizontal ti-lg"></i>
                                                    </div>
                                                    <span>Length</span>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ $product->length ?? 'N/A' }}</td>
                                        </tr>
                                        <!-- Thickness -->
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="badge bg-label-danger rounded-circle p-2 me-2">
                                                        <i class="ti ti-border-radius ti-lg"></i>
                                                    </div>
                                                    <span>Thickness</span>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ $product->thickness ?? 'N/A' }}</td>
                                        </tr>
                                        <!-- Project -->
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="badge bg-label-danger rounded-circle p-2 me-2">
                                                        <i class="ti ti-building ti-lg"></i>
                                                    </div>
                                                    <span>Project</span>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ $product->project }}</td>
                                        </tr>
                                        <!-- Status -->
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="badge bg-label-secondary rounded-circle p-2 me-2">
                                                        <i class="ti ti-flag ti-lg"></i>
                                                    </div>
                                                    <span>Status</span>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ ucfirst($product->status) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
    <!-- Contact Us: End -->
</div>
@endsection
