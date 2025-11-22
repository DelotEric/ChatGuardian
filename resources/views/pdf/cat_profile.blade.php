<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche chat</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2933; font-size: 12px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .brand { font-size: 22px; font-weight: 700; color: #6f42c1; }
        .badge { background: #efe9fb; color: #6f42c1; padding: 6px 10px; border-radius: 6px; font-weight: 600; }
        .section { margin-bottom: 16px; }
        .section h3 { margin: 0 0 8px; font-size: 13px; color: #4b5563; text-transform: uppercase; letter-spacing: .5px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 14px; background: #fff; }
        .row { display: flex; gap: 12px; }
        .col { flex: 1; }
        .table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .table th, .table td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
        .muted { color: #6b7280; }
        .pill { display: inline-block; padding: 4px 8px; border-radius: 999px; background: #f3f4ff; color: #4338ca; font-weight: 600; font-size: 11px; }
    </style>
</head>
<body>
    @php use Illuminate\Support\Str; @endphp
    <div class="header">
        <div>
            <div class="brand">{{ $organization->name }}</div>
            <div class="muted">{{ $organization->legal_name ?? 'Association de protection féline' }}</div>
        </div>
        <div class="badge">Fiche chat</div>
    </div>

    <div class="section row">
        <div class="col card">
            <h3>Identité</h3>
            <strong>{{ $cat->name }}</strong><br>
            Sexe : {{ ucfirst($cat->sex) }}<br>
            Né(e) le : {{ optional($cat->birthdate)->format('d/m/Y') ?? '—' }}<br>
            Statut : <span class="pill">{{ ucfirst($cat->status) }}</span>
        </div>
        <div class="col card">
            <h3>Santé</h3>
            Stérilisé : {{ $cat->sterilized ? 'oui' : 'non' }} {{ optional($cat->sterilized_at)->format('d/m/Y') ? '('.optional($cat->sterilized_at)->format('d/m/Y').')' : '' }}<br>
            Vacciné : {{ $cat->vaccinated ? 'oui' : 'non' }} {{ optional($cat->vaccinated_at)->format('d/m/Y') ? '('.optional($cat->vaccinated_at)->format('d/m/Y').')' : '' }}<br>
            FIV : {{ strtoupper($cat->fiv_status) }} – FELV : {{ strtoupper($cat->felv_status) }}<br>
            @if($cat->notes)
                <span class="muted">Notes : {{ Str::limit($cat->notes, 140) }}</span>
            @else
                <span class="muted">Notes : aucune remarque</span>
            @endif
        </div>
    </div>

    <div class="section row">
        <div class="col card">
            <h3>Séjour en cours</h3>
            @if($cat->currentStay)
                Famille : {{ optional($cat->currentStay->fosterFamily)->name ?? 'Non renseignée' }}<br>
                Depuis : {{ optional($cat->currentStay->started_at)->format('d/m/Y') }}<br>
                <span class="muted">Issue prévue : {{ $cat->currentStay->outcome ?? '—' }}</span>
            @else
                <span class="muted">Aucun séjour actif.</span>
            @endif
        </div>
        <div class="col card">
            <h3>Adoption</h3>
            @if($cat->adoption)
                Adoptant : {{ $cat->adoption->adopter_name }}<br>
                Contact : {{ $cat->adoption->adopter_email ?? '—' }} / {{ $cat->adoption->adopter_phone ?? '—' }}<br>
                Adresse : {{ $cat->adoption->adopter_address ?? '—' }}<br>
                Date : {{ optional($cat->adoption->adopted_at)->format('d/m/Y') }} — Participation : {{ number_format($cat->adoption->fee, 2, ',', ' ') }} €
            @else
                <span class="muted">Aucune adoption enregistrée.</span>
            @endif
        </div>
    </div>

    <div class="section">
        <h3>Visites vétérinaires récentes</h3>
        @php $recentVisits = $cat->vetRecords->sortByDesc('date')->take(4); @endphp
        @if($recentVisits->isEmpty())
            <div class="card muted">Aucune visite enregistrée.</div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Motif</th>
                        <th>Montant (€)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentVisits as $visit)
                        <tr>
                            <td>{{ optional($visit->date)->format('d/m/Y') }}</td>
                            <td>{{ $visit->reason }}</td>
                            <td>{{ number_format($visit->amount, 2, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="section">
        <h3>Historique des séjours</h3>
        @if($cat->stays->isEmpty())
            <div class="card muted">Aucun passage en famille pour l'instant.</div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Famille</th>
                        <th>Entrée</th>
                        <th>Sortie</th>
                        <th>Résultat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cat->stays->sortByDesc('started_at') as $stay)
                        <tr>
                            <td>{{ optional($stay->fosterFamily)->name ?? '—' }}</td>
                            <td>{{ optional($stay->started_at)->format('d/m/Y') }}</td>
                            <td>{{ optional($stay->ended_at)->format('d/m/Y') ?? 'En cours' }}</td>
                            <td>{{ $stay->outcome ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="section row">
        <div class="col card">
            <h3>Coordonnées association</h3>
            {{ $organization->legal_name ?? $organization->name }}<br>
            {{ $organization->address }}<br>
            {{ $organization->postal_code }} {{ $organization->city }}<br>
            <span class="muted">Tel : {{ $organization->phone ?? '—' }} · Email : {{ $organization->email }}</span>
        </div>
        <div class="col card">
            <h3>Généré le</h3>
            {{ $today }}<br>
            <span class="muted">Document produit automatiquement par ChatGuardian.</span>
        </div>
    </div>
</body>
</html>
