@extends('layouts.app')

@section('content')
    <section class="section-head">
        <div>
            <p class="eyebrow">Catalogue</p>
            <h1>Jeux disponibles</h1>
        </div>
        @if(auth()->check() && auth()->user()->isAdmin())
            <a class="primary-button" href="{{ route('admin.games.create') }}">Ajouter un jeu</a>
        @endif
    </section>

    <form class="search-bar" method="GET" action="{{ route('games.index') }}">
        <input type="search" name="q" value="{{ $search }}" placeholder="Rechercher par titre">
        <button class="primary-button" type="submit">Rechercher</button>
    </form>

    <section class="card-grid">
        @forelse($games as $game)
            <article class="game-card">
                <div class="game-meta">
                    <span>{{ $game->platform }}</span>
                    <span>{{ $game->genre }}</span>
                </div>
                <h3>{{ $game->title }}</h3>
                <p>{{ ucfirst($game->condition) }} &middot; {{ str_replace('_', ' + ', $game->offer_type) }}</p>
                <div class="game-footer">
                    <strong>{{ number_format((float) $game->price, 2, ',', ' ') }} &euro;</strong>
                    <span>{{ $game->available_stock }} dispo</span>
                </div>
                <div class="actions-stack">
                    @if($game->canBePurchased())
                        <form method="POST" action="{{ route('cart.add') }}">
                            @csrf
                            <input type="hidden" name="game_id" value="{{ $game->id }}">
                            <button class="primary-button full-width" type="submit">Ajouter au panier</button>
                        </form>
                    @endif

                    @auth
                        @if($game->canBeBorrowed())
                            <a class="ghost-button full-width" href="{{ route('borrowings.create') }}">Louer ce jeu</a>
                        @endif
                    @endauth

                    @if(auth()->check() && auth()->user()->isAdmin())
                        <div class="inline-actions">
                            <a class="ghost-button" href="{{ route('admin.games.edit', $game) }}">Modifier</a>
                            <form method="POST" action="{{ route('admin.games.destroy', $game) }}">
                                @csrf
                                @method('DELETE')
                                <button class="danger-button" type="submit">Supprimer</button>
                            </form>
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <p class="empty-state">Aucun jeu ne correspond a la recherche.</p>
        @endforelse
    </section>

    <div class="pagination-wrap">
        {{ $games->links() }}
    </div>
@endsection