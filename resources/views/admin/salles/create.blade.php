@extends('layouts.app')

@section('content')
<div style="max-width:560px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Ajouter une salle
    </h1>

    @if($errors->any())
        <div style="background:#f8d7da; color:#721c24; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            <ul style="margin:0; padding-left:16px">
                @foreach($errors->all() as $erreur)
                    <li>{{ $erreur }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:32px">

        <form method="POST" action="{{ route('admin.salles.store') }}">
            @csrf

            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">
                    Nom de la salle
                </label>
                <input type="text" name="nom" value="{{ old('nom') }}"
                       placeholder="Ex: Salle 12, Labo Info..."
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
            </div>

            <div style="margin-bottom:28px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">
                    Niveau
                </label>
                <select name="niveau"
                        style="width:100%; padding:10px 14px; border:1px solid #ddd;
                               border-radius:6px; font-size:14px; box-sizing:border-box;
                               background:white">
                    <option value="">-- Choisir un niveau --</option>
                    @foreach(['RDC', 'R+1', 'R+2', 'R+3'] as $niveau)
                        <option value="{{ $niveau }}"
                            {{ old('niveau') === $niveau ? 'selected' : '' }}>
                            {{ $niveau }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:12px">
                <button type="submit"
                        style="background:#1a3c6e; color:white; padding:10px 24px;
                               border:none; border-radius:6px; font-size:14px;
                               font-weight:600; cursor:pointer">
                    Enregistrer
                </button>
                <a href="{{ route('admin.salles.index') }}"
                   style="padding:10px 24px; border:1px solid #ddd; border-radius:6px;
                          font-size:14px; color:#555; text-decoration:none; font-weight:600">
                    Annuler
</a>
            </div>
        </form>
    </div>
</div>
@endsection