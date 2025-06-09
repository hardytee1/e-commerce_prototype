@extends('layouts.app')
@section('content')
<div class="container">
    <h1>All Products</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Shop</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->shop->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    @auth
                        @if(!auth()->user()->shop)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" style="width:60px;">
                            <button class="btn btn-sm btn-success">Add to Cart</button>
                        </form>
                        @endif
                    @endauth
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
