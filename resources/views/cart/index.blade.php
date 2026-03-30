@extends('layouts.app')

@section('content')
    <section class="section-head">
        <div>
            <p class="eyebrow">Panier</p>
            <h1>Vos jeux en attente</h1>
        </div>
    </section>

    @if($items === [])
        <p class="empty-state">Le panier est vide.</p>
    @else
        <section class="list-stack">
            @foreach($items as $item)
                <article class="list-card">
                    <div>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['platform'] }} &middot; {{ $item['condition'] }} &middot; {{ $item['available_stock'] }} dispo</p>
                    </div>
                    <form class="inline-actions" method="POST" action="{{ route('cart.update', $item['id']) }}">
                        @csrf
                        @method('PATCH')
                        <input type="number" min="0" max="99" name="quantity" value="{{ $item['quantity'] }}">
                        <button class="ghost-button" type="submit">Mettre à jour</button>
                    </form>
                    <div class="price-block">
                        <strong>{{ number_format($item['subtotal'], 2, ',', ' ') }} &euro;</strong>
                        <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                            @csrf
                            @method('DELETE')
                            <button class="danger-button" type="submit">Retirer</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="checkout-bar">
            <div>
                <span>Total</span>
                <strong>{{ number_format($cartTotal, 2, ',', ' ') }} &euro;</strong>
            </div>
            @auth
                <form method="POST" action="{{ route('cart.checkout') }}">
                    @csrf
                    <button class="primary-button" type="submit">Valider la commande</button>
                </form>
            @else
                <a class="primary-button" href="{{ route('login') }}">Se connecter pour commander</a>
            @endauth
        </section>
    @endif
@endsection