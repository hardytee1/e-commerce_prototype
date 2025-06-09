@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{ $product->name }}</h1>
    <p>Price: {{ $product->price }}</p>
    <p>Stock: {{ $product->stock }}</p>
    @auth
        @if(!auth()->user()->shop)
        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mb-2">
            @csrf
            <input type="number" name="quantity" value="1" min="1" style="width:60px;">
            <button class="btn btn-success">Add to Cart</button>
        </form>
        @endif
    @endauth
    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit Product</a>
    <a href="{{ route('shops.show', $product->shop) }}" class="btn btn-secondary">Back to Shop</a>
</div>
@endsection
