@extends('layouts.app')

@section('title', 'Commande confirmée')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 bg-green-50 border-b border-green-100 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Commande confirmée</h1>
                <p class="text-gray-600">Merci pour votre commande !</p>
            </div>
            
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-medium text-gray-800 mb-3">Détails de la commande</h2>
                    <div class="bg-gray-50 p-4 rounded-md space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Numéro de commande:</span>
                            <span class="font-medium">{{ $commande->numero_commande }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span>{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Statut:</span>
                            <span class="px-2 py-1 text-xs rounded {{ $commande->statut == 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Méthode de paiement:</span>
                            <span>{{ ucfirst(str_replace('_', ' ', $commande->methode_paiement)) }}</span>
                        </div>
                        @if($commande->paiement_confirme)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date de paiement:</span>
                                <span>{{ $commande->date_paiement ? $commande->date_paiement->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mb-6">
                    <h2 class="text-xl font-medium text-gray-800 mb-3">Produits commandés</h2>
                    <div class="space-y-3">
                        @foreach($commande->ligneCommandes as $ligne)
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <div>
                                    <div class="font-medium">{{ $ligne->nom_produit }}</div>
                                    <div class="text-sm text-gray-600">Quantité: {{ $ligne->quantite }} x {{ number_format($ligne->prix_unitaire, 2) }} MAD</div>
                                </div>
                                <div class="font-medium">
                                    {{ number_format($ligne->total, 2) }} MAD
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-6">
                    <h2 class="text-xl font-medium text-gray-800 mb-3">Adresse de livraison</h2>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p>{{ $commande->adresse_livraison }}</p>
                        <p>{{ $commande->code_postal_livraison }} {{ $commande->ville_livraison }}</p>
                        <p>{{ $commande->pays_livraison }}</p>
                        <p>Téléphone: {{ $commande->telephone_livraison }}</p>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-xl font-medium text-gray-800 mb-3">Récapitulatif</h2>
                    <div class="bg-gray-50 p-4 rounded-md space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sous-total:</span>
                            <span>{{ number_format($commande->montant_total - $commande->frais_livraison + $commande->remise, 2) }} MAD</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frais de livraison:</span>
                            <span>{{ number_format($commande->frais_livraison, 2) }} MAD</span>
                        </div>
                        @if($commande->remise > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Réduction:</span>
                                <span>-{{ number_format($commande->remise, 2) }} MAD</span>
                            </div>
                        @endif
                        <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-2 mt-2">
                            <span class="text-gray-800">Total:</span>
                            <span class="text-primary">{{ number_format($commande->montant_total, 2) }} MAD</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between mt-8">
                    <a href="{{ route('orders.index') }}" class="text-primary hover:underline">
                        <i class="fas fa-clipboard-list mr-2"></i> Voir mes commandes
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-primary transition-colors duration-200">
                        <i class="fas fa-shopping-cart mr-2"></i> Continuer les achats
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection