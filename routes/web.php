<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrScanController;
use Illuminate\Support\Facades\Route;

// Public storefront — the product catalog is the homepage.
Route::get('/', [ProductController::class, 'index'])->name('storefront.index');

Route::post('/locale/toggle', [LocaleController::class, 'toggle'])->name('locale.toggle');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{product}/quantity', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Feedback routes
Route::get('/feedback/{order}', [FeedbackController::class, 'create'])
    ->name('feedback.create');
Route::post('/feedback/{order}', [FeedbackController::class, 'store'])
    ->name('feedback.store');
Route::get('/feedback/thanks', [FeedbackController::class, 'thanks'])
    ->name('feedback.thanks');

// Hidden QR redirect route
Route::get('/links', [QrScanController::class, 'redirect'])
    ->name('qr.redirect');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
