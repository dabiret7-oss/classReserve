@extends('layouts.app')

@section('title', 'Gestion des classes')

@section('content')
<div style="max-width:900px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Gestion des classes
    </h1>

    @if(session('success'))
        <div style="background:#d4edda; color:#155724; padding:12px 16px;
                    border-radius:6px; margin-bottom:20px; font-size:14px">
            {{ session('success') }}
        </div>
    @endif

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px">

        {{-- Formulaire ajout --}}
        <div style="background:white; border-radius:10px;
                    box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px">
            <h2 style="font-size:16px; color:#1a3c6e; margin-bottom:16px; font-weight:700">
                Ajouter une classe
            </h2>

            <form method="POST" action="{{ route('admin.classes.store') }}">
                @csrf

                <div style="margin-bottom:16px">
                    <label style="display:block; font-size:13px; font-weight:600;
                                  color:#444; margin-bottom:6px">Nom de la classe</label>
                    <input type="text" name="nom" value="{{ old('nom') }}"
                           placeholder="Ex: Licence 1 Info A"
                           style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                  border-radius:6px; font-size:14px; box-sizing:border-box">
                    @error('nom')
                        <span style="color:#dc3545; font-size:12px">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom:16px">
                    <label style="display:block; font-size:13px; font-weight:600;
                                  color:#444; margin-bottom:6px">Filière</label>
                    <input type="text" name="filiere" value="{{ old('filiere') }}"
                           placeholder="Ex: Informatique"
                           style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                  border-radius:6px; font-size:14px; box-sizing:border-box">
                    @error('filiere')
                        <span style="color:#dc3545; font-size:12px">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom:20px">
                    <label style="display:block; font-size:13px; font-weight:600;
                                  color:#444; margin-bottom:6px">Niveau</label>
                    <select name="niveau"
                            style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                   border-radius:6px; font-size:14px; box-sizing:border-box;
                                   background:white">
                        <option value="">-- Choisir --</option>
                        @foreach(['Licence 1','Licence 2','Licence 3','Master 1','Master 2'] as $niv)
                            <option value="{{ $niv }}"
                                {{ old('niveau') === $niv ? 'selected' : '' }}>
                                {{ $niv }}
                            </option>
                        @endforeach
                    </select>
                    @error('niveau')
                        <span style="color:#dc3545; font-size:12px">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                        style="background:#1a3c6e; color:white; padding:10px 24px;
                               border:none; border-radius:6px; font-size:14px;
                               font-weight:600; cursor:pointer; width:100%">
                    + Ajouter
                </button>
            </form>
        </div>

        {{-- Liste des classes --}}
        <div style="background:white; border-radius:10px;
                    box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px">
            <h2 style="font-size:16px; color:#1a3c6e; margin-bottom:16px; font-weight:700">
                Classes enregistrées
                <span style="background:#1a3c6e; color:white; font-size:12px;
                             padding:2px 10px; border-radius:12px; margin-left:8px">
                    {{ $classes->count() }}
                </span>
            </h2>

            @forelse($classes as $classe)
                <div style="display:flex; justify-content:space-between; align-items:center;
                            padding:10px 0; border-bottom:1px solid #f0f0f0">
                    <div>
                        <p style="font-weight:600; color:#1a2b4a; margin:0; font-size:14px">
                            {{ $classe->nom }}
                        </p>
                        <span style="font-size:12px; color:#888">
                            {{ $classe->filiere }} — {{ $classe->niveau }}
                        </span>
                    </div>
                    <form method="POST"
                          action="{{ route('admin.classes.destroy', $classe) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Supprimer cette classe ?')"
                                style="background:#f8d7da; color:#dc3545; border:none;
                                       padding:4px 12px; border-radius:6px; font-size:12px;
                                       cursor:pointer; font-weight:600">
                            Supprimer
                        </button>
                    </form>
                </div>
            @empty
                <p style="color:#666; font-size:14px; text-align:center; padding:20px 0">
                    Aucune classe enregistrée.
                </p>
            @endforelse
        </div>
    </div>
</div>
@endsection