@extends('layouts.app')

@section('title', 'Calendrier des réservations')

@section('content')
<div style="max-width:1100px; margin:40px auto; padding:0 24px">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px">
        <h1 style="font-size:28px; font-weight:700; color:#1a2b4a; margin:0">
            Calendrier des réservations
        </h1>
        <a href="{{ route('admin.reservations.index') }}"
           style="font-size:13px; color:#1a3c6e; text-decoration:none">
            ← Retour aux réservations
        </a>
    </div>

    {{-- Filtre par salle --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08);
                padding:16px 24px; margin-bottom:24px;
                display:flex; align-items:center; gap:16px; flex-wrap:wrap">
        <label style="font-size:13px; font-weight:600; color:#444">
            Filtrer par salle
        </label>
        <select id="filtre-salle"
                style="padding:8px 14px; border:1px solid #ddd; border-radius:6px;
                       font-size:14px; background:white; min-width:200px">
            <option value="">-- Toutes les salles --</option>
            @foreach($salles as $salle)
                <option value="{{ $salle->nom }}">
                    {{ $salle->nom }} — {{ $salle->niveau }}
                </option>
            @endforeach
        </select>
        <button onclick="filtrerSalle()"
                style="background:#1a3c6e; color:white; padding:8px 20px;
                       border:none; border-radius:6px; font-size:14px;
                       font-weight:600; cursor:pointer">
            Filtrer
        </button>
        <button onclick="reinitialiser()"
                style="background:#6c757d; color:white; padding:8px 20px;
                       border:none; border-radius:6px; font-size:14px;
                       font-weight:600; cursor:pointer">
            Réinitialiser
        </button>
    </div>

    {{-- Calendrier --}}
    <div style="background:white; border-radius:10px;
                box-shadow:0 1px 4px rgba(0,0,0,0.08); padding:24px">
        <div id="calendrier"></div>
    </div>

    {{-- Modal détail réservation --}}
    <div id="modal"
         style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
                background:rgba(0,0,0,0.5); z-index:1000; justify-content:center; align-items:center">
        <div style="background:white; border-radius:12px; padding:32px;
                    max-width:440px; width:90%; position:relative">
            <button onclick="fermerModal()"
                    style="position:absolute; top:12px; right:16px; background:none;
                           border:none; font-size:20px; cursor:pointer; color:#888">
                ✕
            </button>
            <h3 style="font-size:18px; font-weight:700; color:#1a2b4a; margin:0 0 20px 0"
                id="modal-titre"></h3>
            <div id="modal-contenu"></div>
        </div>
    </div>

</div>

{{-- FullCalendar CDN --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/fr.js"></script>

<script>
    const toutesReservations = @json($reservations);
    let calendrier;

    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('calendrier');

        calendrier = new FullCalendar.Calendar(document.getElementById('calendrier'), {
    locale: 'fr',
    initialView: 'timeGridWeek',
    headerToolbar: {
        left:   'prev,next today',
        center: 'title',
        right:  'dayGridMonth,timeGridWeek,timeGridDay'
    },
    buttonText: {
        today:    'Aujourd\'hui',
        month:    'Mois',
        week:     'Semaine',
        day:      'Jour',
    },
    slotMinTime: '07:00:00',
    slotMaxTime: '21:00:00',
    height: 650,
    events: toutesReservations,
    eventClick: function(info) {
        ouvrirModal(info.event);
    }
});

        calendrier.render();
    });

    function ouvrirModal(event) {
        const props = event.extendedProps;
        document.getElementById('modal-titre').textContent = event.title;
        document.getElementById('modal-contenu').innerHTML = `
            <div style="display:grid; gap:12px; font-size:14px">
                <div style="display:flex; gap:8px">
                    <span style="color:#888; min-width:90px">👤 Professeur</span>
                    <strong>${props.professeur}</strong>
                </div>
                <div style="display:flex; gap:8px">
                    <span style="color:#888; min-width:90px">📍 Salle</span>
                    <strong>${props.salle}</strong>
                </div>
                <div style="display:flex; gap:8px">
                    <span style="color:#888; min-width:90px">👥 Classe</span>
                    <strong>${props.classe}</strong>
                </div>
                <div style="display:flex; gap:8px">
                    <span style="color:#888; min-width:90px">📚 Matière</span>
                    <strong>${props.matiere}</strong>
                </div>
                <div style="display:flex; gap:8px">
                    <span style="color:#888; min-width:90px">🕐 Début</span>
                    <strong>${new Date(event.start).toLocaleString('fr-FR', {
                        day:'2-digit', month:'2-digit', year:'numeric',
                        hour:'2-digit', minute:'2-digit'
                    })}</strong>
                </div>
                <div style="display:flex; gap:8px">
                    <span style="color:#888; min-width:90px">🕐 Fin</span>
                    <strong>${new Date(event.end).toLocaleTimeString('fr-FR', {
                        hour:'2-digit', minute:'2-digit'
                    })}</strong>
                </div>
            </div>
        `;
        document.getElementById('modal').style.display = 'flex';
    }

    function fermerModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function filtrerSalle() {
        const salle = document.getElementById('filtre-salle').value;
        const filtrees = salle
            ? toutesReservations.filter(r => r.extendedProps.salle.startsWith(salle))
            : toutesReservations;

        calendrier.removeAllEvents();
        calendrier.addEventSource(filtrees);
    }

    function reinitialiser() {
        document.getElementById('filtre-salle').value = '';
        calendrier.removeAllEvents();
        calendrier.addEventSource(toutesReservations);
    }

    // Fermer le modal en cliquant dehors
    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) fermerModal();
    });
</script>
@endsection