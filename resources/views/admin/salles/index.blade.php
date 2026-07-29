@extends('layouts.app')
@section('title', 'Gestion des salles')
@section('page-title', 'Gestion des salles')

@section('content')

{{-- Header --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <div>
        <p class="text-sm text-gray-400 mt-0.5">{{ $salles->count() }} salle(s) enregistrée(s)</p>
    </div>
    <a href="{{ route('admin.salles.create') }}"
       class="flex items-center gap-2 px-4 py-2.5 bg-[#1a3c6e] text-white text-sm font-medium rounded-lg hover:bg-blue-900 transition-colors">
        <i class="ti ti-plus text-base"></i> Ajouter une salle
    </a>
</div>

{{-- Stats rapides --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $actives   = $salles->where('statut','active')->count();
        $inactives = $salles->where('statut','inactive')->count();
        $niveaux   = $salles->groupBy('niveau');
    @endphp
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-circle-check text-xl text-green-600"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-[#1a2b4a]">{{ $actives }}</div>
            <div class="text-xs text-gray-400">Actives</div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-circle-x text-xl text-red-500"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-[#1a2b4a]">{{ $inactives }}</div>
            <div class="text-xs text-gray-400">Inactives</div>
        </div>
    </div>
    @foreach(['RDC','R+1','R+2','R+3'] as $niv)
    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-building text-xl text-[#1a3c6e]"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-[#1a2b4a]">{{ $niveaux->get($niv, collect())->count() }}</div>
            <div class="text-xs text-gray-400">{{ $niv }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Tableau --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-base font-semibold text-[#1a2b4a]">Liste des salles</h2>
        <span class="bg-[#1a3c6e] text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $salles->count() }}</span>
    </div>

    @if($salles->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <i class="ti ti-building text-5xl block mb-4 text-gray-200"></i>
            <p class="text-base font-medium text-gray-500 mb-1">Aucune salle enregistrée</p>
            <a href="{{ route('admin.salles.create') }}"
               class="inline-flex items-center gap-2 mt-4 px-4 py-2.5 bg-[#1a3c6e] text-white text-sm rounded-lg">
                <i class="ti ti-plus text-sm"></i> Ajouter une salle
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Nom</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Niveau</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Statut</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($salles as $salle)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg {{ $salle->isActive() ? 'bg-blue-50' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                                    <i class="ti ti-door text-base {{ $salle->isActive() ? 'text-[#1a3c6e]' : 'text-gray-400' }}"></i>
                                </div>
                                <span class="font-semibold text-sm text-[#1a2b4a]">{{ $salle->nom }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <span class="bg-blue-50 text-[#1a3c6e] text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ $salle->niveau }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if($salle->isActive())
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    <span class="text-sm text-green-700 font-medium">Active</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2 h-2 rounded-full bg-red-400"></div>
                                    <span class="text-sm text-red-600 font-medium">Inactive</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5">
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
                                <a href="{{ route('admin.salles.edit', $salle) }}"
                                   title="Modifier"
                                   class="w-8 h-8 rounded-lg bg-blue-50 text-[#1a3c6e] hover:bg-blue-100 flex items-center justify-center transition-colors">
                                    <i class="ti ti-pencil text-base"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if(method_exists($salles, 'hasPages') && $salles->hasPages())
            <div class="flex items-center justify-center gap-3 py-4 border-t border-gray-100">
                @if($salles->onFirstPage())
                    <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">← Précédent</span>
                @else
                    <a href="{{ $salles->previousPageUrl() }}"
                       class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">← Précédent</a>
                @endif
                <span class="text-xs text-gray-500">Page {{ $salles->currentPage() }} / {{ $salles->lastPage() }}</span>
                @if($salles->hasMorePages())
                    <a href="{{ $salles->nextPageUrl() }}"
                       class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">Suivant →</a>
                @else
                    <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">Suivant →</span>
                @endif
            </div>
        @endif
    @endif
</div>

{{-- Modal Toggle --}}
<div id="modal-toggle" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl overflow-hidden max-w-sm w-full mx-4 shadow-2xl">
        <div class="flex flex-col items-center pt-8 pb-4 px-6">
            <div id="modal-icon-container" class="w-16 h-16 rounded-full flex items-center justify-center mb-4">
                <i id="modal-icon" class="text-3xl"></i>
            </div>
            <h3 id="modal-title" class="text-lg font-bold text-[#1a2b4a] text-center mb-1"></h3>
            <p class="text-sm text-gray-400 text-center mb-2">Salle concernée :</p>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center">
                <p id="modal-salle-nom" class="text-base font-bold text-[#1a2b4a]"></p>
            </div>
            <p id="modal-message" class="text-sm text-gray-500 text-center mt-4 leading-relaxed"></p>
        </div>
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="fermerModalToggle()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50">
                Annuler
            </button>
            <button id="btn-confirmer-toggle" class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white"></button>
        </div>
    </div>
</div>

<script>
function ouvrirModalToggle(id, nom, estActive) {
    const c = document.getElementById('modal-icon-container');
    const i = document.getElementById('modal-icon');
    const btn = document.getElementById('btn-confirmer-toggle');
    document.getElementById('modal-salle-nom').textContent = nom;
    if (estActive) {
        c.className = 'w-16 h-16 rounded-full flex items-center justify-center mb-4 bg-amber-100';
        i.className = 'ti ti-player-pause text-3xl text-amber-600';
        document.getElementById('modal-title').textContent = 'Désactiver la salle';
        document.getElementById('modal-message').textContent = 'Cette salle ne sera plus disponible pour les réservations.';
        btn.className = 'flex-1 py-2.5 rounded-xl text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600';
    } else {
        c.className = 'w-16 h-16 rounded-full flex items-center justify-center mb-4 bg-green-100';
        i.className = 'ti ti-player-play text-3xl text-green-600';
        document.getElementById('modal-title').textContent = 'Activer la salle';
        document.getElementById('modal-message').textContent = 'Cette salle sera disponible pour les nouvelles réservations.';
        btn.className = 'flex-1 py-2.5 rounded-xl text-sm font-semibold text-white bg-green-600 hover:bg-green-700';
    }
    btn.onclick = () => document.getElementById('form-toggle-' + id).submit();
    document.getElementById('modal-toggle').classList.remove('hidden');
    document.getElementById('modal-toggle').classList.add('flex');
}
function fermerModalToggle() {
    document.getElementById('modal-toggle').classList.add('hidden');
    document.getElementById('modal-toggle').classList.remove('flex');
}
document.getElementById('modal-toggle').addEventListener('click', function(e) {
    if (e.target === this) fermerModalToggle();
});
</script>
@endsection