@extends('layouts.app')
@section('content')
<div class="container">
    @auth
        @php $isOwner = auth()->user()->shop && auth()->user()->shop->id === $shop->id; @endphp
        <h1>{{ $shop->name }}</h1>
        <p>Owner: {{ $shop->user->name }}</p>
        @if($isOwner)
            <p>Wallet Balance: Rp{{ number_format($shop->wallet_balance,0,',','.') }}</p>
            <a href="{{ route('shops.products.index', $shop) }}" class="btn btn-info">View Products</a>
            <a href="{{ route('shops.edit', $shop) }}" class="btn btn-warning">Edit Shop</a>
        @else
            <a href="{{ route('shops.products.index', $shop) }}" class="btn btn-info">View Products</a>
        @endif
        <a href="{{ route('shops.index') }}" class="btn btn-secondary">Back to Shops</a>
    @endauth
</div>
@endsection
