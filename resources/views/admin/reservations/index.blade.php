@extends('layouts.app')
@section('title', 'Gestion des réservations')
@section('page-title', 'Gestion des réservations')

@section('content')

{{-- Filtre + bouton --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-5">
    <form method="GET" action="{{ route('admin.reservations.index') }}"
          class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-2.5">
        <i class="ti ti-filter text-gray-400 text-base"></i>
        <select name="salle_id"
                class="text-sm text-gray-700 bg-transparent border-none outline-none font-sans">
            <option value="">Toutes les salles</option>
            @foreach($salles as $salle)
                <option value="{{ $salle->id }}" {{ $salleId == $salle->id ? 'selected' : '' }}>
                    {{ $salle->nom }} — {{ $salle->niveau }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                class="px-3 py-1.5 bg-[#1a3c6e] text-white text-xs font-medium rounded-lg hover:bg-blue-900 transition-colors">
            Filtrer
        </button>
        @if($salleId)
            <a href="{{ route('admin.reservations.index') }}"
               class="text-xs text-red-500 hover:underline flex items-center gap-1">
                <i class="ti ti-x text-xs"></i> Effacer
            </a>
        @endif
    </form>

    <a href="{{ route('admin.reservations.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-[#1a3c6e] text-white text-sm font-medium rounded-lg hover:bg-blue-900 transition-colors">
        <i class="ti ti-plus text-base"></i> Attribuer une salle
    </a>
</div>

{{-- Réservations en attente --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-5">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-semibold text-[#1a2b4a]">Demandes en attente</h2>
            <p class="text-xs text-gray-400 mt-0.5">Sélectionnez la salle à attribuer avant de valider</p>
        </div>
        <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2.5 py-1 rounded-full">
            {{ $enAttente->count() }}
        </span>
    </div>

    @if($enAttente->isEmpty())
        <div class="text-center py-10 text-gray-400">
            <i class="ti ti-circle-check text-4xl block mb-3 text-green-200"></i>
            <p class="text-sm">Aucune demande en attente.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($enAttente as $reservation)
            <div class="border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-xs font-bold text-[#1a3c6e] flex-shrink-0">
                            {{ strtoupper(substr($reservation->user->nom,0,1)) }}{{ strtoupper(substr($reservation->user->prenoms,0,1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-[#1a2b4a]">{{ $reservation->motif }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                👤 {{ $reservation->user->nom }} {{ $reservation->user->prenoms }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                📍 {{ $reservation->salle->nom }} — {{ $reservation->salle->niveau }}
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                🕐 {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y à H\hi') }}
                                → {{ substr($reservation->heure_fin, 0, 5) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 min-w-48">
                        <form method="POST"
                              action="{{ route('admin.reservations.valider', $reservation) }}"
                              class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="salle_id"
                                    class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-xs bg-white focus:outline-none focus:border-[#1a3c6e]">
                                @foreach($salles as $salle)
                                    <option value="{{ $salle->id }}"
                                        {{ $salle->id == $reservation->salle_id ? 'selected' : '' }}>
                                        {{ $salle->nom }} — {{ $salle->niveau }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button"
                                    onclick="ouvrirModalValider(this)"
                                    title="Valider"
                                    class="w-8 h-8 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 flex items-center justify-center transition-colors flex-shrink-0">
                                <i class="ti ti-check text-base"></i>
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('admin.reservations.rejeter', $reservation) }}"
                              id="form-rejeter-{{ $reservation->id }}">
                            @csrf @method('PATCH')
                            <button type="button"
                                    onclick="ouvrirModalRejeter('{{ $reservation->id }}', '{{ $reservation->motif }}', '{{ $reservation->user->nom }}')"
                                    class="w-full flex items-center justify-center gap-1.5 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg text-xs font-medium transition-colors">
                                <i class="ti ti-x text-sm"></i> Rejeter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Réservations traitées --}}
<div class="bg-white rounded-xl border border-gray-200 p-5">
    <h2 class="text-base font-semibold text-[#1a2b4a] mb-4">Réservations traitées</h2>

    @if($traitees->isEmpty())
        <div class="text-center py-8 text-gray-400">
            <i class="ti ti-calendar text-3xl block mb-2 text-gray-200"></i>
            <p class="text-sm">Aucune réservation traitée.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Professeur</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Salle</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Date</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Motif</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Statut</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($traitees as $reservation)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 {{ $reservation->longue_periode ? 'bg-blue-50/30' : '' }}">
                        <td class="px-4 py-3">
                            @if($reservation->user)
                                <p class="font-semibold text-[#1a2b4a]">{{ $reservation->user->nom }} {{ $reservation->user->prenoms }}</p>
                                <p class="text-xs text-gray-400">{{ $reservation->user->email }}</p>
                            @else
                                <span class="text-xs bg-orange-100 text-orange-700 font-semibold px-2 py-0.5 rounded-full">Activité externe</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-700 font-medium">{{ $reservation->salle->nom }}</p>
                            <p class="text-xs text-gray-400">{{ $reservation->salle->niveau }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            <p>{{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y') }}</p>
                            <p class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($reservation->date_debut)->format('H\hi') }}
                                → {{ substr($reservation->heure_fin, 0, 5) }}
                            </p>
                            @if($reservation->longue_periode)
                                <span class="text-[10px] bg-blue-100 text-[#1a3c6e] font-semibold px-1.5 py-0.5 rounded mt-0.5 inline-block">
                                    Longue période
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $reservation->motif }}</td>
                        <td class="px-4 py-3">
                            @if($reservation->statut === 'validee')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">Validée ✓</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full">Rejetée</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.reservations.edit', $reservation) }}"
                                   title="Modifier"
                                   class="w-8 h-8 rounded-lg bg-blue-50 text-[#1a3c6e] hover:bg-blue-100 flex items-center justify-center transition-colors">
                                    <i class="ti ti-pencil text-sm"></i>
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.reservations.destroy', $reservation) }}"
                                      id="form-delete-resa-{{ $reservation->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            onclick="ouvrirModalDelete('{{ $reservation->id }}', '{{ addslashes($reservation->motif) }}', {{ $reservation->groupe_id ? 'true' : 'false' }})"
                                            title="Supprimer"
                                            class="w-8 h-8 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 flex items-center justify-center transition-colors">
                                        <i class="ti ti-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($traitees->hasPages())
            <div class="flex items-center justify-center gap-3 mt-5 pt-4 border-t border-gray-100">
                @if($traitees->onFirstPage())
                    <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">← Précédent</span>
                @else
                    <a href="{{ $traitees->previousPageUrl() }}"
                       class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">← Précédent</a>
                @endif
                <span class="text-xs text-gray-500">Page {{ $traitees->currentPage() }} / {{ $traitees->lastPage() }}</span>
                @if($traitees->hasMorePages())
                    <a href="{{ $traitees->nextPageUrl() }}"
                       class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">Suivant →</a>
                @else
                    <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">Suivant →</span>
                @endif
            </div>
        @endif
    @endif
</div>

{{-- ══ MODAL VALIDER ══ --}}
<div id="modal-valider" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl overflow-hidden max-w-sm w-full mx-4 shadow-2xl">
        <div class="flex flex-col items-center pt-8 pb-4 px-6">
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-4">
                <i class="ti ti-circle-check text-3xl text-green-600"></i>
            </div>
            <h3 class="text-lg font-bold text-[#1a2b4a] text-center mb-1">Valider la réservation</h3>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center mb-3">
                <p id="valider-motif" class="text-base font-bold text-[#1a2b4a]"></p>
            </div>
            <p class="text-sm text-gray-500 text-center">La salle sélectionnée sera attribuée et le professeur sera notifié.</p>
        </div>
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="fermerModalValider()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50">
                Annuler
            </button>
            <button id="btn-confirmer-valider"
                    class="flex-1 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-semibold">
                ✓ Valider
            </button>
        </div>
    </div>
</div>

{{-- ══ MODAL REJETER ══ --}}
<div id="modal-rejeter" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl overflow-hidden max-w-sm w-full mx-4 shadow-2xl">
        <div class="flex flex-col items-center pt-8 pb-4 px-6">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                <i class="ti ti-circle-x text-3xl text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-[#1a2b4a] text-center mb-1">Rejeter la réservation</h3>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center mb-3">
                <p id="rejeter-motif" class="text-base font-bold text-[#1a2b4a]"></p>
            </div>
            <p id="rejeter-prof" class="text-sm font-semibold text-gray-700 text-center"></p>
        </div>
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="fermerModalRejeter()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50">
                Annuler
            </button>
            <button id="btn-confirmer-rejeter"
                    class="flex-1 py-2.5 bg-red-700 hover:bg-red-800 text-white rounded-xl text-sm font-semibold">
                ✕ Rejeter
            </button>
        </div>
    </div>
</div>

{{-- ══ MODAL SUPPRIMER ══ --}}
<div id="modal-delete-resa" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl overflow-hidden max-w-sm w-full mx-4 shadow-2xl">
        <div class="flex flex-col items-center pt-8 pb-4 px-6">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                <i class="ti ti-trash text-3xl text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-[#1a2b4a] text-center mb-1">Supprimer la réservation</h3>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center mt-2">
                <p id="delete-resa-motif" class="text-sm font-bold text-[#1a2b4a]"></p>
            </div>
            <p id="delete-resa-msg" class="text-sm text-gray-500 text-center mt-3 leading-relaxed"></p>
        </div>
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="fermerModalDelete()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50">
                Annuler
            </button>
            <button id="btn-confirmer-delete-resa"
                    class="flex-1 py-2.5 bg-red-700 hover:bg-red-800 text-white rounded-xl text-sm font-semibold">
                Supprimer
            </button>
        </div>
    </div>
</div>

<script>
// Modal valider
function ouvrirModalValider(btn) {
    const form  = btn.closest('form');
    const motif = form.closest('.border').querySelector('.font-bold').textContent.trim();
    document.getElementById('valider-motif').textContent = motif;
    document.getElementById('btn-confirmer-valider').onclick = () => form.submit();
    document.getElementById('modal-valider').classList.remove('hidden');
    document.getElementById('modal-valider').classList.add('flex');
}
function fermerModalValider() {
    document.getElementById('modal-valider').classList.add('hidden');
    document.getElementById('modal-valider').classList.remove('flex');
}

// Modal rejeter
function ouvrirModalRejeter(id, motif, prof) {
    document.getElementById('rejeter-motif').textContent = motif;
    document.getElementById('rejeter-prof').textContent = prof;
    document.getElementById('btn-confirmer-rejeter').onclick = () =>
        document.getElementById('form-rejeter-' + id).submit();
    document.getElementById('modal-rejeter').classList.remove('hidden');
    document.getElementById('modal-rejeter').classList.add('flex');
}
function fermerModalRejeter() {
    document.getElementById('modal-rejeter').classList.add('hidden');
    document.getElementById('modal-rejeter').classList.remove('flex');
}

// Modal supprimer
function ouvrirModalDelete(id, motif, estGroupe) {
    document.getElementById('delete-resa-motif').textContent = motif;
    document.getElementById('delete-resa-msg').textContent = estGroupe
        ? '⚠️ Cette réservation fait partie d\'une longue période. Toutes les réservations du groupe seront supprimées.'
        : 'Cette action est irréversible.';
    document.getElementById('btn-confirmer-delete-resa').onclick = () =>
        document.getElementById('form-delete-resa-' + id).submit();
    document.getElementById('modal-delete-resa').classList.remove('hidden');
    document.getElementById('modal-delete-resa').classList.add('flex');
}
function fermerModalDelete() {
    document.getElementById('modal-delete-resa').classList.add('hidden');
    document.getElementById('modal-delete-resa').classList.remove('flex');
}

// Fermer modals en cliquant dehors
['modal-valider','modal-rejeter','modal-delete-resa'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
});
</script>
@endsection