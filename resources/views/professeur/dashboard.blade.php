@extends('layouts.app')
@section('title', 'Tableau de bord Professeur')
@section('page-title', 'Tableau de bord')

@section('content')
@php
    $user         = auth()->user();
    $nbResas      = $user->reservations()->count();
    $nbValidees   = $user->reservations()->where('statut','validee')->count();
    $nbEnAttente  = $user->reservations()->where('statut','en_attente')->count();
    $nbRejetees   = $user->reservations()->where('statut','rejetee')->count();
    $dernieres    = $user->reservations()->with(['salle','matiere','classe'])->latest()->take(5)->get();
    $nbCahiers    = \App\Models\CahierAcces::where('user_id',$user->id)->where('statut','valide')->count();
    $prochaine    = $user->reservations()->where('statut','validee')->where('date_debut','>=',now())->with(['salle','matiere'])->orderBy('date_debut')->first();
@endphp

{{-- ══ BIENVENUE ══ --}}
<div class="bg-[#1a3c6e] rounded-xl p-6 mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-white mb-1">
            Bonjour, {{ $user->nom }} {{ $user->prenoms }} 
        </h2>
        <p class="text-white/70 text-sm">Bienvenue sur votre espace de réservation de salles.</p>
    </div>
    <a href="{{ route('professeur.reservations.create') }}"
       class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors flex-shrink-0">
        <i class="ti ti-plus text-base"></i>
        Nouvelle réservation
    </a>
</div>

{{-- ══ PROCHAINE RÉSERVATION ══ --}}
@if($prochaine)
<div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex items-center gap-4">
    <div class="w-12 h-12 rounded-xl bg-[#1a3c6e] flex items-center justify-center flex-shrink-0">
        <i class="ti ti-calendar-event text-xl text-white"></i>
    </div>
    <div class="flex-1">
        <p class="text-xs text-[#1a3c6e] font-semibold uppercase tracking-wide mb-0.5">Prochaine séance</p>
        <p class="text-sm font-bold text-[#1a2b4a]">{{ $prochaine->motif }}</p>
        <p class="text-xs text-gray-500 mt-0.5">
             {{ $prochaine->salle->nom }} — {{ $prochaine->salle->niveau }}
            &nbsp;|&nbsp;
            🕐 {{ \Carbon\Carbon::parse($prochaine->date_debut)->format('d/m/Y à H\hi') }}
        </p>
    </div>
    <span class="bg-blue-100 text-[#1a3c6e] text-xs font-semibold px-3 py-1 rounded-full flex-shrink-0">
        Validée ✓
    </span>
</div>
@endif

{{-- ══ CARTES STATS ══ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-[#1a3c6e] rounded-t-xl"></div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <i class="ti ti-calendar text-xl text-[#1a3c6e]"></i>
        </div>
        <div class="text-3xl font-bold text-[#1a2b4a]">{{ $nbResas }}</div>
        <div class="text-sm text-gray-500 mt-1">Total réservations</div>
        <a href="{{ route('professeur.reservations.index') }}"
           class="inline-flex items-center gap-1 text-xs text-[#1a3c6e] font-medium mt-3 hover:underline">
            Voir tout <i class="ti ti-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-[#1a3c6e] rounded-t-xl"></div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <i class="ti ti-circle-check text-xl text-[#1a3c6e]"></i>
        </div>
        <div class="text-3xl font-bold text-[#1a2b4a]">{{ $nbValidees }}</div>
        <div class="text-sm text-gray-500 mt-1">Validées</div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-[#1a3c6e] rounded-t-xl"></div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <i class="ti ti-clock text-xl text-[#1a3c6e]"></i>
        </div>
        <div class="text-3xl font-bold text-[#1a2b4a]">{{ $nbEnAttente }}</div>
        <div class="text-sm text-gray-500 mt-1">En attente</div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-[#1a3c6e] rounded-t-xl"></div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <i class="ti ti-notebook text-xl text-[#1a3c6e]"></i>
        </div>
        <div class="text-3xl font-bold text-[#1a2b4a]">{{ $nbCahiers }}</div>
        <div class="text-sm text-gray-500 mt-1">Cahiers accessibles</div>
        <a href="{{ route('professeur.cahiers.index') }}"
           class="inline-flex items-center gap-1 text-xs text-[#1a3c6e] font-medium mt-3 hover:underline">
            Accéder <i class="ti ti-arrow-right text-xs"></i>
        </a>
    </div>
</div>

{{-- ══ LIGNE 2 ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Dernières réservations --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-semibold text-[#1a2b4a]">Mes dernières réservations</h2>
                <p class="text-xs text-gray-400 mt-0.5">Historique de vos demandes</p>
            </div>
            <a href="{{ route('professeur.reservations.create') }}"
               class="flex items-center gap-1.5 px-3 py-2 bg-[#1a3c6e] text-white text-xs font-medium rounded-lg hover:bg-blue-900 transition-colors">
                <i class="ti ti-plus text-sm"></i> Réserver
            </a>
        </div>

        @forelse($dernieres as $reservation)
            <div class="flex items-start gap-3 py-3 border-b border-gray-100 last:border-0">
                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="ti ti-building text-base text-[#1a3c6e]"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-[#1a2b4a] truncate">{{ $reservation->motif }}</p>
                    <div class="flex flex-wrap items-center gap-2 mt-0.5">
                        <span class="text-xs text-gray-400">
                             {{ $reservation->salle->nom }} — {{ $reservation->salle->niveau }}
                        </span>
                        @if($reservation->matiere)
                            <span class="text-xs text-gray-400">📚 {{ $reservation->matiere->nom }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">
                        🕐 {{ \Carbon\Carbon::parse($reservation->date_debut)->format('d/m/Y à H\hi') }}
                        → {{ \Carbon\Carbon::parse($reservation->heure_fin)->format('H\hi') }}
                    </p>
                </div>
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    @if($reservation->statut === 'en_attente')
                        <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full">En attente</span>
                        <form method="POST" action="{{ route('professeur.reservations.annuler', $reservation) }}">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Annuler cette réservation ?')"
                                    class="w-7 h-7 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition-colors"
                                    title="Annuler">
                                <i class="ti ti-x text-sm"></i>
                            </button>
                        </form>
                    @elseif($reservation->statut === 'validee')
                        <span class="bg-blue-100 text-[#1a3c6e] text-xs font-semibold px-2.5 py-1 rounded-full">Validée ✓</span>
                        <a href="{{ route('professeur.cahiers.index') }}"
                           class="w-7 h-7 rounded-lg bg-blue-50 text-[#1a3c6e] hover:bg-blue-100 flex items-center justify-center transition-colors"
                           title="Cahiers">
                            <i class="ti ti-notebook text-sm"></i>
                        </a>
                    @else
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full">Rejetée</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-400">
                <i class="ti ti-calendar text-4xl block mb-3 text-gray-200"></i>
                <p class="text-sm">Vous n'avez aucune réservation pour le moment.</p>
                <a href="{{ route('professeur.reservations.create') }}"
                   class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-[#1a3c6e] text-white text-sm rounded-lg hover:bg-blue-900">
                    <i class="ti ti-plus text-sm"></i> Faire une réservation
                </a>
            </div>
        @endforelse

        @if($nbResas > 5)
            <a href="{{ route('professeur.reservations.index') }}"
               class="block text-center text-xs text-[#1a3c6e] mt-3 pt-3 border-t border-gray-100 hover:underline">
                Voir toutes mes réservations →
            </a>
        @endif
    </div>

    {{-- Actions rapides + Stats --}}
    <div class="flex flex-col gap-4">

        {{-- Actions rapides --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-base font-semibold text-[#1a2b4a] mb-4">Actions rapides</h2>
            <div class="space-y-2.5">
                <a href="{{ route('professeur.reservations.create') }}"
                   class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-[#1a3c6e] flex items-center justify-center">
                        <i class="ti ti-plus text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1a2b4a]">Réserver une salle</p>
                        <p class="text-xs text-gray-400">Nouvelle demande</p>
                    </div>
                    <i class="ti ti-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
                <a href="{{ route('professeur.cahiers.index') }}"
                   class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-[#1a3c6e] flex items-center justify-center">
                        <i class="ti ti-notebook text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1a2b4a]">Cahiers de texte</p>
                        <p class="text-xs text-gray-400">{{ $nbCahiers }} accessible(s)</p>
                    </div>
                    <i class="ti ti-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
                <a href="{{ route('professeur.calendrier') }}"
                   class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-[#1a3c6e] flex items-center justify-center">
                        <i class="ti ti-calendar text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1a2b4a]">Voir le calendrier</p>
                        <p class="text-xs text-gray-400">Disponibilités des salles</p>
                    </div>
                    <i class="ti ti-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
                <a href="{{ route('profil.index') }}"
                   class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-gray-500 flex items-center justify-center">
                        <i class="ti ti-user text-white text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#1a2b4a]">Mon profil</p>
                        <p class="text-xs text-gray-400">Modifier mes infos</p>
                    </div>
                    <i class="ti ti-chevron-right text-gray-400 text-sm ml-auto"></i>
                </a>
            </div>
        </div>

        {{-- Mini stats --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="text-base font-semibold text-[#1a2b4a] mb-4">Mes statistiques</h2>
            @if($nbResas > 0)
                <div class="h-2 rounded-full overflow-hidden flex mb-3">
                    <div class="bg-[#1a3c6e] h-full" style="width:{{ round($nbValidees/$nbResas*100) }}%"></div>
                    <div class="bg-[#1a3c6e] h-full" style="width:{{ round($nbEnAttente/$nbResas*100) }}%"></div>
                    <div class="bg-red-500 h-full" style="width:{{ round($nbRejetees/$nbResas*100) }}%"></div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-[#1a3c6e]"></div>
                            <span class="text-gray-600">Validées</span>
                        </div>
                        <span class="font-semibold text-gray-800">{{ $nbValidees }} ({{ round($nbValidees/$nbResas*100) }}%)</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-[#1a3c6e]"></div>
                            <span class="text-gray-600">En attente</span>
                        </div>
                        <span class="font-semibold text-gray-800">{{ $nbEnAttente }} ({{ round($nbEnAttente/$nbResas*100) }}%)</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <span class="text-gray-600">Rejetées</span>
                        </div>
                        <span class="font-semibold text-gray-800">{{ $nbRejetees }} ({{ round($nbRejetees/$nbResas*100) }}%)</span>
                    </div>
                </div>
            @else
                <p class="text-xs text-gray-400 text-center py-4">Aucune statistique disponible</p>
            @endif
        </div>
    </div>
</div>
@endsection