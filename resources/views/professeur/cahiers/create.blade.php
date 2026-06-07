@extends('layouts.app')

@section('title', 'Nouveau cahier de texte')

@section('content')
<div style="max-width:680px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:8px">
        Cahier de texte
    </h1>
    <p style="color:#666; font-size:14px; margin-bottom:24px">
        Renseignez les informations sur le cours dispensé.
    </p>

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

        {{-- Informations auto --}}
        <div style="background:#f5f7fa; border-radius:8px; padding:16px;
                    margin-bottom:24px; border-left:4px solid #1a3c6e">
            <p style="font-size:13px; font-weight:700; color:#1a3c6e;
                      margin:0 0 10px 0">Informations du cours (automatiques)</p>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px">
                <div>
                    <span style="font-size:12px; color:#888">Date</span>
                    <p style="font-size:14px; font-weight:600; color:#333; margin:2px 0 0 0">
                        {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}
                    </p>
                </div>
                <div>
                    <span style="font-size:12px; color:#888">Heure</span>
                    <p style="font-size:14px; font-weight:600; color:#333; margin:2px 0 0 0">
                        {{ \Carbon\Carbon::parse($reservation->date_debut)->format('H\hi') }}
                        → {{ \Carbon\Carbon::parse($reservation->heure_fin)->format('H\hi') }}
                    </p>
                </div>
                <div>
                    <span style="font-size:12px; color:#888">Salle</span>
                    <p style="font-size:14px; font-weight:600; color:#333; margin:2px 0 0 0">
                        {{ $reservation->salle->nom }} — {{ $reservation->salle->niveau }}
                    </p>
                </div>
                <div>
                    <span style="font-size:12px; color:#888">Matière</span>
                    <p style="font-size:14px; font-weight:600; color:#333; margin:2px 0 0 0">
                        {{ $reservation->matiere?->nom ?? '—' }}
                    </p>
                </div>
                <div>
                    <span style="font-size:12px; color:#888">Classe</span>
                    <p style="font-size:14px; font-weight:600; color:#333; margin:2px 0 0 0">
                        {{ $reservation->classe?->nom ?? '—' }}
                    </p>
                </div>
            </div>
        </div>

        <form method="POST"
              action="{{ route('professeur.cahiers.store', $reservation) }}">
            @csrf

            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">
                    Titre du module / chapitre
                </label>
                <input type="text" name="titre_module"
                       value="{{ old('titre_module') }}"
                       placeholder="Ex: Chapitre 3 — Les structures de données"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
            </div>

            <div style="margin-bottom:28px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">
                    Contenu traité
                </label>
                <textarea name="contenu" rows="8"
                          placeholder="Décrivez le contenu du cours dispensé..."
                          style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                 border-radius:6px; font-size:14px; box-sizing:border-box;
                                 resize:vertical; line-height:1.6">{{ old('contenu') }}</textarea>
            </div>

            <div style="display:flex; gap:12px">
                <button type="submit"
                        style="background:#1a3c6e; color:white; padding:10px 24px;
                               border:none; border-radius:6px; font-size:14px;
                               font-weight:600; cursor:pointer">
                    Enregistrer le cahier
                </button>
                <a href="{{ route('professeur.cahiers.index') }}"
                   style="padding:10px 24px; border:1px solid #ddd; border-radius:6px;
                          font-size:14px; color:#555; text-decoration:none; font-weight:600">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection