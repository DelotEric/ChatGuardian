<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu fiscal</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2933; font-size: 12px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .brand { font-size: 22px; font-weight: 700; color: #6f42c1; }
        .badge { background: #efe9fb; color: #6f42c1; padding: 6px 10px; border-radius: 6px; font-weight: 600; }
        .section { margin-bottom: 16px; }
        .section h3 { margin: 0 0 8px; font-size: 14px; color: #4b5563; text-transform: uppercase; letter-spacing: .5px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px 14px; background: #fff; }
        .row { display: flex; gap: 12px; }
        .col { flex: 1; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        .muted { color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="brand">{{ $organization->name }}</div>
            <div class="muted">{{ $organization->legal_name ?? 'Association de protection féline' }}</div>
        </div>
        <div class="badge">Reçu fiscal</div>
    </div>

    <div class="section">
        <h3>Informations donateur</h3>
        <div class="card">
            <strong>{{ optional($donation->donor)->name ?? 'Donateur' }}</strong><br>
            {{ optional($donation->donor)->address ?? 'Adresse non renseignée' }}<br>
            {{ optional($donation->donor)->postal_code }} {{ optional($donation->donor)->city }}<br>
            <span class="muted">Email : {{ optional($donation->donor)->email ?? '—' }}</span>
        </div>
    </div>

    <div class="section">
        <h3>Détail du don</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Mode de paiement</th>
                    <th>N° reçu</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ number_format($donation->amount, 2, ',', ' ') }} €</td>
                    <td>{{ optional($donation->donated_at)->format('d/m/Y') }}</td>
                    <td>{{ ucfirst($donation->payment_method) }}</td>
                    <td>{{ $donation->receipt_number ?: 'Non attribué' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section row">
        <div class="col card">
            <strong>Association</strong><br>
            {{ $organization->legal_name ?? $organization->name }}<br>
            {{ $organization->address }}<br>
            {{ $organization->postal_code }} {{ $organization->city }}
        </div>
        <div class="col card">
            <strong>Certification</strong><br>
            Reçu édité le {{ $today }}<br>
            Signature : ______________________
        </div>
    </div>

    <p class="muted" style="margin-top: 20px;">Ce reçu est délivré conformément aux dispositions de l'article 200 du CGI pour les dons effectués au profit des œuvres ou organismes d'intérêt général.</p>
</body>
</html>
