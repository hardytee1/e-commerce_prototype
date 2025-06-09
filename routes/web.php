<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = \App\Models\Product::with('shop')->latest()->get();
    return view('welcome', compact('products'));
});

Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->id === 1) {
        return redirect('/admin/dashboard');
    }
    $products = \App\Models\Product::with('shop')->latest()->get();
    return view('dashboard', compact('products'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{itemId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{itemId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::get('/topup', [\App\Http\Controllers\TransactionController::class, 'showTopUpForm'])->name('transactions.topup.form');
    Route::post('/topup', [\App\Http\Controllers\TransactionController::class, 'topUp'])->name('transactions.topup');
});

Route::resource('shops', ShopController::class);
Route::resource('shops.products', ProductController::class)->shallow();

// Route for sellers to mark an order as in shipping
Route::post('/orders/{order}/inshipping', [OrderController::class, 'markInShipping'])->name('orders.inshipping');
// Route for sellers to mark an order as completed
Route::post('/orders/{order}/complete', [OrderController::class, 'markCompleted'])->name('orders.complete');

Route::resource('orders', OrderController::class);
Route::resource('users', UserController::class);

// Show all products from all shops
Route::get('/products-all', [ProductController::class, 'allProducts'])->name('products.all');

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

Route::get('/admin/topups', [\App\Http\Controllers\AdminTopupController::class, 'index'])->name('admin.topups');
Route::post('/admin/topups/{id}/approve', [\App\Http\Controllers\AdminTopupController::class, 'approve'])->name('admin.topups.approve');

require __DIR__.'/auth.php';
