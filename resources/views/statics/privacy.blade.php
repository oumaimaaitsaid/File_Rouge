@extends('layouts.app')

@section('title', 'Politique de Confidentialité - Tradition Sucrée')

@section('content')
    <!-- Bannière -->
    <div class="relative py-12 bg-cover bg-center" style="background-image: url('{{ asset('images/privacy-banner.jpg') }}');">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="font-playfair text-4xl md:text-5xl font-bold text-white mb-4">Politique de Confidentialité</h1>
            <p class="text-gray-200 max-w-2xl mx-auto">
                Chez Tradition Sucrée, nous accordons une grande importance à la protection de vos données personnelles.
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
                    
                    <h2>Introduction</h2>
                    <p>
                        Chez Tradition Sucrée, nous nous engageons à protéger votre vie privée. Cette politique de confidentialité explique comment nous collectons, utilisons, divulguons et protégeons vos informations lorsque vous visitez notre site web ou utilisez nos services.
                    </p>
                    <p>
                        En utilisant notre site web et nos services, vous acceptez les pratiques décrites dans cette politique de confidentialité. Si vous n'acceptez pas cette politique, veuillez ne pas utiliser notre site web ou nos services.
                    </p>
                    
                    <h2>Informations que nous collectons</h2>
                    <p>
                        Nous pouvons collecter différents types d'informations vous concernant, notamment :
                    </p>
                    <ul>
                        <li>
                            <strong>Informations personnelles</strong> : nom, adresse e-mail, numéro de téléphone, adresse postale, informations de paiement lorsque vous passez une commande.
                        </li>
                        <li>
                            <strong>Informations de compte</strong> : identifiants de connexion, préférences, historique d'achat lorsque vous créez un compte.
                        </li>
                        <li>
                            <strong>Informations d'utilisation</strong> : comment vous interagissez avec notre site web, les pages que vous visitez, les fonctionnalités que vous utilisez.
                        </li>
                        <li>
                            <strong>Informations techniques</strong> : adresse IP, type de navigateur, fournisseur d'accès Internet, système d'exploitation, horodatage, pages de référence/sortie, clics.
                        </li>
                    </ul>
                    
                    <h2>Comment nous collectons vos informations</h2>
                    <p>
                        Nous collectons vos informations de différentes manières :
                    </p>
                    <ul>
                        <li>
                            <strong>Directement auprès de vous</strong> : lorsque vous remplissez un formulaire, créez un compte, passez une commande ou nous contactez.
                        </li>
                        <li>
                            <strong>Automatiquement</strong> : lorsque vous naviguez sur notre site web, nous utilisons des cookies et des technologies similaires pour collecter des informations sur votre utilisation.
                        </li>
                        <li>
                            <strong>De tiers</strong> : nous pouvons recevoir des informations vous concernant de la part de partenaires commerciaux, de prestataires de services ou de sources accessibles au public.
                        </li>
                    </ul>
                    
                    <h2>Comment nous utilisons vos informations</h2>
                    <p>
                        Nous utilisons vos informations pour :
                    </p>
                    <ul>
                        <li>Fournir, maintenir et améliorer nos services</li>
                        <li>Traiter et livrer vos commandes</li>
                        <li>Communiquer avec vous concernant votre compte, vos commandes ou nos services</li>
                        <li>Vous envoyer des informations marketing si vous avez choisi de les recevoir</li>
                        <li>Personnaliser votre expérience sur notre site web</li>
                        <li>Analyser l'utilisation de notre site web pour améliorer nos services</li>
                        <li>Détecter, prévenir et résoudre les problèmes techniques ou de sécurité</li>
                        <li>Se conformer aux obligations légales</li>
                    </ul>
                    
                    <h2>Partage de vos informations</h2>
                    <p>
                        Nous pouvons partager vos informations avec :
                    </p>
                    <ul>
                        <li>
                            <strong>Prestataires de services</strong> : entreprises qui nous aident à fournir nos services (traitement des paiements, livraison, hébergement web, analyse de données).
                        </li>
                        <li>
                            <strong>Partenaires commerciaux</strong> : avec votre consentement, nous pouvons partager vos informations avec des partenaires sélectionnés.
                        </li>
                        <li>
                            <strong>Autorités légales</strong> : lorsque nous sommes légalement tenus de le faire ou pour protéger nos droits.
                        </li>
                    </ul>
                    <p>
                        Nous ne vendons pas vos données personnelles à des tiers.
                    </p>
                    
                    <h2>Cookies et technologies similaires</h2>
                    <p>
                        Nous utilisons des cookies et des technologies similaires pour collecter des informations sur votre utilisation de notre site web. Les cookies sont de petits fichiers texte stockés sur votre appareil qui nous aident à améliorer votre expérience.
                    </p>
                    <p>
                        Vous pouvez configurer votre navigateur pour refuser tous les cookies ou pour vous avertir lorsqu'un cookie est envoyé. Cependant, certaines fonctionnalités de notre site web peuvent ne pas fonctionner correctement sans cookies.
                    </p>
                    
                    <h2>Sécurité de vos informations</h2>
                    <p>
                        Nous mettons en œuvre des mesures de sécurité appropriées pour protéger vos informations contre l'accès non autorisé, l'altération, la divulgation ou la destruction. Cependant, aucune méthode de transmission sur Internet ou de stockage électronique n'est totalement sécurisée, et nous ne pouvons garantir une sécurité absolue.
                    </p>
                    
                    <h2>Conservation des données</h2>
                    <p>
                        Nous conservons vos informations aussi longtemps que nécessaire pour fournir nos services, respecter nos obligations légales, résoudre les litiges et faire respecter nos accords. La durée de conservation dépend du type d'information et des exigences légales applicables.
                    </p>
                    
                    <h2>Vos droits</h2>
                    <p>
                        Selon votre lieu de résidence, vous pouvez avoir certains droits concernant vos informations personnelles, notamment :
                    </p>
                    <ul>
                        <li>Accéder à vos informations personnelles</li>
                        <li>Corriger vos informations personnelles</li>
                        <li>Supprimer vos informations personnelles</li>
                        <li>Restreindre ou vous opposer au traitement de vos informations personnelles</li>
                        <li>Recevoir vos informations personnelles dans un format structuré</li>
                        <li>Retirer votre consentement à tout moment</li>
                    </ul>
                    <p>
                        Pour exercer ces droits, veuillez nous contacter à l'adresse indiquée ci-dessous.
                    </p>
                    
                    <h2>Modifications de cette politique</h2>
                    <p>
                        Nous pouvons mettre à jour cette politique de confidentialité de temps à autre. La version la plus récente sera toujours disponible sur notre site web, avec la date de la dernière mise à jour. Nous vous encourageons à consulter régulièrement cette politique pour rester informé de la façon dont nous protégeons vos informations.
                    </p>
                    
                    <h2>Contact</h2>
                    <p>
                        Si vous avez des questions ou des préoccupations concernant cette politique de confidentialité ou le traitement de vos informations personnelles, veuillez nous contacter à :
                    </p>
                    <p>
                        Tradition Sucrée<br>
                        123 Boulevard Mohammed V<br>
                        Casablanca, 20250<br>
                        Maroc<br>
                        Email : privacy@traditionsucrée.ma<br>
                        Téléphone : +212 522 123 456
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection