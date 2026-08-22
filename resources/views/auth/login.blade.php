<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HETEC — Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.19.0/tabler-icons.min.css">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-100 flex flex-col">
    <div class="flex-1 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-[#1a3c6e] px-8 py-8 text-center border-b-4 border-red-700">
                <img src="{{ asset('images/logo-hetec.jpeg') }}" alt="HETEC"
                     class="w-16 h-16 rounded-xl bg-white p-1 mx-auto mb-4 object-contain">
                <h1 class="text-2xl font-bold text-white">Connexion</h1>
                <p class="text-sm text-white/70 mt-1">Accédez à votre espace de réservation</p>
            </div>
            <div class="px-8 py-8">
                @if(session('success'))
                    <div class="flex items-center gap-2 bg-green-50 text-green-800 border-l-4 border-green-500 px-4 py-3 rounded-lg mb-5 text-sm">
                        <i class="ti ti-circle-check text-lg"></i> {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="flex items-center gap-2 bg-red-50 text-red-800 border-l-4 border-red-500 px-4 py-3 rounded-lg mb-5 text-sm">
                        <i class="ti ti-alert-circle text-lg flex-shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                        <div class="relative">
                            <i class="ti ti-mail absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   placeholder="votre@email.com" required autofocus
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe</label>
                        <div class="relative">
                            <i class="ti ti-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="password" name="password"
                                   placeholder="••••••••" required
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-[#1a3c6e] focus:ring-2 focus:ring-blue-100 transition-colors">
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <input type="checkbox" id="remember" name="remember"
                               class="w-4 h-4 accent-[#1a3c6e] cursor-pointer">
                        <label for="remember" class="text-sm text-gray-600 cursor-pointer">Se souvenir de moi</label>
                    </div>
                    <button type="submit"
                            class="w-full py-3 bg-[#1a3c6e] text-white font-semibold rounded-lg text-base hover:bg-blue-900 transition-colors">
                        Se connecter
                    </button>
                </form>
                <div class="mt-6 pt-5 border-t border-gray-100 text-center">
                    <a href="{{ route('register') }}" class="text-sm text-[#1a3c6e] font-medium hover:underline">
                        Pas encore inscrit ? S'inscrire →
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