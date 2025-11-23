<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Reçu Fiscal - {{ $donation->receipt_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            color: #333;
            line-height: 1.6;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3a7e8c;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #3a7e8c;
            margin: 0;
            font-size: 24pt;
        }

        .header p {
            color: #666;
            margin: 5px 0;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section h2 {
            color: #3a7e8c;
            font-size: 14pt;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .col {
            width: 48%;
        }

        .amount-box {
            background: #f0f8ff;
            border-left: 4px solid #3a7e8c;
            padding: 15px;
            margin: 15px 0;
        }

        .amount-box .amount {
            font-size: 18pt;
            color: #3a7e8c;
            font-weight: bold;
        }

        .tax-benefit {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 15px 0;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .small {
            font-size: 10pt;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>REÇU FISCAL</h1>
        <p>Cerfa n° 11580*05 - Article 200 du Code général des impôts</p>
    </div>

    <div class="row">
        <div class="col">
            <div class="info-section">
                <h2>Association</h2>
                <p><strong>ChatGuardian</strong></p>
                <p>Association de protection des chats</p>
                <p>123 Rue de l'Exemple<br>75000 Paris</p>
                <p>SIREN: 123 456 789</p>
            </div>
        </div>
        <div class="col" style="text-align: right;">
            <div class="info-section">
                <h2>Reçu N°</h2>
                <p style="font-size: 16pt; color: #3a7e8c; font-weight: bold;">{{ $donation->receipt_number }}</p>
                <p>Date: {{ $donation->donated_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h2>Donateur</h2>
        <p><strong>{{ $donation->donor->name }}</strong></p>
        @if($donation->donor->address)
            <p>{{ $donation->donor->address }}<br>
                {{ $donation->donor->postal_code }} {{ $donation->donor->city }}</p>
        @endif
    </div>

    <div class="info-section">
        <h2>Montant du don</h2>
        <div class="amount-box">
            <p><strong>Montant versé :</strong></p>
            <p class="amount">{{ number_format($donation->amount, 2, ',', ' ') }} €</p>
            <p class="small">En toutes lettres : {{ $amountInWords }}</p>
        </div>
    </div>

    <div class="info-section">
        <h2>Calcul de votre avantage fiscal</h2>
        <div class="tax-benefit">
            <p><strong>Réduction d'impôt : {{ number_format($donation->amount * 0.66, 2, ',', ' ') }} €</strong></p>
            <p class="small">Soit 66% du montant de votre don, dans la limite de 20% de votre revenu imposable.</p>
        </div>
    </div>

    <div class="info-section">
        <h2>Attestation</h2>
        <p class="small">
            L'association certifie sur l'honneur que les dons et versements qu'elle reçoit ouvrent droit à la réduction
            d'impôt prévue à l'article 200 du Code général des impôts.
        </p>
        <p class="small">
            Le bénéficiaire reconnaît que le montant indiqué ci-dessus correspond aux sommes versées au titre de l'année
            {{ $donation->donated_at->year }}, pour lesquelles il n'a pas reçu de contrepartie.
        </p>
    </div>

    <div class="footer">
        <div class="row">
            <div class="col">
                <p class="small"><strong>Date :</strong> {{ now()->format('d/m/Y') }}</p>
            </div>
            <div class="col" style="text-align: right;">
                <p class="small"><strong>Signature de l'association</strong></p>
                <p class="small">(Cachet et signature)</p>
            </div>
        </div>
    </div>

    <p class="small" style="text-align: center; margin-top: 40px; color: #999;">
        Conservez ce justificatif, il pourra vous être demandé par l'administration fiscale.
    </p>
</body>

</html>