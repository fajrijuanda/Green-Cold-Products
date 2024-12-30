@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Account Settings - Security')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

<!-- Page Styles -->
@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-account-settings.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/pages-account-settings-security.js', 'resources/assets/js/modal-enable-otp.js'])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('account-settings-account') }}"><i
                                class="ti-sm ti ti-users me-1_5"></i> Account</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('account-settings-security') }}"><i
                                class="ti-sm ti ti-lock me-1_5"></i> Security</a></li>
                </ul>
            </div>
            <!-- Change Password -->
            <div class="card mb-6">
                <h5 class="card-header">Change Password</h5>
                <div class="card-body pt-1">
                    <form id="formAccountSettings" method="POST" action="{{ route('security.password.update') }}">
                        @csrf
                        @method('PATCH') <!-- Menggunakan PATCH sesuai dengan update -->
                        <div class="row">
                            <div class="mb-6 col-md-6 form-password-toggle">
                                <label class="form-label" for="currentPassword">Current Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="current_password" id="currentPassword"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('current_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-6 col-md-6 form-password-toggle">
                                <label class="form-label" for="newPassword">New Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" id="newPassword" name="new_password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('new_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-6 col-md-6 form-password-toggle">
                                <label class="form-label" for="confirmPassword">Confirm New Password</label>
                                <div class="input-group input-group-merge">
                                    <input class="form-control" type="password" name="new_password_confirmation"
                                        id="confirmPassword"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                                @error('new_password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3">Save changes</button>
                            <button type="reset" class="btn btn-label-secondary">Reset</button>
                        </div>
                    </form>

                </div>
            </div>
            <!--/ Change Password -->

            <!-- Two-steps verification -->
            <div class="card mb-6">
                <div class="card-body">
                    <h5 class="mb-6">Two-steps verification</h5>
                    <h5 class="mb-4 text-body">Two factor authentication is not enabled yet.</h5>
                    <p class="w-75">Two-factor authentication adds an additional layer of security to your account by
                        requiring more than just a password to log in.
                        <a href="javascript:void(0);">Learn more.</a>
                    </p>
                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#enableOTP">Enable
                        Two-Factor Authentication</button>
                </div>
            </div>
            <!-- Modal -->
            @include('_partials/_modals/modal-enable-otp')
            <!-- /Modal -->

            <!--/ Two-steps verification -->

            <!-- Recent Devices -->
            <div class="card mb-6">
                <h5 class="card-header">Recent Devices</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-truncate">Browser</th>
                                <th class="text-truncate">Device</th>
                                <th class="text-truncate">Location</th>
                                <th class="text-truncate">Recent Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDevices as $device)
                                <tr>
                                    <td class="text-truncate text-heading fw-medium">
                                        <i
                                            class='ti 
                                            @if ($device->platform === 'Windows') ti-brand-windows text-info
                                            @elseif($device->platform === 'iOS') ti-device-mobile text-danger
                                            @elseif($device->platform === 'Android') ti-brand-android text-success
                                            @elseif($device->platform === 'MacOS') ti-brand-apple
                                            @else ti-desktop @endif ti-md align-top me-2'>
                                        </i>
                                        {{ $device->browser }} on {{ $device->platform }}
                                    </td>
                                    <td class="text-truncate">{{ $device->device }}</td>
                                    <td class="text-truncate">{{ $device->location }}</td>
                                    <td class="text-truncate">{{ $device->last_activity->format('d, M Y H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent devices found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        
                    </table>
                </div>
            </div>
            <!--/ Recent Devices -->

        </div>
    </div>

@endsection
