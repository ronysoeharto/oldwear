<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [PageController::class, 'categories'])->name('categories.index');
Route::get('/categories/{category:slug}', [ProductController::class, 'index'])->name('categories.show');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Route::get('/storage/{path}', function (string $path) {
    $fullPath = storage_path('app/public/' . $path);
    if (! file_exists($fullPath) || is_dir($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath, [
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');

    Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Admin (auth + role admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', Admin\DashboardController::class)->name('dashboard');

    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('testimonials', Admin\TestimonialController::class)->except('show');
    Route::patch('testimonials/{testimonial}/toggle', [Admin\TestimonialController::class, 'toggle'])->name('testimonials.toggle');

    Route::get('/store-profile', [Admin\StoreProfileController::class, 'edit'])->name('store-profile.edit');
    Route::put('/store-profile', [Admin\StoreProfileController::class, 'update'])->name('store-profile.update');
    
    Route::get('/banner', [Admin\StoreProfileController::class, 'editBanner'])->name('banner.edit');
    Route::put('/banner', [Admin\StoreProfileController::class, 'updateBanner'])->name('banner.update');
    Route::delete('/banner', [Admin\StoreProfileController::class, 'destroyBanner'])->name('banner.destroy');

    Route::get('/whatsapp', [Admin\StoreProfileController::class, 'editWhatsapp'])->name('whatsapp.edit');
    Route::put('/whatsapp', [Admin\StoreProfileController::class, 'updateWhatsapp'])->name('whatsapp.update');

    Route::get('/profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
});
