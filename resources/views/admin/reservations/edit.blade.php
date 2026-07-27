@extends('layouts.app')
@section('title', 'Modifier la réservation')
@section('page-title', 'Modifier la réservation')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-pencil text-3xl text-white"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Modifier la réservation</h2>
            <p class="text-white/70 text-sm mt-0.5">{{ $reservation->motif }}</p>
        </div>
        @if($reservation->groupe_id)
            <span class="ml-auto bg-orange-400/20 text-orange-200 text-xs font-semibold px-3 py-1.5 rounded-full border border-orange-400/30 flex-shrink-0">
                Longue période
            </span>
        @endif
    </div>

    @if($reservation->groupe_id)
        <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-5 flex items-start gap-3">
            <i class="ti ti-alert-triangle text-amber-600 text-lg flex-shrink-0 mt-0.5"></i>
            <p class="text-sm text-amber-800">
                Cette réservation fait partie d'un groupe de longue période.
                La modification ne s'applique qu'à <strong>cette journée uniquement</strong>.
            </p>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.reservations.update', $reservation) }}" class="space-y-5">
            @csrf @method('PATCH')

            {{-- Professeur --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Professeur <span class="text-gray-400 font-normal text-xs">(optionnel)</span>
                </label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="ti ti-user text-[#1a3c6e] text-base"></i>
                    </div>
                    <select name="user_id"
                            class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 bg-white">
                        <option value="">-- Aucun (activité externe) --</option>
                        @foreach($professeurs as $prof)
                            <option value="{{ $prof->id }}"
                                {{ old('user_id', $reservation->user_id) == $prof->id ? 'selected' : '' }}>
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
                        @foreach($salles as $salle)
                            <option value="{{ $salle->id }}"
                                {{ old('salle_id', $reservation->salle_id) == $salle->id ? 'selected' : '' }}>
                                {{ $salle->nom }} — {{ $salle->niveau }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('salle_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Matière + Classe --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Matière <span class="text-gray-400 font-normal text-xs">(optionnel)</span>
                    </label>
                    <select name="matiere_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                        <option value="">-- Choisir --</option>
                        @foreach($matieres as $m)
                            <option value="{{ $m->id }}"
                                {{ old('matiere_id', $reservation->matiere_id) == $m->id ? 'selected' : '' }}>
                                {{ $m->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Classe <span class="text-gray-400 font-normal text-xs">(optionnel)</span>
                    </label>
                    <select name="classe_id"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                        <option value="">-- Choisir --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}"
                                {{ old('classe_id', $reservation->classe_id) == $c->id ? 'selected' : '' }}>
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
                    <input type="text" name="motif"
                           value="{{ old('motif', $reservation->motif) }}"
                           class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                </div>
                @error('motif')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Date + Heure --}}
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                <div class="flex items-center gap-2 mb-3">
                    <i class="ti ti-clock text-[#1a3c6e]"></i>
                    <span class="text-sm font-semibold text-[#1a2b4a]">Horaires</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">
                            Date et heure de début <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="date_debut"
                               value="{{ old('date_debut', \Carbon\Carbon::parse($reservation->date_debut)->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                        @error('date_debut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">
                            Heure de fin <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="heure_fin"
                               value="{{ old('heure_fin', substr($reservation->heure_fin, 0, 5)) }}"
                               class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] bg-white">
                        @error('heure_fin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                    <i class="ti ti-check text-base"></i> Enregistrer
                </button>
                <a href="{{ route('admin.reservations.index') }}"
                   class="flex items-center justify-center gap-2 px-5 py-3 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                    <i class="ti ti-arrow-left text-base"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection