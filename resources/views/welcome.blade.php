<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-commerce UMKM</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-blue-600">E-commerce UMKM</a>
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-gray-700 hover:text-blue-600">Register</a>
                @endauth
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">All Products</h1>
        <div class="flex justify-center mb-6">
            @auth
                <a href="{{ route('transactions.topup.form') }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Top Up Wallet</a>
            @endauth
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                    <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300x300?text=No+Image' }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded mb-4">
                    <h2 class="text-lg font-semibold mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-700 mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    @auth
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm w-20" style="width:60px;">
                            <button type="submit" class="btn btn-sm btn-success">Add to Cart</button>
                        </form>
                    @else
                        <button onclick="window.location='{{ route('login') }}'" class="btn btn-sm btn-primary mt-2">Login to Add to Cart</button>
                    @endauth
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">No products available.</div>
            @endforelse
        </div>
    </div>
</body>
</html>
