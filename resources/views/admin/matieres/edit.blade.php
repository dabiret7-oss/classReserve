@extends('layouts.app')
@section('title', 'Modifier la matière')
@section('page-title', 'Modifier la matière')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-pencil text-3xl text-white"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Modifier la matière</h2>
            <p class="text-white/70 text-sm mt-0.5">{{ $matiere->nom }} — <code class="font-mono">{{ $matiere->code }}</code></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
            <i class="ti ti-info-circle text-gray-400"></i>
            <p class="text-xs text-gray-500">Le code sera régénéré automatiquement si vous modifiez le nom.</p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.matieres.update', $matiere) }}" class="space-y-5">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom de la matière <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-book text-[#1a3c6e] text-base"></i>
                        </div>
                        <input type="text" name="nom" value="{{ old('nom', $matiere->nom) }}"
                               class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                    </div>
                    @error('nom')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 py-3 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                        <i class="ti ti-check text-base"></i> Enregistrer
                    </button>
                    <a href="{{ route('admin.matieres.index') }}"
                       class="flex items-center justify-center gap-2 px-5 py-3 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">
                        <i class="ti ti-arrow-left text-base"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection