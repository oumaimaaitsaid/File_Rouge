@extends('admin.layout')

@section('title', 'Statistiques')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Statistiques</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Statistiques de ventes -->
        <a href="{{ route('admin.statistics.sales') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Ventes</h3>
                <div class="rounded-full bg-blue-100 p-3 text-blue-500">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
            <p class="text-gray-700 mb-4">Analysez les tendances de ventes, les revenus et les performances commerciales</p>
            <div class="mt-4 text-primary font-medium flex items-center">
                Voir les détails <i class="fas fa-arrow-right ml-2"></i>
            </div>
        </a>
        
        <!-- Statistiques de produits -->
        <a href="{{ route('admin.statistics.products') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Produits</h3>
                <div class="rounded-full bg-green-100 p-3 text-green-500">
                    <i class="fas fa-cookie text-xl"></i>
                </div>
            </div>
            <p class="text-gray-700 mb-4">Consultez les produits et catégories les plus populaires, les stocks et les tendances</p>
            <div class="mt-4 text-primary font-medium flex items-center">
                Voir les détails <i class="fas fa-arrow-right ml-2"></i>
            </div>
        </a>
        
        <!-- Statistiques clients -->
        <a href="{{ route('admin.statistics.users') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Clients</h3>
                <div class="rounded-full bg-purple-100 p-3 text-purple-500">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <p class="text-gray-700 mb-4">Analysez les données clients, la fidélisation et les comportements d'achat</p>
            <div class="mt-4 text-primary font-medium flex items-center">
                Voir les détails <i class="fas fa-arrow-right ml-2"></i>
            </div>
        </a>
    </div>
    
    <!-- Résumé rapide -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="font-bold text-accent text-lg mb-4">Résumé des performances</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total des ventes ce mois -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-gray-500 text-sm mb-1">Ventes du mois</div>
                <div class="text-2xl font-bold text-accent">{{ number_format($monthlySales ?? 0, 2) }} MAD</div>
                @if(isset($salesGrowth))
                    <div class="flex items-center mt-2 text-sm {{ $salesGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        <i class="fas fa-{{ $salesGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($salesGrowth) }}% vs mois dernier
                    </div>
                @endif
            </div>
            
            <!-- Commandes ce mois -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-gray-500 text-sm mb-1">Commandes du mois</div>
                <div class="text-2xl font-bold text-accent">{{ $monthlyOrders ?? 0 }}</div>
                @if(isset($ordersGrowth))
                    <div class="flex items-center mt-2 text-sm {{ $ordersGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        <i class="fas fa-{{ $ordersGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($ordersGrowth) }}% vs mois dernier
                    </div>
                @endif
            </div>
            
            <!-- Clients actifs -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-gray-500 text-sm mb-1">Clients actifs</div>
                <div class="text-2xl font-bold text-accent">{{ $activeCustomers ?? 0 }}</div>
                @if(isset($customersGrowth))
                    <div class="flex items-center mt-2 text-sm {{ $customersGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        <i class="fas fa-{{ $customersGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($customersGrowth) }}% vs mois dernier
                    </div>
                @endif
            </div>
            
            <!-- Moyenne panier -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-gray-500 text-sm mb-1">Panier moyen</div>
                <div class="text-2xl font-bold text-accent">{{ number_format($averageOrder ?? 0, 2) }} MAD</div>
                @if(isset($avgOrderGrowth))
                    <div class="flex items-center mt-2 text-sm {{ $avgOrderGrowth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        <i class="fas fa-{{ $avgOrderGrowth >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                        {{ abs($avgOrderGrowth) }}% vs mois dernier
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    
</div>
@endsection