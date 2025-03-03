import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';

document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('calendar-holder');
    const calendar = new Calendar(calendarEl, {
        defaultView: 'dayGridMonth',
        locale: frLocale,
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventSources: [
            {
                url: '/evenement/calendar/events',
                method: 'GET',
                failure: () => {
                    alert('Erreur lors du chargement des événements !');
                },
            },
        ],
        timeZone: 'Europe/Paris',
        slotMinTime: '00:00:00',
        slotMaxTime: '24:00:00',
        allDaySlot: false,
        nowIndicator: true,
        dayMaxEvents: true,
        eventDisplay: 'block',
        displayEventTime: true,
        displayEventEnd: true,
        eventClick: (info) => {
            window.location.href = `/evenement/${info.event.id}`;
        }
    });
    calendar.render();
});
