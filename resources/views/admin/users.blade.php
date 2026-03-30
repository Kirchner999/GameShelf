@extends('layouts.app')

@section('content')
    <section class="section-head">
        <div>
            <p class="eyebrow">Administration</p>
            <h1>Utilisateurs</h1>
        </div>
    </section>

    <section class="list-stack">
        @foreach($users as $user)
            <article class="list-card">
                <div>
                    <h3>{{ $user->pseudo }}</h3>
                    <p>{{ $user->email }}</p>
                </div>
                <form class="inline-actions" method="POST" action="{{ route('admin.users.role', $user) }}">
                    @csrf
                    @method('PATCH')
                    <select name="role">
                        <option value="user" @selected($user->role === 'user')>user</option>
                        <option value="admin" @selected($user->role === 'admin')>admin</option>
                    </select>
                    <button class="ghost-button" type="submit">Mettre à jour</button>
                </form>
            </article>
        @endforeach
    </section>
@endsection