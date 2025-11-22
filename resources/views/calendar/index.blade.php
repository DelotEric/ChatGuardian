@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex align-items-center mb-4">
    <div class="flex-grow-1">
        <p class="text-muted mb-1">Vue consolidée</p>
        <h1 class="h3 mb-0">Agenda des rappels & visites véto</h1>
        <p class="text-muted">Visualisez en un coup d'œil tous les rappels planifiés et les visites vétérinaires des chats.</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('reminders.index') }}" class="btn btn-outline-primary"><i class="bi bi-list-check me-1"></i> Liste des rappels</a>
        <a href="{{ route('calendar.export') }}" class="btn btn-primary"><i class="bi bi-download me-1"></i> Exporter (ICS)</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Légende</h5>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge rounded-pill me-2" style="background-color:#0ea5e9; width:24px; height:12px;"></span>
                    <span>Rappels à venir</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge rounded-pill me-2 bg-secondary" style="width:24px; height:12px;"></span>
                    <span>Rappels complétés</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge rounded-pill me-2" style="background-color:#22c55e; width:24px; height:12px;"></span>
                    <span>Visites vétérinaires</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-0">Agenda</h5>
                        <small class="text-muted">Cliquez sur un événement pour ouvrir la fiche du chat.</small>
                    </div>
                    <div class="ms-auto">
                        <div class="btn-group" role="group" aria-label="Calendar actions">
                            <button class="btn btn-sm btn-outline-secondary" id="todayBtn">Aujourd'hui</button>
                            <button class="btn btn-sm btn-outline-secondary" id="prevBtn"><i class="bi bi-chevron-left"></i></button>
                            <button class="btn btn-sm btn-outline-secondary" id="nextBtn"><i class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
                <div id="calendar" class="flex-grow-1"></div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="card-title">Événements à venir</h5>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="text-muted">
                        <th>Type</th>
                        <th>Chat</th>
                        <th>Titre / motif</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events->sortBy('start')->take(10) as $event)
                        <tr>
                            <td>
                                @if($event['extendedProps']['type'] === 'vet')
                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-hospital me-1"></i>Véto</span>
                                @else
                                    <span class="badge bg-info-subtle text-info"><i class="bi bi-bell me-1"></i>Rappel</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $event['extendedProps']['cat'] ?? '—' }}</td>
                            <td class="text-muted">{{ $event['title'] }}</td>
                            <td class="fw-semibold">{{ \Carbon\Carbon::parse($event['start'])->translatedFormat('d M Y') }}</td>
                            <td>
                                @if(($event['extendedProps']['status'] ?? '') === 'done')
                                    <span class="badge bg-secondary-subtle text-secondary">Terminé</span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary">Planifié</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($event['url'])
                                    <a href="{{ $event['url'] }}" class="btn btn-sm btn-outline-primary">Ouvrir</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucun événement trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const events = @json($events);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr',
                height: 'auto',
                firstDay: 1,
                buttonText: {
                    today: "Aujourd'hui",
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour'
                },
                eventDisplay: 'block',
                eventClick: function(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                },
                events: events,
            });

            calendar.render();

            document.getElementById('todayBtn').addEventListener('click', () => calendar.today());
            document.getElementById('prevBtn').addEventListener('click', () => calendar.prev());
            document.getElementById('nextBtn').addEventListener('click', () => calendar.next());
        });
    </script>
@endpush
