@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Shops</h1>
    @auth
        @if(auth()->user()->shop)
            <div class="alert alert-info">You are a seller. You cannot view or edit other shops.</div>
            <a href="{{ route('shops.show', auth()->user()->shop) }}" class="btn btn-primary mb-3">Go to My Shop</a>
        @else
            <a href="{{ route('shops.create') }}" class="btn btn-primary mb-3">Open New Shop</a>
            <table class="table">
                <thead><tr><th>Name</th><th>Owner</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($shops as $shop)
                    <tr>
                        <td><a href="{{ route('shops.show', $shop) }}">{{ $shop->name }}</a></td>
                        <td>{{ $shop->user->name }}</td>
                        <td>
                            <a href="{{ route('shops.products.index', $shop) }}" class="btn btn-sm btn-info">Products</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    @endauth
</div>
@endsection
