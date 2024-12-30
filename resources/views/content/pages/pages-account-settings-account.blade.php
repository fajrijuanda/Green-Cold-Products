@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Account Settings - Account')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/pages-account-settings-account.js'])
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('account-settings-account') }}"><i
                                class="ti-sm ti ti-users me-1_5"></i> Account</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('account-settings-security') }}"><i
                                class="ti-sm ti ti-lock me-1_5"></i> Security</a></li>
                </ul>
            </div>

            <div class="card mb-6">
                <form id="formAccountSettings" method="POST" action="{{ route('account-update') }}"
                    enctype="multipart/form-data"> @csrf @method('PATCH')
                    <div class="card-body">
                        <!-- Avatar Upload Section -->
                        <div class="d-flex align-items-start align-items-sm-center gap-6">
                            <img src="{{ 'assets/img/avatars/'.Auth::user()->avatar }}" alt="user-avatar"
                                class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="ti ti-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" name="avatar" class="account-file-input" hidden
                                        accept="image/png, image/jpeg, image/gif" />
                                </label>
                                <button type="button" id="resetAvatar"
                                    class="btn btn-label-secondary account-image-reset mb-4">
                                    <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>
                                <div class="text-muted">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                @error('avatar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input class="form-control" type="text" id="firstName" name="first_name"
                                    value="{{ Auth::user()->first_name }}" autofocus />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input class="form-control" type="text" name="last_name" id="lastName"
                                    value="{{ Auth::user()->last_name }}" />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="email" id="email" name="email"
                                    value="{{ Auth::user()->email }}" placeholder="john.doe@example.com" />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="phoneNumber">Phone Number</label>
                                <input type="text" id="phoneNumber" name="phone_number" class="form-control"
                                    value="{{ Auth::user()->phone_number }}" placeholder="202 555 0111" />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    value="{{ Auth::user()->address }}" placeholder="Address" />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="province" class="form-label">Province</label>
                                <input class="form-control" type="text" id="province" name="province"
                                    value="{{ Auth::user()->province }}" placeholder="Jawa Barat" />
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="country">Country</label>
                                <select id="country" name="country" class="select2 form-select">
                                    <option value="">Select</option>
                                    <option value="Indonesia"
                                        {{ Auth::user()->country == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                    <option value="United States"
                                        {{ Auth::user()->country == 'United States' ? 'selected' : '' }}>United States
                                    </option>
                                    <!-- Add other options as needed -->
                                </select>
                            </div>
                            <div class="mb-4 col-md-6">
                                <label for="zipCode" class="form-label">Zip Code</label>
                                <input type="text" class="form-control" id="zipCode" name="zip_code"
                                    value="{{ Auth::user()->zip_code }}" placeholder="231465" maxlength="6" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-3">Save changes</button>
                            <button type="reset" class="btn btn-label-secondary">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /Account -->
            <div class="card">
                <h5 class="card-header">Delete Account</h5>
                <div class="card-body">
                    <div class="mb-6 col-12 mb-0">
                        <div class="alert alert-warning">
                            <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                            <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                        </div>
                    </div>
                    <form id="formAccountDeactivation" method="POST" action="{{ route('account-deactivate') }}">
                        @csrf
                        <div class="form-check my-8">
                            <input class="form-check-input" type="checkbox" name="account_activation"
                                id="accountActivation" />
                            <label class="form-check-label" for="accountActivation">I confirm my account
                                deactivation</label>
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account" disabled>Deactivate
                            Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
