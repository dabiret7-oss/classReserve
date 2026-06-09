@extends('layouts.app')
@section('title', 'Cahier de texte')
@section('content')
<div style="max-width:900px; margin:40px auto; padding:0 24px">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <div>
            <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin:0 0 4px 0">
                {{ $cahier->classe->nom }}
            </h1>
            <p style="color:#888; font-size:14px; margin:0">
                {{ $cahier->classe->filiere }} — Année {{ $cahier->annee_academique }}
            </p>
        </div>
        <a href="{{ route('professeur.cahiers.index') }}"
           style="font-size:13px; color:#1a3c6e; text-decoration:none">
            ← Retour
        </a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulaire ajouter une séance --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px; margin-bottom:24px">

        <h2 style="font-size:16px; color:#1a3c6e; margin:0 0 16px 0; font-weight:700">
            + Ajouter une séance
        </h2>

        <form method="POST" action="{{ route('professeur.cahiers.seances.store', $cahier) }}">
            @csrf

            @if($errors->any())
                <div style="background:#f8d7da; color:#721c24; padding:12px 16px;
                            border-radius:6px; margin-bottom:16px; font-size:14px">
                    <ul style="margin:0; padding-left:16px">
                        @foreach($errors->all() as $erreur)
                            <li>{{ $erreur }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600;
                                  color:#444; margin-bottom:6px">Matière</label>
                    <select name="matiere_id"
                            style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                   border-radius:6px; font-size:14px; background:white">
                        <option value="">-- Choisir --</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}"
                                {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                {{ $matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600;
                                  color:#444; margin-bottom:6px">Date</label>
                    <input type="date" name="date_seance" value="{{ old('date_seance') }}"
                           style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                  border-radius:6px; font-size:14px; box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600;
                                  color:#444; margin-bottom:6px">Heure début</label>
                    <input type="time" name="heure_debut" value="{{ old('heure_debut') }}"
                           style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                  border-radius:6px; font-size:14px; box-sizing:border-box">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600;
                                  color:#444; margin-bottom:6px">Heure fin</label>
                    <input type="time" name="heure_fin" value="{{ old('heure_fin') }}"
                           style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                  border-radius:6px; font-size:14px; box-sizing:border-box">
                </div>
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Titre du module</label>
                <input type="text" name="titre_module" value="{{ old('titre_module') }}"
                       placeholder="Ex: Chapitre 3 — Les structures de données"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Contenu traité</label>
                <textarea name="contenu" rows="5"
                          placeholder="Décrivez le contenu du cours dispensé..."
                          style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                 border-radius:6px; font-size:14px; box-sizing:border-box;
                                 resize:vertical">{{ old('contenu') }}</textarea>
            </div>

            <button type="submit"
                    style="background:#1a3c6e; color:white; padding:10px 24px;
                           border:none; border-radius:6px; font-size:14px;
                           font-weight:600; cursor:pointer">
                Enregistrer la séance
            </button>
        </form>
    </div>

    {{-- Liste des séances --}}
    @forelse($seances as $nomMatiere => $liste)
        <div style="margin-bottom:24px">
            <div style="background:#cc0000; color:white; padding:10px 20px;
                        border-radius:10px 10px 0 0; font-weight:700; font-size:15px">
                📚 {{ $nomMatiere }}
            </div>
            <div style="background:white; border-radius:0 0 10px 10px;
                        box-shadow:0 1px 4px rgba(0,0,0,0.08)">
                @foreach($liste as $seance)
                    <div style="padding:16px 20px; border-bottom:1px solid #f5f5f5">
                        <p style="font-weight:700; color:#1a2b4a; margin:0 0 6px 0">
                            {{ $seance->titre_module }}
                        </p>
                        <p style="color:#888; font-size:12px; margin:0 0 8px 0">
                            📅 {{ \Carbon\Carbon::parse($seance->date_seance)->format('d/m/Y') }}
                            &nbsp;|&nbsp;
                            🕐 {{ \Carbon\Carbon::parse($seance->heure_debut)->format('H\hi') }}
                            → {{ \Carbon\Carbon::parse($seance->heure_fin)->format('H\hi') }}
                        </p>
                        <p style="color:#555; font-size:14px; margin:0;
                                  line-height:1.6; white-space:pre-line">
                            {{ $seance->contenu }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div style="background:white; border-radius:10px;
                    box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:32px; text-align:center">
            <p style="color:#666">Aucune séance renseignée. Ajoutez votre première séance ci-dessus.</p>
        </div>
    @endforelse
</div>
@endsection