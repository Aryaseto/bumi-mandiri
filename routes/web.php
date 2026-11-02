<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\AdminActivityController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDestinationController;
use App\Http\Controllers\Admin\AdminPackageController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination}', [DestinationController::class, 'show'])->name('destinations.show');

Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/about', fn () => view('pages.about'))->name('about');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Rating pengunjung umum (tanpa login), dibatasi agar tidak spam.
Route::post('/activities/{activity}/rating', [ReviewController::class, 'storeVisitor'])
    ->middleware('throttle:5,1')
    ->name('reviews.visitor.store');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->post('/logout', [LoginController::class, 'destroy'])->name('logout');

/*
|--------------------------------------------------------------------------
| USER ROUTES (authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile.edit');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');

    // Booking flow (multi-step)
    Route::get('/booking/{activity}/start', [BookingController::class, 'start'])->name('booking.start');
    Route::post('/booking/{activity}/store', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/confirmed', [BookingController::class, 'confirmed'])->name('booking.confirmed');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Favorite
    Route::post('/favorites/{activity}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    // Review
    Route::post('/bookings/{booking}/activities/{activity}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', AdminUserController::class)->except('show');
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('destinations', AdminDestinationController::class)->except('show');
    Route::resource('activities', AdminActivityController::class)->except('show');
    Route::resource('packages', AdminPackageController::class)->except('show');

    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::put('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');

    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::put('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::resource('articles', \App\Http\Controllers\Admin\AdminArticleController::class)->except('show');
    Route::resource('gallery', \App\Http\Controllers\Admin\AdminGalleryController::class)->except('show');

    Route::get('settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
