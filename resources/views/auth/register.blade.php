<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HETEC — Inscription</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-100 flex flex-col">

    <div class="flex-1 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-xl overflow-hidden">

            <div class="bg-[#1a3c6e] px-8 py-8 text-center border-b-4 border-red-700">
                <img src="{{ asset('images/logo-hetec.jpeg') }}"
                     alt="HETEC" class="w-16 h-16 rounded-xl bg-white p-1 mx-auto mb-4 object-contain">
                <h1 class="text-2xl font-bold text-white">Inscription — Professeur</h1>
                <p class="text-sm text-white/70 mt-1">Votre compte sera activé après validation par l'administration</p>
            </div>

            <div class="px-8 py-8">
                <div class="flex items-start gap-3 bg-blue-50 border-l-4 border-[#1a3c6e] px-4 py-3 rounded-lg mb-6 text-sm text-blue-900">
                    <i class="ti ti-info-circle text-lg flex-shrink-0 mt-0.5"></i>
                    Après inscription, votre compte sera soumis à la validation de l'administrateur avant activation.
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-lg mb-5 text-sm text-red-800">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <div class="relative">
                                <i class="ti ti-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Votre nom" required
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                            </div>
                            @error('nom')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prénom(s)</label>
                            <div class="relative">
                                <i class="ti ti-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="text" name="prenoms" value="{{ old('prenoms') }}" placeholder="Vos prénoms" required
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                            </div>
                            @error('prenoms')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                        <div class="relative">
                            <i class="ti ti-mail absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="votre@email.com" required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                        </div>
                        @error('email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                            <div class="relative">
                                <i class="ti ti-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="password" name="password" placeholder="Min. 6 caractères" required
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer</label>
                            <div class="relative">
                                <i class="ti ti-lock-check absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                                <input type="password" name="password_confirmation" placeholder="Confirmer" required
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100">
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full py-3 bg-[#1a3c6e] text-white font-semibold rounded-lg text-base hover:bg-blue-900 transition-colors mt-2">
                        Envoyer la demande d'inscription
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-[#1a3c6e] font-medium hover:underline">
                        Déjà inscrit ? Se connecter →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-[#1a3c6e] border-t-4 border-red-700 text-center py-3 text-xs text-white/60">
        © {{ date('Y') }} <strong class="text-white">HETEC</strong> — Tous droits réservés.
    </footer>
</body>
</html>
