@extends('layouts.app')

@section('content')
    <section class="section-head">
        <div>
            <p class="eyebrow">Commandes</p>
            <h1>{{ $isAdmin ? 'Toutes les commandes' : 'Mes commandes' }}</h1>
        </div>
    </section>

    <section class="list-stack">
        @forelse($orders as $order)
            <article class="list-card column">
                <div class="list-head">
                    <div>
                        <h3>Commande #{{ $order->id }}</h3>
                        <p>{{ $order->user->pseudo }} &middot; {{ $order->ordered_at->format('d/m/Y') }} &middot; {{ $order->status }}</p>
                    </div>
                    <strong>{{ number_format((float) $order->total_amount, 2, ',', ' ') }} &euro;</strong>
                </div>
                <ul class="items-list order-items">
                    @foreach($order->items as $item)
                        <li>{{ $item->game->title }} &middot; x{{ $item->quantity }} &middot; {{ number_format((float) $item->unit_price, 2, ',', ' ') }} &euro;</li>
                    @endforeach
                </ul>
            </article>
        @empty
            <p class="empty-state">Aucune commande pour le moment.</p>
        @endforelse
    </section>
@endsection