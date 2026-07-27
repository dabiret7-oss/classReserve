@extends('layouts.app')
@section('title', 'Cahier de texte')
@section('page-title', 'Cahier de texte')

@section('content')
@php
    $aAcces = $cahier->acces->where('user_id', auth()->id())->where('statut','valide')->isNotEmpty();
@endphp

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-[#1a2b4a]">{{ $cahier->classe->nom }}</h2>
        <p class="text-sm text-gray-400 mt-0.5">{{ $cahier->classe->filiere }} — Année {{ $cahier->annee_academique }}</p>
    </div>
    <a href="{{ route('professeur.cahiers.index') }}"
       class="flex items-center gap-1.5 text-sm text-[#1a3c6e] hover:underline">
        <i class="ti ti-arrow-left text-sm"></i> Retour
    </a>
</div>

@if($aAcces)
{{-- ══ FORMULAIRE AJOUTER SÉANCE ══ --}}
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
            <i class="ti ti-plus text-[#1a3c6e] text-lg"></i>
        </div>
        <div>
            <h3 class="text-base font-semibold text-[#1a2b4a]">Ajouter une séance</h3>
            <p class="text-xs text-gray-400">Vous pouvez importer un PDF pour pré-remplir le titre</p>
        </div>
    </div>

    {{-- ══ IMPORT PDF ══ --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-4 mb-5">
        <div class="flex items-center gap-2 mb-3">
            <i class="ti ti-file-type-pdf text-red-500 text-lg"></i>
            <span class="text-sm font-semibold text-[#1a2b4a]">Extraire les titres depuis un PDF</span>
            <span class="ml-auto text-xs text-gray-400">(optionnel)</span>
        </div>
        <div class="flex gap-3">
            <input type="file" id="pdf-input" accept=".pdf"
                   class="flex-1 px-3 py-2 border border-blue-200 rounded-lg text-sm bg-white
                          file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                          file:text-xs file:font-medium file:bg-[#1a3c6e] file:text-white">
            <button type="button" onclick="extrairePdf()"
                    id="btn-extraire"
                    class="flex items-center gap-2 px-4 py-2 bg-[#1a3c6e] text-white text-sm font-medium rounded-lg hover:bg-blue-900 transition-colors flex-shrink-0">
                <i class="ti ti-scan text-base"></i>
                <span>Extraire</span>
            </button>
        </div>

        {{-- Résultat extraction --}}
        <div id="pdf-result" class="hidden mt-3">
            <div class="flex items-center justify-between mb-2">
                <span id="pdf-count" class="text-xs font-semibold text-[#1a3c6e]"></span>
                <button type="button" onclick="cacherResultats()"
                        class="text-xs text-gray-400 hover:text-gray-600">Fermer</button>
            </div>
            <div class="max-h-40 overflow-y-auto space-y-1" id="titres-list"></div>
        </div>

        <div id="pdf-error" class="hidden mt-3 flex items-center gap-2 text-xs text-red-600 bg-red-50 px-3 py-2 rounded-lg">
            <i class="ti ti-alert-circle text-sm"></i>
            <span id="pdf-error-msg"></span>
        </div>

        <div id="pdf-loading" class="hidden mt-3 flex items-center gap-2 text-xs text-[#1a3c6e]">
            <i class="ti ti-loader-2 text-sm animate-spin"></i>
            <span>Extraction en cours...</span>
        </div>
    </div>

    {{-- ══ FORMULAIRE SÉANCE ══ --}}
    <form method="POST" action="{{ route('professeur.cahiers.seances.store', $cahier) }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Matière <span class="text-red-500">*</span></label>
                <select name="matiere_id"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 bg-white">
                    <option value="">-- Choisir --</option>
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                            {{ $matiere->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                <input type="date" name="date_seance" value="{{ old('date_seance') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Heure de début <span class="text-red-500">*</span></label>
                <input type="time" name="heure_debut" value="{{ old('heure_debut') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Heure de fin <span class="text-red-500">*</span></label>
                <input type="time" name="heure_fin" value="{{ old('heure_fin') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
            </div>
        </div>

        {{-- Titre du module avec dropdown PDF --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Titre du module / chapitre <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="text" name="titre_module" id="titre-module"
                       value="{{ old('titre_module') }}"
                       placeholder="Ex: Chapitre 3 — Les structures de données"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 pr-10">
                <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm" id="icon-dropdown"></i>
            </div>

            {{-- Dropdown titres extraits --}}
            <div id="dropdown-titres" class="hidden mt-1 border border-blue-100 rounded-xl overflow-hidden shadow-lg bg-white max-h-48 overflow-y-auto z-50">
                <div class="px-3 py-2 bg-blue-50 text-xs font-semibold text-[#1a3c6e] border-b border-blue-100">
                    Titres extraits du PDF — cliquez pour sélectionner
                </div>
                <div id="dropdown-list"></div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Contenu traité <span class="text-red-500">*</span>
            </label>
            <textarea name="contenu" rows="4"
                      placeholder="Décrivez les points abordés durant cette séance..."
                      class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 resize-none">{{ old('contenu') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                <i class="ti ti-check text-base"></i> Enregistrer la séance
            </button>
        </div>
    </form>
</div>
@endif

{{-- ══ SÉANCES ENREGISTRÉES ══ --}}
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-semibold text-[#1a2b4a]">Séances enregistrées</h3>
        <span class="bg-[#1a3c6e] text-white text-xs font-bold px-2.5 py-1 rounded-full">
            {{ $cahier->seances->count() }}
        </span>
    </div>

    @forelse($cahier->seances->sortByDesc('date_seance') as $seance)
        <div class="px-5 py-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="ti ti-notebook text-base text-[#1a3c6e]"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#1a2b4a]">{{ $seance->titre_module }}</p>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                            <span class="text-xs text-gray-400">
                                📚 {{ $seance->matiere?->nom ?? '—' }}
                            </span>
                            <span class="text-xs text-gray-400">
                                📅 {{ \Carbon\Carbon::parse($seance->date_seance)->format('d/m/Y') }}
                            </span>
                            <span class="text-xs text-gray-400">
                                🕐 {{ substr($seance->heure_debut, 0, 5) }} → {{ substr($seance->heure_fin, 0, 5) }}
                            </span>
                            <span class="text-xs text-gray-400">
                                👤 {{ $seance->user->nom }} {{ $seance->user->prenoms }}
                            </span>
                        </div>
                        @if($seance->contenu)
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ Str::limit($seance->contenu, 120) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-16 text-gray-400">
            <i class="ti ti-notebook text-5xl block mb-4 text-gray-200"></i>
            <p class="text-sm font-medium text-gray-500">Aucune séance enregistrée</p>
            <p class="text-xs mt-1">Les séances ajoutées apparaîtront ici.</p>
        </div>
    @endforelse
</div>

<script>
const CSRF = '{{ csrf_token() }}';
const ROUTE_PDF = '{{ route('professeur.cahiers.extraire-pdf') }}';

async function extrairePdf() {
    const input   = document.getElementById('pdf-input');
    const loading = document.getElementById('pdf-loading');
    const result  = document.getElementById('pdf-result');
    const error   = document.getElementById('pdf-error');
    const btn     = document.getElementById('btn-extraire');

    if (!input.files[0]) {
        alert('Veuillez sélectionner un fichier PDF.');
        return;
    }

    // Reset
    result.classList.add('hidden');
    error.classList.add('hidden');
    loading.classList.remove('hidden');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('pdf', input.files[0]);
    formData.append('_token', CSRF);

    try {
        const response = await fetch(ROUTE_PDF, { method: 'POST', body: formData });
        const data     = await response.json();

        loading.classList.add('hidden');
        btn.disabled = false;

        if (data.success) {
            document.getElementById('pdf-count').textContent =
                `${data.count} titre(s) détecté(s) — cliquez pour sélectionner`;

            const list = document.getElementById('titres-list');
            list.innerHTML = '';
            const dropList = document.getElementById('dropdown-list');
            dropList.innerHTML = '';

            data.titres.forEach(titre => {
                // Dans la liste de résultats
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'w-full text-left px-3 py-2 text-xs text-[#1a3c6e] hover:bg-blue-50 rounded-lg transition-colors border border-transparent hover:border-blue-100 mb-0.5';
                item.textContent = titre;
                item.onclick = () => selectionnerTitre(titre);
                list.appendChild(item);

                // Dans le dropdown du champ titre
                const opt = document.createElement('button');
                opt.type = 'button';
                opt.className = 'w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1a3c6e] transition-colors border-b border-gray-100 last:border-0';
                opt.textContent = titre;
                opt.onclick = () => selectionnerTitre(titre);
                dropList.appendChild(opt);
            });

            result.classList.remove('hidden');
            document.getElementById('dropdown-titres').classList.remove('hidden');
        } else {
            document.getElementById('pdf-error-msg').textContent = data.message;
            error.classList.remove('hidden');
        }
    } catch (e) {
        loading.classList.add('hidden');
        btn.disabled = false;
        document.getElementById('pdf-error-msg').textContent = 'Erreur de connexion. Réessayez.';
        error.classList.remove('hidden');
    }
}

function selectionnerTitre(titre) {
    document.getElementById('titre-module').value = titre;
    document.getElementById('dropdown-titres').classList.add('hidden');
    document.getElementById('titre-module').focus();
}

function cacherResultats() {
    document.getElementById('pdf-result').classList.add('hidden');
}

// Fermer dropdown en cliquant ailleurs
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('dropdown-titres');
    const input    = document.getElementById('titre-module');
    if (!dropdown.contains(e.target) && e.target !== input) {
        dropdown.classList.add('hidden');
    }
});
</script>
@endsection