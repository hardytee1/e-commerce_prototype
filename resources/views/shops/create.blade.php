@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Open New Shop</h1>
    <form method="POST" action="{{ route('shops.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Shop Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Shop</button>
    </form>
</div>
@endsection
