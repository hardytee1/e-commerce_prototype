@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Orders</h1>
    <table class="table">
        <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td><a href="{{ route('orders.show', $order) }}">{{ $order->id }}</a></td>
                <td>{{ $order->customer->name }}</td>
                <td>{{ $order->total_amount }}</td>
                <td>{{ $order->status }}</td>
                <td><a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">View</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
