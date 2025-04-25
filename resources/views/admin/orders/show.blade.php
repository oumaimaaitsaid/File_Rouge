@extends('admin.layout')

@section('title', 'Détails de la commande #' . $order->numero_commande)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">
                    Commande #{{ $order->numero_commande }}
                </h2>
                <div class="w-20 h-1 bg-primary"></div>
            </div>
            
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
    
    <!-- Statut de la commande et Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h3 class="text-lg font-bold text-accent mb-2">Statut de la commande</h3>
                <div class="flex flex-col md:flex-row gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Statut actuel:</span>
                        @if($order->statut === 'en_attente')
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                        @elseif($order->statut === 'confirmee')
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Confirmée
                            </span>
                        @elseif($order->statut === 'preparee')
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                Préparée
                            </span>
                        @elseif($order->statut === 'expediee')
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Expédiée
                            </span>
                        @elseif($order->statut === 'livree')
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Livrée
                            </span>
                        @elseif($order->statut === 'annulee')
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Annulée
                            </span>
                        @endif
                    </div>
                    
                    <div>
                        <span class="text-sm text-gray-500">Paiement:</span>
                        @if($order->paiement_confirme)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Payée
                            </span>
                        @else
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Non payée
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-2">
                <!-- Formulaire de mise à jour du statut -->
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    <div class="flex gap-2">
                        <select name="statut" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm">
                            <option value="en_attente" {{ $order->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="confirmee" {{ $order->statut === 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                            <option value="preparee" {{ $order->statut === 'preparee' ? 'selected' : '' }}>Préparée</option>
                            <option value="expediee" {{ $order->statut === 'expediee' ? 'selected' : '' }}>Expédiée</option>
                            <option value="livree" {{ $order->statut === 'livree' ? 'selected' : '' }}>Livrée</option>
                            <option value="annulee" {{ $order->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-300 text-sm">
                            Mettre à jour le statut
                        </button>
                    </div>
                </form>
                
                <!-- Formulaire de mise à jour du paiement -->
                <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    <input type="hidden" name="paiement_confirme" value="{{ $order->paiement_confirme ? '0' : '1' }}">
                    <button type="submit" class="{{ $order->paiement_confirme ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white py-2 px-4 rounded-md transition-colors duration-300 text-sm">
                        @if($order->paiement_confirme)
                            <i class="fas fa-times-circle mr-1"></i> Marquer comme non payée
                        @else
                            <i class="fas fa-check-circle mr-1"></i> Marquer comme payée
                        @endif
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Colonne 1: Informations client -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Informations client</h3>
                </div>
                <div class="p-4">
                    @if($order->user)
                        <div class="mb-4">
                            <h4 class="font-bold text-accent">{{ $order->user->name }} {{ $order->user->prenom }}</h4>
                            <p class="text-gray-600">{{ $order->user->email }}</p>
                            @if($order->user->telephone)
                                <p class="text-gray-600">{{ $order->user->telephone }}</p>
                            @endif
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('admin.users.show', $order->user->id) }}" class="text-primary hover:text-primary-dark text-sm">
                                Voir le profil client <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Client supprimé</p>
                    @endif
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Adresse de livraison</h3>
                </div>
                <div class="p-4">
                    <p>{{ $order->adresse_livraison }}</p>
                    <p>{{ $order->code_postal_livraison }} {{ $order->ville_livraison }}</p>
                    <p>{{ $order->pays_livraison }}</p>
                    <p class="mt-2">
                        <span class="font-medium">Téléphone:</span> {{ $order->telephone_livraison }}
                    </p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Paiement</h3>
                </div>
                <div class="p-4">
                    <div class="mb-2">
                        <span class="font-medium">Méthode de paiement:</span>
                        @if($order->methode_paiement === 'carte')
                            <span class="ml-2">Carte bancaire</span>
                        @elseif($order->methode_paiement === 'livraison')
                            <span class="ml-2">Paiement à la livraison</span>
                        @elseif($order->methode_paiement === 'virement')
                            <span class="ml-2">Virement bancaire</span>
                        @else
                            <span class="ml-2">{{ $order->methode_paiement }}</span>
                        @endif
                    </div>
                    
                    @if($order->reference_paiement)
                        <div class="mb-2">
                            <span class="font-medium">Référence:</span>
                            <span class="ml-2">{{ $order->reference_paiement }}</span>
                        </div>
                    @endif
                    
                    @if($order->date_paiement)
                        <div class="mb-2">
                            <span class="font-medium">Date de paiement:</span>
                            <span class="ml-2">{{ \Carbon\Carbon::parse($order->date_paiement)->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    
                    <div class="mb-2">
                        <span class="font-medium">Statut:</span>
                        @if($order->paiement_confirme)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Payée
                            </span>
                        @else
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Non payée
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Colonne 2: Détails de la commande -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Détails de la commande</h3>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->ligneCommandes as $ligne)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if(isset($ligne->produit) && $ligne->produit->imagePrincipale)
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ asset('storage/' . $ligne->produit->imagePrincipale->chemin) }}" alt="{{ $ligne->nom_produit }}">
                                                    </div>
                                                @endif
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-accent">
                                                        {{ $ligne->nom_produit }}
                                                    </div>
                                                    @if(isset($ligne->produit))
                                                        <div class="text-xs text-gray-500">
                                                            <a href="{{ route('admin.products.edit', $ligne->produit->id) }}" class="text-primary hover:text-primary-dark">
                                                                Voir le produit
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="text-xs text-gray-500 italic">
                                                            Produit supprimé
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($ligne->prix_unitaire, 2) }} MAD
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ligne->quantite }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ number_format($ligne->total, 2) }} MAD
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Sous-total:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($order->ligneCommandes->sum('total'), 2) }} MAD
                                    </td>
                                </tr>
                                @if($order->remise > 0)
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Remise:</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                            -{{ number_format($order->remise, 2) }} MAD
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Frais de livraison:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($order->frais_livraison, 2) }} MAD
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-sm font-bold text-accent text-right">Total:</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-accent">
                                        {{ number_format($order->montant_total, 2) }} MAD
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-accent text-white p-4">
                        <h3 class="font-bold text-lg">Notes du client</h3>
                    </div>
                    <div class="p-4">
                        @if($order->notes)
                            <p class="text-gray-700">{{ $order->notes }}</p>
                        @else
                            <p class="text-gray-500 italic">Aucune note</p>
                        @endif
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-accent text-white p-4">
                        <h3 class="font-bold text-lg">Notes administratives</h3>
                    </div>
                    <div class="p-4">
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <textarea name="notes_admin" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ $order->notes_admin }}</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-primary hover:bg-primary-dark text-white py-1 px-3 rounded-md transition-colors duration-300 text-sm">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Chronologie -->
        </div>
    </div>
</div>
@endsection