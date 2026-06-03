@extends('layouts.app')

@section('content')
<div style="max-width:960px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Gestion des réservations
    </h1>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

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
            <table style="width:100%; border-collapse:collapse; font-size:14px">
                <thead>
                    <tr style="background:#f5f7fa">
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Professeur</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Salle</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Début</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Fin</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Motif</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enAttente as $reservation)
                        <tr style="border-bottom:1px solid #f0f0f0">
                            <td style="padding:14px 16px; font-weight:600; color:#1a2b4a">
                                {{ $reservation->user->nom }}
                                <span style="display:block; font-weight:400;
                                             font-size:12px; color:#888">
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
                                </span>
                            </td>
                            <td style="padding:14px 16px; color:#555">
                                {{ \Carbon\Carbon::parse($reservation->heure_fin)->format('H\hi') }}
                            </td>
                            <td style="padding:14px 16px; color:#555">
                                {{ $reservation->motif }}
                            </td>
                            <td style="padding:14px 16px">
                                <div style="display:flex; gap:8px">
                                    <form method="POST"
                                          action="{{ route('admin.reservations.valider', $reservation) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                style="background:#28a745; color:white; padding:6px 14px;
                                                       border:none; border-radius:6px; font-size:13px;
                                                       font-weight:600; cursor:pointer">
                                            Valider
                                        </button>
                                    </form>
                                    <form method="POST"
                                          action="{{ route('admin.reservations.rejeter', $reservation) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                style="background:#dc3545; color:white; padding:6px 14px;
                                                       border:none; border-radius:6px; font-size:13px;
                                                       font-weight:600; cursor:pointer">
                                            Rejeter
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                                   font-weight:600; border-bottom:2px solid #e9ecef">Salle</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Début</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Fin</th>
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
                                </span>
                            </td>
                            <td style="padding:14px 16px; color:#555">
                                {{ \Carbon\Carbon::parse($reservation->heure_fin)->format('H\hi') }}
                            </td>
                            <td style="padding:14px 16px; color:#555">
                                {{ $reservation->motif }}
                            </td>
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

            <div style="margin-top:16px">
                {{ $traitees->links() }}
            </div>
        @endif
    </div>
</div>
@endsection