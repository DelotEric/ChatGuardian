<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu Fiscal N° {{ $membership->receipt_number }}</title>
    <style>
        @media print {
            body {
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: Arial, sans-serif;
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm;
            background: white;
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
            font-weight: bold;
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

        .attestation {
            margin-bottom: 20px;
        }

        .attestation .title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 14px;
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

        .donor-info .details {
            font-size: 12px;
            line-height: 1.6;
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
            align-items: end;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-box .label {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .signature-box .placeholder {
            border: 2px dashed #ccc;
            width: 150px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 11px;
            margin-top: 10px;
        }

        .action-buttons {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 5px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="header">
            <h1>REÇU FISCAL</h1>
            <div style="font-size: 11px; color: #666; margin-top: 5px;">
                Article 200 et 238 bis du Code Général des Impôts
            </div>
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
            <p>
                L'association <strong>ChatGuardian</strong>, reconnue d'intérêt général, certifie avoir reçu
                au titre des versements ouvrant droit à réduction d'impôt, la somme de :
            </p>
        </div>

        <div class="amount">
            <div class="value">{{ number_format($membership->amount, 2, ',', ' ') }} €</div>
            <div class="words">{{ $amountInWords }}</div>
        </div>

        <div class="donor-info">
            <div class="title">De la part de :</div>
            <div class="details">
                <strong>{{ strtoupper($membership->member->full_name) }}</strong><br>
                @if($membership->member->address)
                    {{ $membership->member->address }}<br>
                @endif
                @if($membership->member->postal_code || $membership->member->city)
                    {{ $membership->member->postal_code }} {{ strtoupper($membership->member->city) }}
                @endif
            </div>
        </div>

        <div class="tax-info">
            <strong>💡 Avantage fiscal :</strong> Ce don ouvre droit à une réduction d'impôt de <strong>66%</strong>
            du montant du don dans la limite de <strong>20%</strong> du revenu imposable.
            <br><br>
            <em>Exemple : Pour un don de {{ number_format($membership->amount, 2, ',', ' ') }} €,
                votre réduction d'impôt sera de {{ number_format($membership->amount * 0.66, 2, ',', ' ') }} €.</em>
        </div>

        <div class="footer">
            <div>
                <div class="signature-box">
                    <div class="label">ChatGuardian</div>
                    <div style="font-size: 11px;">Signature du Président</div>
                    <div class="placeholder">Signature</div>
                </div>
            </div>
            <div style="font-size: 10px; color: #666; max-width: 300px; text-align: right;">
                Ce reçu est à conserver et à joindre à votre déclaration de revenus.<br>
                En cas de contrôle fiscal, il pourra vous être demandé.
            </div>
        </div>
    </div>

    <div class="action-buttons no-print">
        <a href="{{ route('memberships.receipt.download', $membership) }}" class="btn">📥 Télécharger PDF</a>
        <form action="{{ route('memberships.receipt.email', $membership) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn" style="background: #28a745;">📧 Envoyer par email</button>
        </form>
        <button onclick="window.print()" class="btn btn-secondary">🖨️ Imprimer</button>
        <a href="{{ route('members.show', $membership->member) }}" class="btn btn-secondary">← Retour</a>
    </div>

    @if(session('status'))
        <div class="no-print" style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            {{ session('status') }}
        </div>
    @endif

    @if(session('error'))
        <div class="no-print" style="position: fixed; top: 20px; right: 20px; background: #dc3545; color: white; padding: 15px 20px; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            {{ session('error') }}
        </div>
    @endif

    <script>
        // Option pour imprimer automatiquement au chargement
        // window.addEventListener('load', () => window.print());
    </script>
</body>

</html>