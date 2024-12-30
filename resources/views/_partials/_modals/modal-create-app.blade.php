<!-- Create App Modal -->
<div class="modal fade" id="createApp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-simple modal-upgrade-plan">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center">
                    <h4 class="mb-2">Create Project</h4>
                    <p class="mb-5">Fill the data for new project</p>
                </div>
                <div id="wizard-create-app" class="bs-stepper vertical mt-2 shadow-none">
                    <div class="bs-stepper-header border-0 p-1">
                        <div class="step" data-target="#details">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-file-text"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Details</span>
                                    <span class="bs-stepper-subtitle">Enter Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#products">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-box"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Products</span>
                                    <span class="bs-stepper-subtitle">Select Product</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#delviery-date">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-calendar"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Delivery Date</span>
                                    <span class="bs-stepper-subtitle">Select Delivery Date</span>
                                </span>
                            </button>
                        </div>
                        <div class="line"></div>
                        <div class="step" data-target="#submit">
                            <button type="button" class="step-trigger">
                                <span class="bs-stepper-circle"><i class="ti ti-check"></i></span>
                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title text-uppercase">Review & Submit</span>
                                    <span class="bs-stepper-subtitle">Review & Submit</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content p-1">
                        <form onSubmit="return false">
                            <!-- Details -->
                            <div id="details" class="content pt-4 pt-lg-0">
                                <div class="mb-6">
                                    <label for="project-id" class="form-label">Project ID</label>
                                    <input type="text" class="form-control" id="project-id" placeholder="LKI-MRT XX"
                                        name="project_id">
                                </div>
                                <div class="mb-6">
                                    <label for="project-name" class="form-label">Project Name</label>
                                    <input type="text" class="form-control" id="project-name" placeholder="MRT CPXXX"
                                        name="project_name">
                                </div>
                                <div class="mb-6">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location"
                                        placeholder="Input Location" name="location">
                                </div>
                                <div class="mb-6">
                                    <label for="customer" class="form-label">Customer</label>
                                    <input type="text" class="form-control" id="customer"
                                        placeholder="Customer Name" name="customer">
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-6">
                                    <button class="btn btn-label-secondary btn-prev" disabled> <i
                                            class="ti ti-arrow-left ti-xs me-sm-2 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button class="btn btn-primary btn-next"> <span
                                            class="align-middle d-sm-inline-block d-none me-sm-2">Next</span> <i
                                            class="ti ti-arrow-right ti-xs"></i></button>
                                </div>
                            </div>
                            <!-- products -->
                            <div id="products" class="content text-start pt-4 pt-lg-0">
                                <h5 class="mb-1">Select Product</h5>
                                <!-- Wrapper untuk membatasi maksimal 3 produk -->
                                <div class="product-wrapper" style="max-height: 250px; overflow-y: auto;">
                                    <ul class="p-0 m-0">
                                        @foreach ($products as $product)
                                            <li class="d-flex align-items-start mb-4">
                                                <!-- Display Product Image -->
                                                <div class="badge bg-label-light p-2 me-3 rounded">
                                                    <img src="{{ asset('assets/img/products/' . $product->image) }}"
                                                        alt="{{ $product->product_name }}" width="30"
                                                        height="30" class="rounded-circle">
                                                </div>
                                                <div class="d-flex justify-content-between w-100">
                                                    <div class="me-2">
                                                        <!-- Display Product Name -->
                                                        <h6 class="mb-1">{{ $product->product_name }}
                                                            {{ $product->category->abbreviation }}</h6>
                                                        <!-- Display Variants -->
                                                        <small>
                                                            <div>Size:
                                                                {{ $product->size }}
                                                            </div>
                                                            <div>Length:
                                                                {{ $product->length }}
                                                            </div>
                                                            <div>Thickness:
                                                                {{ $product->thickness }}
                                                            </div>
                                                        </small>
                                                    </div>
                                                    <div class="d-flex align-items-start">
                                                        <div class="form-check form-check-inline">
                                                            <!-- Radio Button for Selection -->
                                                            <input name="product-radio" class="form-check-input"
                                                                type="radio" value="{{ $product->id }}"
                                                                data-qr-code-path="{{ asset('assets/img/qr-codes/' . $product->qr_code_path) }}" />

                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- Navigation Buttons -->
                                <div class="col-12 d-flex justify-content-between mt-6">
                                    <button class="btn btn-label-secondary btn-prev">
                                        <i class="ti ti-arrow-left ti-xs me-sm-2 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button class="btn btn-primary btn-next">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Next</span>
                                        <i class="ti ti-arrow-right ti-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- delviery-date -->
                            <div id="delviery-date" class="content text-center pt-4 pt-lg-0">
                                <h5 class="mb-1">Delivery Date</h5>
                                <div class="px-3 pt-2">
                                    <!-- inline calendar (flatpicker) -->
                                    <div class="calendar-wrapper d-flex justify-content-center mt-0">
                                        <div class="inline-calendar input">
                                            <input type="hidden" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-between mt-6">
                                    <button class="btn btn-label-secondary btn-prev">
                                        <i class="ti ti-arrow-left ti-xs me-sm-2 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button id="delivery-date-next" class="btn btn-primary btn-next">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Next</span>
                                        <i class="ti ti-arrow-right ti-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- submit -->
                            <div id="submit" class="content text-center pt-4 pt-lg-0">
                                <h5 class="mb-1">Review & Submit</h5>
                                <p class="small">Preview QR Code</p>
                                <!-- Card Wrapper -->
                                <div class="col-12 d-flex justify-content-center">
                                    <div class="card"
                                        style="max-width: 500px; max-height: 200px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
                                        <div class="row g-0 align-items-stretch">
                                            <!-- QR Code -->
                                            <div class="col-md-3 d-flex flex-column align-items-center justify-content-center p-2"
                                                style="margin-right: 0px">
                                                <img id="qr-code" class="img-fluid"
                                                    src="{{ asset('assets/img/qr-codes/ducting-reducer.png') }}"
                                                    alt="QR Code"
                                                    style="width: 113px; height: 113px; object-fit: cover;" />
                                                <span class="mt-2 text-center"
                                                    style="font-weight: bold; font-size: 12px; color: black;">Product
                                                    by</span>
                                                <span class="mt-2 text-center"
                                                    style="font-weight: bold; font-size: 12px; color: black;">PT. Green
                                                    Cold</span>
                                            </div>
                                            <!-- Tabel -->
                                            <div class="col-md-9 p-0">
                                                <table class="table table-bordered text-center"
                                                    style="font-size: 10px; width: 99%; height: 100%; font-weight:bold; border-color:black;">
                                                    <tbody class="p-0">
                                                        <tr>
                                                            <td style="color: black; transform: scale(1.2); line-height: 1;"
                                                                id="review-project-id"></td>
                                                            <td id="review-project-name" class="text-start"
                                                                style="border-right:none; width:100px; color: black; transform: scale(1.2); line-height: 1;">
                                                            </td>
                                                            <td id="review-location" class="text-start"
                                                                style="border-left:none; width: 200px; color: black; transform: scale(1.2); line-height: 1;"
                                                                colspan="2"></td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="color: black; transform: scale(1.2); line-height: 1;">
                                                                Size:</td>
                                                            <td id="review-size" class="text-start"
                                                                style="border-right:none; width:125px; color: black; transform: scale(1.2); line-height: 1;">
                                                            </td>
                                                            <td class="text-start" colspan="2"
                                                                style="border-left:none; color: black; transform: scale(1.2); line-height: 1;">
                                                                DO: <span id="review-delivery-date"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="color: black; transform: scale(1.2); line-height: 1;">
                                                                Type:</td>
                                                            <td colspan="2" id="review-type" class="text-start"
                                                                style="border-right:none; width:150px; color: black; transform: scale(1.2); line-height: 1;">
                                                            </td>
                                                            <td style="border-left:none; transform: scale(1.2); line-height: 1;"
                                                                class="text-start">
                                                                @include('_partials.macros', [
                                                                    'height' => 40,
                                                                ])</td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="color: black; transform: scale(1.2); line-height: 1;">
                                                                Length:</td>
                                                            <td id="review-length" class="text-start"
                                                                style="color: black; transform: scale(1.2); line-height: 1;">
                                                            </td>
                                                            <td class="text-start text-center"
                                                                style="color: black; transform: scale(1.2); line-height: 1;"
                                                                colspan="2">Cust:
                                                                <span id="review-customer"
                                                                    style="color: black"></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Navigation Buttons -->
                                <div class="col-12 d-flex justify-content-between mt-4">
                                    <button class="btn btn-label-secondary btn-prev">
                                        <i class="ti ti-arrow-left ti-xs me-sm-2 me-0"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button id="btn-submit-project" class="btn btn-success btn-next btn-submit"
                                        data-bs-dismiss="modal" aria-label="Close" data-id="">
                                        <span class="align-middle">Submit</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Create App Modal -->
