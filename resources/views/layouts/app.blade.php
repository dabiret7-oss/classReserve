<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HETEC — @yield('title', 'Réservation de salles')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f4f6f8; color: #333; }
        nav {
            background: #1a3c6e; color: white;
            padding: 14px 32px;
            display: flex; justify-content: space-between; align-items: center;
        }
        nav a { color: white; text-decoration: none; margin-left: 16px; }
        .container { max-width: 960px; margin: 40px auto; padding: 0 16px; }
        .card { background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
        .alert-success {
            background: #d4edda; color: #155724;
            border: 1px solid #c3e6cb;
            padding: 12px 16px; border-radius: 6px; margin-bottom: 16px;
        }
        .alert-error {
            background: #f8d7da; color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; list-style: none;
        }
        .btn {
            display: inline-block; padding: 9px 18px; border-radius: 5px;
            border: none; cursor: pointer; font-size: 14px; text-decoration: none;
        }
        .btn-primary   { background: #1a3c6e; color: white; }
        .btn-success   { background: #28a745; color: white; }
        .btn-danger    { background: #dc3545; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 12px; border: 1px solid #ced4da;
            border-radius: 5px; font-size: 14px;
        }
        .form-group input.error { border-color: #dc3545; }
        .field-error { color: #dc3545; font-size: 12px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 12px 14px; border-bottom: 1px solid #e9ecef; text-align: left; }
        th { background: #f8f9fa; font-weight: 600; }
        .badge { padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-validated { background: #d4edda; color: #155724; }
        .badge-rejected  { background: #f8d7da; color: #721c24; }

        /* Cloche notifications */
        .notif-wrapper {
            position: relative; display: inline-block; margin-left: 16px; cursor: pointer;
        }
        .notif-bell {
            font-size: 20px; cursor: pointer; user-select: none;
        }
        .notif-badge {
            position: absolute; top: -6px; right: -8px;
            background: #dc3545; color: white;
            font-size: 10px; font-weight: 700;
            padding: 2px 5px; border-radius: 10px;
            min-width: 16px; text-align: center;
        }
        .notif-dropdown {
            display: none; position: absolute; right: 0; top: 32px;
            background: white; border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            width: 320px; z-index: 999; overflow: hidden;
        }
        .notif-wrapper:hover .notif-dropdown,
        .notif-wrapper:focus-within .notif-dropdown {
            display: block;
        }
        .notif-header {
            padding: 12px 16px; font-weight: 700; font-size: 13px;
            color: #1a2b4a; border-bottom: 1px solid #f0f0f0;
            background: #f8f9fa;
        }
        .notif-item {
            padding: 12px 16px; border-bottom: 1px solid #f5f5f5;
            font-size: 13px; color: #333; background: #fffbf0;
        }
        .notif-item.read { background: white; color: #666; }
        .notif-item p { margin: 0 0 4px 0; font-weight: 600; }
        .notif-item span { font-size: 12px; color: #888; }
        .notif-empty {
            padding: 20px 16px; text-align: center;
            font-size: 13px; color: #888;
        }
        .notif-footer {
            padding: 10px 16px; text-align: center;
            border-top: 1px solid #f0f0f0;
        }
        .notif-footer a { font-size: 13px; color: #1a3c6e; text-decoration: none; }
    </style>
</head>
<body>
<nav>
    <strong>HETEC — Réservation de salles</strong>
    @auth
    <div style="display:flex; align-items:center">
        <span style="font-size:14px">{{ auth()->user()->nom }}</span>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
            <a href="{{ route('admin.users.index') }}">Professeurs</a>
            <a href="{{ route('admin.salles.index') }}">Salles</a>
            <a href="{{ route('admin.matieres.index') }}">Matières</a>  {{-- ✅ ajouter --}}
            <a href="{{ route('admin.classes.index') }}">Classes</a>   {{-- ✅ ajouter --}}  
            <a href="{{ route('admin.cahiers.index') }}">Cahiers de texte</a>

        @else
            <a href="{{ route('professeur.dashboard') }}">Tableau de bord</a>
            <a href="{{ route('professeur.reservations.index') }}">Mes réservations</a>
            <a href="{{ route('professeur.cahiers.index') }}">Cahiers de texte</a>

            {{-- Cloche notifications (professeur uniquement) --}}
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

        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit"
                    style="background:none;border:none;color:white;cursor:pointer;margin-left:16px">
                Déconnexion
            </button>
        </form>
    </div>
    @endauth
</nav>
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
</body>
</html>