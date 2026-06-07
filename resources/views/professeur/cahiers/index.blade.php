@extends('layouts.app')

@section('title', 'Mes cahiers de texte')

@section('content')
<div style="max-width:900px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Mes cahiers de texte
    </h1>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

    @if($cahiers->isEmpty())
        <div style="background:white; border-radius:10px;
                    box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:40px; text-align:center">
            <p style="color:#666; font-size:15px">
                Aucun cahier de texte pour le moment.
            </p>
            <p style="color:#888; font-size:13px; margin-top:8px">
                Vous pouvez renseigner un cahier depuis vos réservations validées.
            </p>
        </div>
    @else
        @foreach($cahiers as $nomClasse => $entries)
            <div style="margin-bottom:32px">

                {{-- En-tête classe --}}
                <div style="background:#1a3c6e; color:white; padding:12px 20px;
                            border-radius:10px 10px 0 0; font-weight:700; font-size:16px">
                    👥 {{ $nomClasse }}
                    <span style="font-size:13px; font-weight:400; margin-left:8px; opacity:0.8">
                        {{ $entries->count() }} séance(s)
                    </span>
                </div>

                {{-- Grouper par matière --}}
                @foreach($entries->groupBy(fn($c) => $c->matiere?->nom ?? 'Sans matière') as $nomMatiere => $cours)
                    <div style="background:white; border-left:4px solid #cc0000;
                                box-shadow:0 1px 4px rgba(0,0,0,0.06); margin-bottom:2px">

                        {{-- Sous-titre matière --}}
                        <div style="padding:10px 20px; background:#f8f9fa;
                                    font-weight:600; font-size:14px; color:#cc0000;
                                    border-bottom:1px solid #f0f0f0">
                            📚 {{ $nomMatiere }}
                        </div>

                        @foreach($cours as $cahier)
                            <div style="padding:16px 20px; border-bottom:1px solid #f5f5f5">
                                <div style="display:flex; justify-content:space-between; align-items:start">
                                    <div style="flex:1">
                                        <p style="font-weight:700; color:#1a2b4a;
                                                  margin:0 0 6px 0; font-size:15px">
                                            {{ $cahier->titre_module }}
                                        </p>
                                        <p style="color:#888; font-size:12px; margin:0 0 10px 0">
                                            📅 {{ \Carbon\Carbon::parse($cahier->reservation->date_debut)->format('d/m/Y') }}
                                            &nbsp;|&nbsp;
                                            🕐 {{ \Carbon\Carbon::parse($cahier->reservation->date_debut)->format('H\hi') }}
                                            → {{ \Carbon\Carbon::parse($cahier->reservation->heure_fin)->format('H\hi') }}
                                            &nbsp;|&nbsp;
                                            📍 {{ $cahier->reservation->salle->nom }}
                                        </p>
                                        <p style="color:#555; font-size:14px; margin:0;
                                                  line-height:1.6; white-space:pre-line">
                                            {{ $cahier->contenu }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <div style="border-radius:0 0 10px 10px; background:white;
                            box-shadow:0 1px 4px rgba(0,0,0,0.06); height:4px"></div>
            </div>
        @endforeach
    @endif

    {{-- Lien vers réservations pour renseigner un cahier --}}
    <div style="text-align:center; margin-top:8px">
        <a href="{{ route('professeur.reservations.index') }}"
           style="font-size:13px; color:#1a3c6e">
            ← Retour à mes réservations
        </a>
    </div>
</div>
@endsection