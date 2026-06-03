@extends('layouts.app')

@section('content')
<div style="max-width:900px; margin:40px auto; padding:0 24px">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin:0">
            Mes réservations
        </h1>
        <a href="{{ route('professeur.reservations.create') }}"
           style="background:#1a3c6e; color:white; padding:10px 20px;
                  border-radius:6px; text-decoration:none; font-size:14px; font-weight:600">
            + Nouvelle réservation
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

        @forelse($reservations as $reservation)
            <div style="border:1px solid #f0f0f0; border-radius:8px;
                        padding:16px; margin-bottom:12px">
                <div style="display:flex; justify-content:space-between; align-items:start">
                    <div>
                        <p style="font-weight:700; color:#1a2b4a; margin:0 0 6px 0; font-size:16px">
                            {{ $reservation->motif }}
                        </p>
                        <p style="color:#555; font-size:14px; margin:0 0 4px 0">
                            📍 {{ $reservation->salle->nom }} — {{ $reservation->salle->niveau }}
                        </p>
                        <p style="color:#555; font-size:14px; margin:0">
                            🕐 {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y à H\hi') }}
                            → {{ \Carbon\Carbon::parse($reservation->heure_fin)->format('H\hi') }}
                        </p>
                    </div>
                    <div>
                        @if($reservation->statut === 'en_attente')
                            <span style="background:#fff3cd; color:#856404; padding:4px 12px;
                                         border-radius:12px; font-size:12px; font-weight:600">
                                En attente
                            </span>
                        @elseif($reservation->statut === 'validee')
                            <span style="background:#d4edda; color:#155724; padding:4px 12px;
                                         border-radius:12px; font-size:12px; font-weight:600">
                                Validée ✓
                            </span>
                        @else
                            <span style="background:#f8d7da; color:#721c24; padding:4px 12px;
                                         border-radius:12px; font-size:12px; font-weight:600">
                                Rejetée
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p style="color:#666; text-align:center; padding:40px 0">
                Vous n'avez aucune réservation pour le moment.
            </p>
        @endforelse

        <div style="margin-top:16px">
            {{ $reservations->links() }}
        </div>
    </div>
</div>
@endsection