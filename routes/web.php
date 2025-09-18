<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Mail\SendReportMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

// Home Page - langsung ke product
Route::get('/', function () {
    return redirect('/product');
})->name('home');

// Login page
Route::get('/login', function () {
    return view('login');
})->name('login');

// Login action
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Product page (bisa diakses tanpa login)
Route::get('/product', [ProductController::class, 'index'])
    ->name('product');

// Cart routes
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::delete('/cart/remove/{cart_id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'getCart'])->name('cart.get');

// Checkout
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout')->middleware('auth.session');
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process')->middleware('auth.session');

// Profile
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile')->middleware('auth.session');
Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update')->middleware('auth.session');

// Register
Route::get('/register', function () {
    $cities = \App\Models\City::all();
    return view('register', compact('cities'));
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/send-pdf', function () {
    // Generate PDF di memory
    $pdf = Pdf::loadView('pdf.report', [
        'nama' => 'Hun',
        'periode' => 'September 2025'
    ]);
    $pdfContent = $pdf->output();

    // Kirim Email
    Mail::to('targetemail@example.com')->send(new SendReportMail($pdfContent));

    return "Email dengan PDF terkirim!";
});