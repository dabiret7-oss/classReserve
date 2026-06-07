@extends('layouts.app')

@section('title', 'Nouvelle réservation')

@section('content')
<div style="max-width:600px; margin:40px auto; padding:0 24px">

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

            {{-- Salle --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Salle</label>
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

            {{-- Matière --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Matière</label>
                <select name="matiere_id"
                        style="width:100%; padding:10px 14px; border:1px solid #ddd;
                               border-radius:6px; font-size:14px; box-sizing:border-box; background:white">
                    <option value="">-- Choisir une matière --</option>
                    @foreach($matieres as $matiere)
                        <option value="{{ $matiere->id }}"
                            {{ old('matiere_id') == $matiere->id ? 'selected' : '' }}>
                            {{ $matiere->nom }} ({{ $matiere->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Classe --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Classe</label>
                <select name="classe_id"
                        style="width:100%; padding:10px 14px; border:1px solid #ddd;
                               border-radius:6px; font-size:14px; box-sizing:border-box; background:white">
                    <option value="">-- Choisir une classe --</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->id }}"
                            {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }} — {{ $classe->filiere }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Motif --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Titre du cours</label>
                <input type="text" name="motif" value="{{ old('motif') }}"
                       placeholder="Ex: Introduction aux algorithmes"
                       style="width:100%; padding:10px 14px; border:1px solid #ddd;
                              border-radius:6px; font-size:14px; box-sizing:border-box">
            </div>

            {{-- Date et heure début --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Date et heure de début</label>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px">
                    <input type="date" name="date_cours" id="date_cours"
                           value="{{ old('date_cours') }}"
                           style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                  border-radius:6px; font-size:14px; box-sizing:border-box">
                    <select name="heure_debut" id="heure_debut"
                            style="width:100%; padding:10px 14px; border:1px solid #ddd;
                                   border-radius:6px; font-size:14px; background:white">
                        <option value="">-- Heure --</option>
                        @php
                            $heures = [];
                            for ($h = 7; $h <= 20; $h++) {
                                $heures[] = sprintf('%02d:00', $h);
                                $heures[] = sprintf('%02d:30', $h);
                            }
                        @endphp
                        @foreach($heures as $h)
                            <option value="{{ $h }}"
                                {{ old('heure_debut') === $h ? 'selected' : '' }}>
                                {{ $h }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Champ caché date_debut combiné --}}
                <input type="hidden" name="date_debut" id="date_debut"
                       value="{{ old('date_debut') }}">
            </div>

            {{-- Durée --}}
            <div style="margin-bottom:20px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Durée du cours</label>
                <select name="duree" id="duree"
                        style="width:100%; padding:10px 14px; border:1px solid #ddd;
                               border-radius:6px; font-size:14px; background:white">
                    <option value="">-- Choisir la durée --</option>
                    @foreach([
                        '1'   => '1h00',
                        '1.5' => '1h30',
                        '2'   => '2h00',
                        '2.5' => '2h30',
                        '3'   => '3h00',
                        '3.5' => '3h30',
                        '4'   => '4h00',
                    ] as $val => $label)
                        <option value="{{ $val }}"
                            {{ old('duree') == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Heure de fin calculée automatiquement --}}
            <div style="margin-bottom:28px">
                <label style="display:block; font-size:13px; font-weight:600;
                              color:#444; margin-bottom:6px">Heure de fin (calculée)</label>
                <div id="heure_fin_affichage"
                     style="padding:10px 14px; background:#f5f7fa; border:1px solid #ddd;
                            border-radius:6px; font-size:14px; color:#555">
                    — sera calculée automatiquement
                </div>
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

<script>
    // Calcul automatique heure de fin
    function calculerHeureFin() {
        const date      = document.getElementById('date_cours').value;
        const heure     = document.getElementById('heure_debut').value;
        const duree     = parseFloat(document.getElementById('duree').value);
        const affichage = document.getElementById('heure_fin_affichage');
        const hidden    = document.getElementById('date_debut');

        if (!date || !heure || isNaN(duree)) {
            affichage.textContent = '— sera calculée automatiquement';
            hidden.value = '';
            return;
        }

        // Combiner date + heure pour date_debut
        hidden.value = date + ' ' + heure + ':00';

        // Calculer heure de fin
        const [h, m]   = heure.split(':').map(Number);
        const totalMin = h * 60 + m + duree * 60;
        const finH     = Math.floor(totalMin / 60);
        const finM     = totalMin % 60;
        const finStr   = String(finH).padStart(2, '0') + 'h' + String(finM).padStart(2, '0');

        affichage.textContent = '🕐 Fin prévue à ' + finStr;
        affichage.style.color = '#1a3c6e';
        affichage.style.fontWeight = '600';
    }

    document.getElementById('date_cours').addEventListener('change', calculerHeureFin);
    document.getElementById('heure_debut').addEventListener('change', calculerHeureFin);
    document.getElementById('duree').addEventListener('change', calculerHeureFin);
</script>
@endsection