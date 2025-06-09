@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{ $user->name }}</h1>
    <p>Email: {{ $user->email }}</p>
    <p>Wallet Balance: {{ $user->wallet_balance }}</p>
    <h3>Shops</h3>
    <ul>
        @foreach($user->shops as $shop)
            <li><a href="{{ route('shops.show', $shop) }}">{{ $shop->name }}</a></li>
        @endforeach
    </ul>
</div>
@endsection
