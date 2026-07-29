@extends('layouts.app')
@section('title', 'Import CSV')
@section('page-title', 'Import CSV')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center gap-4">
    <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center">
        <i class="ti ti-file-upload text-3xl text-white"></i>
    </div>
    <div>
        <h2 class="text-xl font-bold text-white">Import CSV</h2>
        <p class="text-white/70 text-sm mt-0.5">Importez des salles, matières ou professeurs en masse</p>
    </div>
</div>

{{-- Erreurs d'import --}}
@if(session('erreurs_import') && count(session('erreurs_import')) > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-5">
        <div class="flex items-center gap-2 mb-2">
            <i class="ti ti-alert-triangle text-amber-600 text-lg"></i>
            <span class="text-sm font-semibold text-amber-800">{{ count(session('erreurs_import')) }} ligne(s) ignorée(s) :</span>
        </div>
        <ul class="space-y-1">
            @foreach(session('erreurs_import') as $erreur)
                <li class="text-xs text-amber-700 flex items-center gap-1.5">
                    <i class="ti ti-point text-xs"></i> {{ $erreur }}
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ══ IMPORT SALLES ══ --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ti ti-building text-[#1a3c6e] text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-[#1a2b4a]">Importer des salles</h3>
                <p class="text-xs text-gray-400">Format : nom, niveau</p>
            </div>
        </div>
        <div class="p-5">
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-3 mb-4">
                <p class="text-xs font-semibold text-gray-600 mb-2">Format attendu du CSV :</p>
                <code class="text-xs text-[#1a3c6e] block">nom,niveau</code>
                <code class="text-xs text-gray-500 block">Salle 10,RDC</code>
                <code class="text-xs text-gray-500 block">Amphi B,R+1</code>
                <code class="text-xs text-gray-500 block">Labo Info,R+2</code>
                <p class="text-xs text-gray-400 mt-2">Niveaux valides : RDC, R+1, R+2, R+3</p>
            </div>

            <form method="POST" action="{{ route('admin.import.salles') }}"
                  enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fichier CSV <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="fichier" accept=".csv,.txt"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#1a3c6e] file:text-white">
                </div>
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2.5 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                    <i class="ti ti-upload text-base"></i> Importer les salles
                </button>
            </form>

            <a href="{{ route('admin.salles.index') }}"
               class="block text-center text-xs text-[#1a3c6e] mt-3 hover:underline">
                Voir les salles →
            </a>
        </div>
    </div>

    {{-- ══ IMPORT MATIÈRES ══ --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ti ti-book text-[#1a3c6e] text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-[#1a2b4a]">Importer des matières</h3>
                <p class="text-xs text-gray-400">Format : nom (code auto-généré)</p>
            </div>
        </div>
        <div class="p-5">
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-3 mb-4">
                <p class="text-xs font-semibold text-gray-600 mb-2">Format attendu du CSV :</p>
                <code class="text-xs text-[#1a3c6e] block">nom</code>
                <code class="text-xs text-gray-500 block">Mathématiques</code>
                <code class="text-xs text-gray-500 block">Algorithmique</code>
                <code class="text-xs text-gray-500 block">Bases de données</code>
                <p class="text-xs text-gray-400 mt-2">Le code est généré automatiquement.</p>
            </div>

            <form method="POST" action="{{ route('admin.import.matieres') }}"
                  enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fichier CSV <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="fichier" accept=".csv,.txt"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#1a3c6e] file:text-white">
                </div>
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2.5 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                    <i class="ti ti-upload text-base"></i> Importer les matières
                </button>
            </form>

            <a href="{{ route('admin.matieres.index') }}"
               class="block text-center text-xs text-[#1a3c6e] mt-3 hover:underline">
                Voir les matières →
            </a>
        </div>
    </div>

    {{-- ══ IMPORT PROFESSEURS ══ --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ti ti-users text-[#1a3c6e] text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-[#1a2b4a]">Importer des professeurs</h3>
                <p class="text-xs text-gray-400">Format : nom, prenoms, email, password</p>
            </div>
        </div>
        <div class="p-5">
            <div class="bg-gray-50 rounded-xl border border-gray-200 p-3 mb-4">
                <p class="text-xs font-semibold text-gray-600 mb-2">Format attendu du CSV :</p>
                <code class="text-xs text-[#1a3c6e] block">nom,prenoms,email,password</code>
                <code class="text-xs text-gray-500 block">TRAORE,Issouf,i.traore@hetec.edu,Pass123</code>
                <code class="text-xs text-gray-500 block">KABORE,Jean,j.kabore@hetec.edu,Pass123</code>
                <p class="text-xs text-gray-400 mt-2">Les comptes seront créés avec statut <strong>validé</strong>.</p>
            </div>

            <form method="POST" action="{{ route('admin.import.professeurs') }}"
                  enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fichier CSV <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="fichier" accept=".csv,.txt"
                           class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] bg-white file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#1a3c6e] file:text-white">
                </div>
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2.5 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                    <i class="ti ti-upload text-base"></i> Importer les professeurs
                </button>
            </form>

            <a href="{{ route('admin.users.index') }}"
               class="block text-center text-xs text-[#1a3c6e] mt-3 hover:underline">
                Voir les professeurs →
            </a>
        </div>
    </div>
</div>

{{-- Télécharger modèles CSV --}}
<div class="bg-white rounded-2xl border border-gray-200 p-5 mt-5">
    <h3 class="text-sm font-semibold text-[#1a2b4a] mb-4 flex items-center gap-2">
        <i class="ti ti-download text-[#1a3c6e]"></i>
        Télécharger les modèles CSV
    </h3>
    <div class="grid grid-cols-3 gap-3">
        <a href="{{ route('admin.import.modele', 'salles') }}"
           class="flex items-center gap-2 px-4 py-2.5 border border-[#1a3c6e] text-[#1a3c6e] text-sm font-medium rounded-xl hover:bg-blue-50 transition-colors">
            <i class="ti ti-file-download text-base"></i> Modèle salles.csv
        </a>
        <a href="{{ route('admin.import.modele', 'matieres') }}"
           class="flex items-center gap-2 px-4 py-2.5 border border-[#1a3c6e] text-[#1a3c6e] text-sm font-medium rounded-xl hover:bg-blue-50 transition-colors">
            <i class="ti ti-file-download text-base"></i> Modèle matieres.csv
        </a>
        <a href="{{ route('admin.import.modele', 'professeurs') }}"
           class="flex items-center gap-2 px-4 py-2.5 border border-[#1a3c6e] text-[#1a3c6e] text-sm font-medium rounded-xl hover:bg-blue-50 transition-colors">
            <i class="ti ti-file-download text-base"></i> Modèle professeurs.csv
        </a>
    </div>
</div>

@endsection