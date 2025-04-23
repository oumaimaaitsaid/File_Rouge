@extends('layouts.app')

@section('title', $product->nom . ' - ' . config('app.name'))

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Fil d'Ariane -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                            <i class="fas fa-home mr-2"></i>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                            <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary">
                                Produits
                            </a>
                        </div>
                    </li>
                    @if($product->categorie)
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                            <a href="{{ route('categories.show', $product->categorie->slug) }}" class="text-sm font-medium text-gray-700 hover:text-primary">
                                {{ $product->categorie->nom }}
                            </a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                            <span class="text-sm font-medium text-primary">
                                {{ $product->nom }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        
        <!-- Section Produit -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Galerie d'images -->
                <div class="md:w-1/2 p-6">
                    <!-- Image principale -->
                    <div class="mb-4 rounded-lg overflow-hidden">
                        @if($product->images && $product->images->count() > 0)
                            @php
                                $mainImage = $product->images->firstWhere('principale', true) ?: $product->images->first();
                            @endphp
                            <img id="main-image" src="{{ asset('storage/' . $mainImage->chemin) }}" alt="{{ $product->nom }}" class="w-full h-96 object-cover">
                        @else
                            <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->nom }}" class="w-full h-96 object-cover">
                        @endif
                    </div>
                    
                    <!-- Miniatures -->
                    @if($product->images && $product->images->count() > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($product->images as $image)
                                <div class="cursor-pointer rounded-md overflow-hidden border-2 {{ $image->id == $mainImage->id ? 'border-primary' : 'border-gray-200' }}" 
                                     onclick="document.getElementById('main-image').src = '{{ asset('storage/' . $image->chemin) }}'">
                                    <img src="{{ asset('storage/' . $image->chemin) }}" alt="{{ $product->nom }}" class="w-full h-20 object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <!-- Informations produit -->
                <div class="md:w-1/2 p-6 bg-white">
                    <!-- Badge de catégorie -->
                    @if($product->categorie)
                        <a href="{{ route('categories.show', $product->categorie->slug) }}" class="inline-block bg-gray-100 text-primary text-xs uppercase tracking-wide px-3 py-1 rounded-md mb-3">
                            {{ $product->categorie->nom }}
                        </a>
                    @endif
                    
                    <!-- Nom du produit -->
                    <h1 class="font-playfair text-3xl font-bold text-accent mb-2">{{ $product->nom }}</h1>
                    
                    <!-- Notation -->
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            @php
                                $rating = $product->noteMoyenne();
                                $fullStars = floor($rating);
                                $halfStar = round($rating) > $fullStars;
                            @endphp
                            
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <i class="fas fa-star"></i>
                                @elseif($halfStar && $i == $fullStars + 1)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500 ml-2">
                            {{ $rating }} ({{ $product->avis()->where('approuve', true)->count() }} avis)
                        </span>
                    </div>
                    
                    <!-- Prix -->
                    <div class="mb-6">
                        @if($product->prix_promo && $product->prix_promo < $product->prix)
                            <div class="flex items-center">
                                <span class="font-bold text-primary text-2xl">{{ number_format($product->prix_promo, 2) }} MAD</span>
                                <span class="text-gray-500 text-lg line-through ml-3">{{ number_format($product->prix, 2) }} MAD</span>
                                @php
                                    $reduction = round((($product->prix - $product->prix_promo) / $product->prix) * 100);
                                @endphp
                                <span class="ml-3 bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">
                                    -{{ $reduction }}%
                                </span>
                            </div>
                        @else
                            <span class="font-bold text-primary text-2xl">{{ number_format($product->prix, 2) }} MAD</span>
                        @endif
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-6">
                        <h2 class="font-medium text-accent text-lg mb-2">Description</h2>
                        <p class="text-gray-700">{{ $product->description }}</p>
                    </div>
                    
                    <!-- Ingrédients -->
                    <div class="mb-6">
                        <h2 class="font-medium text-accent text-lg mb-2">Ingrédients</h2>
                        <p class="text-gray-700">{{ $product->ingredients }}</p>
                    </div>
                    
                    <!-- Disponibilité -->
                    <div class="mb-6">
                        <h2 class="font-medium text-accent text-lg mb-2">Disponibilité</h2>
                        @if($product->stock > 0)
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>En stock ({{ $product->stock }} disponible{{ $product->stock > 1 ? 's' : '' }})</span>
                            </div>
                        @else
                            <div class="flex items-center text-red-600">
                                <i class="fas fa-times-circle mr-2"></i>
                                <span>Rupture de stock</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Sélecteur de quantité et ajout au panier -->
                    <div class="flex flex-wrap gap-4 items-center">
                        <div class="w-24">
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                            <div class="flex border border-gray-300 rounded-md">
                                <button type="button" class="px-3 py-1 text-gray-500 hover:text-primary" onclick="decrementQuantity()">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" name="quantity" min="1" max="{{ $product->stock }}" value="1" 
                                       class="w-10 text-center border-x border-gray-300 focus:outline-none">
                                <button type="button" class="px-3 py-1 text-gray-500 hover:text-primary" onclick="incrementQuantity()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button 
                            onclick="addToCartWithQuantity({{ $product->id }}, '{{ $product->nom }}', {{ $product->prix_promo ?? $product->prix }}, '{{ $product->imagePrincipale ? asset('storage/' . $product->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}')"
                            class="flex-grow bg-primary hover:bg-primary-dark text-white py-3 px-4 rounded-md transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-shopping-cart mr-2"></i> Ajouter au panier
                        </button>
                        
                        <button class="bg-white border border-gray-300 hover:border-primary text-gray-700 hover:text-primary p-3 rounded-md transition-colors duration-200">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Détails supplémentaires -->
            <div class="border-t border-gray-200 p-6">
                <div x-data="{ activeTab: 'description' }">
                    <!-- Onglets -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex space-x-8">
                            <button 
                                @click="activeTab = 'description'"
                                :class="{ 'border-primary text-primary': activeTab === 'description', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'description' }"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                                Description détaillée
                            </button>
                            <button 
                                @click="activeTab = 'reviews'"
                                :class="{ 'border-primary text-primary': activeTab === 'reviews', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'reviews' }"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                                Avis clients ({{ $product->avis()->where('approuve', true)->count() }})
                            </button>
                        </nav>
                    </div>
                    
                    <!-- Contenu des onglets -->
                    <div x-show="activeTab === 'description'">
                        <div class="prose max-w-none">
                            <p>{{ $product->description }}</p>
                            
                            <!-- Caractéristiques du produit -->
                            <div class="mt-6">
                                <h3 class="text-lg font-medium text-accent mb-4">Caractéristiques du produit</h3>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>Produit artisanal fait main</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>Ingrédients de qualité supérieure</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>Recette traditionnelle marocaine</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>Conservation optimale pendant 7 jours</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        
       
    </div>
</div>

@endsection