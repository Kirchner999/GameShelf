@extends('layouts.app')

@section('content')
    <form class="form-card compact" method="POST" action="{{ route('register') }}">
        @csrf
        <div>
            <p class="eyebrow">Inscription</p>
            <h1>Creer un compte joueur</h1>
        </div>

        <label>
            <span>Pseudo</span>
            <input type="text" name="pseudo" value="{{ old('pseudo') }}" required>
        </label>

        <label>
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>

        <label>
            <span>Mot de passe</span>
            <input type="password" name="password" required>
        </label>

        <label>
            <span>Confirmation</span>
            <input type="password" name="password_confirmation" required>
        </label>

        <button class="primary-button" type="submit">Creer le compte</button>
    </form>
@endsection
