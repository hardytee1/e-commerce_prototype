@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Order #{{ $order->id }}</h1>
    <p>Customer: {{ $order->customer->name }}</p>
    <p>Total: {{ $order->total_amount }}</p>
    <p>Status: {{ $order->status }}</p>
    @if(auth()->user()->shop && $order->status !== 'completed')
        <form action="{{ route('orders.complete', $order) }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-success">Mark as Completed</button>
        </form>
    @endif
    <h3>Items</h3>
    <table class="table">
        <thead><tr><th>Product</th><th>Shop</th><th>Quantity</th><th>Price</th></tr></thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->shop->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price_per_unit }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
