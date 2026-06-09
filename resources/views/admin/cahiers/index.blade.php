@extends('layouts.app')
@section('title', 'Cahiers de texte')
@section('content')
<div style="max-width:960px; margin:40px auto; padding:0 24px">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin:0">
            Cahiers de texte
        </h1>
        <div style="display:flex; gap:12px">
            <a href="{{ route('admin.cahiers.acces') }}"
               style="background:#fd7e14; color:white; padding:10px 20px;
                      border-radius:6px; text-decoration:none; font-size:14px; font-weight:600">
                Demandes d'accès
            </a>
            <a href="{{ route('admin.cahiers.create') }}"
               style="background:#1a3c6e; color:white; padding:10px 20px;
                      border-radius:6px; text-decoration:none; font-size:14px; font-weight:600">
                + Nouveau cahier
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

    @forelse($cahiers as $annee => $listeCahiers)
        <div style="margin-bottom:32px">
            <div style="background:#1a3c6e; color:white; padding:12px 20px;
                        border-radius:10px 10px 0 0; font-weight:700; font-size:16px">
                📅 Année académique {{ $annee }}
            </div>
            <div style="background:white; border-radius:0 0 10px 10px;
                        box-shadow:0 1px 4px rgba(0,0,0,0.08)">
                @foreach($listeCahiers as $cahier)
                    <div style="display:flex; justify-content:space-between; align-items:center;
                                padding:16px 20px; border-bottom:1px solid #f0f0f0">
                        <div>
                            <p style="font-weight:700; color:#1a2b4a; margin:0 0 4px 0">
                                {{ $cahier->classe->nom }}
                            </p>
                            <span style="font-size:12px; color:#888">
                                {{ $cahier->classe->filiere }} — {{ $cahier->classe->niveau }}
                                &nbsp;|&nbsp;
                                {{ $cahier->seances->count() }} séance(s)
                                &nbsp;|&nbsp;
                                {{ $cahier->acces->where('statut','valide')->count() }} prof(s) autorisé(s)
                            </span>
                        </div>
                        <div style="display:flex; align-items:center; gap:12px">
                            @if($cahier->statut === 'actif')
                                <span style="background:#d4edda; color:#155724; padding:4px 12px;
                                             border-radius:12px; font-size:12px; font-weight:600">
                                    Actif
                                </span>
                            @else
                                <span style="background:#f8d7da; color:#721c24; padding:4px 12px;
                                             border-radius:12px; font-size:12px; font-weight:600">
                                    Inactif
                                </span>
                            @endif
                            <a href="{{ route('admin.cahiers.show', $cahier) }}"
                               style="background:#1a3c6e; color:white; padding:6px 16px;
                                      border-radius:6px; font-size:13px; text-decoration:none;
                                      font-weight:600">
                                Voir →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div style="background:white; border-radius:10px;
                    box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:40px; text-align:center">
            <p style="color:#666">Aucun cahier de texte créé.</p>
        </div>
    @endforelse
</div>
@endsection