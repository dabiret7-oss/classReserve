@extends('layouts.app')
@section('title', 'Mon calendrier')
@section('page-title', 'Calendrier')

@section('content')

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center">
                <i class="ti ti-calendar text-[#1a3c6e] text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-[#1a2b4a]">Mes réservations</h3>
                <p class="text-xs text-gray-400">Bleu = mes réservations | Gris = autres</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <select id="filtre-salle"
                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs bg-white focus:outline-none focus:border-[#1a3c6e]">
                <option value="">Toutes les salles</option>
                @foreach($salles as $salle)
                    <option value="{{ $salle->nom }}">{{ $salle->nom }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="p-4">
        <div id='calendar'></div>
    </div>
</div>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet'/>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reservations = @json($reservations);
    const monId        = {{ auth()->id() }};

    const events = reservations.map(r => ({
        ...r,
        color: r.extendedProps.user_id === monId ? '#1a3c6e' : '#9ca3af',
    }));

    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'timeGridWeek',
        locale: 'fr',
        headerToolbar: {
            left:   'prev,next today',
            center: 'title',
            right:  'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: events,
        eventClick: function(info) {
            const p = info.event.extendedProps;
            alert(
                '📚 ' + (p.matiere || info.event.title) + '\n' +
                '📍 ' + p.salle + '\n' +
                '👤 ' + p.professeur + '\n' +
                '👥 ' + (p.classe || '—')
            );
        },
    });

    calendar.render();

    document.getElementById('filtre-salle').addEventListener('change', function() {
        const val = this.value;
        calendar.getEvents().forEach(e => {
            const salle = e.extendedProps.salle || '';
            e.setProp('display', (!val || salle.includes(val)) ? 'auto' : 'none');
        });
    });
});
</script>
@endsection