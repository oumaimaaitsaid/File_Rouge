@extends('layouts.app')

@section('title', 'Détail de la commande')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Commande #{{ $commande->numero_commande }}</h1>
            <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-primary transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Retour aux commandes
            </a>
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Informations de commande</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-800 mb-3">Détails</h3>
                        <div class="bg-gray-50 p-4 rounded-md space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span>{{ $commande->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Statut:</span>
                                <span class="px-2 py-1 text-xs rounded 
                                    @if($commande->statut == 'en_attente')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($commande->statut == 'confirmee')
                                        bg-blue-100 text-blue-800
                                    @elseif($commande->statut == 'en_preparation')
                                        bg-purple-100 text-purple-800
                                    @elseif($commande->statut == 'en_livraison')
                                        bg-indigo-100 text-indigo-800
                                    @elseif($commande->statut == 'livree')
                                        bg-green-100 text-green-800
                                    @elseif($commande->statut == 'annulee')
                                        bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Paiement:</span>
                                <span class="px-2 py-1 text-xs rounded 
                                    @if($commande->paiement_confirme)
                                        bg-green-100 text-green-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ $commande->paiement_confirme ? 'Payé' : 'En attente' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Méthode:</span>
                                <span>{{ ucfirst(str_replace('_', ' ', $commande->methode_paiement)) }}</span>
                            </div>
                            @if($commande->paiement_confirme && $commande->reference_paiement)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Référence:</span>
                                    <span>{{ $commande->reference_paiement }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-medium text-gray-800 mb-3">Adresse de livraison</h3>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <p>{{ $commande->adresse_livraison }}</p>
                            <p>{{ $commande->code_postal_livraison }} {{ $commande->ville_livraison }}</p>
                            <p>{{ $commande->pays_livraison }}</p>
                            <p>Téléphone: {{ $commande->telephone_livraison }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Produits commandés</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($commande->ligneCommandes as $ligne)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800">{{ $ligne->nom_produit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($ligne->prix_unitaire, 2) }} MAD
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $ligne->quantite }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">
                                    {{ number_format($ligne->total, 2) }} MAD
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 border-t border-gray-200">
                <div class="max-w-md ml-auto">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Sous-total:</span>
                        <span>{{ number_format($commande->montant_total - $commande->frais_livraison + $commande->remise, 2) }} MAD</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Frais de livraison:</span>
                        <span>{{ number_format($commande->frais_livraison, 2) }} MAD</span>
                    </div>
                    @if($commande->remise > 0)
                        <div class="flex justify-between mb-2 text-green-600">
                            <span>Réduction:</span>
                            <span>-{{ number_format($commande->remise, 2) }} MAD</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-lg border-t border-gray-200 pt-3 mt-3">
                        <span class="text-gray-800">Total:</span>
                        <span class="text-primary">{{ number_format($commande->montant_total, 2) }} MAD</span>
                    </div>
                </div>
            </div>
        </div>
        
        @if($commande->notes)
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">Notes</h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-700">{{ $commande->notes }}</p>
                </div>
            </div>
        @endif
        
        <div class="flex justify-between">
            <div>
                @if(($commande->statut == 'en_attente' || $commande->statut == 'confirmee') && !$commande->paiement_confirme)
                    <form action="{{ route('orders.cancel', $commande->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md font-medium transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i> Annuler la commande
                        </button>
                    </form>
                @endif
                
                @if(!$commande->paiement_confirme && $commande->statut != 'annulee' && $commande->methode_paiement != 'a_la_livraison')
                    <form action="{{ route('payment.confirm', $commande->id) }}" method="POST" class="inline ml-2">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md font-medium transition-colors duration-200">
                            <i class="fas fa-credit-card mr-2"></i> Confirmer le paiement
                        </button>
                    </form>
                @endif
            </div>
            
            <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-primary transition-colors duration-200">
                Retour à mes commandes
            </a>
        </div>
    </div>
</div>
@endsection