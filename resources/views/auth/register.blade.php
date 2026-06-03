@extends('layouts.app')
@section('title', 'Inscription')

@section('content')
<div style="max-width:480px; margin: 0 auto;">
    <div class="card">
        <h2 style="margin-bottom:24px; color:#1a3c6e">Inscription — Professeur</h2>
        <p style="margin-bottom:20px; font-size:14px; color:#666">
            Votre compte sera activé après validation par l'administration.
        </p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom"
                       value="{{ old('nom') }}"
                       class="{{ $errors->has('nom') ? 'error' : '' }}">
                @error('nom')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="prenoms">Prenoms</label>
                <input type="text" id="prenoms" name="prenoms"
                       value="{{ old('prenoms') }}"
                       class="{{ $errors->has('prenoms') ? 'error' : '' }}">
                @error('prenoms')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

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

            <div class="form-group">
                <label for="password_confirmation">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%">
                Envoyer la demande
            </button>
        </form>

        <p style="margin-top:16px; font-size:14px; text-align:center">
            Déjà inscrit ?
            <a href="{{ route('login') }}" style="color:#1a3c6e">Se connecter</a>
        </p>
    </div>
</div>
@endsection