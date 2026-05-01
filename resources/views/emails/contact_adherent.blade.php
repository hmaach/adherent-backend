<!DOCTYPE html>
<html>
<head>
    <title>Nouveau message d'un utilisateur</title>
</head>
<body>
    <h2>Vous avez reçu un nouveau message sur SOBOL Numérique</h2>
    <p><strong>De:</strong> {{ $senderName }} ({{ $senderEmail }})</p>
    <br>
    <p><strong>Message:</strong></p>
    <p>{{ $messageBody }}</p>
    <br>
    <p>Merci,</p>
    <p>L'équipe SOBOL Numérique</p>
</body>
</html>
