<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 13px; }
        .header { border-bottom: 2px solid #6C5CE7; margin-bottom: 18px; padding-bottom: 10px; }
        .section { margin-bottom: 16px; }
        .title { font-size: 18px; font-weight: bold; color: #6C5CE7; margin: 0; }
        .subtitle { font-size: 14px; color: #555; margin: 4px 0 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #e5e7eb; text-align: left; }
        .badge { background: #e9d5ff; color: #6C5CE7; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <p class="title">Contrat d'adoption - {{ $organization->name }}</p>
        <p class="subtitle">Généré le {{ $today }}</p>
    </div>

    <div class="section">
        <p><strong>Association :</strong> {{ $organization->legal_name }}</p>
        <p>{{ $organization->address }} - {{ $organization->postal_code }} {{ $organization->city }}</p>
        <p>Email : {{ $organization->email }} · Téléphone : {{ $organization->phone }}</p>
    </div>

    <div class="section">
        <p class="badge">Chat adopté</p>
        <table>
            <tr>
                <th>Nom</th>
                <td>{{ $adoption->cat->name }}</td>
            </tr>
            <tr>
                <th>Sexe</th>
                <td>{{ $adoption->cat->sex }}</td>
            </tr>
            <tr>
                <th>Statut santé</th>
                <td>Stérilisé : {{ $adoption->cat->sterilized ? 'oui' : 'non' }} | Vacciné : {{ $adoption->cat->vaccinated ? 'oui' : 'non' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <p class="badge">Adoptant</p>
        <table>
            <tr>
                <th>Nom complet</th>
                <td>{{ $adoption->adopter_name }}</td>
            </tr>
            <tr>
                <th>Coordonnées</th>
                <td>{{ $adoption->adopter_email }} · {{ $adoption->adopter_phone }}</td>
            </tr>
            <tr>
                <th>Adresse</th>
                <td>{{ $adoption->adopter_address }}</td>
            </tr>
            <tr>
                <th>Date d'adoption</th>
                <td>{{ optional($adoption->adopted_at)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Participation financière</th>
                <td>{{ number_format($adoption->fee, 2, ',', ' ') }} €</td>
            </tr>
        </table>
    </div>

    @if($adoption->notes)
        <div class="section">
            <p class="badge">Notes complémentaires</p>
            <p>{{ $adoption->notes }}</p>
        </div>
    @endif

    <div class="section">
        <p>Le présent contrat formalise l'adoption du chat ci-dessus. L'adoptant s'engage à assurer les soins, la sécurité et le bien-être de l'animal, et à informer l'association en cas de changement majeur.</p>
        <p>Date et signature de l'association : ____________________</p>
        <p>Date et signature de l'adoptant : ______________________</p>
    </div>
</body>
</html>
