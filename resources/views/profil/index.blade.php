@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
<div style="max-width:700px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Mon profil
    </h1>

    {{-- Message succès infos --}}
    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

    {{-- Message succès mot de passe --}}
    @if(session('success_password'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success_password') }}
        </div>
    @endif

    {{-- Carte informations --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:32px; margin-bottom:24px">

        <div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;
                    padding-bottom:20px; border-bottom:1px solid #f0f0f0">
            <div style="width:64px; height:64px; border-radius:50%;
                        background:#1a3c6e; color:white; display:flex;
                        align-items:center; justify-content:center;
                        font-size:24px; font-weight:700">
                {{ strtoupper(substr($user->nom, 0, 1)) }}
            </div>
            <div>
                <p style="font-weight:700; color:#1a2b4a; font-size:18px; margin:0 0 4px 0">
                    {{ $user->nom }} {{ $user->prenoms }}
                </p>
                <span style="font-size:13px; color:#888">
                    {{ $user->role === 'admin' ? 'Administrateur' : 'Professeur' }}
                    &nbsp;|&nbsp;
                    <span style="color:{{ $user->statut === 'valide' ? '#28a745' : '#856404' }}">
                        {{ $user->statut === 'valide' ? 'Compte actif' : ucfirst($user->statut) }}
                    </span>
                </span>
            </div>
        </div>

        <h2 style="font-size:16px; color:#1a3c6e; margin:0 0 20px 0; font-weight:700">
            Informations personnelles
        </h2>

        <form method="POST" action="{{ route('profil.infos') }}">
            @csrf
            @method('PATCH')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600;
                                  color:#444; margin-bottom:6px">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', $user->nom) }}"
                           style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                  border-radius:6px; font-size:14px; box-sizing:border-box">
                    @error('nom')
                        <span style="color:#dc3545; font-size:12px">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600;
                                  color:#444; margin-bottom:6px">Prénom(s)</label>
                    <input type="text" name="prenoms" value="{{ old('prenoms', $user->prenoms) }}"
                           style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                  border-radius:6px; font-size:14px; box-sizing:border-box">
                    @error('prenoms')
                        <span style="color:#dc3545; font-size:12px">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Adresse email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
                @error('email')
                    <span style="color:#dc3545; font-size:12px">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                    style="background:#1a3c6e; color:white; padding:10px 24px;
                           border:none; border-radius:6px; font-size:14px;
                           font-weight:600; cursor:pointer">
                Mettre à jour
            </button>
        </form>
    </div>

    {{-- Carte mot de passe --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:32px">

        <h2 style="font-size:16px; color:#1a3c6e; margin:0 0 20px 0; font-weight:700">
            Changer le mot de passe
        </h2>

        <form method="POST" action="{{ route('profil.password') }}">
            @csrf
            @method('PATCH')

            <div style="margin-bottom:16px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Ancien mot de passe</label>
                <input type="password" name="ancien_mot_de_passe"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
                @error('ancien_mot_de_passe')
                    <span style="color:#dc3545; font-size:12px">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom:16px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Nouveau mot de passe</label>
                <input type="password" name="password"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
                @error('password')
                    <span style="color:#dc3545; font-size:12px">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom:24px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
            </div>

            <button type="submit"
                    style="background:#cc0000; color:white; padding:10px 24px;
                           border:none; border-radius:6px; font-size:14px;
                           font-weight:600; cursor:pointer">
                Changer le mot de passe
            </button>
        </form>
    </div>
</div>
@endsection