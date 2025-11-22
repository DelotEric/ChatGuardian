<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2937; font-size: 12px; line-height: 1.5; }
        h1 { color: #6f8df6; font-size: 20px; margin-bottom: 6px; }
        h2 { font-size: 14px; margin: 18px 0 8px; color: #374151; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 6px; background: #eef2ff; color: #4338ca; font-size: 11px; }
        .section { border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; margin-bottom: 12px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .muted { color: #6b7280; }
        .footer { margin-top: 24px; font-size: 11px; color: #6b7280; }
    </style>
</head>
<body>
    <h1>Contrat de famille d'accueil</h1>
    <p class="muted">Émis le {{ $today }} — {{ $organization->name }}</p>

    <div class="section">
        <h2>Famille d'accueil</h2>
        <div class="grid">
            <div>
                <strong>{{ $family->name }}</strong><br>
                {{ $family->email }}<br>
                {{ $family->phone }}
            </div>
            <div>
                <span class="badge">Capacité: {{ $family->capacity ?? 'n.c.' }} chat(s)</span><br>
                <span class="muted">Préférences : {{ $family->preferences ?? 'non renseigné' }}</span><br>
                <span class="muted">Ville : {{ $family->city ?? '—' }}</span>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Engagements</h2>
        <ul>
            <li>Accueil, soins et nourrissage dans le respect du bien-être animal.</li>
            <li>Prévenir l'association pour tout besoin vétérinaire ou changement majeur.</li>
            <li>Autoriser l'association à organiser des visites de suivi.</li>
            <li>Restituer le chat sur demande de l'association pour adoption ou transfert.</li>
        </ul>
    </div>

    <div class="section">
        <h2>Informations pratiques</h2>
        <p class="muted">Ce modèle sera complété automatiquement (nom du chat, dates, vétérinaire référent) lorsque la fonctionnalité sera connectée à la base.</p>
        <p>Merci d'imprimer, signer et renvoyer le document ou de le signer électroniquement.</p>
    </div>

    <div class="footer">
        {{ $organization->legal_name ?? $organization->name }} — {{ $organization->address }}, {{ $organization->postal_code }} {{ $organization->city }} · {{ $organization->email }}
    </div>
</body>
</html>
