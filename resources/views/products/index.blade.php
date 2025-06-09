@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Products in {{ $shop->name }}</h1>
    @auth
        @php $isOwner = auth()->user()->shop && auth()->user()->shop->id === $shop->id; @endphp
        @if(auth()->user()->shop && !$isOwner)
            <div class="alert alert-danger">You cannot view or manage products of other sellers' shops.</div>
        @else
            @if($isOwner)
                <a href="{{ route('shops.products.create', $shop) }}" class="btn btn-primary mb-3">Add Product</a>
            @endif
            <div class="bg-white">
                <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 sm:py-12 lg:max-w-7xl lg:px-8">
                    <h2 class="text-2xl font-bold mb-8 text-gray-900">Product List</h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                        @forelse($products as $product)
                            <div class="group block">
                                <img src="{{ $product->image_url ? Storage::url($product->image_url) : 'https://via.placeholder.com/300x300?text=No+Image' }}" alt="{{ $product->name }}" class="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-75 xl:aspect-7/8">
                                <h3 class="mt-4 text-sm text-gray-700">{{ $product->name }}</h3>
                                <p class="mt-1 text-lg font-medium text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-muted">Stock: {{ $product->stock }}</p>
                                @if(!$isOwner)
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-2 flex items-center gap-2">
                                        @csrf
                                        <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm w-20" style="width:60px;">
                                        <button type="submit" class="btn btn-sm btn-success">Add to Cart</button>
                                    </form>
                                @else
                                    <div class="mt-2 flex gap-2">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-500">No products available.</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <a href="{{ route('shops.show', $shop) }}" class="btn btn-secondary mt-4">Back to Shop</a>
        @endif
    @endauth
</div>
@endsection
