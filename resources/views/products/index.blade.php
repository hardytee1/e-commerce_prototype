@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Products in {{ $shop->name }}</h1>
    @auth
        @if(auth()->user()->shop && auth()->user()->shop->id !== $shop->id)
            <div class="alert alert-danger">You cannot view or manage products of other sellers' shops.</div>
        @else
            @if(auth()->user()->shop)
                <a href="{{ route('shops.products.create', $shop) }}" class="btn btn-primary mb-3">Add Product</a>
            @endif
            <table class="table">
                <thead><tr><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            @if(auth()->user()->shop && $product->shop_id === auth()->user()->shop->id)
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="{{ route('shops.show', $shop) }}" class="btn btn-secondary">Back to Shop</a>
        @endif
    @endauth
</div>
@endsection
