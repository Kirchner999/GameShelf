<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GameShelf') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}">
</head>
<body>
    @php($cartCount = collect(session('cart', []))->sum())
    <div class="site-backdrop"></div>

    <header class="site-header">
        <div class="shell nav-row">
            <a class="brand" href="{{ route('home') }}">
                <span class="brand-mark">GS</span>
                <span>GameShelf</span>
            </a>
            <nav class="nav-links">
                <a href="{{ route('games.index') }}">Catalogue</a>
                <a href="{{ route('cart.index') }}">Panier <span>{{ $cartCount }}</span></a>
                @auth
                    <a href="{{ route('borrowings.index') }}">Locations</a>
                    <a href="{{ route('orders.index') }}">Commandes</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif
                @endauth
            </nav>
            <div class="nav-actions">
                @auth
                    <span class="pill">{{ auth()->user()->pseudo }} &middot; {{ auth()->user()->role }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="ghost-button" type="submit">Deconnexion</button>
                    </form>
                @else
                    <a class="ghost-button" href="{{ route('login') }}">Connexion</a>
                    <a class="primary-button" href="{{ route('register') }}">Inscription</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="shell page">
        @if(session('status'))
            <div class="flash success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="flash error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>