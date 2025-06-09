@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Checkout</h2>
    @if(isset($success) && $success)
        <div class="alert alert-success">Checkout berhasil! Total dibayar: Rp{{ number_format($total,0,',','.') }}</div>
        <a href="{{ route('orders.index') }}" class="btn btn-primary">Lihat Pesanan</a>
    @else
        <div class="alert alert-danger">Checkout gagal. Silakan coba lagi.</div>
        <a href="{{ route('cart.index') }}" class="btn btn-secondary">Kembali ke Keranjang</a>
    @endif
</div>
@endsection
