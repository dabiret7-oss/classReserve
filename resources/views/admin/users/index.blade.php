@extends('layouts.app')
@section('title', 'Gestion des professeurs')

@section('content')
<h1 style="margin-bottom:24px">Gestion des professeurs</h1>

{{-- Demandes en attente --}}
<div class="card" style="margin-bottom:32px">
    <h2 style="font-size:18px; margin-bottom:16px; color:#1a3c6e">
        Demandes en attente
        <span class="badge badge-pending" style="margin-left:8px">
            {{ $en_coursUser->count() }}
        </span>
    </h2>

    @if($en_coursUser->isEmpty())
        <p style="color:#666; font-size:14px">Aucune demande en attente.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>E-mail</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($en_coursUser as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td style="display:flex; gap:8px">
                        <form method="POST"
                              action="{{ route('admin.users.validate', $user) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-success" style="padding:6px 14px; font-size:13px">
                                Valider
                            </button>
                        </form>
                        <form method="POST"
                              action="{{ route('admin.users.reject', $user) }}">
                            @csrf @method('PATCH')
                            <button class="btn btn-danger" style="padding:6px 14px; font-size:13px">
                                Refuser
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Tous les professeurs traités --}}
<div class="card">
    <h2 style="font-size:18px; margin-bottom:16px; color:#1a3c6e">
        Professeurs traités
    </h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>E-mail</th>
                <th>Statut</th>
                <th>Mis à jour le</th>
            </tr>
        </thead>
        <tbody>
            @forelse($toutProfesseur as $user)
            <tr>
                <td>{{ $user->nom }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge badge-{{ $user->statut }}">
                        {{ match($user->status) {
                            'validated' => 'Validé',
                            'rejected'  => 'Refusé',
                            default     => $user->statut,
                        } }}
                    </span>
                </td>
                <td>{{ $user->updated_at->format('d/m/Y') }}</td>
            </tr>
            @empty
                <tr><td colspan="4" style="color:#666">Aucun professeur traité.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:16px">{{ $toutProfesseur->links() }}</div>
</div>
@endsection