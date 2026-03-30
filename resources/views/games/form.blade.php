@extends('layouts.app')

@section('content')
    <section class="section-head">
        <div>
            <p class="eyebrow">Admin</p>
            <h1>{{ $game->exists ? 'Modifier un jeu' : 'Ajouter un jeu' }}</h1>
        </div>
    </section>

    <form class="form-card" method="POST" action="{{ $action }}">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <label>
            <span>Titre</span>
            <input type="text" name="title" value="{{ old('title', $game->title) }}" required>
        </label>

        <label>
            <span>Plateforme</span>
            <input type="text" name="platform" value="{{ old('platform', $game->platform) }}" required>
        </label>

        <label>
            <span>Genre</span>
            <input type="text" name="genre" value="{{ old('genre', $game->genre) }}" required>
        </label>

        <label>
            <span>Type d'offre</span>
            <select name="offer_type">
                @foreach(['location' => 'Location', 'vente' => 'Vente', 'location_vente' => 'Location + vente'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('offer_type', $game->offer_type ?: 'location') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>

        <label>
            <span>Etat</span>
            <select name="condition">
                @foreach(['neuf' => 'Neuf', 'occasion' => 'Occasion', 'collector' => 'Collector'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('condition', $game->condition ?: 'neuf') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>

        <label>
            <span>Prix</span>
            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $game->price ?: '0.00') }}" required>
        </label>

        <label>
            <span>Stock total</span>
            <input type="number" min="0" name="stock_total" value="{{ old('stock_total', $game->stock_total ?? 1) }}" required>
        </label>

        <button class="primary-button" type="submit">Enregistrer</button>
    </form>
@endsection
