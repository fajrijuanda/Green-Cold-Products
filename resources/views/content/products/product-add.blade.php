@extends('layouts/layoutMaster')

@section('title', $title)

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/quill/typography.scss', 'resources/assets/vendor/libs/quill/katex.scss', 'resources/assets/vendor/libs/quill/editor.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/dropzone/dropzone.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/quill/katex.js', 'resources/assets/vendor/libs/quill/quill.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/dropzone/dropzone.js', 'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/app-ecommerce-product-add.js'])
@endsection

@section('content')
    <div class="app-ecommerce">

        <!-- Add Product -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">

            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1" id="formLabel">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h4>
                <p class="mb-0">Orders placed across your store</p>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-4">
                <div class="d-flex gap-4">
                    <button class="btn btn-label-secondary"
                        onclick="window.location.href='{{ route('product-list') }}'">Discard</button>
                </div>
                <!-- Form Content -->
                <button type="submit" class="btn btn-primary publish">
                    {{ isset($product) ? 'Update Product' : 'Publish Product' }}
                </button>
            </div>

        </div>

        <div class="row">

            <!-- First column-->
            <div class="col-12 col-lg-8">
                <!-- Product Information -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-tile mb-0">Product information</h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="slug" id="slug" value="{{ isset($product) ? $product->slug : '' }}">
                        <div class="mb-6">
                            <label class="form-label" for="ecommerce-product-name">Name</label>
                            <input type="text" class="form-control" id="ecommerce-product-name"
                                placeholder="Product title" name="product_name" aria-label="Product title"
                                value="{{ isset($product) ? $product->product_name : '' }}">
                        </div>
                        <div>
                            <label class="mb-1">Description (Optional)</label>
                            <div class="form-control p-0">
                                <div class="comment-toolbar border-0 border-bottom">
                                    <div class="d-flex justify-content-start">
                                        <span class="ql-formats me-0">
                                            <button class="ql-bold"></button>
                                            <button class="ql-italic"></button>
                                            <button class="ql-underline"></button>
                                            <button class="ql-list" value="ordered"></button>
                                            <button class="ql-list" value="bullet"></button>
                                            <button class="ql-link"></button>
                                            <button class="ql-image"></button>
                                        </span>
                                    </div>
                                </div>
                                <div class="comment-editor border-0 pb-6" id="ecommerce-category-description">
                                    <!-- Quill editor container -->
                                </div>
                                <input type="hidden" name="description" id="description-input"
                                    value="{{ isset($product) ? $product->description : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Product Information -->
                <!-- Media -->
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Product Image</h5>
                        <a href="javascript:void(0);" class="fw-medium">Add media from URL</a>
                    </div>
                    <div class="card-body">
                        <form action="/upload" class="dropzone needsclick p-0" id="dropzone-basic">
                            <div class="dz-message needsclick">
                                <p class="h4 needsclick pt-3 mb-2">Drag and drop your image here</p>
                                <p class="h6 text-muted d-block fw-normal mb-2">or</p>
                                <span class="note needsclick btn btn-sm btn-label-primary" id="btnBrowse">Browse
                                    image</span>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>
                        </form>
                        <!-- Input hidden untuk menyimpan nama file -->
                        <input type="hidden" id="hidden-image-name" name="image"
                            value="{{ isset($product) ? $product->image : '' }}">
                    </div>
                </div>
                <!-- /Media -->
                <!-- Variants -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Variants</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="mb-6 col-4">
                                <label class="form-label" for="size">Size</label>
                                <!-- Ganti ID dengan class untuk menghindari konflik -->
                                <input type="text" class="form-control" id="size" placeholder="Enter size"
                                    name="size" aria-label="Size" value="{{ isset($product) ? $product->size : '' }}">
                            </div>
                            <div class="mb-6 col-4">
                                <label class="form-label" for="length">Length</label>
                                <!-- Ganti ID dengan class untuk menghindari konflik -->
                                <input id="length" type="text" class="form-control" name="length"
                                    placeholder="Enter length" aria-label="Length"
                                    value="{{ isset($product) ? $product->length : '' }}" />
                            </div>
                            <div class="mb-6 col-4">
                                <label class="form-label" for="thickness">Thickness</label>
                                <!-- Ganti ID dengan class untuk menghindari konflik -->
                                <input id="thickness" type="text" class="form-control" name="thickness"
                                    placeholder="Enter thickness" aria-label="Thickness"
                                    value="{{ isset($product) ? $product->thickness : '' }}" />
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /Second column -->
            <!-- Second column -->
            <div class="col-12 col-lg-4">
                <!-- Organize Card -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Organize</h5>
                    </div>
                    <div class="card-body">
                        <!-- Company -->
                        <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="project">
                                Project
                            </label>
                            <input id="project" type="text" class="form-control"
                                name="project" aria-label="Project"
                                value="{{ isset($product) ? $product->project : $user->project }}" disabled>
                        </div>
                        <!-- Category -->
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Category Dropdown -->
                            <div class="mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="category-org">
                                    <span>Category</span>
                                </label>
                                <select id="category-org" class="select2 form-select" data-placeholder="Select Category"
                                    name="category">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <!-- Status -->
                        <div class="mb-6 col ecommerce-select2-dropdown">
                            <label class="form-label mb-1" for="status-org">Status
                            </label>
                            <select id="status-org" class="select2 form-select" data-placeholder="Published"
                                name="status">
                                <option value="">Published</option>
                                <option value="Published"
                                    {{ isset($product) && $product->status == 'Published' ? 'selected' : '' }}>Published
                                </option>
                                <option value="Scheduled"
                                    {{ isset($product) && $product->status == 'Scheduled' ? 'selected' : '' }}>Scheduled
                                </option>
                                <option value="Inactive"
                                    {{ isset($product) && $product->status == 'Inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /Organize Card -->
            </div>
            <!-- /Second column -->
        </div>
    </div>

@endsection
