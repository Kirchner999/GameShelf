@extends('layouts.app')

@section('content')
    <form class="form-card compact auth-card" method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <p class="eyebrow">Connexion</p>
            <h1>Acceder a votre espace</h1>
        </div>

        <label>
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label>

        <label>
            <span>Mot de passe</span>
            <input type="password" name="password" required>
        </label>

        <label class="checkbox-row">
            <input type="checkbox" name="remember" value="1">
            <span>Se souvenir de moi</span>
        </label>

        <button class="primary-button" type="submit">Connexion</button>
    </form>
@endsection