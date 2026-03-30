@extends('layouts.app')

@section('content')
    <section class="section-head">
        <div>
            <p class="eyebrow">Locations</p>
            <h1>{{ $isAdmin ? 'Toutes les locations' : 'Mes locations' }}</h1>
        </div>
        <a class="primary-button" href="{{ route('borrowings.create') }}">Nouvelle location</a>
    </section>

    <section class="list-stack">
        @forelse($borrowings as $borrowing)
            <article class="list-card">
                <div>
                    <h3>{{ $borrowing->game->title }}</h3>
                    <p>{{ $borrowing->user->pseudo }} &middot; debut {{ $borrowing->borrowed_at->format('d/m/Y') }}</p>
                </div>
                <div class="price-block">
                    <span>{{ $borrowing->returned_at ? 'Retourne le '.$borrowing->returned_at->format('d/m/Y') : 'En cours' }}</span>
                    @if(!$borrowing->returned_at)
                        <form method="POST" action="{{ route('borrowings.return', $borrowing) }}">
                            @csrf
                            @method('PATCH')
                            <button class="ghost-button" type="submit">Marquer retourne</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <p class="empty-state">Aucune location enregistree.</p>
        @endforelse
    </section>
@endsection