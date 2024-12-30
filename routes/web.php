<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserManagement;
use App\Http\Controllers\language\LanguageController;

use App\Http\Controllers\front_pages\Pricing;
use App\Http\Controllers\front_pages\Payment;
use App\Http\Controllers\front_pages\Checkout;
use App\Http\Controllers\front_pages\HelpCenter;
use App\Http\Controllers\front_pages\HelpCenterArticle;

use App\Http\Controllers\Products\ProductList;
use App\Http\Controllers\Products\ProductAdd;
use App\Http\Controllers\Products\ProductCategory;
use App\Http\Controllers\Products\ProductDetail;

use App\Http\Controllers\Dashboard\Dashboard;

use App\Http\Controllers\Projects\ProjectList;

use App\Http\Controllers\Accounts\UserProfile;
use App\Http\Controllers\Accounts\AccountSettingsAccount;
use App\Http\Controllers\Accounts\AccountSettingsSecurity;

use App\Http\Controllers\Auth\Authentication;
use App\Http\Controllers\Auth\VerifyEmailCover;
use App\Http\Controllers\Auth\ResetPasswordCover;
use App\Http\Controllers\Auth\ForgotPasswordCover;
use App\Http\Controllers\Auth\TwoStepsCover;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscNotAuthorized;
use App\Http\Controllers\pages\MiscUnderMaintenance;

// Main Page Route
Route::get('/', [Authentication::class, 'index'])->name('auth-login-cover');
Route::get('/login', [Authentication::class, 'index'])->name('auth-login-cover');
Route::post('/login', [Authentication::class, 'login'])->name('login');
Route::get('/forgot-password', [ForgotPasswordCover::class, 'index'])->name('auth-forgot-password-cover');
Route::post('/forgot-password', [ForgotPasswordCover::class, 'sendResetLink'])->name('auth-forgot-password-send');
Route::get('/verify-email', [VerifyEmailCover::class, 'index'])->name('auth-verify-email-cover');
Route::get('/reset-password', [ResetPasswordCover::class, 'index'])->name('reset-password');
    Route::post('/reset-password', [ResetPasswordCover::class,'update'])->name('password.update');
Route::get('/two-steps', [TwoStepsCover::class, 'index'])->name('auth-two-steps-cover');

Route::middleware('custom.error')->group(function () {
    Route::get('/error', [MiscError::class, 'index'])->name('error.index');
    Route::fallback([MiscError::class, 'index'])->name('error.index');
    Route::get('/not-authorized', [MiscNotAuthorized::class, 'index'])->name('error.not-authorized');
    Route::get('/under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('maintenance');
});

Route::middleware(['auth', 'session.timeout'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
    Route::get('/lang/{locale}', [LanguageController::class, 'swap']);
    // Project
    Route::get('/project/list', [ProjectList::class, 'ProjectList'])->name('project-list');
    Route::get('/project', [ProjectList::class, 'index'])->name('project');
    Route::post('/project/store', [ProjectList::class, 'store'])->name('project-store');
    Route::get('/project/{id}/edit', [ProjectList::class, 'getProjectById'])->name('project-edit');
    Route::put('/project/{id}/update', [ProjectList::class, 'update']);
    Route::delete('/project/{id}/delete', [ProjectList::class, 'destroy'])->name('project.destroy');

    Route::resource('/project-list', ProjectList::class);


    // User
    Route::get('/account-my-account', [UserProfile::class, 'index'])->name('account-my-account');
    Route::get('/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('account-settings-account');
    Route::patch('/account/update', [AccountSettingsAccount::class, 'update'])->name('account-update');
    Route::post('/account/deactivate', [AccountSettingsAccount::class, 'deactivate'])->name('account-deactivate');

    Route::get('/account-settings-security', [AccountSettingsSecurity::class, 'index'])->name('account-settings-security');
    Route::patch('/account-settings/security/password', [AccountSettingsSecurity::class, 'updatePassword'])
        ->name('security.password.update');

    // Rute-rute yang hanya boleh diakses oleh admin
    Route::get('/users/user-management/view', [UserManagement::class, 'UserManagement'])->name('users-user-management');
    Route::get('/users/user-management/statistics', [UserManagement::class, 'statistics'])->name('users.user-management.data');
    Route::get('/users/user-management', [UserManagement::class, 'index'])->name('users.user-management.index');
    Route::resource('/user-list', UserManagement::class);

    // Product
    Route::get('/product/list', [ProductList::class, 'ProductList'])->name('product-list');
    Route::get('/product', [ProductList::class, 'index'])->name('product');
    Route::delete('/product/{id}', [ProductList::class, 'destroy'])->name('product-delete');
    Route::get('/product/add', [ProductAdd::class, 'index'])->name('product-add');
    Route::post('/product/store', [ProductAdd::class, 'store'])->name('product-store');
    Route::get('/product/{slug}/edit', [ProductAdd::class, 'edit'])->name('product-edit');
    Route::post('/product/{slug}/update', [ProductAdd::class, 'update'])->name('product-update');

    //Product Category
    Route::get('/product/category/list', [ProductCategory::class, 'ProductCategory'])->name('product-category-list');
    Route::get('/category', [ProductCategory::class, 'index'])->name('product-category');
    Route::post('/category-list', [ProductCategory::class, 'store']); // Create
    Route::post('/category-list/{id}', [ProductCategory::class, 'update']); // Update (dengan _method=PUT)
    Route::resource('/category-list', ProductCategory::class);

    Route::get('/dashboard/{slug}', [ProductDetail::class, 'show'])->name('dashboard-detail');
    // Logout
    Route::post('/logout', [Authentication::class, 'logout'])->name('logout');

    Route::post('/logout-on-close', [Authentication::class, 'logoutOnClose'])->name('user.logout');
});

Route::get('/products/{slug}', [ProductDetail::class, 'show'])->name('product-detail');
