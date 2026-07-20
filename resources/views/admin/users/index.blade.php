@extends('layouts.app')
@section('title', 'Gestion des professeurs')
@section('page-title', 'Gestion des professeurs')

@section('content')

{{-- Demandes en attente --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-[#1a2b4a]">Demandes en attente</h2>
        <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full">
            {{ $pendingUsers->count() }}
        </span>
    </div>

    @if($pendingUsers->isEmpty())
        <div class="text-center py-8 text-gray-400">
            <i class="ti ti-users text-3xl block mb-2 text-gray-200"></i>
            <p class="text-sm">Aucune demande en attente.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Nom</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">E-mail</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Date d'inscription</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-[#1a2b4a]">{{ $user->nom }} {{ $user->prenoms }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        onclick="ouvrirModalAction('valider', '{{ $user->id }}', '{{ $user->nom }} {{ $user->prenoms }}')"
                                        title="Valider"
                                        class="w-8 h-8 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 flex items-center justify-center transition-colors">
                                    <i class="ti ti-check text-base"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.users.validate', $user) }}" id="form-valider-{{ $user->id }}">
                                    @csrf @method('PATCH')
                                </form>

                                <button type="button"
                                        onclick="ouvrirModalAction('rejeter', '{{ $user->id }}', '{{ $user->nom }} {{ $user->prenoms }}')"
                                        title="Rejeter"
                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 flex items-center justify-center transition-colors">
                                    <i class="ti ti-x text-base"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.users.reject', $user) }}" id="form-rejeter-{{ $user->id }}">
                                    @csrf @method('PATCH')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Professeurs traités --}}
<div class="bg-white rounded-xl border border-gray-200 p-5">
    <h2 class="text-base font-semibold text-[#1a2b4a] mb-4">Professeurs traités</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Nom</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">E-mail</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Statut</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Mis à jour le</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($toutProfesseur as $user)
                <tr class="border-b border-gray-100 hover:bg-gray-50 {{ $user->deleted_at ? 'opacity-50' : '' }}">
                    <td class="px-4 py-3 font-semibold text-[#1a2b4a]">
                        {{ $user->nom }} {{ $user->prenoms }}
                        @if($user->deleted_at)
                            <span class="text-xs text-red-500 font-normal ml-1">(supprimé)</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        @if($user->statut === 'valide')
                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">Validé</span>
                        @elseif($user->statut === 'desactive')
                            <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full">Désactivé</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full">Refusé</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $user->updated_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1.5">
                            @if(!$user->deleted_at)
                                {{-- Désactiver / Réactiver --}}
                                <button type="button"
                                        onclick="ouvrirModalAction('{{ $user->statut === 'desactive' ? 'reactiver' : 'desactiver' }}', '{{ $user->id }}', '{{ $user->nom }} {{ $user->prenoms }}')"
                                        title="{{ $user->statut === 'desactive' ? 'Réactiver' : 'Désactiver' }}"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors
                                               {{ $user->statut === 'desactive'
                                                   ? 'bg-green-50 text-green-700 hover:bg-green-100'
                                                   : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                                    <i class="ti {{ $user->statut === 'desactive' ? 'ti-player-play' : 'ti-player-pause' }} text-base"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.users.toggle-desactive', $user) }}"
                                      id="form-toggle-{{ $user->id }}">
                                    @csrf @method('PATCH')
                                </form>

                                {{-- Supprimer --}}
                                <button type="button"
                                        onclick="ouvrirModalSuppression('{{ $user->id }}', '{{ $user->nom }} {{ $user->prenoms }}')"
                                        title="Supprimer"
                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 flex items-center justify-center transition-colors">
                                    <i class="ti ti-trash text-base"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      id="form-supprimer-{{ $user->id }}">
                                    @csrf @method('DELETE')
                                </form>
                            @else
                                {{-- Restaurer --}}
                                <button type="button"
                                        onclick="ouvrirModalAction('restaurer', '{{ $user->id }}', '{{ $user->nom }} {{ $user->prenoms }}')"
                                        title="Restaurer"
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-[#1a3c6e] hover:bg-blue-100 flex items-center justify-center transition-colors">
                                    <i class="ti ti-restore text-base"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.users.restore', $user->id) }}"
                                      id="form-restaurer-{{ $user->id }}">
                                    @csrf @method('PATCH')
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">Aucun professeur traité.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($toutProfesseur->hasPages())
        <div class="flex items-center justify-center gap-3 mt-5 pt-4 border-t border-gray-100">
            @if($toutProfesseur->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">← Précédent</span>
            @else
                <a href="{{ $toutProfesseur->previousPageUrl() }}"
                   class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">← Précédent</a>
            @endif
            <span class="text-xs text-gray-500">Page {{ $toutProfesseur->currentPage() }} / {{ $toutProfesseur->lastPage() }}</span>
            @if($toutProfesseur->hasMorePages())
                <a href="{{ $toutProfesseur->nextPageUrl() }}"
                   class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">Suivant →</a>
            @else
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">Suivant →</span>
            @endif
        </div>
    @endif
</div>

{{-- ══ MODAL ACTION (valider, rejeter, désactiver, réactiver, restaurer) ══ --}}
<div id="modal-action"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl overflow-hidden max-w-sm w-full mx-4 shadow-2xl">
        <div class="flex flex-col items-center pt-8 pb-4 px-6">
            <div id="action-icon-container"
                 class="w-16 h-16 rounded-full flex items-center justify-center mb-4">
                <i id="action-icon" class="text-3xl"></i>
            </div>
            <h3 id="action-title" class="text-lg font-bold text-[#1a2b4a] text-center mb-1"></h3>
            <p class="text-sm text-gray-400 text-center mb-2">Professeur concerné :</p>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center">
                <p id="action-nom" class="text-base font-bold text-[#1a2b4a]"></p>
            </div>
            <p id="action-message" class="text-sm text-gray-500 text-center mt-4 leading-relaxed"></p>
        </div>
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="fermerModalAction()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                Annuler
            </button>
            <button id="btn-confirmer-action"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-colors">
                Confirmer
            </button>
        </div>
    </div>
</div>

{{-- ══ MODAL SUPPRESSION ══ --}}
<div id="modal-suppression"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl overflow-hidden max-w-sm w-full mx-4 shadow-2xl">
        <div class="flex flex-col items-center pt-8 pb-4 px-6">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                <i class="ti ti-trash text-3xl text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-[#1a2b4a] text-center mb-1">Supprimer le compte</h3>
            <p class="text-sm text-gray-400 text-center mb-2">Professeur concerné :</p>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center">
                <p id="modal-nom-prof" class="text-base font-bold text-[#1a2b4a]"></p>
            </div>
            <p class="text-sm text-gray-500 text-center mt-4 leading-relaxed">
                📌 Les réservations et séances seront conservées.<br>
                Cette action peut être annulée via <strong>Restaurer</strong>.
            </p>
        </div>
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="fermerModalSuppression()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                Annuler
            </button>
            <button id="btn-confirmer-suppression"
                    class="flex-1 py-2.5 bg-red-700 hover:bg-red-800 text-white rounded-xl text-sm font-semibold transition-colors">
                Oui, supprimer
            </button>
        </div>
    </div>
</div>

<script>
const configs = {
    valider: {
        icon: 'ti-circle-check', iconBg: 'bg-green-100', iconColor: 'text-green-600',
        title: 'Valider le compte',
        message: 'Ce professeur pourra se connecter et accéder à la plateforme.',
        btnClass: 'bg-green-600 hover:bg-green-700',
        formPrefix: 'form-valider'
    },
    rejeter: {
        icon: 'ti-circle-x', iconBg: 'bg-red-100', iconColor: 'text-red-600',
        title: 'Rejeter le compte',
        message: 'Ce professeur ne pourra pas accéder à la plateforme.',
        btnClass: 'bg-red-600 hover:bg-red-700',
        formPrefix: 'form-rejeter'
    },
    desactiver: {
        icon: 'ti-player-pause', iconBg: 'bg-amber-100', iconColor: 'text-amber-600',
        title: 'Désactiver le compte',
        message: 'Ce professeur ne pourra plus se connecter. Ses données sont conservées.',
        btnClass: 'bg-amber-500 hover:bg-amber-600',
        formPrefix: 'form-toggle'
    },
    reactiver: {
        icon: 'ti-player-play', iconBg: 'bg-green-100', iconColor: 'text-green-600',
        title: 'Réactiver le compte',
        message: 'Ce professeur pourra à nouveau se connecter à la plateforme.',
        btnClass: 'bg-green-600 hover:bg-green-700',
        formPrefix: 'form-toggle'
    },
    restaurer: {
        icon: 'ti-restore', iconBg: 'bg-blue-100', iconColor: 'text-[#1a3c6e]',
        title: 'Restaurer le compte',
        message: 'Le compte sera restauré avec le statut validé.',
        btnClass: 'bg-[#1a3c6e] hover:bg-blue-900',
        formPrefix: 'form-restaurer'
    },
};

function ouvrirModalAction(type, userId, nomProf) {
    const c = configs[type];
    const modal = document.getElementById('modal-action');
    const container = document.getElementById('action-icon-container');
    const icon = document.getElementById('action-icon');

    container.className = `w-16 h-16 rounded-full flex items-center justify-center mb-4 ${c.iconBg}`;
    icon.className = `ti ${c.icon} text-3xl ${c.iconColor}`;
    document.getElementById('action-title').textContent = c.title;
    document.getElementById('action-nom').textContent = nomProf;
    document.getElementById('action-message').textContent = c.message;

    const btn = document.getElementById('btn-confirmer-action');
    btn.className = `flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-colors ${c.btnClass}`;
    btn.onclick = function() {
        document.getElementById(c.formPrefix + '-' + userId).submit();
    };

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function fermerModalAction() {
    const modal = document.getElementById('modal-action');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function ouvrirModalSuppression(userId, nomProf) {
    document.getElementById('modal-nom-prof').textContent = nomProf;
    document.getElementById('btn-confirmer-suppression').onclick = function() {
        document.getElementById('form-supprimer-' + userId).submit();
    };
    document.getElementById('modal-suppression').classList.remove('hidden');
    document.getElementById('modal-suppression').classList.add('flex');
}

function fermerModalSuppression() {
    document.getElementById('modal-suppression').classList.add('hidden');
    document.getElementById('modal-suppression').classList.remove('flex');
}

document.getElementById('modal-action').addEventListener('click', function(e) {
    if (e.target === this) fermerModalAction();
});
document.getElementById('modal-suppression').addEventListener('click', function(e) {
    if (e.target === this) fermerModalSuppression();
});
</script>
@endsection