<!DOCTYPE html>
<html>
<head>
    <title>Votre code promo de fidélité</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background-color: #4CAF50; color: white; text-align: center; padding: 15px; border-radius: 5px 5px 0 0;">
            <h1>Merci pour votre fidélité!</h1>
        </div>
        
        <div style="background-color: #f9f9f9; padding: 20px; border-radius: 0 0 5px 5px;">
            <p>Bonjour {{ $user->name }},</p>
            
            <p>Félicitations! Vous avez atteint <strong>+2 commandes</strong> sur notre boutique.</p>
            
            <p>Pour vous remercier de votre fidélité, voici votre code promo personnel qui vous offre <strong>15% de réduction</strong> sur votre prochaine commande:</p>
            
            <div style="font-size: 24px; font-weight: bold; text-align: center; background-color: #eee; padding: 15px; margin: 20px 0; border: 2px dashed #4CAF50; border-radius: 5px;">
                {{ $codePromo }}
            </div>
            
            <p><strong>Détails:</strong></p>
            <ul>
                <li>15% de réduction sur votre prochaine commande</li>
                <li>Montant minimum d'achat: 100 MAD</li>
                <li>Valable pendant 3 mois</li>
                <li>Usage unique</li>
            </ul>
            
            <p>À très bientôt sur notre boutique!</p>
            
            <p>Cordialement,<br>L'équipe commerciale</p>
        </div>
    </div>
</body>
</html>