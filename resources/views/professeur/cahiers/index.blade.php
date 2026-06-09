@extends('layouts.app')
@section('title', 'Cahiers de texte')
@section('content')
<div style="max-width:900px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Cahiers de texte
    </h1>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

    @forelse($cahiers as $cahier)
        @php
            $monAcces = $cahier->acces->first();
        @endphp
        <div style="background:white; border-radius:10px;
                    box-shadow:0 1px 4px rgba(0,0,0,0.08);
                    padding:20px 24px; margin-bottom:12px;
                    display:flex; justify-content:space-between; align-items:center">
            <div>
                <p style="font-weight:700; color:#1a2b4a; margin:0 0 4px 0; font-size:16px">
                    {{ $cahier->classe->nom }}
                </p>
                <span style="font-size:13px; color:#888">
                    {{ $cahier->classe->filiere }} — {{ $cahier->classe->niveau }}
                    &nbsp;|&nbsp; Année {{ $cahier->annee_academique }}
                </span>
            </div>
            <div>
                @if(!$monAcces)
                    {{-- Pas encore demandé --}}
                    <form method="POST"
                          action="{{ route('professeur.cahiers.acces', $cahier) }}">
                        @csrf
                        <button type="submit"
                                style="background:#1a3c6e; color:white; padding:8px 20px;
                                       border:none; border-radius:6px; font-size:13px;
                                       font-weight:600; cursor:pointer">
                            Demander l'accès
                        </button>
                    </form>
                @elseif($monAcces->statut === 'en_attente')
                    <span style="background:#fff3cd; color:#856404; padding:6px 16px;
                                 border-radius:12px; font-size:13px; font-weight:600">
                        ⏳ En attente de validation
                    </span>
                @elseif($monAcces->statut === 'valide')
                    <a href="{{ route('professeur.cahiers.show', $cahier) }}"
                       style="background:#28a745; color:white; padding:8px 20px;
                              border-radius:6px; font-size:13px; text-decoration:none;
                              font-weight:600">
                        ✓ Accéder au cahier
                    </a>
                @else
                    <span style="background:#f8d7da; color:#721c24; padding:6px 16px;
                                 border-radius:12px; font-size:13px; font-weight:600">
                        ✕ Accès refusé
                    </span>
                @endif
            </div>
        </div>
    @empty
        <div style="background:white; border-radius:10px;
                    box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:40px; text-align:center">
            <p style="color:#666">Aucun cahier de texte disponible pour le moment.</p>
        </div>
    @endforelse
</div>
@endsection