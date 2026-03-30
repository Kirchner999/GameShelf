@extends('layouts.app')

@section('content')
    <section class="hero">
        <div>
            <p class="eyebrow">Laravel Marketplace</p>
            <h1>Louer, acheter et administrer un catalogue de jeux video depuis une seule application.</h1>
            <p class="hero-copy">
                Cette version de GameShelf reprend l'esprit de Client_leger mais avec une vraie structure Laravel:
                auth, catalogue, panier, commandes, locations et espace admin.
            </p>
        </div>
        <div class="hero-panel">
            <div>
                <strong>{{ $stats['catalogue'] }}</strong>
                <span>jeux au catalogue</span>
            </div>
            <div>
                <strong>{{ $stats['stock'] }}</strong>
                <span>unites en stock</span>
            </div>
            <div>
                <strong>{{ $stats['users'] }}</strong>
                <span>comptes seedes</span>
            </div>
            <div>
                <strong>{{ $stats['orders'] }}</strong>
                <span>commandes existantes</span>
            </div>
        </div>
    </section>

    <section class="stats-grid">
        <article class="stat-card">
            <span>Catalogue mixte</span>
            <strong>{{ $stats['catalogue'] }}</strong>
            <p>Jeux a gerer en location, vente ou double mode.</p>
        </article>
        <article class="stat-card">
            <span>Location</span>
            <strong>{{ $stats['location'] }}</strong>
            <p>Titres disponibles pour l'emprunt.</p>
        </article>
        <article class="stat-card">
            <span>Vente</span>
            <strong>{{ $stats['vente'] }}</strong>
            <p>Jeux pouvant etre ajoutes au panier.</p>
        </article>
    </section>

    <section class="section-head">
        <div>
            <p class="eyebrow">Selection</p>
            <h2>Jeux mis en avant</h2>
        </div>
        <a class="ghost-button" href="{{ route('games.index') }}">Voir tout le catalogue</a>
    </section>

    <section class="card-grid">
        @foreach($featuredGames as $game)
            <article class="game-card">
                <div class="game-meta">
                    <span>{{ $game->platform }}</span>
                    <span>{{ ucfirst(str_replace('_', ' / ', $game->offer_type)) }}</span>
                </div>
                <h3>{{ $game->title }}</h3>
                <p>{{ $game->genre }} · {{ $game->condition }}</p>
                <div class="game-footer">
                    <strong>{{ number_format((float) $game->price, 2, ',', ' ') }} €</strong>
                    <span>{{ $game->available_stock }} dispo</span>
                </div>
            </article>
        @endforeach
    </section>
@endsection
