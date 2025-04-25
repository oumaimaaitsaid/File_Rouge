@extends('admin.layout')

@section('title', 'Gestion des avis')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Gestion des avis</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <!-- Filtres et recherche -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('admin.reviews.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Produit, client, commentaire...">
            </div>
            
            <div class="w-full md:w-1/4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="all">Tous les statuts</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvés</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            
            <div class="w-full md:w-1/4">
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                <select id="rating" name="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    <option value="all">Toutes les notes</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 étoiles</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 étoiles</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 étoiles</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 étoiles</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 étoile</option>
                </select>
            </div>
            
            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-300">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
            </div>
            
            <div class="w-full md:w-auto">
                <a href="{{ route('admin.reviews.index') }}" class="inline-block w-full md:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md transition-colors duration-300 text-center">
                    <i class="fas fa-redo mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>
    
    <!-- En-tête des résultats -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-700">
                {{ $reviews->total() }} avis trouvé(s)
            </h3>
        </div>
    </div>
    
    
</div>
@endsection