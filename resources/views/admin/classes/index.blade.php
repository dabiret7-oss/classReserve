@extends('layouts.app')
@section('title', 'Gestion des classes')
@section('page-title', 'Gestion des classes')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center">
            <i class="ti ti-school text-3xl text-white"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Gestion des classes</h2>
            <p class="text-white/70 text-sm mt-0.5">{{ $classes->total() }} classe(s) enregistrée(s)</p>
        </div>
    </div>
    <div class="bg-white/15 rounded-xl px-4 py-2 text-center">
        <div class="text-2xl font-bold text-white">{{ $classes->total() }}</div>
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
                    <h3 class="text-sm font-semibold text-[#1a2b4a]">Nouvelle classe</h3>
                    <p class="text-xs text-gray-400">Renseignez les informations</p>
                </div>
            </div>
            <div class="p-5 space-y-4">
                <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nom <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="ti ti-users text-[#1a3c6e] text-sm"></i>
                            </div>
                            <input type="text" name="nom" value="{{ old('nom') }}"
                                   placeholder="Ex: Licence 1 Info A"
                                   class="w-full pl-13 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                        </div>
                        @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Filière <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="ti ti-certificate text-[#1a3c6e] text-sm"></i>
                            </div>
                            <input type="text" name="filiere" value="{{ old('filiere') }}"
                                   placeholder="Ex: Informatique"
                                   class="w-full pl-13 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                        </div>
                        @error('filiere')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Niveau <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['Licence 1','Licence 2','Licence 3','Master 1','Master 2'] as $niv)
                                @php $sel = old('niveau') === $niv; @endphp
                                <label class="cursor-pointer">
                                    <input type="radio" name="niveau" value="{{ $niv }}" {{ $sel ? 'checked' : '' }} class="peer sr-only">
                                    <div class="border-2 rounded-lg px-3 py-2 text-xs font-medium text-center transition-all
                                                {{ $sel ? 'border-[#1a3c6e] bg-blue-50 text-[#1a3c6e]' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}
                                                peer-checked:border-[#1a3c6e] peer-checked:bg-blue-50 peer-checked:text-[#1a3c6e]">
                                        {{ $niv }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('niveau')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 py-2.5 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                        <i class="ti ti-plus text-base"></i> Ajouter la classe
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Liste --}}
    <div class="lg:col-span-3">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-[#1a2b4a]">Liste des classes</h3>
                <span class="bg-[#1a3c6e] text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $classes->total() }}</span>
            </div>

            @forelse($classes as $classe)
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center flex-shrink-0 group-hover:from-blue-100 transition-colors">
                            <i class="ti ti-school text-lg text-[#1a3c6e]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#1a2b4a]">{{ $classe->nom }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-gray-400">{{ $classe->filiere }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span class="bg-blue-50 text-[#1a3c6e] text-xs font-medium px-2 py-0.5 rounded-full">{{ $classe->niveau }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.classes.edit', $classe) }}"
                           title="Modifier"
                           class="w-8 h-8 rounded-lg bg-blue-50 text-[#1a3c6e] hover:bg-blue-100 flex items-center justify-center transition-colors">
                            <i class="ti ti-pencil text-sm"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.classes.destroy', $classe) }}"
                              id="form-delete-classe-{{ $classe->id }}">
                            @csrf @method('DELETE')
                            <button type="button"
                                    onclick="ouvrirModalDelete('classe','{{ $classe->id }}','{{ $classe->nom }}')"
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors">
                                <i class="ti ti-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="ti ti-school text-3xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Aucune classe enregistrée</p>
                    <p class="text-xs text-gray-400">Ajoutez votre première classe via le formulaire</p>
                </div>
            @endforelse

            @if($classes->hasPages())
                <div class="flex items-center justify-center gap-3 py-4 border-t border-gray-100">
                    @if($classes->onFirstPage())
                        <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg">← Précédent</span>
                    @else
                        <a href="{{ $classes->previousPageUrl() }}"
                           class="px-3 py-1.5 text-xs text-[#1a3c6e] border border-[#1a3c6e] rounded-lg hover:bg-blue-50">← Précédent</a>
                    @endif
                    <span class="text-xs text-gray-500">Page {{ $classes->currentPage() }} / {{ $classes->lastPage() }}</span>
                    @if($classes->hasMorePages())
                        <a href="{{ $classes->nextPageUrl() }}"
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
            <h3 class="text-lg font-bold text-[#1a2b4a] text-center mb-1">Supprimer la classe</h3>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-5 py-3 w-full text-center mt-3">
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

// Radio niveau
document.querySelectorAll('input[name="niveau"]').forEach(r => {
    r.addEventListener('change', () => {
        document.querySelectorAll('input[name="niveau"]').forEach(rb => {
            const d = rb.nextElementSibling;
            if (rb.checked) {
                d.classList.add('border-[#1a3c6e]','bg-blue-50','text-[#1a3c6e]');
                d.classList.remove('border-gray-200','text-gray-600');
            } else {
                d.classList.remove('border-[#1a3c6e]','bg-blue-50','text-[#1a3c6e]');
                d.classList.add('border-gray-200','text-gray-600');
            }
        });
    });
});
</script>
@endsection