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
    
    
    
    
    
  
</div>
@endsection