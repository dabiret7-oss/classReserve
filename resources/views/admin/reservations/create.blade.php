@extends('layouts.app')
@section('title', 'Attribuer une salle')
@section('page-title', 'Attribuer une salle')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-calendar-plus text-3xl text-white"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Attribuer une salle</h2>
            <p class="text-white/70 text-sm mt-0.5">Réservation simple, longue période ou activité externe</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-6">
            <form method="POST" action="{{ route('admin.reservations.store') }}" class="space-y-5">
                @csrf

                {{-- ══ TYPE DE RÉSERVATION ══ --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Type de réservation</label>
                    <div class="grid grid-cols-3 gap-3">

                        {{-- Simple --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="type_reservation" value="simple"
                                   checked onchange="setType('simple')" class="peer sr-only">
                            <div id="card-simple"
                                 class="border-2 border-[#1a3c6e] bg-blue-50 rounded-xl p-3 text-center transition-all hover:border-gray-300">
                                <i class="ti ti-calendar-event text-2xl text-[#1a3c6e] block mb-1.5"></i>
                                <p class="text-xs font-semibold text-[#1a3c6e]">Simple</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Un seul jour</p>
                            </div>
                        </label>

                        {{-- Longue période --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="type_reservation" value="longue"
                                   onchange="setType('longue')" class="peer sr-only">
                            <div id="card-longue"
                                 class="border-2 border-gray-200 rounded-xl p-3 text-center transition-all hover:border-gray-300">
                                <i class="ti ti-calendar-week text-2xl text-gray-400 block mb-1.5" id="icon-longue"></i>
                                <p class="text-xs font-semibold text-gray-500" id="text-longue">Longue période</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Plusieurs jours</p>
                            </div>
                        </label>

                        {{-- Activité externe --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="type_reservation" value="externe"
                                   onchange="setType('externe')" class="peer sr-only">
                            <div id="card-externe"
                                 class="border-2 border-gray-200 rounded-xl p-3 text-center transition-all hover:border-gray-300">
                                <i class="ti ti-calendar-off text-2xl text-gray-400 block mb-1.5" id="icon-externe"></i>
                                <p class="text-xs font-semibold text-gray-500" id="text-externe">Activité externe</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">Sans professeur</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- ══ INFO CONTEXTUELLE ══ --}}
                <div id="info-box" class="hidden rounded-xl px-4 py-3 flex items-start gap-3 text-xs leading-relaxed"></div>

                <div class="border-t border-gray-100 pt-5 space-y-5">

                    {{-- Professeur (masqué pour activité externe) --}}
                    <div id="bloc-professeur">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Professeur <span id="prof-required" class="text-red-500">*</span>
                            <span id="prof-optional" class="hidden text-gray-400 font-normal text-xs">(optionnel)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="ti ti-user text-[#1a3c6e] text-base"></i>
                            </div>
                            <select name="user_id" id="select-professeur"
                                    class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 bg-white">
                                <option value="">-- Choisir un professeur --</option>
                                @foreach($professeurs as $prof)
                                    <option value="{{ $prof->id }}" {{ old('user_id') == $prof->id ? 'selected' : '' }}>
                                        {{ $prof->nom }} {{ $prof->prenoms }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Salle --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Salle <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="ti ti-building text-[#1a3c6e] text-base"></i>
                            </div>
                            <select name="salle_id"
                                    class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 bg-white">
                                <option value="">-- Choisir une salle --</option>
                                @foreach($salles as $salle)
                                    <option value="{{ $salle->id }}" {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                                        {{ $salle->nom }} — {{ $salle->niveau }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Matière + Classe (masqués pour activité externe) --}}
                    <div id="bloc-matiere-classe" class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Matière <span class="text-gray-400 font-normal text-xs">(optionnel)</span></label>
                            <select name="matiere_id"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 bg-white">
                                <option value="">-- Choisir --</option>
                                @foreach(\App\Models\Matiere::orderBy('nom')->get() as $m)
                                    <option value="{{ $m->id }}" {{ old('matiere_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Classe <span class="text-gray-400 font-normal text-xs">(optionnel)</span></label>
                            <select name="classe_id"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 bg-white">
                                <option value="">-- Choisir --</option>
                                @foreach(\App\Models\Classe::orderBy('nom')->get() as $c)
                                    <option value="{{ $c->id }}" {{ old('classe_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Motif --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Motif <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="ti ti-file-text text-[#1a3c6e] text-base"></i>
                            </div>
                            <input type="text" name="motif" value="{{ old('motif') }}"
                                   placeholder="Ex: Cours de maths, Réunion pédagogique, Examen..."
                                   class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2" id="label-date-debut">
                                    Date et heure de début <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                        <i class="ti ti-calendar text-[#1a3c6e] text-base"></i>
                                    </div>
                                    <input type="datetime-local" name="date_debut"
                                           value="{{ old('date_debut') }}"
                                           class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Heure de fin <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                        <i class="ti ti-clock text-[#1a3c6e] text-base"></i>
                                    </div>
                                    <input type="time" name="heure_fin" value="{{ old('heure_fin') }}"
                                           class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                                </div>
                            </div>
                        </div>

                        {{-- Date fin période --}}
                        <div id="date-fin-container" class="hidden">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Date de fin de période <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                                    <i class="ti ti-calendar-event text-orange-500 text-base"></i>
                                </div>
                                <input type="date" name="date_fin_periode"
                                       value="{{ old('date_fin_periode') }}"
                                       class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                            </div>
                            <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                                <i class="ti ti-info-circle text-xs"></i>
                                Une réservation sera créée pour chaque jour de la période.
                            </p>
                        </div>
                    </div>

                    {{-- Champ caché type --}}
                    <input type="hidden" name="longue_periode" id="input-longue" value="0">
                    <input type="hidden" name="activite_externe" id="input-externe" value="0">
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
</div>

<script>
const cards = {
    simple:  { id: 'card-simple',  icon: 'ti-calendar-event',  color: 'text-[#1a3c6e]', text: 'Simple' },
    longue:  { id: 'card-longue',  icon: 'ti-calendar-week',   color: 'text-[#1a3c6e]', text: 'Longue période' },
    externe: { id: 'card-externe', icon: 'ti-calendar-off',    color: 'text-orange-500', text: 'Activité externe' },
};

function setType(type) {
    // Reset toutes les cards
    Object.keys(cards).forEach(k => {
        const card = document.getElementById('card-' + k);
        card.classList.remove('border-[#1a3c6e]', 'bg-blue-50', 'border-orange-400', 'bg-orange-50');
        card.classList.add('border-gray-200');
    });

    // Activer la card sélectionnée
    const card = document.getElementById('card-' + type);
    if (type === 'externe') {
        card.classList.add('border-orange-400', 'bg-orange-50');
    } else {
        card.classList.add('border-[#1a3c6e]', 'bg-blue-50');
    }
    card.classList.remove('border-gray-200');

    // Gérer les blocs
    const dateFin      = document.getElementById('date-fin-container');
    const infoBox      = document.getElementById('info-box');
    const blocProf     = document.getElementById('bloc-professeur');
    const blocMat      = document.getElementById('bloc-matiere-classe');
    const profReq      = document.getElementById('prof-required');
    const profOpt      = document.getElementById('prof-optional');
    const selectProf   = document.getElementById('select-professeur');
    const btnText      = document.getElementById('btn-text');

    // Reset
    dateFin.classList.add('hidden');
    infoBox.classList.add('hidden');
    document.getElementById('input-longue').value = '0';
    document.getElementById('input-externe').value = '0';
    blocProf.classList.remove('hidden');
    blocMat.classList.remove('hidden');
    profReq.classList.remove('hidden');
    profOpt.classList.add('hidden');
    selectProf.required = true;
    btnText.textContent = 'Attribuer la salle';

    if (type === 'longue') {
        dateFin.classList.remove('hidden');
        document.getElementById('input-longue').value = '1';
        btnText.textContent = 'Créer les réservations';
        infoBox.className = 'rounded-xl px-4 py-3 flex items-start gap-3 text-xs leading-relaxed bg-blue-50 border border-blue-100';
        infoBox.innerHTML = `<i class="ti ti-calendar-week text-[#1a3c6e] text-lg flex-shrink-0 mt-0.5"></i>
            <div class="text-blue-800"><strong>Longue période :</strong> une réservation est créée automatiquement pour chaque jour entre la date de début et la date de fin, avec la même heure. Les conflits sont vérifiés pour chaque jour.</div>`;
        infoBox.classList.remove('hidden');

    } else if (type === 'externe') {
        document.getElementById('input-externe').value = '1';
        blocProf.classList.add('hidden');
        blocMat.classList.add('hidden');
        selectProf.required = false;
        btnText.textContent = 'Réserver la salle';
        infoBox.className = 'rounded-xl px-4 py-3 flex items-start gap-3 text-xs leading-relaxed bg-orange-50 border border-orange-100';
        infoBox.innerHTML = `<i class="ti ti-info-circle text-orange-600 text-lg flex-shrink-0 mt-0.5"></i>
            <div class="text-orange-800"><strong>Activité externe :</strong> la salle est réservée sans être liée à un professeur ni à un cours. Utile pour les réunions, examens, événements extérieurs, etc.</div>`;
        infoBox.classList.remove('hidden');
    }
}
</script>
@endsection