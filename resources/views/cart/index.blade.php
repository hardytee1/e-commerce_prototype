@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Keranjang Belanja</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($items->count())
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>Rp{{ number_format($item->product->price,0,',','.') }}</td>
                <td>
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width:60px;">
                        <button class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
                <td>Rp{{ number_format($item->product->price * $item->quantity,0,',','.') }}</td>
                <td>
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="text-end">
        <form action="{{ route('cart.checkout') }}" method="POST" class="d-inline">
            @csrf
            <div class="mb-3">
                <label for="address" class="form-label">Alamat Pengiriman</label>
                <input type="text" name="address" id="address" class="form-control" required placeholder="Masukkan alamat lengkap">
            </div>
            <button type="submit" class="btn btn-success">Checkout</button>
        </form>
    </div>
    @else
        <p>Keranjang kosong.</p>
    @endif
</div>
@endsection
