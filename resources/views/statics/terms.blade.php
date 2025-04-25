@extends('layouts.app')

@section('title', 'Conditions Générales de Vente - Tradition Sucrée')

@section('content')
    <!-- Bannière -->
    <div class="relative py-12 bg-cover bg-center" style="background-image: url('{{ asset('images/terms-banner.jpg') }}');">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="font-playfair text-4xl md:text-5xl font-bold text-white mb-4">Conditions Générales de Vente</h1>
            <p class="text-gray-200 max-w-2xl mx-auto">
                Veuillez lire attentivement ces conditions avant de commander nos produits.
            </p>
        </div>
    </div>

    <!-- Contenu -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-600">
                        Dernière mise à jour : 15 avril 2023
                    </p>
                    
                    <h2>1. Introduction</h2>
                    <p>
                        Les présentes Conditions Générales de Vente (ci-après "CGV") régissent les relations contractuelles entre la société Tradition Sucrée, SARL au capital de 100 000 MAD, immatriculée au Registre du Commerce de Casablanca sous le numéro RC12345, dont le siège social est situé au 123 Boulevard Mohammed V, Casablanca, 20250, Maroc (ci-après "Tradition Sucrée" ou "nous") et toute personne effectuant un achat sur le site www.traditionsucrée.ma (ci-après "le Client" ou "vous").
                    </p>
                    <p>
                        En passant commande sur notre site, vous reconnaissez avoir pris connaissance des présentes CGV et les accepter sans réserve.
                    </p>
                    
                    <h2>2. Produits</h2>
                    <p>
                        Les produits proposés à la vente sont ceux figurant sur notre site au jour de la consultation par le Client. Les photographies, textes et informations présentés ne sont pas contractuels et peuvent être modifiés à tout moment.
                    </p>
                    <p>
                        Nos produits sont des denrées périssables. Leur date limite de consommation est indiquée sur l'emballage ou dans la description du produit.
                    </p>
                    
                    <h2>3. Prix</h2>
                    <p>
                        Les prix de nos produits sont indiqués en Dirhams Marocains (MAD) toutes taxes comprises (TTC). Ils ne comprennent pas les frais de livraison, qui sont facturés en supplément et indiqués avant la validation de la commande.
                    </p>
                    <p>
                        Tradition Sucrée se réserve le droit de modifier ses prix à tout moment, mais les produits seront facturés sur la base des tarifs en vigueur au moment de la validation de la commande.
                    </p>
                    
                    <h2>4. Commande</h2>
                    <p>
                        Pour passer commande, le Client doit suivre les étapes suivantes :
                    </p>
                    <ol>
                        <li>Sélection des produits et ajout au panier</li>
                        <li>Validation du contenu du panier</li>
                        <li>Identification (création d'un compte ou connexion à un compte existant)</li>
                        <li>Choix du mode de livraison</li>
                        <li>Choix du mode de paiement</li>
                        <li>Validation finale de la commande</li>
                    </ol>
                    <p>
                        La commande n'est définitive qu'après confirmation du paiement. Un email de confirmation récapitulant la commande est envoyé au Client.
                    </p>
                    <p>
                        Tradition Sucrée se réserve le droit de refuser ou d'annuler toute commande d'un Client avec lequel il existerait un litige relatif au paiement d'une commande antérieure, ou pour tout autre motif légitime.
                    </p>
                    
                    <h2>5. Paiement</h2>
                    <p>
                        Le paiement peut être effectué par :
                    </p>
                    <ul>
                        <li>Carte bancaire (Visa, Mastercard)</li>
                        <li>PayPal</li>
                        <li>Virement bancaire</li>
                        <li>Paiement à la livraison (uniquement pour certaines zones géographiques)</li>
                    </ul>
                    <p>
                        Pour les paiements par carte bancaire, le débit est effectué au moment de la validation de la commande. La transaction est sécurisée par cryptage SSL.
                    </p>
                    <p>  La transaction est sécurisée par cryptage SSL.
                    </p>
                    <p>
                        En cas de paiement à la livraison, le Client s'engage à régler le montant total de la commande au livreur lors de la réception des produits.
                    </p>
                    
                    <h2>6. Livraison</h2>
                    <p>
                        La livraison est effectuée à l'adresse indiquée par le Client lors de la commande. Les délais de livraison sont donnés à titre indicatif et peuvent varier selon la zone géographique :
                    </p>
                    <ul>
                        <li>Casablanca et environs : 24-48h</li>
                        <li>Autres villes du Maroc : 2-4 jours</li>
                        <li>Zones rurales : 3-5 jours</li>
                    </ul>
                    <p>
                        Les frais de livraison sont calculés en fonction de la destination et du poids total de la commande. Ils sont indiqués avant la validation de la commande.
                    </p>
                    <p>
                        En cas d'absence lors de la livraison, un avis de passage sera laissé. Le Client devra contacter le service client pour organiser une nouvelle livraison.
                    </p>
                    
                    <h2>7. Droit de rétractation et retours</h2>
                    <p>
                        Conformément à la législation en vigueur, et en raison de la nature périssable de nos produits, le Client ne bénéficie pas d'un droit de rétractation pour les denrées alimentaires.
                    </p>
                    <p>
                        Toutefois, en cas de produit défectueux ou non conforme, le Client dispose d'un délai de 24 heures à compter de la réception pour nous en informer par email ou téléphone. Une photo du produit concerné pourra être demandée. Après vérification, Tradition Sucrée procédera, au choix du Client, à un remboursement ou à un remplacement du produit.
                    </p>
                    
                    <h2>8. Garanties</h2>
                    <p>
                        Tradition Sucrée garantit que ses produits sont conformes à leur description et répondent aux normes d'hygiène et de sécurité alimentaire en vigueur au Maroc.
                    </p>
                    <p>
                        Le Client est responsable du respect des conditions de conservation indiquées sur l'emballage ou dans la description du produit.
                    </p>
                    
                    <h2>9. Responsabilité</h2>
                    <p>
                        Tradition Sucrée ne pourra être tenue responsable des dommages indirects résultant de l'utilisation de ses produits, ni des retards de livraison dus à des circonstances indépendantes de sa volonté (intempéries, grèves, etc.).
                    </p>
                    <p>
                        Le Client est responsable de vérifier que les produits commandés ne contiennent pas d'ingrédients auxquels il serait allergique. La composition des produits est indiquée sur notre site et sur l'emballage.
                    </p>
                    
                    <h2>10. Propriété intellectuelle</h2>
                    <p>
                        Tous les éléments du site www.traditionsucrée.ma (textes, images, logos, etc.) sont la propriété exclusive de Tradition Sucrée ou de ses partenaires. Toute reproduction, représentation ou diffusion, totale ou partielle, est strictement interdite.
                    </p>
                    
                    <h2>11. Protection des données personnelles</h2>
                    <p>
                        Les informations collectées lors de la commande sont nécessaires au traitement de celle-ci et peuvent être transmises aux partenaires chargés de son exécution (livraison, paiement).
                    </p>
                    <p>
                        Conformément à notre Politique de Confidentialité, le Client dispose d'un droit d'accès, de rectification et d'opposition aux données le concernant, qu'il peut exercer en nous contactant à privacy@traditionsucrée.ma.
                    </p>
                    
                    <h2>12. Droit applicable et litiges</h2>
                    <p>
                        Les présentes CGV sont soumises au droit marocain. En cas de litige, une solution amiable sera recherchée avant toute action judiciaire. À défaut, les tribunaux de Casablanca seront seuls compétents.
                    </p>
                    
                    <h2>13. Service client</h2>
                    <p>
                        Pour toute question relative à une commande ou à nos produits, notre service client est disponible :
                    </p>
                    <ul>
                        <li>Par téléphone : +212 522 123 456 (du lundi au samedi, de 9h à 18h)</li>
                        <li>Par email : contact@traditionsucrée.ma</li>
                        <li>Via le formulaire de contact sur notre site</li>
                    </ul>
                    
                    <h2>14. Modifications des CGV</h2>
                    <p>
                        Tradition Sucrée se réserve le droit de modifier les présentes CGV à tout moment. Les CGV applicables sont celles en vigueur au moment de la passation de la commande.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection