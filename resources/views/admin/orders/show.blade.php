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
            </div>
        </div>
    </div>
    
</div>
@endsection