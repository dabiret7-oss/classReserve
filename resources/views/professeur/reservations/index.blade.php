@extends('layouts.app')
@section('title', 'Mes réservations')
@section('page-title', 'Mes réservations')

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-400">Historique de toutes vos demandes de réservation</p>
    <a href="{{ route('professeur.reservations.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-[#1a3c6e] text-white text-sm font-medium rounded-lg hover:bg-blue-900 transition-colors">
        <i class="ti ti-plus text-base"></i> Nouvelle réservation
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-5">

    @forelse($reservations as $reservation)
        <div class="border border-gray-100 rounded-xl p-4 mb-3 last:mb-0 hover:bg-gray-50 transition-colors">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <i class="ti ti-building text-base text-[#1a3c6e]"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#1a2b4a]">{{ $reservation->motif }}</p>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                            <span class="text-xs text-gray-400">
                                 {{ $reservation->matiere?->nom ?? '—' }}
                            </span>
                            <span class="text-xs text-gray-400">
                                👥 {{ $reservation->classe?->nom ?? '—' }}
                            </span>
                            <span class="text-xs text-gray-400">
                                 {{ $reservation->salle->nom }} — {{ $reservation->salle->niveau }}
                            </span>
                            <span class="text-xs text-gray-400">
                                 {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y à H\hi') }}
                                → {{ \Carbon\Carbon::parse($reservation->heure_fin)->format('H\hi') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-2">
                    @if($reservation->statut === 'en_attente')
                        <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                            En attente
                        </span>
                        <form method="POST"
                              action="{{ route('professeur.reservations.annuler', $reservation) }}"
                              id="form-annuler-{{ $reservation->id }}">
                            @csrf @method('DELETE')
                            <button type="button"
                                    onclick="ouvrirModalAnnuler('{{ $reservation->id }}', '{{ $reservation->motif }}')"
                                    title="Annuler"
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 flex items-center justify-center transition-colors">
                                <i class="ti ti-x text-sm"></i>
                            </button>
                        </form>

                    @elseif($reservation->statut === 'validee')
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                            Validée ✓
                        </span>
                        <a href="{{ route('professeur.cahiers.index') }}"
                           title="Cahiers de texte"
                           class="w-8 h-8 rounded-lg bg-blue-50 text-[#1a3c6e] hover:bg-blue-100 flex items-center justify-center transition-colors">
                            <i class="ti ti-notebook text-sm"></i>
                        </a>

                    @else
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                            Rejetée
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-16 text-gray-400">
            <i class="ti ti-calendar text-5xl block mb-4 text-gray-200"></i>
            <p class="text-base font-medium text-gray-500 mb-1">Aucune réservation pour le moment</p>
            <p class="text-sm mb-5">Commencez par réserver une salle pour votre prochain cours.</p>
            <a href="{{ route('professeur.reservations.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#1a3c6e] text-white text-sm font-medium rounded-lg hover:bg-blue-900">
                <i class="ti ti-plus text-base"></i> Faire une réservation
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($reservations->hasPages())
        <div class="flex items-center justify-center gap-3 mt-5 pt-4 border-t border-gray-100">
            @if($reservations->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">← Précédent</span>
            @else
                <a href="{{ $reservations->previousPageUrl() }}"
                   class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">← Précédent</a>
            @endif
            <span class="text-xs text-gray-500">Page {{ $reservations->currentPage() }} / {{ $reservations->lastPage() }}</span>
            @if($reservations->hasMorePages())
                <a href="{{ $reservations->nextPageUrl() }}"
                   class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">Suivant →</a>
            @else
                <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">Suivant →</span>
            @endif
        </div>
    @endif
</div>

{{-- ══ MODAL ANNULER ══ --}}
<div id="modal-annuler"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl overflow-hidden max-w-sm w-full mx-4 shadow-2xl">
        <div class="flex flex-col items-center pt-8 pb-4 px-6">
            <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mb-4">
                <i class="ti ti-calendar-off text-3xl text-amber-600"></i>
            </div>
            <h3 class="text-lg font-bold text-[#1a2b4a] text-center mb-1">Annuler la réservation</h3>
            <p class="text-sm text-gray-400 text-center mb-2">Cours concerné :</p>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center mb-3">
                <p id="annuler-motif" class="text-base font-bold text-[#1a2b4a]"></p>
            </div>
            <p class="text-sm text-gray-500 text-center leading-relaxed">
                Cette action est irréversible. La demande sera supprimée définitivement.
            </p>
        </div>
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="fermerModalAnnuler()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50">
                Garder
            </button>
            <button id="btn-confirmer-annuler"
                    class="flex-1 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold">
                Oui, annuler
            </button>
        </div>
    </div>
</div>

<script>
function ouvrirModalAnnuler(id, motif) {
    document.getElementById('annuler-motif').textContent = motif;
    document.getElementById('btn-confirmer-annuler').onclick = function() {
        document.getElementById('form-annuler-' + id).submit();
    };
    document.getElementById('modal-annuler').classList.remove('hidden');
    document.getElementById('modal-annuler').classList.add('flex');
}
function fermerModalAnnuler() {
    document.getElementById('modal-annuler').classList.add('hidden');
    document.getElementById('modal-annuler').classList.remove('flex');
}
document.getElementById('modal-annuler').addEventListener('click', function(e) {
    if (e.target === this) fermerModalAnnuler();
});
</script>
@endsection