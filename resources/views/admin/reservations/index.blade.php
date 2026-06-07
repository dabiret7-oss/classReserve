@extends('layouts.app')

@section('content')
<div style="max-width:960px; margin:40px auto; padding:0 24px">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin:0">
            Gestion des réservations
        </h1>
        <a href="{{ route('admin.reservations.create') }}"
           style="background:#1a3c6e; color:white; padding:10px 20px;
                  border-radius:6px; text-decoration:none; font-size:14px; font-weight:600">
            + Attribuer une salle
        </a>
    </div>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filtre par salle --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08);
                padding:16px 24px; margin-bottom:24px">
        <form method="GET" action="{{ route('admin.reservations.index') }}"
              style="display:flex; align-items:center; gap:16px; flex-wrap:wrap">
            <label style="font-size:13px; font-weight:600; color:#444">
                Filtrer par salle
            </label>
            <select name="salle_id"
                    style="padding:8px 14px; border:1px solid #ddd; border-radius:6px;
                           font-size:14px; background:white; min-width:200px">
                <option value="">-- Toutes les salles --</option>
                @foreach($salles as $salle)
                    <option value="{{ $salle->id }}"
                        {{ $salleId == $salle->id ? 'selected' : '' }}>
                        {{ $salle->nom }} — {{ $salle->niveau }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                    style="background:#1a3c6e; color:white; padding:8px 20px;
                           border:none; border-radius:6px; font-size:14px;
                           font-weight:600; cursor:pointer">
                Filtrer
            </button>
            @if($salleId)
                <a href="{{ route('admin.reservations.index') }}"
                   style="font-size:13px; color:#dc3545; text-decoration:none">
                    ✕ Effacer le filtre
                </a>
            @endif
        </form>
    </div>

    {{-- Réservations en attente --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px; margin-bottom:32px">

        <h2 style="font-size:18px; color:#1a3c6e; margin:0 0 16px 0">
            Demandes en attente
            <span style="background:#fd7e14; color:white; font-size:12px;
                         padding:2px 10px; border-radius:12px; margin-left:8px">
                {{ $enAttente->count() }}
            </span>
        </h2>

        @if($enAttente->isEmpty())
            <p style="color:#666; font-size:14px">Aucune demande en attente.</p>
        @else
            @foreach($enAttente as $reservation)
            <div style="border:1px solid #f0f0f0; border-radius:8px; padding:16px; margin-bottom:12px">
                <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:12px">
                    <div>
                        <p style="font-weight:700; color:#1a2b4a; margin:0 0 4px 0">
                            {{ $reservation->motif }}
                        </p>
                        <p style="color:#555; font-size:13px; margin:0 0 2px 0">
                            👤 {{ $reservation->user->nom }} {{ $reservation->user->prenoms }}
                        </p>
                        <p style="color:#555; font-size:13px; margin:0 0 2px 0">
                            📍 Demande : {{ $reservation->salle->nom }} — {{ $reservation->salle->niveau }}
                        </p>
                        <p style="color:#555; font-size:13px; margin:0">
                            🕐 {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y à H\hi') }}
                            → {{ \Carbon\Carbon::parse($reservation->heure_fin)->format('H\hi') }}
                        </p>
                    </div>

                    {{-- Formulaire valider avec choix de salle --}}
                    <form method="POST"
                          action="{{ route('admin.reservations.valider', $reservation) }}"
                          style="display:flex; flex-direction:column; gap:8px; min-width:220px">
                        @csrf
                        @method('PATCH')

                        <label style="font-size:12px; font-weight:600; color:#444">
                            Salle à attribuer
                        </label>
                        <select name="salle_id"
                                style="padding:7px 12px; border:1px solid #ddd;
                                       border-radius:6px; font-size:13px; background:white">
                            @foreach($salles as $salle)
                                <option value="{{ $salle->id }}"
                                    {{ $salle->id == $reservation->salle_id ? 'selected' : '' }}>
                                    {{ $salle->nom }} — {{ $salle->niveau }}
                                </option>
                            @endforeach
                        </select>

                        <div style="display:flex; gap:8px">
                            <button type="submit"
                                    style="flex:1; background:#28a745; color:white; padding:7px 0;
                                           border:none; border-radius:6px; font-size:13px;
                                           font-weight:600; cursor:pointer">
                                ✓ Valider
                            </button>
                        </div>
                    </form>

                    {{-- Bouton rejeter --}}
                    <form method="POST"
                          action="{{ route('admin.reservations.rejeter', $reservation) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                style="background:#dc3545; color:white; padding:7px 16px;
                                       border:none; border-radius:6px; font-size:13px;
                                       font-weight:600; cursor:pointer; margin-top:20px">
                            ✕ Rejeter
                        </button>
                    </form>

                </div>
            </div>
            @endforeach
        @endif
    </div>

    {{-- Réservations traitées --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px">

        <h2 style="font-size:18px; color:#1a3c6e; margin:0 0 16px 0">
            Réservations traitées
        </h2>

        @if($traitees->isEmpty())
            <p style="color:#666; font-size:14px">Aucune réservation traitée.</p>
        @else
            <table style="width:100%; border-collapse:collapse; font-size:14px">
                <thead>
                    <tr style="background:#f5f7fa">
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Professeur</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Salle attribuée</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Date</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Motif</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($traitees as $reservation)
                        <tr style="border-bottom:1px solid #f0f0f0">
                            <td style="padding:14px 16px; font-weight:600; color:#1a2b4a">
                                {{ $reservation->user->nom }}
                                <span style="display:block; font-weight:400; font-size:12px; color:#888">
                                    {{ $reservation->user->email }}
                                </span>
                            </td>
                            <td style="padding:14px 16px; color:#555">
                                {{ $reservation->salle->nom }}
                                <span style="display:block; font-size:12px; color:#888">
                                    {{ $reservation->salle->niveau }}
                                </span>
                            </td>
                            <td style="padding:14px 16px; color:#555">
                                {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}
                                <span style="display:block; font-size:12px; color:#888">
                                    {{ \Carbon\Carbon::parse($reservation->date_debut)->format('H\hi') }}
                                    → {{ \Carbon\Carbon::parse($reservation->heure_fin)->format('H\hi') }}
                                </span>
                            </td>
                            <td style="padding:14px 16px; color:#555">{{ $reservation->motif }}</td>
                            <td style="padding:14px 16px">
                                @if($reservation->statut === 'validee')
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px">{{ $traitees->links() }}</div>
        @endif
    </div>
</div>
@endsection