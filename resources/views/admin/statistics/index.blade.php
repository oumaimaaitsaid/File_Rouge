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
    
   
    
    
</div>
@endsection