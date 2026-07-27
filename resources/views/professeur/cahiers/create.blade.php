@extends('layouts.app')
@section('title', 'Nouveau cahier')
@section('page-title', 'Nouveau cahier')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-notebook-plus text-3xl text-white"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Nouveau cahier</h2>
            <p class="text-white/70 text-sm mt-0.5">Créer un cahier de texte</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <form method="POST" action="{{ route('professeur.cahiers.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Classe <span class="text-red-500">*</span></label>
                <select name="classe_id"
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 bg-white">
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }} — {{ $classe->filiere }}
                        </option>
                    @endforeach
                </select>
                @error('classe_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Année académique <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <i class="ti ti-calendar text-[#1a3c6e] text-base"></i>
                    </div>
                    <input type="text" name="annee_academique"
                           value="{{ old('annee_academique', date('Y') . '-' . (date('Y')+1)) }}"
                           placeholder="Ex: 2025-2026"
                           class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                </div>
                @error('annee_academique')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                    <i class="ti ti-check text-base"></i> Créer le cahier
                </button>
                <a href="{{ route('professeur.cahiers.index') }}"
                   class="flex items-center justify-center gap-2 px-5 py-3 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">
                    <i class="ti ti-arrow-left text-base"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection