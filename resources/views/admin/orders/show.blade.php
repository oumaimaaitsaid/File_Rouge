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
            
        </div>
    </div>
    
</div>
@endsection