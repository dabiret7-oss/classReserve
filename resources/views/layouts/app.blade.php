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
        .badge {
            padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;
        }
        .badge-pending  { background: #fff3cd; color: #856404; }
        .badge-validated { background: #d4edda; color: #155724; }
        .badge-rejected { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<nav>
    <strong>HETEC — Réservation de salles</strong>
    @auth
    <div>
        <span style="font-size:14px">{{ auth()->user()->name }}</span>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
            <a href="{{ route('admin.users.index') }}">Professeurs</a>
        @else
            <a href="{{ route('professeur.dashboard') }}">Tableau de bord</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;color:white;cursor:pointer;margin-left:16px">
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