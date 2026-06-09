@extends('layouts.app')
@section('title', 'Demandes d\'accès')
@section('content')
<div style="max-width:900px; margin:40px auto; padding:0 24px">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin:0">
            Demandes d'accès aux cahiers
        </h1>
        <a href="{{ route('admin.cahiers.index') }}"
           style="font-size:13px; color:#1a3c6e; text-decoration:none">
            ← Retour aux cahiers
        </a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px">

        <h2 style="font-size:18px; color:#1a3c6e; margin:0 0 16px 0">
            En attente
            <span style="background:#fd7e14; color:white; font-size:12px;
                         padding:2px 10px; border-radius:12px; margin-left:8px">
                {{ $demandes->count() }}
            </span>
        </h2>

        @forelse($demandes as $demande)
            <div style="display:flex; justify-content:space-between; align-items:center;
                        padding:16px 0; border-bottom:1px solid #f0f0f0">
                <div>
                    <p style="font-weight:700; color:#1a2b4a; margin:0 0 4px 0">
                        {{ $demande->user->nom }} {{ $demande->user->prenoms }}
                    </p>
                    <span style="font-size:13px; color:#888">
                        Demande accès au cahier :
                        <strong>{{ $demande->cahier->classe->nom }}</strong>
                        — {{ $demande->cahier->annee_academique }}
                    </span>
                </div>
                <div style="display:flex; gap:8px">
                    <form method="POST"
                          action="{{ route('admin.cahiers.acces.valider', $demande) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                style="background:#28a745; color:white; padding:6px 16px;
                                       border:none; border-radius:6px; font-size:13px;
                                       font-weight:600; cursor:pointer">
                            Valider
                        </button>
                    </form>
                    <form method="POST"
                          action="{{ route('admin.cahiers.acces.rejeter', $demande) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                style="background:#dc3545; color:white; padding:6px 16px;
                                       border:none; border-radius:6px; font-size:13px;
                                       font-weight:600; cursor:pointer">
                            Rejeter
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p style="color:#666; text-align:center; padding:20px 0">
                Aucune demande en attente.
            </p>
        @endforelse
    </div>
</div>
@endsection