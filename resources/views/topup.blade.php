@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md mt-10">
    <h2 class="text-2xl font-bold mb-4">Top Up Wallet</h2>
    <form method="POST" action="{{ route('transactions.topup') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="amount" class="block text-gray-700">Amount</label>
            <input type="number" name="amount" id="amount" min="1" step="0.01" required class="w-full border rounded px-3 py-2 mt-1" />
        </div>
        <div class="mb-4">
            <label for="image" class="block text-gray-700">Upload Proof of Payment</label>
            <input type="file" name="image" id="image" accept="image/*" required class="w-full border rounded px-3 py-2 mt-1" />
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Top Up</button>
    </form>

    <hr class="my-8">
    <h3 class="text-xl font-semibold mb-4">Top Up History</h3>
    <table class="table-auto w-full border">
        <thead>
            <tr>
                <th class="px-2 py-1 border">Amount</th>
                <th class="px-2 py-1 border">Status</th>
                <th class="px-2 py-1 border">Proof</th>
                <th class="px-2 py-1 border">Requested At</th>
            </tr>
        </thead>
        <tbody>
            @php
                $topups = \App\Models\Transaction::where('user_id', auth()->id())
                    ->where('type', 'top-up')
                    ->orderByDesc('created_at')
                    ->get();
            @endphp
            @forelse($topups as $topup)
                <tr>
                    <td class="px-2 py-1 border">Rp {{ number_format($topup->amount, 0, ',', '.') }}</td>
                    <td class="px-2 py-1 border">{{ ucfirst($topup->status) }}</td>
                    <td class="px-2 py-1 border">
                        @if($topup->image_url)
                            <a href="{{ asset('storage/' . $topup->image_url) }}" target="_blank">
                                <img src="{{ asset('storage/' . $topup->image_url) }}" alt="Proof" style="max-width:40px;max-height:40px;">
                            </a>
                        @else
                            <span class="text-muted">No image</span>
                        @endif
                    </td>
                    <td class="px-2 py-1 border">{{ $topup->created_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No top up history.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
