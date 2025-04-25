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
    
    
    
    
</div>
@endsection