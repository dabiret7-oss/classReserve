@extends('layouts.app')
@section('title', 'Gestion des matières')
@section('page-title', 'Gestion des matières')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center">
            <i class="ti ti-book text-3xl text-white"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Gestion des matières</h2>
            <p class="text-white/70 text-sm mt-0.5">{{ $matieres->total() }} matière(s) enregistrée(s)</p>
        </div>
    </div>
    <div class="bg-white/15 rounded-xl px-4 py-2 text-center">
        <div class="text-2xl font-bold text-white">{{ $matieres->total() }}</div>
        <div class="text-xs text-white/70">Total</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

    {{-- Formulaire --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm sticky top-20">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="ti ti-plus text-[#1a3c6e] text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-[#1a2b4a]">Nouvelle matière</h3>
                    <p class="text-xs text-gray-400">Code généré automatiquement</p>
                </div>
            </div>
            <div class="p-5">
                <form method="POST" action="{{ route('admin.matieres.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Nom de la matière <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="ti ti-book text-[#1a3c6e] text-sm"></i>
                            </div>
                            <input type="text" name="nom" value="{{ old('nom') }}"
                                   placeholder="Ex: Mathématiques"
                                   class="w-full pl-13 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>
                        @error('nom')<p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="ti ti-alert-circle text-xs"></i>{{ $message }}</p>@enderror
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl px-4 py-3">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="ti ti-sparkles text-[#1a3c6e] text-sm"></i>
                            <span class="text-xs font-semibold text-[#1a3c6e]">Génération automatique</span>
                        </div>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            Le code est créé à partir du nom.<br>
                            Ex: <strong>"Mathématiques"</strong> → <code class="bg-white px-1.5 py-0.5 rounded text-[#1a3c6e] font-mono">MATH-001</code>
                        </p>
                    </div>

                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-2.5 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                        <i class="ti ti-plus text-base"></i> Ajouter la matière
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Liste --}}
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-[#1a2b4a]">Liste des matières</h3>
                <span class="bg-[#1a3c6e] text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $matieres->total() }}</span>
            </div>

            @forelse($matieres as $matiere)
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center flex-shrink-0 group-hover:from-blue-100 group-hover:to-indigo-100 transition-colors">
                            <i class="ti ti-book text-lg text-[#1a3c6e]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1a2b4a]">{{ $matiere->nom }}</p>
                            <code class="text-xs bg-gray-100 text-gray-600 font-mono px-2 py-0.5 rounded-md mt-0.5 inline-block">
                                {{ $matiere->code }}
                            </code>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.matieres.edit', $matiere) }}"
                           title="Modifier"
                           class="w-8 h-8 rounded-lg bg-blue-50 text-[#1a3c6e] hover:bg-blue-100 flex items-center justify-center transition-colors">
                            <i class="ti ti-pencil text-sm"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.matieres.destroy', $matiere) }}"
                              id="form-delete-matiere-{{ $matiere->id }}">
                            @csrf @method('DELETE')
                            <button type="button"
                                    onclick="ouvrirModalDelete('matiere', '{{ $matiere->id }}', '{{ $matiere->nom }}')"
                                    title="Supprimer"
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors">
                                <i class="ti ti-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-book text-3xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Aucune matière enregistrée</p>
                    <p class="text-xs text-gray-400">Ajoutez votre première matière via le formulaire</p>
                </div>
            @endforelse

            @if($matieres->hasPages())
                <div class="flex items-center justify-center gap-3 py-4 border-t border-gray-100">
                    @if($matieres->onFirstPage())
                        <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">← Précédent</span>
                    @else
                        <a href="{{ $matieres->previousPageUrl() }}"
                           class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">← Précédent</a>
                    @endif
                    <span class="text-xs text-gray-500">Page {{ $matieres->currentPage() }} / {{ $matieres->lastPage() }}</span>
                    @if($matieres->hasMorePages())
                        <a href="{{ $matieres->nextPageUrl() }}"
                           class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">Suivant →</a>
                    @else
                        <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">Suivant →</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal suppression --}}
<div id="modal-delete" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl overflow-hidden max-w-sm w-full mx-4 shadow-2xl">
        <div class="flex flex-col items-center pt-8 pb-4 px-6">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                <i class="ti ti-trash text-3xl text-red-600"></i>
            </div>
            <h3 class="text-lg font-bold text-[#1a2b4a] text-center mb-1">Supprimer la matière</h3>
            <p class="text-sm text-gray-400 text-center mb-2">Matière concernée :</p>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center">
                <p id="delete-nom" class="text-base font-bold text-[#1a2b4a]"></p>
            </div>
            <p class="text-sm text-gray-500 text-center mt-3">Cette action est irréversible.</p>
        </div>
        <div class="px-6 pb-6 flex gap-3">
            <button onclick="fermerModalDelete()"
                    class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50">Annuler</button>
            <button id="btn-confirmer-delete"
                    class="flex-1 py-2.5 bg-red-700 hover:bg-red-800 text-white rounded-xl text-sm font-semibold">Supprimer</button>
        </div>
    </div>
</div>

<script>
function ouvrirModalDelete(type, id, nom) {
    document.getElementById('delete-nom').textContent = nom;
    document.getElementById('btn-confirmer-delete').onclick = () =>
        document.getElementById('form-delete-' + type + '-' + id).submit();
    document.getElementById('modal-delete').classList.remove('hidden');
    document.getElementById('modal-delete').classList.add('flex');
}
function fermerModalDelete() {
    document.getElementById('modal-delete').classList.add('hidden');
    document.getElementById('modal-delete').classList.remove('flex');
}
document.getElementById('modal-delete').addEventListener('click', function(e) {
    if (e.target === this) fermerModalDelete();
});
</script>
@endsection