@extends('layouts.app')
@section('title', 'Attribuer une salle')
@section('page-title', 'Attribuer une salle')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-calendar-plus text-3xl text-white"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Attribuer une salle</h2>
            <p class="text-white/70 text-sm mt-0.5">Cours ou activité externe — journalier ou longue période</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.reservations.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="longue_periode" id="input-longue" value="0">
            <input type="hidden" name="activite_externe" id="input-externe" value="0">

            {{-- Type motif + Durée --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-category text-[#1a3c6e] text-base"></i>
                        </div>
                        <select id="select-type-motif" onchange="setMotif(this.value)"
                                class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                            <option value="cours">Cours</option>
                            <option value="externe">Activité externe</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durée <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-calendar-week text-[#1a3c6e] text-base"></i>
                        </div>
                        <select id="select-duree" onchange="setDuree(this.value)"
                                class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                            <option value="journaliere">Journalière</option>
                            <option value="periode">Longue période</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Champs cours --}}
            <div id="bloc-cours" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Professeur <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-user text-[#1a3c6e] text-base"></i>
                        </div>
                        <select name="user_id" id="select-prof"
                                class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                            <option value="">-- Choisir --</option>
                            @foreach($professeurs as $prof)
                                <option value="{{ $prof->id }}" {{ old('user_id') == $prof->id ? 'selected' : '' }}>
                                    {{ $prof->nom }} {{ $prof->prenoms }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Matière <span class="text-gray-400 text-xs font-normal">(optionnel)</span></label>
                        <select name="matiere_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                            <option value="">-- Choisir --</option>
                            @foreach(\App\Models\Matiere::orderBy('nom')->get() as $m)
                                <option value="{{ $m->id }}" {{ old('matiere_id') == $m->id ? 'selected' : '' }}>{{ $m->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Classe <span class="text-gray-400 text-xs font-normal">(optionnel)</span></label>
                        <select name="classe_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                            <option value="">-- Choisir --</option>
                            @foreach(\App\Models\Classe::orderBy('nom')->get() as $c)
                                <option value="{{ $c->id }}" {{ old('classe_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Salle --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Salle <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="ti ti-building text-[#1a3c6e] text-base"></i>
                    </div>
                    <select name="salle_id" class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                        <option value="">-- Choisir --</option>
                        @foreach($salles as $salle)
                            <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                {{ $salle->nom }} — {{ $salle->niveau }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Motif --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Motif <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="ti ti-file-text text-[#1a3c6e] text-base"></i>
                    </div>
                    <input type="text" name="motif" value="{{ old('motif') }}"
                           placeholder="Ex: Cours de maths, Réunion, Examen..."
                           class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e]">
                </div>
                @error('motif')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Horaires --}}
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-4">
                <div class="flex items-center gap-2">
                    <i class="ti ti-clock text-[#1a3c6e]"></i>
                    <span class="text-sm font-semibold text-[#1a2b4a]">Horaires</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Date et heure de début <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="date_debut" value="{{ old('date_debut') }}"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                        @error('date_debut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Heure de fin <span class="text-red-500">*</span></label>
                        <input type="time" name="heure_fin" value="{{ old('heure_fin') }}"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                        @error('heure_fin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Longue période --}}
                <div id="bloc-periode" class="hidden space-y-4">

                    {{-- Date de fin --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Date de fin de période <span class="text-red-500">*</span></label>
                        <input type="date" name="date_fin_periode" value="{{ old('date_fin_periode') }}"
                               class="w-full px-3 py-2.5 border border-orange-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 bg-white">
                        @error('date_fin_periode')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Choix des jours --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Jours de réservation <span class="text-red-500">*</span></label>

                        {{-- Raccourcis --}}
                        <div class="flex gap-2 mb-3">
                            <button type="button" onclick="selectJours('ouvrable')"
                                    class="px-3 py-1.5 bg-[#1a3c6e] text-white text-xs font-medium rounded-lg hover:bg-blue-900 transition-colors">
                                Jours ouvrables (Lun→Ven)
                            </button>
                            <button type="button" onclick="selectJours('tous')"
                                    class="px-3 py-1.5 bg-gray-600 text-white text-xs font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                Tous les jours
                            </button>
                            <button type="button" onclick="selectJours('aucun')"
                                    class="px-3 py-1.5 border border-gray-200 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                Effacer
                            </button>
                        </div>

                        {{-- Cases à cocher --}}
                        <div class="grid grid-cols-7 gap-2">
                            @foreach([
                                ['1','Lun'],['2','Mar'],['3','Mer'],
                                ['4','Jeu'],['5','Ven'],['6','Sam'],['0','Dim']
                            ] as [$val, $label])
                            <label class="cursor-pointer text-center">
                                <input type="checkbox" name="jours[]" value="{{ $val }}"
                                       id="jour-{{ $val }}"
                                       class="peer sr-only"
                                       {{ is_array(old('jours')) && in_array($val, old('jours')) ? 'checked' : '' }}>
                                <div class="border-2 border-gray-200 rounded-lg py-2 text-xs font-semibold text-gray-500 transition-all
                                            peer-checked:border-[#1a3c6e] peer-checked:bg-blue-50 peer-checked:text-[#1a3c6e]
                                            hover:border-gray-300 select-none jour-box"
                                     onclick="toggleJour('{{ $val }}')">
                                    {{ $label }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('jours')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-lg px-3 py-2 flex items-start gap-2">
                        <i class="ti ti-info-circle text-[#1a3c6e] text-sm flex-shrink-0 mt-0.5"></i>
                        <p class="text-xs text-blue-800">
                            Une réservation sera créée uniquement pour les jours cochés entre la date de début et la date de fin.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                    <i class="ti ti-check text-base"></i>
                    <span id="btn-text">Attribuer la salle</span>
                </button>
                <a href="{{ route('admin.reservations.index') }}"
                   class="flex items-center justify-center gap-2 px-5 py-3 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    <i class="ti ti-arrow-left text-base"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function setMotif(type) {
    const blocCours  = document.getElementById('bloc-cours');
    const selectProf = document.getElementById('select-prof');
    const inputExt   = document.getElementById('input-externe');
    const btnText    = document.getElementById('btn-text');

    if (type === 'cours') {
        blocCours.classList.remove('hidden');
        selectProf.required = true;
        inputExt.value = '0';
        btnText.textContent = document.getElementById('select-duree').value === 'periode'
            ? 'Créer les réservations' : 'Attribuer la salle';
    } else {
        blocCours.classList.add('hidden');
        selectProf.required = false;
        inputExt.value = '1';
        btnText.textContent = 'Réserver la salle';
    }
}

function setDuree(type) {
    const blocPeriode = document.getElementById('bloc-periode');
    const inputLong   = document.getElementById('input-longue');
    const btnText     = document.getElementById('btn-text');
    const typeMotif   = document.getElementById('select-type-motif').value;

    if (type === 'periode') {
        blocPeriode.classList.remove('hidden');
        inputLong.value = '1';
        btnText.textContent = typeMotif === 'cours' ? 'Créer les réservations' : 'Réserver la salle';
        // Cocher jours ouvrables par défaut
        selectJours('ouvrable');
    } else {
        blocPeriode.classList.add('hidden');
        inputLong.value = '0';
        btnText.textContent = typeMotif === 'cours' ? 'Attribuer la salle' : 'Réserver la salle';
    }
}

function toggleJour(val) {
    const cb  = document.getElementById('jour-' + val);
    cb.checked = !cb.checked;
    const div = cb.nextElementSibling;
    if (cb.checked) {
        div.classList.add('border-[#1a3c6e]', 'bg-blue-50', 'text-[#1a3c6e]');
        div.classList.remove('border-gray-200', 'text-gray-500');
    } else {
        div.classList.remove('border-[#1a3c6e]', 'bg-blue-50', 'text-[#1a3c6e]');
        div.classList.add('border-gray-200', 'text-gray-500');
    }
}

function selectJours(type) {
    const ouvrables = ['1','2','3','4','5'];
    const tous      = ['1','2','3','4','5','6','0'];
    const selects   = type === 'ouvrable' ? ouvrables : type === 'tous' ? tous : [];

    tous.forEach(val => {
        const cb  = document.getElementById('jour-' + val);
        const div = cb.nextElementSibling;
        cb.checked = selects.includes(val);
        if (cb.checked) {
            div.classList.add('border-[#1a3c6e]', 'bg-blue-50', 'text-[#1a3c6e]');
            div.classList.remove('border-gray-200', 'text-gray-500');
        } else {
            div.classList.remove('border-[#1a3c6e]', 'bg-blue-50', 'text-[#1a3c6e]');
            div.classList.add('border-gray-200', 'text-gray-500');
        }
    });
}
</script>
@endsection