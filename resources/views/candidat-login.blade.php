<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Votre accès au portail CEFF</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; color: #333;">
    <div
        style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #e9ecef;">
        <h2 style="color: #4A4A4A; margin-bottom: 20px;">Portail d'inscription CEFF</h2>
        <p style="font-size: 16px;">Bonjour,</p>
        <p style="font-size: 16px;">
            Vous avez demandé l'accès au formulaire d'inscription du CEFF.
            Veuillez cliquer sur le lien ci-dessous pour accéder à votre dossier de manière sécurisée :
        </p>
        <p style="margin: 30px;">
            <a href="{{ $connexionLink }}" style="color: #0d6efd; font-size: 16px; font-weight: bold;">
                {{ $connexionLink }}
            </a>
        </p>
        <p style="font-size: 14px; color: #6c757d;">
            Conservez cet e-mail : ce lien vous permet de vous reconnecter à tout moment pour compléter ou modifier
            votre formulaire.
        </p>
    </div>
</body>

</html>