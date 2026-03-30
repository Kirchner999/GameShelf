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
            <article class="list-card column">
                <div class="list-head">
                    <div>
                        <h3>{{ $borrowing->game->title }}</h3>
                        <p>{{ $borrowing->user->pseudo }} &middot; debut {{ $borrowing->borrowed_at->format('d/m/Y') }}</p>
                    </div>
                    <span class="availability {{ $borrowing->returned_at ? 'soldout' : 'available' }}">
                        {{ $borrowing->returned_at ? 'Retournee' : 'En cours' }}
                    </span>
                </div>
                @if($borrowing->returned_at)
                    <p>Retour enregistre le {{ $borrowing->returned_at->format('d/m/Y') }}</p>
                @else
                    <div class="card-actions-row">
                        <span class="muted">Location active</span>
                        <form method="POST" action="{{ route('borrowings.return', $borrowing) }}">
                            @csrf
                            @method('PATCH')
                            <button class="ghost-button" type="submit">Marquer comme retournee</button>
                        </form>
                    </div>
                @endif
            </article>
        @empty
            <p class="empty-state">Aucune location enregistree.</p>
        @endforelse
    </section>
@endsection