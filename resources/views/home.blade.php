@extends('layouts.app')

@section('content')
    <section class="hero">
        <div class="hero-copy-wrap">
            <p class="eyebrow">Marketplace jeux video</p>
            <h1>Acheter, louer et gerer un catalogue de jeux dans une interface claire et moderne.</h1>
            <p class="hero-copy">
                GameShelf rassemble le catalogue, le panier, les commandes, les locations et l'administration dans
                une application Laravel simple a presenter et agreable a utiliser.
            </p>
            <div class="hero-cta-row">
                <a class="primary-button" href="{{ route('games.index') }}">Voir le catalogue</a>
                @guest
                    <a class="ghost-button" href="{{ route('register') }}">Creer un compte</a>
                @else
                    <a class="ghost-button" href="{{ route('orders.index') }}">Voir mes commandes</a>
                @endguest
            </div>
            <div class="feature-strip">
                <span>Catalogue</span>
                <span>Panier</span>
                <span>Admin</span>
            </div>
        </div>

        <div class="hero-stage">
            <div class="hero-stage-card spotlight">
                <span class="card-kicker">Selection du moment</span>
                <strong>{{ $featuredGames->first()?->title ?? 'GameShelf' }}</strong>
                <p>Consultez les titres disponibles, le type d'offre et le stock restant en un coup d'oeil.</p>
            </div>
            <div class="hero-stage-grid">
                <div class="hero-stage-card">
                    <span class="card-kicker">Catalogue</span>
                    <strong>{{ $stats['catalogue'] }}</strong>
                    <p>jeux actifs</p>
                </div>
                <div class="hero-stage-card">
                    <span class="card-kicker">Stock</span>
                    <strong>{{ $stats['stock'] }}</strong>
                    <p>unites disponibles</p>
                </div>
                <div class="hero-stage-card">
                    <span class="card-kicker">Comptes</span>
                    <strong>{{ $stats['users'] }}</strong>
                    <p>utilisateurs</p>
                </div>
                <div class="hero-stage-card">
                    <span class="card-kicker">Commandes</span>
                    <strong>{{ $stats['orders'] }}</strong>
                    <p>enregistrees</p>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-grid home-stats">
        <article class="stat-card">
            <span>Locations</span>
            <strong>{{ $stats['location'] }}</strong>
            <p>Jeux disponibles a l'emprunt.</p>
        </article>
        <article class="stat-card">
            <span>Ventes</span>
            <strong>{{ $stats['vente'] }}</strong>
            <p>Jeux disponibles a l'achat.</p>
        </article>
        <article class="stat-card">
            <span>Comptes</span>
            <strong>{{ $stats['users'] }}</strong>
            <p>Utilisateurs presents dans la base.</p>
        </article>
    </section>

    <section class="section-head">
        <div>
            <p class="eyebrow">Selection</p>
            <h2>Jeux mis en avant</h2>
        </div>
        <a class="ghost-button" href="{{ route('games.index') }}">Voir tout le catalogue</a>
    </section>

    <section class="card-grid featured-grid">
        @foreach($featuredGames as $game)
            <article class="game-card featured-card">
                <div class="catalog-card-top">
                    <span class="game-ribbon">{{ strtoupper($game->platform) }}</span>
                    <span class="availability {{ $game->available_stock > 0 ? 'available' : 'soldout' }}">
                        {{ $game->available_stock > 0 ? $game->available_stock.' dispo' : 'rupture' }}
                    </span>
                </div>
                <div class="game-meta">
                    <span>{{ ucfirst(str_replace('_', ' / ', $game->offer_type)) }}</span>
                    <span>{{ ucfirst($game->condition) }}</span>
                </div>
                <h3>{{ $game->title }}</h3>
                <p>{{ $game->genre }} &middot; {{ $game->available_stock }} exemplaires disponibles</p>
                <div class="game-footer">
                    <strong>{{ number_format((float) $game->price, 2, ',', ' ') }} &euro;</strong>
                    <span class="stock-chip">En stock</span>
                </div>
            </article>
        @endforeach
    </section>
@endsection