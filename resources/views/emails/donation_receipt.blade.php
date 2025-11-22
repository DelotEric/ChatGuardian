<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre reçu fiscal</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; background: #f7f7f7; padding: 24px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden;">
        <tr>
            <td style="background: linear-gradient(135deg, #6c63ff, #2d2b57); padding: 24px; color: #ffffff;">
                <h1 style="margin: 0; font-size: 20px;">Merci pour votre don</h1>
                <p style="margin: 8px 0 0; opacity: 0.9;">ChatGuardian - Association partenaire</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <p style="margin: 0 0 12px;">Bonjour {{ $donation->donor->name }},</p>
                <p style="margin: 0 0 12px;">Nous avons bien enregistré votre don de <strong>{{ number_format($donation->amount, 2, ',', ' ') }} €</strong> en date du {{ \Illuminate\Support\Carbon::parse($donation->donated_at)->translatedFormat('d/m/Y') }}.</p>
                <p style="margin: 0 0 12px;">Vous trouverez en pièce jointe votre reçu fiscal <strong>#{{ $donation->receipt_number }}</strong> au format PDF.</p>
                <p style="margin: 0 0 12px;">Merci de votre soutien pour la protection féline.</p>
                <p style="margin: 0 0 12px;">L'équipe {{ $organization->name }}</p>
                <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 24px 0;">
                <p style="margin: 0 0 6px; font-size: 12px; color: #6b7280;">Si vous n'êtes pas à l'origine de cette demande, merci d'ignorer cet email.</p>
                <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $organization->legal_name ?? $organization->name }} — {{ $organization->address }}, {{ $organization->postal_code }} {{ $organization->city }} · {{ $organization->email }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
