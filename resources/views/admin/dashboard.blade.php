@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-12">
            <a href="{{ route('admin.topups') }}" class="btn btn-primary">View Top Up Requests</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Platform Income</h5>
                    <p class="card-text fs-2">Rp {{ number_format($totalIncome, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mt-5">Recent Profitable Transactions</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Platform Fee (Your Profit)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentOrders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>Rp {{ number_format($order->total_amount, 2) }}</td>
                    <td><strong>Rp {{ number_format($order->platform_fee, 2) }}</strong></td>
                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No transactions yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
