@extends('layouts.app')

@section('content')
    <section class="section-head">
        <div>
            <p class="eyebrow">Administration</p>
            <h1>Tableau de bord</h1>
        </div>
        <a class="ghost-button" href="{{ route('admin.users.index') }}">Gérer les utilisateurs</a>
    </section>

    <section class="stats-grid admin-stats">
        <article class="stat-card">
            <span>Catalogue</span>
            <strong>{{ $stats['catalogue'] }}</strong>
            <p>Fiches produits actives.</p>
        </article>
        <article class="stat-card">
            <span>Location</span>
            <strong>{{ $stats['location'] }}</strong>
            <p>Titres louables.</p>
        </article>
        <article class="stat-card">
            <span>Vente</span>
            <strong>{{ $stats['vente'] }}</strong>
            <p>Titres vendables.</p>
        </article>
        <article class="stat-card">
            <span>Stock</span>
            <strong>{{ $stats['stock'] }}</strong>
            <p>Unités en base.</p>
        </article>
    </section>

    <section class="dashboard-grid admin-panels">
        <article class="panel">
            <h2>Utilisateurs</h2>
            <ul class="items-list">
                @foreach($users as $user)
                    <li>{{ $user->pseudo }} &middot; {{ $user->email }} &middot; {{ $user->role }}</li>
                @endforeach
            </ul>
        </article>

        <article class="panel">
            <h2>Dernières commandes</h2>
            <ul class="items-list">
                @foreach($orders as $order)
                    <li>#{{ $order->id }} &middot; {{ $order->user->pseudo }} &middot; {{ number_format((float) $order->total_amount, 2, ',', ' ') }} &euro;</li>
                @endforeach
            </ul>
        </article>
    </section>
@endsection