@extends('layouts.app')
@section('title', 'Connexion')

@section('content')
<div style="max-width:420px; margin: 0 auto;">
    <div class="card">
        <h2 style="margin-bottom:24px; color:#1a3c6e">Connexion</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="{{ $errors->has('email') ? 'error' : '' }}">
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password"
                       class="{{ $errors->has('password') ? 'error' : '' }}">
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="display:flex; align-items:center; gap:8px">
                <input type="checkbox" id="remember" name="remember" style="width:auto">
                <label for="remember" style="margin:0; font-weight:normal">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%">
                Se connecter
            </button>
        </form>

        <p style="margin-top:16px; font-size:14px; text-align:center">
            Pas encore de compte ?
            <a href="{{ route('register') }}" style="color:#1a3c6e">S'inscrire</a>
        </p>
    </div>
</div>
@endsection