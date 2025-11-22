@php($org = $organization)
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rappels ChatGuardian</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1a1a1a; margin: 0; padding: 0; }
        .container { max-width: 680px; margin: 0 auto; padding: 24px; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 16px; }
        .muted { color: #6b7280; font-size: 14px; }
        .title { font-size: 20px; margin: 0 0 8px; }
        .pill { display: inline-block; border-radius: 999px; padding: 4px 10px; font-size: 12px; background: #eef2ff; color: #4338ca; }
        .footer { color: #6b7280; font-size: 13px; margin-top: 16px; }
        .list { padding-left: 16px; }
    </style>
</head>
<body>
<div class="container">
    <p class="muted" style="text-transform: uppercase; letter-spacing: 1px; margin: 0 0 4px;">Rappels ChatGuardian</p>
    <h1 style="margin: 0 0 8px;">Bonjour {{ $recipient->name }},</h1>
    <p style="margin: 0 0 16px;">Voici un récapitulatif des rappels chats en attente. Pensez à mettre à jour les fiches concernées.</p>

    @if($reminders->isEmpty())
        <div class="card">
            <p class="title" style="font-size: 16px;">Aucun rappel à suivre</p>
            <p class="muted">Tout est à jour, aucun rappel en retard ou à venir dans la période sélectionnée.</p>
        </div>
    @else
        @foreach($reminders as $reminder)
            <div class="card">
                <p class="title">{{ $reminder->title }}</p>
                <p class="muted">Chat : <strong>{{ $reminder->cat->name }}</strong> — Échéance : <strong>{{ $reminder->due_date->translatedFormat('d M Y') }}</strong></p>
                <p class="muted">Type : {{ $reminder->type }} — Statut : {{ $reminder->status === 'pending' ? 'À faire' : 'Clôturé' }}</p>
                @if($reminder->notes)
                    <p style="margin: 8px 0 0;">{{ $reminder->notes }}</p>
                @endif
            </div>
        @endforeach
    @endif

    <p class="muted" style="margin-top: 4px;">Accéder aux fiches : <a href="{{ url('/reminders') }}">liste des rappels</a> ou <a href="{{ url('/') }}">dashboard</a>.</p>

    <div class="footer">
        <p style="margin: 0 0 4px;">{{ $org->name }} — {{ $org->address }}, {{ $org->postal_code }} {{ $org->city }}</p>
        <p style="margin: 0;">Contact : {{ $org->email }} — {{ $org->phone }}</p>
    </div>
</div>
</body>
</html>
