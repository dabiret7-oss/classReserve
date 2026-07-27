@extends('layouts.app')
@section('title', 'Mes cahiers de texte')
@section('page-title', 'Cahiers de texte')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center gap-4">
    <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center">
        <i class="ti ti-notebook text-3xl text-white"></i>
    </div>
    <div>
        <h2 class="text-xl font-bold text-white">Cahiers de texte</h2>
        <p class="text-white/70 text-sm mt-0.5">Accédez aux cahiers de vos classes</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Cahiers avec accès validé --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-[#1a2b4a]">Mes cahiers accessibles</h3>
            <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-full">
                {{ $cahiersAcces->count() }}
            </span>
        </div>

        @forelse($cahiersAcces as $acces)
            <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                        <i class="ti ti-notebook text-lg text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#1a2b4a]">{{ $acces->cahier->classe->nom }}</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-gray-400">{{ $acces->cahier->annee_academique }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <span class="text-xs text-gray-400">{{ $acces->cahier->seances->count() }} séance(s)</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('professeur.cahiers.show', $acces->cahier) }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 bg-[#1a3c6e] text-white text-xs font-medium rounded-lg hover:bg-blue-900 transition-colors">
                    <i class="ti ti-eye text-sm"></i> Ouvrir
                </a>
            </div>
        @empty
            <div class="text-center py-10 text-gray-400">
                <i class="ti ti-notebook text-3xl block mb-2 text-gray-200"></i>
                <p class="text-sm">Aucun cahier accessible pour le moment.</p>
            </div>
        @endforelse
    </div>

    {{-- Demander accès --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-[#1a2b4a]">Demander l'accès à un cahier</h3>
            <p class="text-xs text-gray-400 mt-0.5">Sélectionnez un cahier pour faire une demande</p>
        </div>
        <div class="p-5">
            @forelse($cahiersSansAcces as $cahier)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <i class="ti ti-lock text-sm text-[#1a3c6e]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#1a2b4a]">{{ $cahier->classe->nom }}</p>
                            <span class="text-xs text-gray-400">{{ $cahier->annee_academique }}</span>
                        </div>
                    </div>

                    @php
                        $demandeEnCours = $cahier->acces->where('user_id', auth()->id())->where('statut','en_attente')->isNotEmpty();
                    @endphp

                    @if($demandeEnCours)
                        <span class="bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                            En attente
                        </span>
                    @else
                        <form method="POST" action="{{ route('professeur.cahiers.acces', $cahier) }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-1.5 px-3 py-1.5 border border-[#1a3c6e] text-[#1a3c6e] text-xs font-medium rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="ti ti-key text-sm"></i> Demander accès
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="ti ti-check text-3xl block mb-2 text-green-200"></i>
                    <p class="text-sm">Vous avez accès à tous les cahiers disponibles.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection