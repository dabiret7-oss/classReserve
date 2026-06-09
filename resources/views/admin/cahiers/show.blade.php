@extends('layouts.app')
@section('title', 'Cahier de texte')
@section('content')
<div style="max-width:960px; margin:40px auto; padding:0 24px">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <div>
            <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin:0 0 4px 0">
                {{ $cahier->classe->nom }}
            </h1>
            <p style="color:#888; font-size:14px; margin:0">
                {{ $cahier->classe->filiere }} — {{ $cahier->classe->niveau }}
                &nbsp;|&nbsp; Année {{ $cahier->annee_academique }}
            </p>
        </div>
        <a href="{{ route('admin.cahiers.index') }}"
           style="font-size:13px; color:#1a3c6e; text-decoration:none">
            ← Retour
        </a>
    </div>

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
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px">
                            <p style="font-weight:700; color:#1a2b4a; margin:0; font-size:15px">
                                {{ $seance->titre_module }}
                            </p>
                            <span style="font-size:12px; color:#888">
                                👤 {{ $seance->user->nom }} {{ $seance->user->prenoms }}
                            </span>
                        </div>
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
                    box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:40px; text-align:center">
            <p style="color:#666">Aucune séance renseignée dans ce cahier.</p>
        </div>
    @endforelse
</div>
@endsection