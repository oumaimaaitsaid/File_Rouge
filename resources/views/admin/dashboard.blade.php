@extends('admin.layout')

@section('title', 'Tableau de bord administrateur')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Tableau de bord</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Statistique Clients -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Clients</h3>
                <div class="rounded-full bg-blue-100 p-3 text-blue-500">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ $totalUsers }}</div>
            <p class="text-gray-500 text-sm">Clients inscrits</p>
        </div>
        
        <!-- Statistique Produits -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Produits</h3>
                <div class="rounded-full bg-green-100 p-3 text-green-500">
                    <i class="fas fa-cookie text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ $totalProducts }}</div>
            <p class="text-gray-500 text-sm">Produits en catalogue</p>
        </div>
        
        <!-- Statistique Commandes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-amber-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Commandes</h3>
                <div class="rounded-full bg-amber-100 p-3 text-amber-500">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ $totalOrders }}</div>
            <p class="text-gray-500 text-sm">Commandes totales</p>
        </div>
        
        <!-- Statistique Revenus -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Revenus</h3>
                <div class="rounded-full bg-purple-100 p-3 text-purple-500">
                    <i class="fas fa-money-bill text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ number_format($totalRevenue, 2) }} MAD</div>
            <p class="text-gray-500 text-sm">Revenus totaux</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Commandes récentes -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4 flex justify-between items-center">
                <h3 class="font-bold text-lg">Commandes récentes</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-white hover:text-secondary transition-colors duration-200">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Commande</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary hover:text-primary-dark font-medium">
                                        {{ $order->numero_commande }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $order->user->name ?? 'Client supprimé' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">
                                    {{ number_format($order->montant_total, 2) }} MAD
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Aucune commande récente
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50">
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors duration-200">
                    Voir toutes les commandes <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        
        <!-- Produits les plus vendus -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4 flex justify-between items-center">
                <h3 class="font-bold text-lg">Produits les plus vendus</h3>
                <a href="{{ route('admin.statistics.products') }}" class="text-white hover:text-secondary transition-colors duration-200">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité vendue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chiffre d'affaires</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($popularProducts as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-accent">
                                    {{ $product->nom_produit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                    {{ $product->total_vendus }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                    Aucune donnée disponible
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50">
                <a href="{{ route('admin.statistics.products') }}" class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors duration-200">
                    Voir toutes les statistiques produits <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Derniers utilisateurs -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4">
                <h3 class="font-bold text-lg">Nouveaux clients</h3>
            </div>
            <div class="p-4">
                <ul class="divide-y divide-gray-200">
                    @forelse($latestUsers ?? [] as $user)
                        <li class="py-3 flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-accent truncate">
                                    {{ $user->name }} {{ $user->prenom }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $user->email }}
                                </p>
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </li>
                    @empty
                        <li class="py-3 text-center text-gray-500">
                            Aucun client récent
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="px-4 py-3 bg-gray-50">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors duration-200">
                    Gérer les utilisateurs <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        
        <!-- Derniers avis -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4">
                <h3 class="font-bold text-lg">Derniers avis</h3>
            </div>
            <div class="p-4">
                <ul class="divide-y divide-gray-200">
                    @forelse($latestReviews ?? [] as $review)
                        <li class="py-3">
                            <div class="flex items-center mb-1">
                                <p class="text-sm font-medium text-accent truncate mr-2">
                                    {{ $review->user->name ?? 'Client' }}
                                </p>
                                <div class="flex text-yellow-400 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->note)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 line-clamp-2">
                                {{ $review->commentaire }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Sur : {{ $review->produit->nom ?? 'Produit' }} - {{ $review->created_at->diffForHumans() }}
                            </p>
                        </li>
                    @empty
                        <li class="py-3 text-center text-gray-500">
                            Aucun avis récent
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="px-4 py-3 bg-gray-50">
                <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors duration-200">
                    Gérer les avis <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
        
        <!-- Promotions actives -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4">
                <h3 class="font-bold text-lg">Promotions actives</h3>
            </div>
            <div class="p-4">
                <ul class="divide-y divide-gray-200">
                    @forelse($activePromotions ?? [] as $promo)
                        <li class="py-3">
                            <div class="flex items-center">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-accent truncate">
                                        {{ $promo->code }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        @if($promo->type === 'pourcentage')
                                            {{ $promo->valeur }}% de réduction
                                        @elseif($promo->type === 'montant')
                                            {{ number_format($promo->valeur, 2) }} MAD de réduction
                                        @elseif($promo->type === 'livraison_gratuite')
                                            Livraison gratuite
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Expire le {{ \Carbon\Carbon::parse($promo->date_fin)->format('d/m/Y') }}
                                    </p>
                                </div>
                                <div class="ml-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-3 text-center text-gray-500">
                            Aucune promotion active
                        </li>
                    @endforelse
                </ul>
            </div>
            <div class="px-4 py-3 bg-gray-50">
                <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors duration-200">
                    Gérer les promotions <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h3 class="font-bold text-lg text-accent mb-4">Actions rapides</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.products.create') }}" class="flex items-center justify-center p-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors duration-300">
                <i class="fas fa-plus mr-2"></i> Ajouter un produit
            </a>
            <a href="{{ route('admin.categories.create') }}" class="flex items-center justify-center p-4 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-300">
                <i class="fas fa-folder-plus mr-2"></i> Nouvelle catégorie
            </a>
            <a href="{{ route('admin.promotions.create') }}" class="flex items-center justify-center p-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-300">
                <i class="fas fa-tag mr-2"></i> Créer une promotion
            </a>
            <a href="{{ route('admin.statistics.sales') }}" class="flex items-center justify-center p-4 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors duration-300">
                <i class="fas fa-chart-line mr-2"></i> Voir les statistiques
            </a>
        </div>
    </div>
</div>
@endsection