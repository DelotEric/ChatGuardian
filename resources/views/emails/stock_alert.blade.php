@php($org = $organization)
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Alerte stocks ChatGuardian</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; margin: 0; padding: 0; }
        .container { max-width: 680px; margin: 0 auto; padding: 24px; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 12px; }
        .muted { color: #6b7280; font-size: 14px; }
        .title { font-size: 18px; margin: 0 0 6px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; background: #fef9c3; color: #92400e; font-size: 12px; }
        .footer { color: #6b7280; font-size: 13px; margin-top: 16px; }
    </style>
</head>
<body>
<div class="container">
    <p class="muted" style="text-transform: uppercase; letter-spacing: 1px; margin: 0 0 4px;">Stocks & fournitures</p>
    <h1 style="margin: 0 0 10px;">Bonjour {{ $recipient->name }},</h1>
    <p style="margin: 0 0 14px;">Voici les articles dont le niveau est au seuil d'alerte ou en dessous. Pensez à réapprovisionner ou à ajuster les sorties.</p>

    @foreach($items as $item)
        <div class="card">
            <p class="title">{{ $item->name }} <span class="badge">Stock faible</span></p>
            <p class="muted">Catégorie : {{ $item->category ?? '—' }} — Localisation : {{ $item->location ?? '—' }}</p>
            <p style="margin: 6px 0 0;">Quantité actuelle : <strong>{{ $item->quantity }} {{ $item->unit }}</strong> (seuil : {{ $item->restock_threshold }} {{ $item->unit }}).</p>
            @if($item->notes)
                <p class="muted" style="margin: 6px 0 0;">Notes : {{ $item->notes }}</p>
            @endif
        </div>
    @endforeach

    <p class="muted">Gérer l'inventaire : <a href="{{ url('/stocks') }}">ouvrir la page Stocks</a>.</p>

    <div class="footer">
        <p style="margin: 0 0 4px;">{{ $org->name }} — {{ $org->address }}, {{ $org->postal_code }} {{ $org->city }}</p>
        <p style="margin: 0;">Contact : {{ $org->email }} — {{ $org->phone }}</p>
    </div>
</div>
</body>
</html>
