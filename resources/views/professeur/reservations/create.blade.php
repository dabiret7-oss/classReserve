@extends('layouts.app')

@section('content')
<div style="max-width:560px; margin:40px auto; padding:0 24px">

    <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin-bottom:24px">
        Nouvelle réservation
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

        <form method="POST" action="{{ route('professeur.reservations.store') }}">
            @csrf

            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">
                    Salle
                </label>
                <select name="salle_id"
                        style="width:100%; padding:10px 14px; border:1px solid #ddd;
                               border-radius:6px; font-size:14px; box-sizing:border-box; background:white">
                    <option value="">-- Choisir une salle --</option>
                    @foreach($salles as $salle)
                        <option value="{{ $salle->id }}"
                            {{ old('salle_id') == $salle->id ? 'selected' : '' }}>
                            {{ $salle->nom }} — {{ $salle->niveau }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">
                    Date et heure de début
                </label>
                <input type="datetime-local" name="date_debut"
                       value="{{ old('date_debut') }}"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
            </div>

            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">
                    Heure de fin
                </label>
                <input type="time" name="heure_fin"
                       value="{{ old('heure_fin') }}"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
            </div>

            <div style="margin-bottom:28px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">
                    Motif / Nom du cours
                </label>
                <input type="text" name="motif"
                       value="{{ old('motif') }}"
                       placeholder="Ex: Cours de mathématiques"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
            </div>

            <div style="display:flex; gap:12px">
                <button type="submit"
                        style="background:#1a3c6e; color:white; padding:10px 24px;
                               border:none; border-radius:6px; font-size:14px;
                               font-weight:600; cursor:pointer">
                    Envoyer la demande
                </button>
                <a href="{{ route('professeur.reservations.index') }}"
                   style="padding:10px 24px; border:1px solid #ddd; border-radius:6px;
                          font-size:14px; color:#555; text-decoration:none; font-weight:600">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection