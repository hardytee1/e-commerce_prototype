@extends('layouts.app')
@section('content')
<div class="container">
    @auth
        @php $isOwner = auth()->user()->shop && auth()->user()->shop->id === $shop->id; @endphp
        <h1>{{ $shop->name }}</h1>
        <p>Owner: {{ $shop->user->name }}</p>
        @if($isOwner)
            <p>Wallet Balance: Rp{{ number_format($shop->wallet_balance,0,',','.') }}</p>
            <a href="{{ route('shops.edit', $shop) }}" class="btn btn-warning mb-3">Edit Shop</a>
        @endif
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Products</h2>
            <div class="row">
                @forelse($shop->products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <img src="{{ $product->image_url ? Storage::url($product->image_url) : 'https://via.placeholder.com/300x300?text=No+Image' }}" class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="card-text text-muted">Stock: {{ $product->stock }}</p>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-primary">View</a>
                                @if($isOwner)
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-gray-500">No products available for this shop.</div>
                @endforelse
            </div>
        </div>
        @if(!$isOwner)
            <a href="{{ route('shops.index') }}" class="btn btn-secondary">Back to Shops</a>
        @endif
    @endauth
</div>
@endsection
