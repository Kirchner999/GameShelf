@extends('layouts.app')

@section('content')
    <form class="form-card" method="POST" action="{{ route('borrowings.store') }}">
        @csrf
        <div>
            <p class="eyebrow">Nouvelle location</p>
            <h1>Reserver un jeu</h1>
            <p class="muted">Limite: 2 locations actives. Vous en avez actuellement {{ $activeBorrowCount }}.</p>
        </div>

        <label>
            <span>Jeu</span>
            <select name="game_id" @disabled(!$canCreateBorrowing)>
                @foreach($games as $game)
                    <option value="{{ $game->id }}">
                        {{ $game->title }} · {{ $game->platform }} · {{ $game->available_stock }} dispo
                    </option>
                @endforeach
            </select>
        </label>

        <label>
            <span>Date d'emprunt</span>
            <input type="date" name="borrowed_at" value="{{ now()->toDateString() }}" @disabled(!$canCreateBorrowing)>
        </label>

        <button class="primary-button" type="submit" @disabled(!$canCreateBorrowing)>Valider</button>
    </form>
@endsection
