@extends('layouts.app')

@section('title', 'Catalogue de produits')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="mb-8 text-center">
            <h1 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Nos Produits</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Découvrez notre sélection de pâtisseries marocaines authentiques, préparées avec des ingrédients de qualité selon des recettes traditionnelles.</p>
            <div class="w-20 h-1 bg-primary mx-auto mt-4"></div>
        </div>
        
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="font-bold text-lg text-accent mb-4">Filtres</h2>
                    
                    <!-- Catégories -->
                    <div class="mb-6">
                        <h3 class="font-medium text-accent mb-2">Catégories</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-primary transition-colors duration-200 {{ !request('category') ? 'text-primary font-medium' : '' }}">
                                    Toutes les catégories
                                </a>
                            </li>
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-gray-600 hover:text-primary transition-colors duration-200 {{ request('category') == $category->slug ? 'text-primary font-medium' : '' }}">
                                        {{ $category->nom }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <!-- Tri -->
                    <div class="mb-6">
                        <h3 class="font-medium text-accent mb-2">Trier par</h3>
                        <form action="{{ route('products.index') }}" method="GET" id="sort-form">
                            <input type="hidden" name="category" value="{{ request('category') }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <select name="sort" id="sort" class="w-full p-2 border border-gray-300 rounded-md" onchange="document.getElementById('sort-form').submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récents</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix: croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix: décroissant</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom: A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom: Z-A</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popularité</option>
                            </select>
                        </form>
                    </div>
                    
                    <!-- Recherche -->
                    <div>
                        <h3 class="font-medium text-accent mb-2">Recherche</h3>
                        <form action="{{ route('products.index') }}" method="GET">
                            <input type="hidden" name="category" value="{{ request('category') }}">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            <div class="flex">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="flex-grow p-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-1 focus:ring-primary">
                                <button type="submit" class="bg-primary text-white px-3 py-2 rounded-r-md hover:bg-primary-dark transition-colors duration-200">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection