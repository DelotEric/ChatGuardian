<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reçu fiscal</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2c3e50;">Bonjour {{ $membership->member->full_name }},</h2>

        <p>Votre reçu fiscal <strong>{{ $membership->receipt_number }}</strong> est joint à ce message.</p>

        <p>Ce reçu atteste de votre cotisation de <strong>{{ number_format($membership->amount, 2, ',', ' ') }}
                €</strong> pour l'année {{ $membership->year }}.</p>

        <div style="background: #f8f9fa; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0;">
            <strong>💡 Avantage fiscal :</strong><br>
            Ce don ouvre droit à une réduction d'impôt de 66% du montant, soit
            <strong>{{ number_format($membership->amount * 0.66, 2, ',', ' ') }} €</strong>.
        </div>

        <p>Conservez précieusement ce reçu pour votre déclaration de revenus.</p>

        <p style="margin-top: 30px;">
            Cordialement,<br>
            <strong>L'équipe ChatGuardian</strong>
        </p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p style="font-size: 12px; color: #666;">
            ChatGuardian - Association pour la protection féline<br>
            123 Rue des Chats, 75000 Paris
        </p>
    </div>
</body>

</html>