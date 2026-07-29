@extends('layouts.app')
@section('title', 'Nouvelle réservation')
@section('page-title', 'Nouvelle réservation')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-calendar-plus text-3xl text-white"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Nouvelle réservation</h2>
            <p class="text-white/70 text-sm mt-0.5">Demandez une salle pour votre cours</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="{{ route('professeur.reservations.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
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
                    @error('salle_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Matière <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-book text-[#1a3c6e] text-base"></i>
                        </div>
                        <select name="matiere_id"
                                class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">-- Choisir une matière --</option>
                            @foreach($matieres as $matiere)
                                <option value="{{ $matiere->id }}" {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                    {{ $matiere->nom }} ({{ $matiere->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('matiere_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Classe <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-school text-[#1a3c6e] text-base"></i>
                        </div>
                        <select name="classe_id"
                                class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 bg-white">
                            <option value="">-- Choisir une classe --</option>
                            @foreach($classes as $classe)
                                <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->nom }} — {{ $classe->filiere }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('classe_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Titre du cours <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-file-text text-[#1a3c6e] text-base"></i>
                        </div>
                        <input type="text" name="motif" value="{{ old('motif') }}"
                               placeholder="Ex: Introduction aux algorithmes"
                               class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                    </div>
                    @error('motif')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Dates et heures --}}
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-4">
                <div class="flex items-center gap-2">
                    <i class="ti ti-clock text-[#1a3c6e]"></i>
                    <span class="text-sm font-semibold text-[#1a2b4a]">Horaires</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_cours" id="date_cours"
                               value="{{ old('date_cours') }}"
                               onchange="calculerHeureFin()"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                        @error('date_cours')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">
                            Heure de début <span class="text-red-500">*</span>
                        </label>
                        <select name="heure_debut" id="heure_debut"
                                onchange="calculerHeureFin()"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                            <option value="">-- Heure --</option>
                            @php
                                for ($h = 7; $h <= 20; $h++) {
                                    echo "<option value=\"" . sprintf('%02d', $h) . ":00\">" . sprintf('%02d', $h) . "h00</option>";
                                    echo "<option value=\"" . sprintf('%02d', $h) . ":30\">" . sprintf('%02d', $h) . "h30</option>";
                                }
                            @endphp
                        </select>
                        @error('heure_debut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">
                            Durée <span class="text-red-500">*</span>
                        </label>
                        <select name="duree" id="duree"
                                onchange="calculerHeureFin()"
                                class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                            <option value="">-- Durée --</option>
                            @foreach(['1'=>'1h00','1.5'=>'1h30','2'=>'2h00','2.5'=>'2h30','3'=>'3h00','3.5'=>'3h30','4'=>'4h00'] as $val => $label)
                                <option value="{{ $val }}" {{ old('duree') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('duree')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Heure de fin calculée --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Heure de fin (calculée)</label>
                    <div id="heure_fin_affichage"
                         class="px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-400 font-medium">
                        — sélectionnez une heure de début et une durée
                    </div>
                    <input type="hidden" name="date_debut" id="date_debut">
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                    <i class="ti ti-send text-base"></i> Envoyer la demande
                </button>
                <a href="{{ route('professeur.reservations.index') }}"
                   class="flex items-center justify-center gap-2 px-5 py-3 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">
                    <i class="ti ti-arrow-left text-base"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function calculerHeureFin() {
    const date  = document.getElementById('date_cours').value;
    const heure = document.getElementById('heure_debut').value;
    const duree = parseFloat(document.getElementById('duree').value);
    const affichage = document.getElementById('heure_fin_affichage');
    const hidden    = document.getElementById('date_debut');

    if (!date || !heure || isNaN(duree)) {
        affichage.textContent = '— sélectionnez une heure de début et une durée';
        affichage.className = 'px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-400 font-medium';
        hidden.value = '';
        return;
    }

    hidden.value = date + ' ' + heure + ':00';

    const [h, m] = heure.split(':').map(Number);
    const totalMin = h * 60 + m + duree * 60;
    const finH = Math.floor(totalMin / 60);
    const finM = totalMin % 60;
    const finStr = String(finH).padStart(2,'0') + 'h' + String(finM).padStart(2,'0');

    affichage.textContent = '🕐 Fin prévue à ' + finStr;
    affichage.className = 'px-4 py-2.5 bg-blue-50 border border-[#1a3c6e] rounded-lg text-sm text-[#1a3c6e] font-semibold';
}

document.getElementById('date_cours').addEventListener('change', calculerHeureFin);
</script>
@endsection