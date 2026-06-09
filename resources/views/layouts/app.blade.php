<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HETEC — @yield('title', 'Réservation de salles')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── NAVBAR ── */
        nav {
            background: #1a3c6e;
            color: white;
            padding: 0 32px;
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            text-decoration: none;
        }

        .nav-brand img {
            height: 48px;
            width: auto;
            border-radius: 4px;
            background: white;
            padding: 2px;
        }

        .nav-brand-text {
            display: flex;
            flex-direction: column;
        }

        .nav-brand-text span:first-child {
            font-size: 11px;
            color: rgba(255,255,255,0.7);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-brand-text strong {
            font-size: 16px;
            color: white;
            letter-spacing: 0.5px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-links a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .nav-links a:hover {
            background: rgba(255,255,255,0.12);
            color: white;
        }

        .nav-divider {
            width: 1px;
            height: 24px;
            background: rgba(255,255,255,0.2);
            margin: 0 8px;
        }

        .nav-user {
            font-size: 13px;
            color: rgba(255,255,255,0.7);
            padding: 8px 4px;
        }

        .btn-logout {
            background: rgba(204,0,0,0.8);
            color: white !important;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .btn-logout:hover { background: #cc0000 !important; }

        /* ── BARRE ROUGE HETEC ── */
        .hetec-bar {
            height: 4px;
            background: linear-gradient(to right, #cc0000, #ff4444);
        }

        /* ── CONTENU ── */
        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 32px 16px;
            flex: 1;
        }

        /* ── ALERTES ── */
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-left: 4px solid #28a745;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-left: 4px solid #cc0000;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            list-style: none;
            font-size: 14px;
        }

        /* ── CARDS ── */
        .card {
            background: white;
            border-radius: 10px;
            padding: 28px 32px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.08);
        }

        /* ── BOUTONS ── */
        .btn {
            display: inline-block;
            padding: 9px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.88; }
        .btn-primary   { background: #1a3c6e; color: white; }
        .btn-danger    { background: #cc0000; color: white; }
        .btn-success   { background: #28a745; color: white; }
        .btn-secondary { background: #6c757d; color: white; }

        /* ── FORMULAIRES ── */
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 13px;
            color: #444;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #1a3c6e;
            box-shadow: 0 0 0 3px rgba(26,60,110,0.1);
        }
        .form-group input.error { border-color: #cc0000; }
        .field-error { color: #cc0000; font-size: 12px; margin-top: 4px; }

        /* ── TABLES ── */
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 12px 14px; border-bottom: 1px solid #e9ecef; text-align: left; }
        th { background: #f8f9fa; font-weight: 600; color: #555; font-size: 13px; }
        tr:hover td { background: #fafbfc; }

        /* ── BADGES ── */
        .badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-validated { background: #d4edda; color: #155724; }
        .badge-rejected  { background: #f8d7da; color: #721c24; }

        /* ── NOTIFICATIONS (cloche) ── */
        .notif-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            margin-left: 8px;
            cursor: pointer;
        }
        .notif-bell { font-size: 18px; }
        .notif-badge {
            position: absolute;
            top: -4px; right: -6px;
            background: #cc0000;
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 5px;
            border-radius: 10px;
            min-width: 16px;
            text-align: center;
        }
        .notif-dropdown {
            display: none;
            position: absolute;
            right: 0; top: 36px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            width: 320px;
            z-index: 999;
            overflow: hidden;
        }
        .notif-wrapper:hover .notif-dropdown,
        .notif-wrapper:focus-within .notif-dropdown { display: block; }
        .notif-header {
            padding: 12px 16px;
            font-weight: 700;
            font-size: 13px;
            color: #1a2b4a;
            border-bottom: 1px solid #f0f0f0;
            background: #f8f9fa;
        }
        .notif-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f5f5f5;
            font-size: 13px;
            background: #fffbf0;
        }
        .notif-item p { margin: 0 0 4px 0; font-weight: 600; color: #333; }
        .notif-item span { font-size: 12px; color: #888; }
        .notif-empty {
            padding: 20px 16px;
            text-align: center;
            font-size: 13px;
            color: #888;
        }
        .notif-footer {
            padding: 10px 16px;
            text-align: center;
            border-top: 1px solid #f0f0f0;
        }
        .notif-footer a { font-size: 13px; color: #1a3c6e; text-decoration: none; }

        /* ── FOOTER ── */
        footer {
            background: #1a3c6e;
            color: rgba(255,255,255,0.6);
            text-align: center;
            padding: 16px 32px;
            font-size: 12px;
            border-top: 4px solid #cc0000;
        }
        footer strong { color: white; }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav>
    <a href="{{ auth()->check() && auth()->user()->isAdmin()
        ? route('admin.dashboard')
        : (auth()->check() ? route('professeur.dashboard') : route('login')) }}"
       class="nav-brand">
        <img src="{{ asset('images/logo-hetec.jpeg') }}" alt="Logo HETEC">
        <div class="nav-brand-text">
            <span>Groupe Écoles d'Ingénieurs</span>
            <strong>Réservation de salles</strong>
        </div>
    </a>

    @auth
    <div class="nav-links">
        <a href="{{ route('profil.index') }}"
    style="color:rgba(255,255,255,0.85); text-decoration:none; font-size:13px;
            padding:8px 14px; border-radius:6px; transition:background 0.2s;"
    onmouseover="this.style.background='rgba(255,255,255,0.12)'"
    onmouseout="this.style.background='none'">
        👤 {{ auth()->user()->nom }}
    </a>
        <div class="nav-divider"></div>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
            <a href="{{ route('admin.users.index') }}">Professeurs</a>
            <a href="{{ route('admin.salles.index') }}">Salles</a>
            <a href="{{ route('admin.matieres.index') }}">Matières</a>
            <a href="{{ route('admin.classes.index') }}">Classes</a>
            <a href="{{ route('admin.reservations.index') }}">Réservations</a>
            <a href="{{ route('admin.cahiers.index') }}">Cahiers</a>
            <a href="{{ route('admin.calendrier') }}">Calendrier</a>
        @else
            <a href="{{ route('professeur.dashboard') }}">Tableau de bord</a>
            <a href="{{ route('professeur.reservations.index') }}">Réservations</a>
            <a href="{{ route('professeur.cahiers.index') }}">Cahiers de texte</a>
            <a href="{{ route('professeur.calendrier') }}">Calendrier</a>

            {{-- Cloche notifications --}}
            @php
                $notifications = auth()->user()->unreadNotifications;
                $nbNotifs = $notifications->count();
            @endphp
            <div class="notif-wrapper" tabindex="0">
                <span class="notif-bell">🔔</span>
                @if($nbNotifs > 0)
                    <span class="notif-badge">{{ $nbNotifs }}</span>
                @endif
                <div class="notif-dropdown">
                    <div class="notif-header">
                        Notifications
                        @if($nbNotifs > 0)
                            — <a href="{{ route('professeur.notifications.lire') }}"
                                 style="font-weight:400; color:#1a3c6e; font-size:12px">
                                Tout marquer comme lu
                              </a>
                        @endif
                    </div>
                    @forelse($notifications->take(5) as $notif)
                        <div class="notif-item">
                            <p>{{ $notif->data['message'] }}</p>
                            <span>
                                📍 {{ $notif->data['salle'] }} — {{ $notif->data['niveau'] }}
                                &nbsp;|&nbsp;
                                🕐 {{ \Carbon\Carbon::parse($notif->data['date_debut'])->format('d/m/Y à H\hi') }}
                            </span>
                        </div>
                    @empty
                        <div class="notif-empty">Aucune nouvelle notification</div>
                    @endforelse
                    <div class="notif-footer">
                        <a href="{{ route('professeur.reservations.index') }}">
                            Voir mes réservations →
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <div class="nav-divider"></div>

        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout">Déconnexion</button>
        </form>
    </div>
    @endauth
</nav>

{{-- BARRE ROUGE --}}
<div class="hetec-bar"></div>

{{-- CONTENU --}}
<div class="container">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <ul class="alert-error">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    @yield('content')
</div>

{{-- FOOTER --}}
<footer>
    © {{ date('Y') }} <strong>HETEC</strong> — Hautes Études TEchnologiques et Commerciales.
    Tous droits réservés.
</footer>

</body>
</html>