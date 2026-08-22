<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HETEC — @yield('title', 'Réservation de salles')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans text-gray-800 min-h-screen">

<div class="flex min-h-screen">

    @auth
    <button onclick="toggleSidebar()"
            class="lg:hidden fixed top-4 left-4 z-50 bg-[#1a3c6e] text-white w-10 h-10 rounded-lg flex items-center justify-center shadow-lg">
        <i class="ti ti-menu-2 text-xl"></i>
    </button>

    <div id="overlay" onclick="toggleSidebar()"
         class="hidden fixed inset-0 bg-black/40 z-30 lg:hidden"></div>

    <aside id="sidebar"
           class="fixed top-0 left-0 h-full w-56 bg-[#1a3c6e] flex flex-col z-40
                  -translate-x-full lg:translate-x-0 transition-transform duration-300">

        <div class="flex items-center gap-3 px-4 py-5 border-b border-white/10">
            <img src="{{ asset('images/logo-hetec.jpeg') }}" alt="HETEC"
                 class="w-10 h-10 rounded-lg bg-white p-0.5 object-contain flex-shrink-0">
            <div>
                <span class="block text-[10px] text-white/50 tracking-wide uppercase">Groupe Écoles</span>
                <strong class="block text-sm text-white font-semibold">ClassReserve</strong>
            </div>
        </div>

        <nav class="flex-1 px-2 py-3 overflow-y-auto">
            @auth
            @if(auth()->user()->isAdmin())
                <p class="text-[10px] text-white/35 uppercase tracking-widest px-2 pt-2 pb-1 font-semibold">Principal</p>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-layout-dashboard text-lg"></i> Tableau de bord
                </a>
                <a href="{{ route('admin.calendrier') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('admin.calendrier') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-calendar text-lg"></i> Calendrier
                </a>

                <p class="text-[10px] text-white/35 uppercase tracking-widest px-2 pt-4 pb-1 font-semibold">Gestion</p>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('admin.users.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-users text-lg"></i> Professeurs
                    @php $p = \App\Models\User::where('role','professeur')->where('statut','en_cours')->count() @endphp
                    @if($p > 0)<span class="ml-auto bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $p }}</span>@endif
                </a>
                <a href="{{ route('admin.salles.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('admin.salles.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-building text-lg"></i> Salles
                </a>
                <a href="{{ route('admin.matieres.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('admin.matieres.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-book text-lg"></i> Matières
                </a>
                <a href="{{ route('admin.classes.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('admin.classes.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-school text-lg"></i> Classes
                </a>

                <p class="text-[10px] text-white/35 uppercase tracking-widest px-2 pt-4 pb-1 font-semibold">Activité</p>
                <a href="{{ route('admin.reservations.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('admin.reservations.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-clock text-lg"></i> Réservations
                    @php $r = \App\Models\Reservation::where('statut','en_attente')->count() @endphp
                    @if($r > 0)<span class="ml-auto bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $r }}</span>@endif
                </a>
                <a href="{{ route('admin.cahiers.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('admin.cahiers.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-notebook text-lg"></i> Cahiers
                    @php $ca = \App\Models\CahierAcces::where('statut','en_attente')->count() @endphp
                    @if($ca > 0)<span class="ml-auto bg-red-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $ca }}</span>@endif
                </a>
                <a href="{{ route('admin.import.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('admin.import.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-file-upload text-lg"></i> Import CSV
                </a>

                <p class="text-[10px] text-white/35 uppercase tracking-widest px-2 pt-4 pb-1 font-semibold">Compte</p>
                <a href="{{ route('profil.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('profil.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-user text-lg"></i> Mon profil
                </a>
            @else
                <p class="text-[10px] text-white/35 uppercase tracking-widest px-2 pt-2 pb-1 font-semibold">Principal</p>
                <a href="{{ route('professeur.dashboard') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('professeur.dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-layout-dashboard text-lg"></i> Tableau de bord
                </a>
                <a href="{{ route('professeur.calendrier') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('professeur.calendrier') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-calendar text-lg"></i> Calendrier
                </a>

                <p class="text-[10px] text-white/35 uppercase tracking-widest px-2 pt-4 pb-1 font-semibold">Mes cours</p>
                <a href="{{ route('professeur.reservations.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('professeur.reservations.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-clock text-lg"></i> Réservations
                </a>
                <a href="{{ route('professeur.cahiers.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('professeur.cahiers.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-notebook text-lg"></i> Cahiers de texte
                </a>

                <p class="text-[10px] text-white/35 uppercase tracking-widest px-2 pt-4 pb-1 font-semibold">Compte</p>
                <a href="{{ route('profil.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-colors
                          {{ request()->routeIs('profil.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <i class="ti ti-user text-lg"></i> Mon profil
                </a>
            @endif
            @endauth
        </nav>

        @auth
        <div class="px-3 py-3 border-t border-white/10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-white/15 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->nom,0,1)) }}{{ strtoupper(substr(auth()->user()->prenoms,0,1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white font-medium truncate">{{ auth()->user()->nom }} {{ auth()->user()->prenoms }}</p>
                    <p class="text-[11px] text-white/40">{{ auth()->user()->isAdmin() ? 'Administrateur' : 'Professeur' }}</p>
                </div>
            </div>
        </div>
        @endauth
    </aside>
    @endauth

    <div class="flex-1 flex flex-col min-h-screen lg:ml-56">

        @auth
        <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 sticky top-0 z-20">
            <div class="pl-10 lg:pl-0">
                <h1 class="text-lg font-semibold text-[#1a2b4a]">@yield('page-title', 'Tableau de bord')</h1>
                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
            </div>
            <div class="flex items-center gap-2.5">
                @if(!auth()->user()->isAdmin())
                    @php $notifications = auth()->user()->unreadNotifications; $nbNotifs = $notifications->count(); @endphp
                    <div class="relative" tabindex="0">
                        <div class="w-9 h-9 rounded-lg border border-gray-200 bg-white flex items-center justify-center cursor-pointer relative hover:bg-gray-50">
                            <i class="ti ti-bell text-gray-500 text-lg"></i>
                            @if($nbNotifs > 0)
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-600 rounded-full border-2 border-white"></span>
                            @endif
                        </div>
                        <div class="hidden group-focus-within:block absolute right-0 top-11 bg-white rounded-xl shadow-xl w-80 z-50 overflow-hidden border border-gray-100">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-800">Notifications</span>
                                @if($nbNotifs > 0)
                                    <a href="{{ route('professeur.notifications.lire') }}" class="text-xs text-[#1a3c6e]">Tout lire</a>
                                @endif
                            </div>
                            @forelse($notifications->take(5) as $notif)
                                <div class="px-4 py-3 border-b border-gray-50 bg-amber-50">
                                    <p class="text-sm font-semibold text-gray-800">{{ $notif->data['message'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">📍 {{ $notif->data['salle'] }} | 🕐 {{ \Carbon\Carbon::parse($notif->data['date_debut'])->format('d/m/Y à H\hi') }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-5 text-center text-sm text-gray-400">Aucune notification</div>
                            @endforelse
                            <div class="px-4 py-2.5 text-center border-t border-gray-100">
                                <a href="{{ route('professeur.reservations.index') }}" class="text-xs text-[#1a3c6e]">Voir mes réservations →</a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('professeur.reservations.create') }}"
                       class="flex items-center gap-1.5 px-3 py-2 bg-[#1a3c6e] text-white text-sm font-medium rounded-lg hover:bg-blue-900 transition-colors">
                        <i class="ti ti-plus text-base"></i>
                        <span class="hidden sm:inline">Réserver</span>
                    </a>
                @else
                    <div class="w-9 h-9 rounded-lg border border-gray-200 bg-white flex items-center justify-center cursor-pointer relative hover:bg-gray-50">
                        <i class="ti ti-bell text-gray-500 text-lg"></i>
                        @if(\App\Models\Reservation::where('statut','en_attente')->count() > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-600 rounded-full border-2 border-white"></span>
                        @endif
                    </div>
                    <a href="{{ route('admin.reservations.create') }}"
                       class="flex items-center gap-1.5 px-3 py-2 bg-[#1a3c6e] text-white text-sm font-medium rounded-lg hover:bg-blue-900 transition-colors">
                        <i class="ti ti-plus text-base"></i>
                        <span class="hidden sm:inline">Attribuer</span>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-3 py-2 bg-red-700 text-white text-sm font-medium rounded-lg hover:bg-red-800 transition-colors">
                        Déconnexion
                    </button>
                </form>
            </div>
        </header>
        @endauth

        <main class="flex-1 p-6">
            @if(session('success'))
                <div id="alert-success"
                     class="flex items-center gap-3 bg-green-50 text-green-800 border-l-4 border-green-500 px-4 py-3 rounded-lg mb-5 text-sm transition-all duration-300">
                    <i class="ti ti-circle-check text-lg text-green-600 flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div id="alert-error"
                     class="bg-red-50 text-red-800 border-l-4 border-red-500 px-4 py-3 rounded-lg mb-5 text-sm">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="ti ti-alert-circle text-lg text-red-600"></i>
                        <strong>Veuillez corriger les erreurs suivantes :</strong>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-[#1a3c6e] border-t-4 border-red-700 text-center py-4 text-xs text-white/60">
            © {{ date('Y') }} <strong class="text-white">HETEC</strong> — Hautes Études TEchnologiques et Commerciales. Tous droits réservés.
        </footer>
    </div>
</div>

<script>
function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('overlay');
    sb.classList.toggle('-translate-x-full');
    sb.classList.toggle('translate-x-0');
    ov.classList.toggle('hidden');
}

function autoHide(id) {
    const el = document.getElementById(id);
    if (!el) return;
    setTimeout(() => {
        el.style.opacity = '0';
        el.style.height = '0';
        el.style.padding = '0';
        el.style.margin = '0';
        el.style.overflow = 'hidden';
        setTimeout(() => el.remove(), 400);
    }, 3000);
}
autoHide('alert-success');
</script>
</body>
</html>