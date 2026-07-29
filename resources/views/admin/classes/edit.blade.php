@extends('layouts.app')
@section('title', 'Modifier la classe')
@section('page-title', 'Modifier la classe')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-gradient-to-r from-[#1a3c6e] to-[#1e4d8c] rounded-2xl p-6 mb-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0">
            <i class="ti ti-pencil text-3xl text-white"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-white">Modifier la classe</h2>
            <p class="text-white/70 text-sm mt-0.5">{{ $classe->nom }} — {{ $classe->filiere }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
            <i class="ti ti-info-circle text-gray-400"></i>
            <p class="text-xs text-gray-500">Niveau actuel : <strong class="text-[#1a3c6e]">{{ $classe->niveau }}</strong></p>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.classes.update', $classe) }}" class="space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nom <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-users text-[#1a3c6e] text-base"></i>
                        </div>
                        <input type="text" name="nom" value="{{ old('nom', $classe->nom) }}"
                               class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                    </div>
                    @error('nom')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Filière <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                            <i class="ti ti-certificate text-[#1a3c6e] text-base"></i>
                        </div>
                        <input type="text" name="filiere" value="{{ old('filiere', $classe->filiere) }}"
                               class="w-full pl-14 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                    </div>
                    @error('filiere')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Niveau <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(['Licence 1','Licence 2','Licence 3','Master 1','Master 2'] as $niv)
                            @php $sel = old('niveau', $classe->niveau) === $niv; @endphp
                            <label class="cursor-pointer">
                                <input type="radio" name="niveau" value="{{ $niv }}" {{ $sel ? 'checked' : '' }} class="peer sr-only">
                                <div class="border-2 rounded-lg px-3 py-2 text-xs font-medium text-center transition-all
                                            {{ $sel ? 'border-[#1a3c6e] bg-blue-50 text-[#1a3c6e]' : 'border-gray-200 text-gray-600 hover:border-gray-300' }}">
                                    {{ $niv }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('niveau')<p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 py-3 bg-[#1a3c6e] text-white text-sm font-semibold rounded-xl hover:bg-blue-900 transition-colors">
                        <i class="ti ti-check text-base"></i> Enregistrer
                    </button>
                    <a href="{{ route('admin.classes.index') }}"
                       class="flex items-center justify-center gap-2 px-5 py-3 border border-gray-200 text-gray-600 text-sm font-medium rounded-xl hover:bg-gray-50">
                        <i class="ti ti-arrow-left text-base"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="niveau"]').forEach(r => {
    r.addEventListener('change', () => {
        document.querySelectorAll('input[name="niveau"]').forEach(rb => {
            const d = rb.nextElementSibling;
            if (rb.checked) {
                d.classList.add('border-[#1a3c6e]','bg-blue-50','text-[#1a3c6e]');
                d.classList.remove('border-gray-200','text-gray-600');
            } else {
                d.classList.remove('border-[#1a3c6e]','bg-blue-50','text-[#1a3c6e]');
                d.classList.add('border-gray-200','text-gray-600');
            }
        });
    });
});
</script>
@endsection