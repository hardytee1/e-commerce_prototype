@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Pending Top Up Requests</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Amount</th>
                <th>Proof</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingTopups as $topup)
                <tr>
                    <td>{{ $topup->user->name }}</td>
                    <td>Rp {{ number_format($topup->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($topup->image_url)
                            <a href="{{ asset('storage/' . $topup->image_url) }}" target="_blank">
                                <img src="{{ asset('storage/' . $topup->image_url) }}" alt="Proof" style="max-width:80px;max-height:80px;">
                            </a>
                        @else
                            <span class="text-muted">No image</span>
                        @endif
                    </td>
                    <td>{{ ucfirst($topup->status) }}</td>
                    <td>
                        @if($topup->status === 'pending')
                        <form method="POST" action="{{ route('admin.topups.approve', $topup->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        @else
                            <span class="text-success">Approved</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No pending top-ups.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
