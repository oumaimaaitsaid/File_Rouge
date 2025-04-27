@extends('admin.layout')

@section('title', 'Statistiques des ventes')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">STATISTIQUES</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Analyse des ventes</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <!-- Filtres de période -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('admin.statistics.sales') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Période</label>
                <select id="period" name="period" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="yesterday" {{ $period == 'yesterday' ? 'selected' : '' }}>Hier</option>
                    <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="last_week" {{ $period == 'last_week' ? 'selected' : '' }}>Semaine dernière</option>
                    <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Ce mois</option>
                    <option value="last_month" {{ $period == 'last_month' ? 'selected' : '' }}>Mois dernier</option>
                    <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>Cette année</option>
                    <option value="last_year" {{ $period == 'last_year' ? 'selected' : '' }}>Année dernière</option>
                    <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>Personnalisé</option>
                </select>
            </div>
            
            <div id="custom-date-container" class="flex-1 {{ $period != 'custom' ? 'hidden' : '' }}">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                        <input type="text" id="start_date" name="start_date" value="{{ request('start_date') }}" class="datepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                        <input type="text" id="end_date" name="end_date" value="{{ request('end_date') }}" class="datepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                </div>
            </div>
            
            <div>
                <button type="submit" class="w-full md:w-auto bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-300">
                    Appliquer
                </button>
            </div>
        </form>
    </div>
    
    <!-- Résumé des ventes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Statistique Commandes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-blue-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Commandes</h3>
                <div class="rounded-full bg-blue-100 p-3 text-blue-500">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ $sales->sum('count') }}</div>
            <p class="text-gray-500 text-sm">Total des commandes</p>
        </div>
        
        <!-- Statistique Revenus -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Revenus</h3>
                <div class="rounded-full bg-green-100 p-3 text-green-500">
                    <i class="fas fa-money-bill text-xl"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-accent mb-2">{{ number_format($sales->sum('total'), 2) }} MAD</div>
            <p class="text-gray-500 text-sm">Revenus totaux</p>
        </div>
        
        <!-- Statistique Commande moyenne -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-amber-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Panier moyen</h3>
                <div class="rounded-full bg-amber-100 p-3 text-amber-500">
                    <i class="fas fa-shopping-basket text-xl"></i>
                </div>
            </div>
            @php
                $averageOrder = $sales->sum('count') > 0 ? $sales->sum('total') / $sales->sum('count') : 0;
            @endphp
            <div class="text-3xl font-bold text-accent mb-2">{{ number_format($averageOrder, 2) }} MAD</div>
            <p class="text-gray-500 text-sm">Valeur moyenne des commandes</p>
        </div>
        
        <!-- Statistique clients -->
        <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-purple-500 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-accent text-lg">Période</h3>
                <div class="rounded-full bg-purple-100 p-3 text-purple-500">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
            <div class="text-xl font-bold text-accent mb-2">
                {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
            </div>
            <p class="text-gray-500 text-sm">{{ $endDate->diffInDays($startDate) + 1 }} jours</p>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Graphique évolution des ventes -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4">
                <h3 class="font-bold text-lg">Évolution des ventes</h3>
            </div>
            <div class="p-4">
                <canvas id="salesChart" height="300"></canvas>
            </div>
        </div>
        
        <!-- Graphique des méthodes de paiement -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-accent text-white p-4">
                <h3 class="font-bold text-lg">Méthodes de paiement</h3>
            </div>
            <div class="p-4">
                <canvas id="paymentMethodsChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
   
 
</div>
@endsection

