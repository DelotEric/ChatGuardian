<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu fiscal</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #3a7e8c;">Reçu fiscal pour votre don</h2>

        <p>Bonjour {{ $donation->donor->name }},</p>

        <p>Nous vous remercions chaleureusement pour votre don de <strong>{{ number_format($donation->amount, 2) }}
                €</strong> effectué le {{ $donation->donated_at->format('d/m/Y') }}.</p>

        <p>Votre reçu fiscal est joint à cet email. Ce document vous permettra de bénéficier d'une réduction fiscale de
            :</p>

        <div style="background: #f0f8ff; padding: 15px; border-left: 4px solid #3a7e8c; margin: 20px 0;">
            <strong style="font-size: 1.2em; color: #3a7e8c;">{{ number_format($donation->amount * 0.66, 2) }}
                €</strong>
            <p style="margin: 5px 0 0 0; font-size: 0.9em; color: #666;">
                Soit 66% de votre don (dans la limite de 20% de votre revenu imposable)
            </p>
        </div>

        <p><strong>Numéro de reçu :</strong> {{ $donation->receipt_number }}</p>

        <p>Grâce à votre générosité, nous pouvons continuer notre mission de protection et de soins aux chats libres.
            Votre soutien fait vraiment la différence ! 🐱</p>

        <p>Cordialement,<br>
            <strong>L'équipe ChatGuardian</strong>
        </p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

        <p style="font-size: 0.85em; color: #888;">
            Ce reçu fiscal est conforme à l'article 200 du Code général des impôts.<br>
            En cas de question, n'hésitez pas à nous contacter.
        </p>
    </div>
</body>

</html>