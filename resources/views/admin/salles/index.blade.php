@extends('layouts.app')

@section('content')
<div style="max-width:900px; margin:40px auto; padding:0 24px">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin:0">
            Gestion des salles
        </h1>
        <a href="{{ route('admin.salles.create') }}"
           style="background:#1a3c6e; color:white; padding:10px 20px;
                  border-radius:6px; text-decoration:none; font-size:14px; font-weight:600">
            + Ajouter une salle
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
            Salles enregistrées
            <span style="background:#fd7e14; color:white; font-size:12px;
                         padding:2px 10px; border-radius:12px; margin-left:8px">
                {{ $salles->count() }}
            </span>
        </h2>

        @if($salles->isEmpty())
            <p style="color:#666; font-size:14px">Aucune salle enregistrée.</p>
        @else
            <table style="width:100%; border-collapse:collapse; font-size:14px">
                <thead>
                    <tr style="background:#f5f7fa">
                        <th style="text-align:left; padding:12px 16px; color:#444; font-weight:600; border-bottom:2px solid #e9ecef">Nom</th>
                        <th style="text-align:left; padding:12px 16px; color:#444; font-weight:600; border-bottom:2px solid #e9ecef">Niveau</th>
                        <th style="text-align:left; padding:12px 16px; color:#444; font-weight:600; border-bottom:2px solid #e9ecef">Statut</th>
                        <th style="text-align:left; padding:12px 16px; color:#444; font-weight:600; border-bottom:2px solid #e9ecef">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salles as $salle)
                        <tr style="border-bottom:1px solid #f0f0f0">
                            <td style="padding:14px 16px; font-weight:600; color:#1a2b4a">
                                {{ $salle->nom }}
                            </td>
                            <td style="padding:14px 16px; color:#555">
                                {{ $salle->niveau }}
                            </td>
                            <td style="padding:14px 16px">
                                @if($salle->isActive())
                                    <span style="background:#d4edda; color:#155724; padding:4px 12px;
                                                 border-radius:12px; font-size:12px; font-weight:600">
                                        Active
                                    </span>
                                @else
                                    <span style="background:#f8d7da; color:#721c24; padding:4px 12px;
                                                 border-radius:12px; font-size:12px; font-weight:600">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td style="padding:14px 16px">
                                <form method="POST" action="{{ route('admin.salles.toggle', $salle) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        style="padding:6px 16px; border:none; border-radius:6px;
                                               font-size:13px; font-weight:600; cursor:pointer;
                                               {{ $salle->isActive()
                                                   ? 'background:#dc3545; color:white;'
                                                   : 'background:#28a745; color:white;' }}">
                                        {{ $salle->isActive() ? 'Désactiver' : 'Activer' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:20px 16px; color:#666; text-align:center">
                                Aucune salle enregistrée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection