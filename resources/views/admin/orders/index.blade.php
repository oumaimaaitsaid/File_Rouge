@extends('admin.layout')

@section('title', 'Gestion des commandes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Gestion des commandes</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <!-- Filtres et recherche -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="N° commande, nom client...">
            </div>
            
            <div class="w-full md:w-1/4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="all">Tous les statuts</option>
                    <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="confirmee" {{ request('status') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                    <option value="preparee" {{ request('status') == 'preparee' ? 'selected' : '' }}>Préparée</option>
                    <option value="expediee" {{ request('status') == 'expediee' ? 'selected' : '' }}>Expédiée</option>
                    <option value="livree" {{ request('status') == 'livree' ? 'selected' : '' }}>Livrée</option>
                    <option value="annulee" {{ request('status') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
            
            <div class="w-full md:w-1/4">
                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Paiement</label>
                <select id="payment_status" name="payment_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="">Tous</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payée</option>
                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Non payée</option>
                </select>
            </div>
            
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-300">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
            </div>
            
            <div class="w-full md:w-auto">
                <a href="{{ route('admin.orders.index') }}" class="inline-block w-full md:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md transition-colors duration-300 text-center">
                    <i class="fas fa-redo mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
    
    <!-- En-tête des résultats -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-700">
                {{ $orders->total() }} commande(s) trouvée(s)
            </h3>
        </div>
    </div>
    
    <!-- Tableau des commandes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'numero_commande', 'direction' => request('sort') === 'numero_commande' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                N° Commande
                                @if(request('sort') === 'numero_commande')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'montant_total', 'direction' => request('sort') === 'montant_total' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                Montant
                                @if(request('sort') === 'montant_total')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'statut', 'direction' => request('sort') === 'statut' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                Statut
                                @if(request('sort') === 'statut')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'paiement_confirme', 'direction' => request('sort') === 'paiement_confirme' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                Paiement
                                @if(request('sort') === 'paiement_confirme')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.orders.index', array_merge(request()->except(['sort', 'direction']), ['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center">
                                Date
                                @if(request('sort') === 'created_at')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-accent">
                                    {{ $order->numero_commande }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $order->user->name ?? 'Client supprimé' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $order->user->email ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($order->montant_total, 2) }} MAD</div>
                                @if($order->remise > 0)
                                    <div class="text-xs text-gray-500">
                                        Remise: {{ number_format($order->remise, 2) }} MAD
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->statut === 'en_attente')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        En attente
                                    </span>
                                @elseif($order->statut === 'confirmee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Confirmée
                                    </span>
                                @elseif($order->statut === 'preparee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        Préparée
                                    </span>
                                @elseif($order->statut === 'expediee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Expédiée
                                    </span>
                                @elseif($order->statut === 'livree')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Livrée
                                    </span>
                                @elseif($order->statut === 'annulee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Annulée
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->paiement_confirme)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Payée
                                    </span>
                                    @if($order->date_paiement)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($order->date_paiement)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Non payée
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary hover:text-primary-dark" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Aucune commande trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
       
    </div>
</div>
@endsection