<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 210mm;
            margin: 0;
            padding: 20mm;
        }

        .receipt-container {
            border: 2px solid #333;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
        }

        .association-info {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .receipt-info {
            text-align: right;
            margin-bottom: 30px;
            font-size: 12px;
        }

        .receipt-info .number {
            font-weight: bold;
            font-size: 14px;
        }

        .attestation .title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .attestation p {
            font-size: 12px;
            line-height: 1.6;
            margin: 5px 0;
        }

        .amount {
            text-align: center;
            margin: 20px 0;
        }

        .amount .value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .amount .words {
            font-size: 12px;
            font-style: italic;
        }

        .donor-info {
            margin: 30px 0;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #333;
        }

        .donor-info .title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .tax-info {
            font-size: 11px;
            border: 1px solid #ddd;
            padding: 15px;
            background: #fffef7;
            margin: 20px 0;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-placeholder {
            border: 2px dashed #ccc;
            width: 150px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="header">
            <h1>REÇU FISCAL</h1>
            <div style="font-size: 11px; color: #666;">Article 200 et 238 bis du Code Général des Impôts</div>
        </div>

        <div class="association-info">
            <strong>ChatGuardian</strong><br>
            Association pour la protection féline<br>
            123 Rue des Chats, 75000 Paris<br>
            SIRET: 123 456 789 00012
        </div>

        <div class="receipt-info">
            <div>Date: {{ $membership->payment_date->format('d/m/Y') }}</div>
            <div class="number">N° {{ $membership->receipt_number }}</div>
        </div>

        <div class="attestation">
            <div class="title">Attestation de don</div>
            <p>L'association <strong>ChatGuardian</strong>, reconnue d'intérêt général, certifie avoir reçu au titre des
                versements ouvrant droit à réduction d'impôt, la somme de :</p>
        </div>

        <div class="amount">
            <div class="value">{{ number_format($membership->amount, 2, ',', ' ') }} €</div>
            <div class="words">{{ $amountInWords }}</div>
        </div>

        <div class="donor-info">
            <div class="title">De la part de :</div>
            <div>
                <strong>{{ strtoupper($membership->member->full_name) }}</strong><br>
                @if($membership->member->address){{ $membership->member->address }}<br>@endif
                @if($membership->member->postal_code || $membership->member->city){{ $membership->member->postal_code }}
                {{ strtoupper($membership->member->city) }}@endif
            </div>
        </div>

        <div class="tax-info">
            <strong>Avantage fiscal :</strong> Ce don ouvre droit à une réduction d'impôt de <strong>66%</strong> du
            montant du don dans la limite de <strong>20%</strong> du revenu imposable.
        </div>

        <div class="footer">
            <div class="signature-box">
                <div><strong>ChatGuardian</strong></div>
                <div style="font-size: 11px;">Signature du Président</div>
                <div class="signature-placeholder">Signature</div>
            </div>
            <div style="font-size: 10px; color: #666; max-width: 300px; text-align: right;">
                Ce reçu est à conserver et à joindre à votre déclaration de revenus.
            </div>
        </div>
    </div>
</body>

</html>