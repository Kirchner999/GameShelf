@extends('layouts.app')

@section('content')
    <section class="section-head catalog-head">
        <div>
            <p class="eyebrow">Catalogue</p>
            <h1>Parcourir les jeux</h1>
            <p class="section-copy">Recherche, stock visible et actions rapides depuis une seule page.</p>
        </div>
        @if(auth()->check() && auth()->user()->isAdmin())
            <a class="primary-button" href="{{ route('admin.games.create') }}">Ajouter un jeu</a>
        @endif
    </section>

    <section class="catalog-toolbar">
        <form class="search-bar" method="GET" action="{{ route('games.index') }}">
            <input type="search" name="q" value="{{ $search }}" placeholder="Rechercher par titre">
            <button class="primary-button" type="submit">Rechercher</button>
        </form>
        <div class="catalog-badges">
            <span>vente</span>
            <span>location</span>
            <span>stock</span>
        </div>
    </section>

    <section class="card-grid catalog-grid">
        @forelse($games as $game)
            <article class="game-card catalog-card">
                <div class="catalog-card-top">
                    <span class="game-ribbon">{{ strtoupper($game->platform) }}</span>
                    <span class="availability {{ $game->available_stock > 0 ? 'available' : 'soldout' }}">
                        {{ $game->available_stock > 0 ? $game->available_stock.' dispo' : 'rupture' }}
                    </span>
                </div>
                <div class="game-meta">
                    <span>{{ $game->genre }}</span>
                    <span>{{ ucfirst($game->condition) }}</span>
                </div>
                <h3>{{ $game->title }}</h3>
                <p>{{ str_replace('_', ' / ', $game->offer_type) }}</p>
                <div class="game-footer">
                    <strong>{{ number_format((float) $game->price, 2, ',', ' ') }} &euro;</strong>
                    <span class="stock-chip">En stock</span>
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
                        <div class="inline-actions compact-actions">
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

    @if($games->hasPages())
        <nav class="pagination-nav" aria-label="Pagination catalogue">
            <div class="pagination-links">
                @if($games->onFirstPage())
                    <span class="pagination-link disabled">Precedent</span>
                @else
                    <a class="pagination-link" href="{{ $games->previousPageUrl() }}">Precedent</a>
                @endif

                <span class="pagination-current">Page {{ $games->currentPage() }} / {{ $games->lastPage() }}</span>

                @if($games->hasMorePages())
                    <a class="pagination-link" href="{{ $games->nextPageUrl() }}">Suivant</a>
                @else
                    <span class="pagination-link disabled">Suivant</span>
                @endif
            </div>
            <p class="pagination-info">{{ $games->firstItem() }} a {{ $games->lastItem() }} sur {{ $games->total() }} jeux</p>
        </nav>
    @endif
@endsection