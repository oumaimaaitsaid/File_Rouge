@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Mes Commandes</h1>
        
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
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($commandes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                               <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commande</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($commandes as $commande)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('orders.show', $commande->id) }}" class="text-primary hover:underline font-medium">
                                            {{ $commande->numero_commande }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $commande->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium">{{ number_format($commande->montant_total, 2) }} MAD</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded 
                                            @if($commande->paiement_confirme)
                                                bg-green-100 text-green-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif
                                        ">
                                            {{ $commande->paiement_confirme ? 'Payé' : 'En attente' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('orders.show', $commande->id) }}" class="text-primary hover:text-primary-dark mr-3">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(($commande->statut == 'en_attente' || $commande->statut == 'confirmee') && !$commande->paiement_confirme)
                                            <form action="{{ route('orders.cancel', $commande->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 border-t border-gray-200">
                    {{ $commandes->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-clipboard-list fa-3x"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune commande trouvée</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore passé de commande.</p>
                    <a href="{{ route('products.index') }}" class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md font-medium transition-colors duration-200">
                        Commencer vos achats
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection