@extends('layouts.app')
@section('title', 'Tableau de bord Professeur')

@section('content')
<div style="max-width:900px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:8px">
        Bonjour, {{ auth()->user()->nom }} 👋
    </h1>
    <p style="color:#666; margin-bottom:32px; font-size:15px">
        Bienvenue sur votre espace de réservation de salles.
    </p>

    {{-- Cartes d'actions rapides --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px,1fr)); gap:16px; margin-bottom:32px">

        <div class="card" style="border-left:4px solid #1a3c6e">
            <h3 style="font-size:14px; color:#666; margin-bottom:8px">Mes réservations</h3>
            <p style="font-size:32px; font-weight:700; color:#1a3c6e">
                {{ auth()->user()->reservations()->count() }}
            </p>
            <a href="{{ route('professeur.reservations.index') }}"
               style="font-size:13px; color:#1a3c6e">
                Voir tout →
            </a>
        </div>

        <div class="card" style="border-left:4px solid #28a745">
            <h3 style="font-size:14px; color:#666; margin-bottom:8px">Réservations validées</h3>
            <p style="font-size:32px; font-weight:700; color:#28a745">
                {{ auth()->user()->reservations()->where('statut','validee')->count() }}
            </p>
        </div>

        <div class="card" style="border-left:4px solid #fd7e14">
            <h3 style="font-size:14px; color:#666; margin-bottom:8px">En attente</h3>
            <p style="font-size:32px; font-weight:700; color:#fd7e14">
                {{ auth()->user()->reservations()->where('statut','en_attente')->count() }}
            </p>
        </div>

    </div>

    {{-- Bouton nouvelle réservation --}}
    <div class="card" style="text-align:center; padding:40px">
        <h2 style="font-size:20px; color:#1a2b4a; margin-bottom:12px">
            Réserver une salle
        </h2>
        <p style="color:#666; font-size:14px; margin-bottom:24px">
            Consultez les salles disponibles et soumettez votre demande de réservation.
        </p>
        <a href="{{ route('professeur.reservations.create') }}"
           style="background:#1a3c6e; color:white; padding:12px 32px;
                  border-radius:6px; text-decoration:none; font-size:15px; font-weight:600">
            + Nouvelle réservation
        </a>
    </div>

    {{-- Dernières réservations --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px; margin-top:24px">

        <h2 style="font-size:18px; color:#1a3c6e; margin:0 0 16px 0">
            Mes dernières réservations
        </h2>

        @php
            $dernieres = auth()->user()->reservations()
                ->with('salle')
                ->latest()
                ->take(5)
                ->get();
        @endphp

        @forelse($dernieres as $reservation)
            <div style="border-bottom:1px solid #f0f0f0; padding:14px 0;
                        display:flex; justify-content:space-between; align-items:center">
                <div>
                    <p style="font-weight:600; color:#1a2b4a; margin:0 0 4px 0">
                        {{ $reservation->motif }}
                    </p>
                    <p style="color:#888; font-size:13px; margin:0">
                        📍 {{ $reservation->salle->nom }} — {{ $reservation->salle->niveau }}
                        &nbsp;|&nbsp;
                        🕐 {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y à H\hi') }}
                    </p>
                </div>
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
        @empty
            <p style="color:#666; font-size:14px; text-align:center; padding:20px 0">
                Vous n'avez aucune réservation pour le moment.
            </p>
        @endforelse

        @if($dernieres->count() > 0)
            <div style="text-align:right; margin-top:12px">
                <a href="{{ route('professeur.reservations.index') }}"
                   style="font-size:13px; color:#1a3c6e">
                    Voir toutes mes réservations →
                </a>
            </div>
        @endif
    </div>

</div>
@endsection