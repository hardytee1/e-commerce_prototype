@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard</h1>
        @auth
            @if(auth()->user()->shop)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 mb-4">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v4a1 1 0 001 1h3m10-5h3a1 1 0 011 1v4a1 1 0 01-1 1h-3m-10 0v6a2 2 0 002 2h6a2 2 0 002-2v-6m-10 0h10" /></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">My Shop</h3>
                        <p class="text-gray-500 text-sm mb-4 text-center">View and manage your shop and products.</p>
                        <a href="{{ route('shops.show', auth()->user()->shop) }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">Go to My Shop</a>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a4 4 0 014-4h4m0 0V7a4 4 0 00-4-4H7a4 4 0 00-4 4v10a4 4 0 004 4h4" /></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Orders</h3>
                        <p class="text-gray-500 text-sm mb-4 text-center">View and process customer orders for your shop.</p>
                        <a href="{{ route('orders.index') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Go to Orders</a>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 8v8" /></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Wallet Balance</h3>
                        <p class="text-2xl font-bold text-gray-800 mb-2">Rp {{ number_format(auth()->user()->shop->wallet_balance, 0, ',', '.') }}</p>
                        <p class="text-gray-500 text-xs">Your shop's current balance</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('shops.products.create', auth()->user()->shop) }}" class="inline-block px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition">Add New Product</a>
                        <a href="{{ route('orders.index') }}" class="inline-block px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">View Orders</a>
                        <a href="{{ route('shops.show', auth()->user()->shop) }}" class="inline-block px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">Manage Shop</a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="bg-white rounded-lg shadow p-8 flex flex-col justify-center items-center">
                        <h2 class="text-2xl font-bold mb-2 text-gray-900">Become a Seller</h2>
                        <p class="text-gray-500 mb-4 text-center">Open your own shop and start selling products to customers!</p>
                        <form method="POST" action="{{ route('shops.store') }}" class="w-full max-w-xs">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Shop Name</label>
                                <input type="text" name="name" class="block w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">Open a Shop</button>
                        </form>
                    </div>
                </div>
                <div class="bg-white">
                  <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                    <h2 class="text-2xl font-bold mb-8 text-gray-900">All Products</h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                      @forelse($products as $product)
                        <div class="group block">
                          <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x300?text=No+Image' }}" alt="{{ $product->name }}" class="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-75 xl:aspect-7/8">
                          <h3 class="mt-4 text-sm text-gray-700">{{ $product->name }}</h3>
                          <p class="mt-1 text-lg font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                          <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2 flex items-center gap-2">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm w-20" style="width:60px;">
                            <button type="submit" class="btn btn-sm btn-success">Add to Cart</button>
                          </form>
                        </div>
                      @empty
                        <div class="col-span-full text-center text-gray-500">No products available.</div>
                      @endforelse
                    </div>
                  </div>
                </div>
            @endif
        @endauth
    </div>
</div>
@endsection
