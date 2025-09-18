<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Mail\SendReportMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

// Home Page - redirect based on role
Route::get('/', function () {
    $role = Session::get('role');
    if ($role === 'vendor') {
        return redirect()->route('vendor.dashboard');
    }
    elseif($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
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

// Vendor routes
Route::get('/vendor/dashboard', function () {
    $vendorId = Session::get('user_id');
    $products = \App\Models\Product::where('vendor_id', $vendorId)->with('category')->paginate(10);
    return view('vendor.dashboard', compact('products'));
})->name('vendor.dashboard')->middleware(['auth.session', 'role:vendor']);

// Vendor product management routes
Route::get('/vendor/products', [ProductController::class, 'vendorIndex'])->name('vendor.product.index')->middleware(['auth.session', 'role:vendor']);
Route::get('/vendor/products/create', [ProductController::class, 'create'])->name('vendor.product.create')->middleware(['auth.session', 'role:vendor']);
Route::post('/vendor/products', [ProductController::class, 'store'])->name('vendor.product.store')->middleware(['auth.session', 'role:vendor']);
Route::get('/vendor/products/{product_id}/edit', [ProductController::class, 'edit'])->name('vendor.product.edit')->middleware(['auth.session', 'role:vendor']);
Route::put('/vendor/products/{product_id}', [ProductController::class, 'update'])->name('vendor.product.update')->middleware(['auth.session', 'role:vendor']);
Route::delete('/vendor/products/{product_id}', [ProductController::class, 'destroy'])->name('vendor.product.destroy')->middleware(['auth.session', 'role:vendor']);

// Vendor apply route
Route::get('/vendor/apply', function () {
    return view('vendor.apply');
})->name('vendor.apply');

// Vendor register routes
Route::get('/vendor/register', function () {
    $cities = \App\Models\City::all();
    return view('vendor.register', compact('cities'));
})->name('vendor.register');

Route::post('/vendor/register', [AuthController::class, 'registerVendor'])->name('vendor.register.post');

use App\Http\Controllers\AdminController;

// Admin routes
Route::get('/admin/dashboard', function () {
    $totalUsers = \App\Models\User::count();
    $totalProducts = \App\Models\Product::count();
    $totalOrders = \App\Models\Order::count();
    $recentFeedback = \App\Models\Feedback::with('user')->latest()->take(5)->get();
    return view('admin.dashboard', compact('totalUsers', 'totalProducts', 'totalOrders', 'recentFeedback'));
})->name('admin.dashboard')->middleware(['auth.session', 'role:admin']);

// Admin vendor approval routes
Route::get('/admin/vendors/pending', [AdminController::class, 'pendingVendors'])->name('admin.pending_vendors')->middleware(['auth.session', 'role:admin']);
Route::post('/admin/vendors/{userId}/approve', [AdminController::class, 'approveVendor'])->name('admin.approve_vendor')->middleware(['auth.session', 'role:admin']);
Route::post('/admin/vendors/{userId}/reject', [AdminController::class, 'rejectVendor'])->name('admin.reject_vendor')->middleware(['auth.session', 'role:admin']);

// Admin category management routes
Route::get('/admin/categories', [AdminController::class, 'manageCategories'])->name('admin.manage_categories')->middleware(['auth.session', 'role:admin']);
Route::post('/admin/categories', [AdminController::class, 'createCategory'])->name('admin.create_category')->middleware(['auth.session', 'role:admin']);
Route::put('/admin/categories/{categoryId}', [AdminController::class, 'updateCategory'])->name('admin.update_category')->middleware(['auth.session', 'role:admin']);
Route::delete('/admin/categories/{categoryId}', [AdminController::class, 'deleteCategory'])->name('admin.delete_category')->middleware(['auth.session', 'role:admin']);

// Admin customer management routes
Route::get('/admin/customers', [AdminController::class, 'manageCustomers'])->name('admin.manage_customers')->middleware(['auth.session', 'role:admin']);
Route::get('/admin/customers/{userId}/edit', [AdminController::class, 'editCustomer'])->name('admin.edit_customer')->middleware(['auth.session', 'role:admin']);
Route::put('/admin/customers/{userId}', [AdminController::class, 'updateCustomer'])->name('admin.update_customer')->middleware(['auth.session', 'role:admin']);
Route::delete('/admin/customers/{userId}', [AdminController::class, 'deleteCustomer'])->name('admin.delete_customer')->middleware(['auth.session', 'role:admin']);

// Admin order management routes
Route::get('/admin/orders', [AdminController::class, 'manageOrders'])->name('admin.manage_orders')->middleware(['auth.session', 'role:admin']);
Route::post('/admin/orders/{orderId}/ship', [AdminController::class, 'shipOrder'])->name('admin.ship_order')->middleware(['auth.session', 'role:admin']);

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
