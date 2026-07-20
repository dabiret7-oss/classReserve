@extends('layouts.app')
@section('title', 'Gestion des salles')
@section('page-title', 'Gestion des salles')

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-400">Gérez les salles disponibles pour les réservations</p>
    <a href="{{ route('admin.salles.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-[#1a3c6e] text-white text-sm font-medium rounded-lg hover:bg-blue-900 transition-colors">
        <i class="ti ti-plus text-base"></i> Ajouter une salle
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-[#1a2b4a]">Salles enregistrées</h2>
        <span class="bg-[#1a3c6e] text-white text-xs font-bold px-2.5 py-1 rounded-full">
            {{ $salles->count() }}
        </span>
    </div>

    @if($salles->isEmpty())
        <div class="text-center py-10 text-gray-400">
            <i class="ti ti-building text-4xl block mb-3 text-gray-200"></i>
            <p class="text-sm">Aucune salle enregistrée.</p>
            <a href="{{ route('admin.salles.create') }}"
               class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-[#1a3c6e] text-white text-sm rounded-lg">
                <i class="ti ti-plus text-sm"></i> Ajouter une salle
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Nom</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Niveau</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Statut</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 border-b border-gray-200">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salles as $salle)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-[#1a2b4a]">{{ $salle->nom }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $salle->niveau }}</td>
                        <td class="px-4 py-3">
                            @if($salle->isActive())
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full">Active</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1.5">
                                {{-- Toggle statut avec modal --}}
                                <form method="POST"
                                      action="{{ route('admin.salles.toggle', $salle) }}"
                                      id="form-toggle-{{ $salle->id }}">
                                    @csrf @method('PATCH')
                                    <button type="button"
                                            onclick="ouvrirModalToggle('{{ $salle->id }}', '{{ $salle->nom }}', {{ $salle->isActive() ? 'true' : 'false' }})"
                                            title="{{ $salle->isActive() ? 'Désactiver' : 'Activer' }}"
                                            class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors
                                                   {{ $salle->isActive() ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                                        <i class="ti {{ $salle->isActive() ? 'ti-player-pause' : 'ti-player-play' }} text-base"></i>
                                    </button>
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

{{-- ══ MODAL TOGGLE SALLE ══ --}}
<div id="modal-toggle"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl overflow-hidden max-w-sm w-full mx-4 shadow-2xl">

        {{-- Icône centrale --}}
        <div class="flex flex-col items-center pt-8 pb-4 px-6">
            <div id="modal-icon-container"
                 class="w-16 h-16 rounded-full flex items-center justify-center mb-4">
                <i id="modal-icon" class="text-3xl"></i>
            </div>
            <h3 id="modal-title" class="text-lg font-bold text-[#1a2b4a] text-center mb-1"></h3>
            <p class="text-sm text-gray-400 text-center mb-2">Salle concernée :</p>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center">
                <p id="modal-salle-nom" class="text-base font-bold text-[#1a2b4a]"></p>
            </div>
            <p id="modal-message" class="text-sm text-gray-500 text-center mt-4 leading-relaxed"></p>
        </div>

        {{-- Boutons --}}
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="fermerModalToggle()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors">
                Annuler
            </button>
            <button id="btn-confirmer-toggle"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-colors">
                Confirmer
            </button>
        </div>
    </div>
</div>

<script>
function ouvrirModalToggle(salleId, salleNom, estActive) {
    const modal = document.getElementById('modal-toggle');
    const iconContainer = document.getElementById('modal-icon-container');
    const icon = document.getElementById('modal-icon');
    const title = document.getElementById('modal-title');
    const message = document.getElementById('modal-message');
    const btn = document.getElementById('btn-confirmer-toggle');

    document.getElementById('modal-salle-nom').textContent = salleNom;

    if (estActive) {
        iconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center mb-4 bg-amber-100';
        icon.className = 'ti ti-player-pause text-3xl text-amber-600';
        title.textContent = 'Désactiver la salle';
        message.textContent = 'Cette salle ne sera plus disponible pour les réservations. Les réservations existantes ne seront pas affectées.';
        btn.className = 'flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-colors bg-amber-500 hover:bg-amber-600';
    } else {
        iconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center mb-4 bg-green-100';
        icon.className = 'ti ti-player-play text-3xl text-green-600';
        title.textContent = 'Activer la salle';
        message.textContent = 'Cette salle sera à nouveau disponible pour les nouvelles réservations.';
        btn.className = 'flex-1 py-2.5 rounded-xl text-sm font-semibold text-white transition-colors bg-green-600 hover:bg-green-700';
    }

    btn.onclick = function() {
        document.getElementById('form-toggle-' + salleId).submit();
    };

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function fermerModalToggle() {
    const modal = document.getElementById('modal-toggle');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('modal-toggle').addEventListener('click', function(e) {
    if (e.target === this) fermerModalToggle();
});
</script>
@endsection