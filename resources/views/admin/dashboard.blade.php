@extends('layouts.app')
@section('title', 'Tableau de bord Admin')

@section('content')
<h1 style="margin-bottom:24px">Tableau de bord — Administration</h1>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px,1fr)); gap:16px">
    <div class="card" style="border-left: 4px solid #1a3c6e">
        <h3 style="font-size:14px; color:#666; margin-bottom:8px">Professeurs en attente</h3>
        <p style="font-size:32px; font-weight:700; color:#1a3c6e">
            {{ \App\Models\User::where('role','professeur')->where('statut','en_cours')->count() }}
        </p>
        <a href="{{ route('admin.users.index') }}" style="font-size:13px; color:#1a3c6e">
            Gérer →
        </a>
    </div>
    <div class="card" style="border-left: 4px solid #28a745">
        <h3 style="font-size:14px; color:#666; margin-bottom:8px">Professeurs validés</h3>
        <p style="font-size:32px; font-weight:700; color:#28a745">
            {{ \App\Models\User::where('role','professeur')->where('statut','valide')->count() }}
        </p>
    </div>

    <div class="card" style="border-left: 4px solid #fd7e14">
        <h3 style="font-size:14px; color:#666; margin-bottom:8px">Salles actives</h3>
        <p style="font-size:32px; font-weight:700; color:#fd7e14">
            {{ \App\Models\Salle::where('statut','active')->count() }}
        </p>
        <a href="{{ route('admin.salles.index') }}" style="font-size:13px; color:#fd7e14">
            Gérer →
        </a>
    </div>

    <div class="card" style="border-left: 4px solid #6f42c1">
        <h3 style="font-size:14px; color:#666; margin-bottom:8px">Réservations en attente</h3>
        <p style="font-size:32px; font-weight:700; color:#6f42c1">
            {{ \App\Models\Reservation::where('statut','en_attente')->count() }}
        </p>
        <a href="{{ route('admin.reservations.index') }}" style="font-size:13px; color:#6f42c1">
            Gérer →
        </a>
    </div>
</div>
@endsection