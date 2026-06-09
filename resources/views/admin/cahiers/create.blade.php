@extends('layouts.app')
@section('title', 'Nouveau cahier de texte')
@section('content')
<div style="max-width:560px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Créer un cahier de texte
    </h1>

    @if($errors->any())
        <div style="background:#f8d7da; color:#721c24; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            <ul style="margin:0; padding-left:16px">
                @foreach($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:32px">
        <form method="POST" action="{{ route('admin.cahiers.store') }}">
            @csrf

            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Classe</label>
                <select name="classe_id"
                        style="width:100%; padding:10px 14px; border:1px solid #ddd;
                               border-radius:6px; font-size:14px; box-sizing:border-box; background:white">
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}"
                            {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }} — {{ $classe->filiere }} ({{ $classe->niveau }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:28px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Année académique</label>
                <select name="annee_academique"
                        style="width:100%; padding:10px 14px; border:1px solid #ddd;
                               border-radius:6px; font-size:14px; box-sizing:border-box; background:white">
                    <option value="">-- Choisir une année --</option>
                    @php
                        $anneeActuelle = date('Y');
                        $annees = [];
                        for ($i = 0; $i <= 2; $i++) {
                            $annees[] = ($anneeActuelle + $i) . '-' . ($anneeActuelle + $i + 1);
                        }
                    @endphp
                    @foreach($annees as $annee)
                        <option value="{{ $annee }}"
                            {{ old('annee_academique') === $annee ? 'selected' : '' }}>
                            {{ $annee }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:12px">
                <button type="submit"
                        style="background:#1a3c6e; color:white; padding:10px 24px;
                               border:none; border-radius:6px; font-size:14px;
                               font-weight:600; cursor:pointer">
                    Créer le cahier
                </button>
                <a href="{{ route('admin.cahiers.index') }}"
                   style="padding:10px 24px; border:1px solid #ddd; border-radius:6px;
                          font-size:14px; color:#555; text-decoration:none; font-weight:600">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection