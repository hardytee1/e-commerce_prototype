@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Edit Shop</h1>
    <form method="POST" action="{{ route('shops.update', $shop) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Shop Name</label>
            <input type="text" name="name" class="form-control" value="{{ $shop->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Shop</button>
    </form>
</div>
@endsection
