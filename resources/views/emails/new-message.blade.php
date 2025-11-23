<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nouveau message</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; text-align: center; color: white; border-radius: 8px 8px 0 0;">
            <h2 style="margin: 0;">📩 Nouveau message</h2>
        </div>

        <div
            style="background: white; border: 1px solid #e0e0e0; border-top: none; padding: 30px; border-radius: 0 0 8px 8px;">
            <p>Bonjour {{ $message->recipient->name }},</p>

            <p>Vous avez reçu un nouveau message de <strong>{{ $message->sender->name }}</strong> sur ChatGuardian.</p>

            <div style="background: #f5f5f5; padding: 15px; border-left: 4px solid #667eea; margin: 20px 0;">
                <p style="margin: 0; font-weight: bold;">{{ $message->subject }}</p>
            </div>

            <p style="color: #666; font-size: 0.95em;">{{ Str::limit($message->body, 150) }}</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('messages.show', $message) }}"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                    Lire le message
                </a>
            </div>

            <hr style="border: none; border-top: 1px solid #ddd; margin: 30px 0;">

            <p style="font-size: 0.85em; color: #888;">
                Ce message a été envoyé via la messagerie interne de ChatGuardian.<br>
                Pour répondre, connectez-vous à votre compte.
            </p>
        </div>
    </div>
</body>

</html>