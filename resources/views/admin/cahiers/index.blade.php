@extends('layouts.app')

@section('title', 'Cahiers de texte')

@section('content')
<div style="max-width:960px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Cahiers de texte
    </h1>

    @if($cahiers->isEmpty())
        <div style="background:white; border-radius:10px;
                    box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:40px; text-align:center">
            <p style="color:#666">Aucun cahier de texte enregistré.</p>
        </div>
    @else
        @foreach($cahiers as $nomClasse => $entries)
            <div style="margin-bottom:32px">

                <div style="background:#1a3c6e; color:white; padding:12px 20px;
                            border-radius:10px 10px 0 0; font-weight:700; font-size:16px">
                    👥 {{ $nomClasse }}
                    <span style="font-size:13px; font-weight:400; margin-left:8px; opacity:0.8">
                        {{ $entries->count() }} séance(s)
                    </span>
                </div>

                @foreach($entries->groupBy(fn($c) => $c->matiere?->nom ?? 'Sans matière') as $nomMatiere => $cours)
                    <div style="background:white; border-left:4px solid #cc0000;
                                box-shadow:0 1px 4px rgba(0,0,0,0.06); margin-bottom:2px">

                        <div style="padding:10px 20px; background:#f8f9fa;
                                    font-weight:600; font-size:14px; color:#cc0000;
                                    border-bottom:1px solid #f0f0f0">
                            📚 {{ $nomMatiere }}
                        </div>

                        @foreach($cours as $cahier)
                            <div style="padding:16px 20px; border-bottom:1px solid #f5f5f5">
                                <div style="display:flex; justify-content:space-between">
                                    <div style="flex:1">
                                        <p style="font-weight:700; color:#1a2b4a;
                                                  margin:0 0 4px 0; font-size:15px">
                                            {{ $cahier->titre_module }}
                                        </p>
                                        <p style="color:#888; font-size:12px; margin:0 0 8px 0">
                                            👤 {{ $cahier->user->nom }} {{ $cahier->user->prenoms }}
                                            &nbsp;|&nbsp;
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
</div>
@endsection