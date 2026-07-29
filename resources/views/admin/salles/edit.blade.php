@extends('layouts.app')
@section('title', 'Modifier la salle')
@section('page-title', 'Modifier la salle')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header card --}}
    <div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
                <i class="ti ti-pencil text-3xl text-white"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white">Modifier la salle</h2>
                <p class="text-white/70 text-sm mt-0.5">Mettez à jour les informations de la salle</p>
            </div>
        </div>
        {{-- Badge statut actuel --}}
        <div class="flex-shrink-0">
            @if($salle->isActive())
                <span class="flex items-center gap-1.5 bg-green-500/20 text-green-200 text-xs font-semibold px-3 py-1.5 rounded-full border border-green-400/30">
                    <div class="w-1.5 h-1.5 rounded-full bg-green-400"></div> Active
                </span>
            @else
                <span class="flex items-center gap-1.5 bg-red-500/20 text-red-200 text-xs font-semibold px-3 py-1.5 rounded-full border border-red-400/30">
                    <div class="w-1.5 h-1.5 rounded-full bg-red-400"></div> Inactive
                </span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">

        {{-- Info salle actuelle --}}
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
            <i class="ti ti-info-circle text-gray-400 text-base"></i>
            <p class="text-xs text-gray-500">
                Vous modifiez : <strong class="text-[#1a2b4a]">{{ $salle->nom }}</strong>
                — Niveau actuel : <strong class="text-[#1a2b4a]">{{ $salle->niveau }}</strong>
            </p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.salles.update', $salle) }}" class="space-y-5">
                @csrf @method('PATCH')

                {{-- Nom --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nom de la salle <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-door text-[#1a3c6e] text-base"></i>
                        </div>
                        <input type="text" name="nom" value="{{ old('nom', $salle->nom) }}"
                               placeholder="Ex: Salle 12, Amphi A..."
                               class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 transition-all">
                    </div>
                    @error('nom')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="ti ti-alert-circle text-xs"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Niveau --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Niveau <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-4 gap-3" id="niveau-grid">
                        @foreach(['RDC','R+1','R+2','R+3'] as $niv)
                            @php $selected = old('niveau', $salle->niveau) === $niv; @endphp
                            <label class="relative cursor-pointer">
                                <input type="radio" name="niveau" value="{{ $niv }}"
                                       {{ $selected ? 'checked' : '' }}
                                       class="peer sr-only">
                                <div class="border-2 rounded-xl p-3 text-center transition-all hover:border-gray-300
                                            {{ $selected ? 'border-[#1a3c6e] bg-blue-50' : 'border-gray-200' }}">
                                    <i class="ti ti-building text-xl block mb-1
                                              {{ $selected ? 'text-[#1a3c6e]' : 'text-gray-400' }}"></i>
                                    <span class="text-sm font-semibold {{ $selected ? 'text-[#1a3c6e]' : 'text-gray-600' }}">
                                        {{ $niv }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('niveau')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                            <i class="ti ti-alert-circle text-xs"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Boutons --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 py-3 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                        <i class="ti ti-check text-base"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('admin.salles.index') }}"
                       class="flex items-center justify-center gap-2 px-5 py-3 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                        <i class="ti ti-arrow-left text-base"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="niveau"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('input[name="niveau"]').forEach(r => {
            const div = r.nextElementSibling;
            const icon = div.querySelector('i');
            const span = div.querySelector('span');
            if (r.checked) {
                div.classList.add('border-[#1a3c6e]','bg-blue-50');
                div.classList.remove('border-gray-200');
                icon.classList.add('text-[#1a3c6e]');
                icon.classList.remove('text-gray-400');
                span.classList.add('text-[#1a3c6e]');
                span.classList.remove('text-gray-600');
            } else {
                div.classList.remove('border-[#1a3c6e]','bg-blue-50');
                div.classList.add('border-gray-200');
                icon.classList.remove('text-[#1a3c6e]');
                icon.classList.add('text-gray-400');
                span.classList.remove('text-[#1a3c6e]');
                span.classList.add('text-gray-600');
            }
        });
    });
});
</script>
@endsection