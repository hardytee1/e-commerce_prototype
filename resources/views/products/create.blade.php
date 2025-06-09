@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Add Product to {{ $shop->name }}</h1>
    <form method="POST" action="{{ route('shops.products.store', $shop) }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
        <a href="{{ route('shops.products.index', $shop) }}" class="btn btn-secondary">Back to Products</a>
    </form>
</div>
@endsection
