@extends('layouts.app')
@section('title', 'Tableau de bord Admin')
@section('page-title', 'Tableau de bord')

@section('content')
@php
    $nbEnAttente  = \App\Models\User::where('role','professeur')->where('statut','en_cours')->count();
    $nbValides    = \App\Models\User::where('role','professeur')->where('statut','valide')->count();
    $nbSalles     = \App\Models\Salle::where('statut','active')->count();
    $nbResa       = \App\Models\Reservation::where('statut','en_attente')->count();
    $nbResaTotal  = \App\Models\Reservation::count();
    $nbValidees   = \App\Models\Reservation::where('statut','validee')->count();
    $nbRejetees   = \App\Models\Reservation::where('statut','rejetee')->count();
    $pctValidees  = $nbResaTotal > 0 ? round($nbValidees/$nbResaTotal*100) : 0;
    $pctAttente   = $nbResaTotal > 0 ? round($nbResa/$nbResaTotal*100) : 0;
    $pctRejetees  = $nbResaTotal > 0 ? round($nbRejetees/$nbResaTotal*100) : 0;
    $pendingUsers = \App\Models\User::where('role','professeur')->where('statut','en_cours')->latest()->take(4)->get();
    $pendingResas = \App\Models\Reservation::where('statut','en_attente')->with(['user','salle'])->latest()->take(4)->get();
    $cahiersPending = \App\Models\CahierAcces::where('statut','en_attente')->count();
    $sallesOccupation = \App\Models\Salle::where('statut','active')
        ->withCount(['reservations as nb_validees' => fn($q) => $q->where('statut','validee')])
        ->orderByDesc('nb_validees')->take(5)->get();
    $maxOcc = $sallesOccupation->max('nb_validees') ?: 1;
    $colors = ['bg-[#1a3c6e]','bg-red-700','bg-orange-500','bg-green-600','bg-purple-600'];
@endphp

{{-- ══ CARTES STATS ══ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl border border-gray-200 p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-[#1a3c6e] rounded-t-xl"></div>
        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
            <i class="ti ti-user-clock text-xl text-[#1a3c6e]"></i>
        </div>
        <div class="text-3xl font-bold text-[#1a2b4a]">{{ $nbEnAttente }}</div>
        <div class="text-sm text-gray-500 mt-1">Comptes en attente</div>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-1 text-xs text-[#1a3c6e] font-medium mt-3 hover:underline">
            Gérer <i class="ti ti-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-green-500 rounded-t-xl"></div>
        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center mb-3">
            <i class="ti ti-user-check text-xl text-green-600"></i>
        </div>
        <div class="text-3xl font-bold text-[#1a2b4a]">{{ $nbValides }}</div>
        <div class="text-sm text-gray-500 mt-1">Professeurs actifs</div>
        <a href="{{ route('admin.users.index') }}"
           class="inline-flex items-center gap-1 text-xs text-green-600 font-medium mt-3 hover:underline">
            Voir tout <i class="ti ti-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-orange-500 rounded-t-xl"></div>
        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center mb-3">
            <i class="ti ti-building text-xl text-orange-500"></i>
        </div>
        <div class="text-3xl font-bold text-[#1a2b4a]">{{ $nbSalles }}</div>
        <div class="text-sm text-gray-500 mt-1">Salles actives</div>
        <a href="{{ route('admin.salles.index') }}"
           class="inline-flex items-center gap-1 text-xs text-orange-500 font-medium mt-3 hover:underline">
            Gérer <i class="ti ti-arrow-right text-xs"></i>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-red-600 rounded-t-xl"></div>
        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center mb-3">
            <i class="ti ti-clock text-xl text-red-600"></i>
        </div>
        <div class="text-3xl font-bold text-[#1a2b4a]">{{ $nbResa }}</div>
        <div class="text-sm text-gray-500 mt-1">Réservations en attente</div>
        <a href="{{ route('admin.reservations.index') }}"
           class="inline-flex items-center gap-1 text-xs text-red-600 font-medium mt-3 hover:underline">
            Traiter <i class="ti ti-arrow-right text-xs"></i>
        </a>
    </div>
</div>

{{-- ══ LIGNE 2 ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

    {{-- Réservations en attente --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-semibold text-[#1a2b4a]">Réservations en attente</h2>
                <p class="text-xs text-gray-400 mt-0.5">À valider ou rejeter</p>
            </div>
            <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                {{ $nbResa }} en attente
            </span>
        </div>

        @forelse($pendingResas as $resa)
            <div class="flex items-center gap-3 py-3 border-b border-gray-100 last:border-0">
                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-xs font-bold text-[#1a3c6e] flex-shrink-0">
                    {{ strtoupper(substr($resa->user->nom,0,1)) }}{{ strtoupper(substr($resa->user->prenoms,0,1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-[#1a2b4a] truncate">{{ $resa->user->nom }} {{ $resa->user->prenoms }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $resa->salle->nom }} — {{ \Carbon\Carbon::parse($resa->date_debut)->format('d/m/Y à H\hi') }}</p>
                </div>
                <div class="flex gap-1.5">
                    <form method="POST" action="{{ route('admin.reservations.valider', $resa) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="salle_id" value="{{ $resa->salle_id }}">
                        <button title="Valider"
                                class="w-8 h-8 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 flex items-center justify-center transition-colors">
                            <i class="ti ti-check text-base"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reservations.rejeter', $resa) }}">
                        @csrf @method('PATCH')
                        <button title="Rejeter"
                                class="w-8 h-8 rounded-lg bg-red-50 text-red-700 hover:bg-red-100 flex items-center justify-center transition-colors">
                            <i class="ti ti-x text-base"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-400">
                <i class="ti ti-check text-3xl block mb-2 text-green-400"></i>
                <p class="text-sm">Aucune réservation en attente</p>
            </div>
        @endforelse

        @if($nbResa > 4)
            <a href="{{ route('admin.reservations.index') }}"
               class="block text-center text-xs text-[#1a3c6e] mt-3 pt-3 border-t border-gray-100 hover:underline">
                Voir toutes les réservations →
            </a>
        @endif
    </div>

    {{-- Occupation des salles --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="mb-4">
            <h2 class="text-base font-semibold text-[#1a2b4a]">Occupation des salles</h2>
            <p class="text-xs text-gray-400 mt-0.5">Réservations validées par salle</p>
        </div>
        @foreach($sallesOccupation as $i => $salle)
            <div class="mb-4 last:mb-0">
                <div class="flex justify-between items-center mb-1.5">
                    <span class="text-sm font-medium text-gray-700">{{ $salle->nom }}</span>
                    <span class="text-xs text-gray-400">{{ $salle->nb_validees }} cours</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="{{ $colors[$i % count($colors)] }} h-full rounded-full transition-all duration-500"
                         style="width: {{ $maxOcc > 0 ? round($salle->nb_validees/$maxOcc*100) : 0 }}%"></div>
                </div>
            </div>
        @endforeach
        <a href="{{ route('admin.salles.index') }}"
           class="block text-center text-xs text-[#1a3c6e] mt-4 pt-3 border-t border-gray-100 hover:underline">
            Gérer les salles →
        </a>
    </div>
</div>

{{-- ══ LIGNE 3 ══ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Comptes en attente --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-[#1a2b4a]">Comptes en attente</h2>
            <span class="bg-blue-50 text-[#1a3c6e] text-xs font-semibold px-2.5 py-1 rounded-full">{{ $nbEnAttente }}</span>
        </div>
        @forelse($pendingUsers as $u)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-100 last:border-0">
                <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-xs font-bold text-[#1a3c6e] flex-shrink-0">
                    {{ strtoupper(substr($u->nom,0,1)) }}{{ strtoupper(substr($u->prenoms,0,1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-[#1a2b4a] truncate">{{ $u->nom }} {{ $u->prenoms }}</p>
                    <p class="text-xs text-gray-400">{{ $u->created_at->diffForHumans() }}</p>
                </div>
                <form method="POST" action="{{ route('admin.users.validate', $u) }}">
                    @csrf @method('PATCH')
                    <button title="Valider"
                            class="w-8 h-8 rounded-lg bg-[#1a3c6e] text-white hover:bg-blue-900 flex items-center justify-center transition-colors">
                        <i class="ti ti-check text-sm"></i>
                    </button>
                </form>
            </div>
        @empty
            <div class="text-center py-6 text-gray-400">
                <i class="ti ti-users text-2xl block mb-1"></i>
                <p class="text-xs">Aucun compte en attente</p>
            </div>
        @endforelse
        <a href="{{ route('admin.users.index') }}"
           class="block text-center text-xs text-[#1a3c6e] mt-3 pt-3 border-t border-gray-100 hover:underline">
            Gérer les professeurs →
        </a>
    </div>

    {{-- Répartition réservations --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-base font-semibold text-[#1a2b4a] mb-4">Répartition des réservations</h2>
        <div class="text-center mb-5">
            <div class="text-4xl font-bold text-[#1a2b4a]">{{ $nbResaTotal }}</div>
            <div class="text-xs text-gray-400 mt-1">réservations au total</div>
        </div>
        <div class="h-3 rounded-full overflow-hidden flex mb-4">
            <div class="bg-green-500 h-full" style="width:{{ $pctValidees }}%"></div>
            <div class="bg-orange-400 h-full" style="width:{{ $pctAttente }}%"></div>
            <div class="bg-red-500 h-full" style="width:{{ $pctRejetees }}%"></div>
        </div>
        <div class="space-y-2.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                    <span class="text-sm text-gray-600">Validées</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-[#1a2b4a]">{{ $nbValidees }}</span>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $pctValidees }}%</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-orange-400"></div>
                    <span class="text-sm text-gray-600">En attente</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-[#1a2b4a]">{{ $nbResa }}</span>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $pctAttente }}%</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                    <span class="text-sm text-gray-600">Rejetées</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-[#1a2b4a]">{{ $nbRejetees }}</span>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $pctRejetees }}%</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Cahiers de texte --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="text-base font-semibold text-[#1a2b4a] mb-4">Cahiers de texte</h2>
        @php $cahiers = \App\Models\Cahier::with('classe')->withCount('seances')->latest()->take(4)->get(); @endphp
        @forelse($cahiers as $cahier)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-100 last:border-0">
                <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                    <i class="ti ti-notebook text-base text-green-700"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-[#1a2b4a] truncate">{{ $cahier->classe->nom }}</p>
                    <p class="text-xs text-gray-400">{{ $cahier->annee_academique }} — {{ $cahier->seances_count }} séance(s)</p>
                </div>
                <a href="{{ route('admin.cahiers.show', $cahier) }}"
                   class="w-8 h-8 rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-100 flex items-center justify-center transition-colors">
                    <i class="ti ti-eye text-sm"></i>
                </a>
            </div>
        @empty
            <div class="text-center py-6 text-gray-400">
                <i class="ti ti-notebook text-2xl block mb-1"></i>
                <p class="text-xs">Aucun cahier créé</p>
            </div>
        @endforelse
        @if($cahiersPending > 0)
            <a href="{{ route('admin.cahiers.acces') }}"
               class="flex items-center justify-between bg-amber-50 rounded-lg px-3 py-2.5 mt-3 hover:bg-amber-100 transition-colors">
                <span class="text-xs text-amber-800 font-medium">
                    <i class="ti ti-clock text-xs"></i> {{ $cahiersPending }} demande(s) d'accès
                </span>
                <i class="ti ti-arrow-right text-xs text-amber-700"></i>
            </a>
        @endif
        <a href="{{ route('admin.cahiers.index') }}"
           class="block text-center text-xs text-[#1a3c6e] mt-3 pt-3 border-t border-gray-100 hover:underline">
            Gérer les cahiers →
        </a>
    </div>
</div>
@endsection