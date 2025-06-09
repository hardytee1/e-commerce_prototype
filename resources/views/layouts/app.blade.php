<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container">
                <a class="navbar-brand" href="/">Marketplace</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="{{ route('shops.index') }}">Shops</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">Orders</a></li>
                        @auth
                            @if(!auth()->user()->shop)
                            <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}">Keranjang</a></li>
                            @endif
                        @endauth
                    </ul>
                    @auth
                    <div class="d-flex align-items-center ms-3">
                        @if(auth()->user()->shop)
                            <span class="me-2">Wallet: <strong>Rp{{ number_format(auth()->user()->shop->wallet_balance ?? 0, 0, ',', '.') }}</strong></span>
                        @else
                            <span class="me-2">Wallet: <strong>Rp{{ number_format(auth()->user()->wallet_balance ?? 0, 0, ',', '.') }}</strong></span>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="d-flex ms-auto">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Logout</button>
                    </form>
                    @endauth
                </div>
            </div>
        </nav>
        <main>
            @yield('content')
        </main>
    </body>
</html>
