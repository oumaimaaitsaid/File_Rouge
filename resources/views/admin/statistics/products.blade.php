@extends('admin.layout')
@section('title', 'Statistiques des produits')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">STATISTIQUES</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Performance des produits</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
<!-- Filtres de période -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <form action="{{ route('admin.statistics.products') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
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

<!-- En-tête des résultats -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="font-bold text-accent text-lg mb-4">Résultats pour la période : {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total des produits vendus -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="text-gray-500 text-sm mb-1">Produits vendus</div>
            <div class="text-2xl font-bold text-accent">{{ $topProducts->sum('total_quantity') }}</div>
            <div class="text-sm text-gray-500 mt-1">unités</div>
        </div>
        
        <!-- Revenus générés -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="text-gray-500 text-sm mb-1">Chiffre d'affaires</div>
            <div class="text-2xl font-bold text-accent">{{ number_format($topProducts->sum('total_amount'), 2) }} MAD</div>
            <div class="text-sm text-gray-500 mt-1">pour {{ $topProducts->count() }} produits différents</div>
        </div>
        
        <!-- Produit le plus vendu -->
        @if($topProducts->isNotEmpty())
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-gray-500 text-sm mb-1">Produit star</div>
                <div class="text-lg font-bold text-accent">{{ $topProducts->first()->nom_produit }}</div>
                <div class="text-sm text-gray-500 mt-1">{{ $topProducts->first()->total_quantity }} unités vendues</div>
            </div>
        @endif
    </div>
</div>






</div>
@endsection
