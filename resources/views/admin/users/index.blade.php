@extends('layouts.app')
@section('title', 'Gestion des professeurs')
@section('content')
<div style="max-width:960px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Gestion des professeurs
    </h1>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

    {{-- Demandes en attente --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px; margin-bottom:32px">

        <h2 style="font-size:18px; color:#1a3c6e; margin:0 0 16px 0">
            Demandes en attente
            <span style="background:#fd7e14; color:white; font-size:12px;
                         padding:2px 10px; border-radius:12px; margin-left:8px">
                {{ $pendingUsers->count() }}
            </span>
        </h2>

        @if($pendingUsers->isEmpty())
            <p style="color:#666; font-size:14px">Aucune demande en attente.</p>
        @else
            <table style="width:100%; border-collapse:collapse; font-size:14px">
                <thead>
                    <tr style="background:#f5f7fa">
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Nom</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">E-mail</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Date d'inscription</th>
                        <th style="text-align:left; padding:12px 16px; color:#444;
                                   font-weight:600; border-bottom:2px solid #e9ecef">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                    <tr style="border-bottom:1px solid #f0f0f0">
                        <td style="padding:14px 16px; font-weight:600; color:#1a2b4a">
                            {{ $user->nom }} {{ $user->prenoms }}
                        </td>
                        <td style="padding:14px 16px; color:#555">{{ $user->email }}</td>
                        <td style="padding:14px 16px; color:#555">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td style="padding:14px 16px">
                            <div style="display:flex; gap:8px">
                                <form method="POST"
                                      action="{{ route('admin.users.validate', $user) }}">
                                    @csrf @method('PATCH')
                                    <button style="background:#28a745; color:white; padding:6px 14px;
                                                   border:none; border-radius:6px; font-size:13px;
                                                   font-weight:600; cursor:pointer">
                                        Valider
                                    </button>
                                </form>
                                <form method="POST"
                                      action="{{ route('admin.users.reject', $user) }}">
                                    @csrf @method('PATCH')
                                    <button style="background:#cc0000; color:white; padding:6px 14px;
                                                   border:none; border-radius:6px; font-size:13px;
                                                   font-weight:600; cursor:pointer">
                                        Refuser
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

    {{-- Professeurs traités --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px">

        <h2 style="font-size:18px; color:#1a3c6e; margin:0 0 16px 0">
            Professeurs traités
        </h2>

        <table style="width:100%; border-collapse:collapse; font-size:14px">
            <thead>
                <tr style="background:#f5f7fa">
                    <th style="text-align:left; padding:12px 16px; color:#444;
                               font-weight:600; border-bottom:2px solid #e9ecef">Nom</th>
                    <th style="text-align:left; padding:12px 16px; color:#444;
                               font-weight:600; border-bottom:2px solid #e9ecef">E-mail</th>
                    <th style="text-align:left; padding:12px 16px; color:#444;
                               font-weight:600; border-bottom:2px solid #e9ecef">Statut</th>
                    <th style="text-align:left; padding:12px 16px; color:#444;
                               font-weight:600; border-bottom:2px solid #e9ecef">Mis à jour le</th>
                    <th style="text-align:left; padding:12px 16px; color:#444;
                               font-weight:600; border-bottom:2px solid #e9ecef">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($toutProfesseur as $user)
                <tr style="border-bottom:1px solid #f0f0f0;
                           {{ $user->deleted_at ? 'opacity:0.5;' : '' }}">
                    <td style="padding:14px 16px; font-weight:600; color:#1a2b4a">
                        {{ $user->nom }} {{ $user->prenoms }}
                        @if($user->deleted_at)
                            <span style="font-size:11px; color:#dc3545; font-weight:400">
                                (supprimé)
                            </span>
                        @endif
                    </td>
                    <td style="padding:14px 16px; color:#555">{{ $user->email }}</td>
                    <td style="padding:14px 16px">
                        @if($user->statut === 'valide')
                            <span style="background:#d4edda; color:#155724; padding:4px 12px;
                                         border-radius:12px; font-size:12px; font-weight:600">
                                Validé
                            </span>
                        @elseif($user->statut === 'desactive')
                            <span style="background:#fff3cd; color:#856404; padding:4px 12px;
                                         border-radius:12px; font-size:12px; font-weight:600">
                                Désactivé
                            </span>
                        @else
                            <span style="background:#f8d7da; color:#721c24; padding:4px 12px;
                                         border-radius:12px; font-size:12px; font-weight:600">
                                Refusé
                            </span>
                        @endif
                    </td>
                    <td style="padding:14px 16px; color:#555">
                        {{ $user->updated_at->format('d/m/Y') }}
                    </td>
                    <td style="padding:14px 16px">
                        <div style="display:flex; gap:6px; flex-wrap:wrap">
                            @if(!$user->deleted_at)
                                {{-- Désactiver / Réactiver --}}
                                <form method="POST"
                                      action="{{ route('admin.users.toggle-desactive', $user) }}">
                                    @csrf @method('PATCH')
                                    <button style="background:{{ $user->statut === 'desactive' ? '#28a745' : '#fd7e14' }};
                                                   color:white; padding:5px 12px; border:none;
                                                   border-radius:6px; font-size:12px;
                                                   font-weight:600; cursor:pointer">
                                        {{ $user->statut === 'desactive' ? 'Réactiver' : 'Désactiver' }}
                                    </button>
                                </form>

                                {{-- Supprimer --}}
                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $user) }}"
                                      id="form-supprimer-{{ $user->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            onclick="ouvrirModalSuppression('{{ $user->id }}', '{{ $user->nom }} {{ $user->prenoms }}')"
                                            style="background:#cc0000; color:white; padding:5px 12px;
                                                   border:none; border-radius:6px; font-size:12px;
                                                   font-weight:600; cursor:pointer">
                                        Supprimer
                                    </button>
                                </form>
                            @else
                                {{-- Restaurer --}}
                                <form method="POST"
                                      action="{{ route('admin.users.restore', $user->id) }}">
                                    @csrf @method('PATCH')
                                    <button style="background:#1a3c6e; color:white; padding:5px 12px;
                                                   border:none; border-radius:6px; font-size:12px;
                                                   font-weight:600; cursor:pointer">
                                        Restaurer
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:20px 16px; color:#666; text-align:center">
                            Aucun professeur traité.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination simple --}}
        @if($toutProfesseur->hasPages())
            <div style="display:flex; justify-content:center; align-items:center;
                        gap:8px; margin-top:20px; padding-top:16px;
                        border-top:1px solid #f0f0f0">
                @if($toutProfesseur->onFirstPage())
                    <span style="padding:6px 14px; border-radius:6px; font-size:13px;
                                 color:#adb5bd; border:1px solid #e9ecef">
                        ← Précédent
                    </span>
                @else
                    <a href="{{ $toutProfesseur->previousPageUrl() }}"
                       style="padding:6px 14px; border-radius:6px; font-size:13px;
                              color:#1a3c6e; border:1px solid #1a3c6e; text-decoration:none;
                              font-weight:600">
                        ← Précédent
                    </a>
                @endif

                <span style="font-size:13px; color:#666">
                    Page {{ $toutProfesseur->currentPage() }} / {{ $toutProfesseur->lastPage() }}
                </span>

                @if($toutProfesseur->hasMorePages())
                    <a href="{{ $toutProfesseur->nextPageUrl() }}"
                       style="padding:6px 14px; border-radius:6px; font-size:13px;
                              color:#1a3c6e; border:1px solid #1a3c6e; text-decoration:none;
                              font-weight:600">
                        Suivant →
                    </a>
                @else
                    <span style="padding:6px 14px; border-radius:6px; font-size:13px;
                                 color:#adb5bd; border:1px solid #e9ecef">
                        Suivant →
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- Modal confirmation suppression --}}
<div id="modal-suppression"
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
            background:rgba(0,0,0,0.5); z-index:1000;
            justify-content:center; align-items:center">
    <div style="background:white; border-radius:12px; padding:0;
                max-width:420px; width:90%; overflow:hidden;
                box-shadow:0 20px 60px rgba(0,0,0,0.3)">

        <div style="background:#cc0000; padding:20px 24px">
            <p style="color:white; font-weight:700; font-size:18px; margin:0">
                ⚠️ Confirmer la suppression
            </p>
        </div>

        <div style="padding:24px">
            <p style="color:#333; font-size:15px; margin:0 0 8px 0">
                Vous êtes sur le point de supprimer le compte de :
            </p>
            <p id="modal-nom-prof"
               style="color:#1a2b4a; font-size:16px; font-weight:700;
                      margin:0 0 16px 0; padding:12px 16px;
                      background:#f5f7fa; border-radius:8px;
                      border-left:4px solid #cc0000">
            </p>
            <p style="color:#666; font-size:13px; margin:0">
                📌 Les réservations et séances de ce professeur seront conservées.
                Cette action peut être annulée via le bouton <strong>Restaurer</strong>.
            </p>
        </div>

        <div style="padding:16px 24px 24px; display:flex; gap:12px; justify-content:flex-end">
            <button onclick="fermerModalSuppression()"
                    style="padding:10px 24px; border:1px solid #ddd; border-radius:6px;
                           font-size:14px; font-weight:600; color:#555;
                           background:white; cursor:pointer">
                Annuler
            </button>
            <button id="btn-confirmer-suppression"
                    style="padding:10px 24px; background:#cc0000; color:white;
                           border:none; border-radius:6px; font-size:14px;
                           font-weight:600; cursor:pointer">
                Oui, supprimer
            </button>
        </div>
    </div>
</div>

<script>
    function ouvrirModalSuppression(userId, nomProf) {
        document.getElementById('modal-nom-prof').textContent = nomProf;
        document.getElementById('btn-confirmer-suppression').onclick = function() {
            document.getElementById('form-supprimer-' + userId).submit();
        };
        document.getElementById('modal-suppression').style.display = 'flex';
    }

    function fermerModalSuppression() {
        document.getElementById('modal-suppression').style.display = 'none';
    }

    document.getElementById('modal-suppression').addEventListener('click', function(e) {
        if (e.target === this) fermerModalSuppression();
    });
</script>
@endsection
