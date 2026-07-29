@extends('layouts.app')
@section('title', 'Mon profil')
@section('page-title', 'Mon profil')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
            <span class="text-2xl font-bold text-white">
                {{ strtoupper(substr(auth()->user()->nom,0,1)) }}{{ strtoupper(substr(auth()->user()->prenoms,0,1)) }}
            </span>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">{{ auth()->user()->nom }} {{ auth()->user()->prenoms }}</h2>
            <p class="text-white/70 text-sm mt-0.5">{{ auth()->user()->email }}</p>
            <span class="inline-block bg-white/15 text-white text-xs font-semibold px-2.5 py-1 rounded-full mt-1">
                {{ auth()->user()->isAdmin() ? 'Administrateur' : 'Professeur' }}
            </span>
        </div>
    </div>

    {{-- Modifier informations --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ti ti-user-edit text-[#1a3c6e] text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-[#1a2b4a]">Informations personnelles</h3>
                <p class="text-xs text-gray-400">Modifier vos informations de base</p>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('profil.infos') }}" class="space-y-4">
                @csrf @method('PATCH')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="ti ti-user text-[#1a3c6e] text-base"></i>
                            </div>
                            <input type="text" name="nom"
                                   value="{{ old('nom', auth()->user()->nom) }}"
                                   class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                        </div>
                        @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom(s)</label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                <i class="ti ti-user text-[#1a3c6e] text-base"></i>
                            </div>
                            <input type="text" name="prenoms"
                                   value="{{ old('prenoms', auth()->user()->prenoms) }}"
                                   class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                        </div>
                        @error('prenoms')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse email</label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-mail text-[#1a3c6e] text-base"></i>
                        </div>
                        <input type="email" name="email"
                               value="{{ old('email', auth()->user()->email) }}"
                               class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                    </div>
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                        <i class="ti ti-check text-base"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Changer mot de passe --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center">
                <i class="ti ti-lock text-red-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-[#1a2b4a]">Changer le mot de passe</h3>
                <p class="text-xs text-gray-400">Sécurisez votre compte avec un nouveau mot de passe</p>
            </div>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('profil.password') }}" class="space-y-4">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe actuel</label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                            <i class="ti ti-lock text-red-600 text-base"></i>
                        </div>
                        <input type="password" name="current_password"
                               placeholder="••••••••"
                               class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100">
                    </div>
                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nouveau mot de passe</label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                <i class="ti ti-lock-check text-red-600 text-base"></i>
                            </div>
                            <input type="password" name="password"
                                   placeholder="Min. 6 caractères"
                                   class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100">
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer</label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center">
                                <i class="ti ti-lock-check text-red-600 text-base"></i>
                            </div>
                            <input type="password" name="password_confirmation"
                                   placeholder="Confirmer"
                                   class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100">
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 bg-red-700 text-white text-sm font-semibold rounded-xl hover:bg-red-800 transition-colors">
                        <i class="ti ti-key text-base"></i> Changer le mot de passe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection