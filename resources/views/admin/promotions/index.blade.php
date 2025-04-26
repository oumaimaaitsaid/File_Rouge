@extends('admin.layout')

@section('title', 'Gestion des promotions')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Gestion des promotions</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <!-- Filtres et recherche -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('admin.promotions.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Code, description...">
            </div>
            
            <div class="w-full md:w-1/4">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="all">Tous les types</option>
                    <option value="pourcentage" {{ request('type') == 'pourcentage' ? 'selected' : '' }}>Pourcentage</option>
                    <option value="montant" {{ request('type') == 'montant' ? 'selected' : '' }}>Montant</option>
                    <option value="livraison_gratuite" {{ request('type') == 'livraison_gratuite' ? 'selected' : '' }}>Livraison gratuite</option>
                </select>
            </div>
            
            <div class="w-full md:w-1/4">
                <label for="active" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="active" name="active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="">Tous</option>
                    <option value="yes" {{ request('active') == 'yes' ? 'selected' : '' }}>Actives</option>
                    <option value="no" {{ request('active') == 'no' ? 'selected' : '' }}>Inactives</option>
                </select>
            </div>
            
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-300">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
            </div>
            
            <div class="w-full md:w-auto">
                <a href="{{ route('admin.promotions.index') }}" class="inline-block w-full md:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md transition-colors duration-300 text-center">
                    <i class="fas fa-redo mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
    
    <!-- En-tête et bouton d'ajout -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-700">
                {{ $promotions->total() }} promotion(s) trouvée(s)
            </h3>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md transition-colors duration-300">
                <i class="fas fa-plus-circle mr-2"></i> Ajouter une promotion
            </a>
        </div>
    </div>
    
    <!-- Tableau des promotions -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Période</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($promotions as $promotion)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-accent">
                                    {{ $promotion->code }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $promotion->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($promotion->type === 'pourcentage')
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-percentage mr-2 text-primary"></i> Pourcentage
                                        </span>
                                    @elseif($promotion->type === 'montant')
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-money-bill mr-2 text-green-600"></i> Montant
                                        </span>
                                    @elseif($promotion->type === 'livraison_gratuite')
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-shipping-fast mr-2 text-purple-600"></i> Livraison gratuite
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($promotion->type === 'pourcentage')
                                        <span class="font-medium">{{ $promotion->valeur }}%</span>
                                    @elseif($promotion->type === 'montant')
                                        <span class="font-medium">{{ number_format($promotion->valeur, 2) }} MAD</span>
                                    @elseif($promotion->type === 'livraison_gratuite')
                                        <span class="font-medium">-</span>
                                    @endif
                                </div>
                                @if($promotion->commande_minimum)
                                    <div class="text-xs text-gray-500">
                                        Min: {{ number_format($promotion->commande_minimum, 2) }} MAD
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($promotion->date_debut)->format('d/m/Y') }}
                                    -
                                    @if($promotion->date_fin)
                                        {{ \Carbon\Carbon::parse($promotion->date_fin)->format('d/m/Y') }}
                                    @else
                                        <span class="text-gray-500">Illimitée</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $isActive = $promotion->date_debut <= $now && 
                                              ($promotion->date_fin === null || $promotion->date_fin >= $now);
                                @endphp
                                
                                @if($isActive)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @elseif($promotion->date_debut > $now)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        À venir
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Expirée
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="text-primary hover:text-primary-dark" title="Éditer">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Aucune promotion trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $promotions->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
@endsection