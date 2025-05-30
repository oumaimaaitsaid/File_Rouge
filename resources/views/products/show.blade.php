@extends('layouts.app')

@section('title', $product->nom . ' - ' . config('app.name'))

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
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
        
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/2 p-6">
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
                
                <div class="md:w-1/2 p-6 bg-white">
                    @if($product->categorie)
                        <a href="{{ route('categories.show', $product->categorie->slug) }}" class="inline-block bg-gray-100 text-primary text-xs uppercase tracking-wide px-3 py-1 rounded-md mb-3">
                            {{ $product->categorie->nom }}
                        </a>
                    @endif
                    
                    <h1 class="font-playfair text-3xl font-bold text-accent mb-2">{{ $product->nom }}</h1>
                    
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            @php
                                $rating = $product->noteMoyenne();
                                $fullStars = floor($rating);
                                $halfStar = floor($rating) > $fullStars;
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
                            {{round($rating,1)}} ({{ $product->avis()->where('approuve', true)->count() }} avis)
                        </span>
                    </div>
                    
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
                    
                    <div class="mb-6">
                        <h2 class="font-medium text-accent text-lg mb-2">Description</h2>
                        <p class="text-gray-700">{{ $product->description }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <h2 class="font-medium text-accent text-lg mb-2">Ingrédients</h2>
                        <p class="text-gray-700">{{ $product->ingredients }}</p>
                    </div>
                    
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
                            class="flex-grow {{ $product->stock > 0 ? 'bg-primary hover:bg-primary-dark' : 'bg-gray-400 cursor-not-allowed'}} text-white py-3 px-4 rounded-md transition-colors duration-200 flex items-center justify-center"
                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                           
                            <i class="fas fa-shopping-cart mr-2"></i> 
                           {{ $product->stock > 0 ? 'Ajouter au panier' :'Rupture de stock'}} 
                        </button>
                        
                        <button class="bg-white border border-gray-300 hover:border-primary text-gray-700 hover:text-primary p-3 rounded-md transition-colors duration-200">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 p-6">
                <div x-data="{ activeTab: 'description' }">
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
                    
                    <div x-show="activeTab === 'description'">
                        <div class="prose max-w-none">
                            <p>{{ $product->description }}</p>
                            
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
                    
                    <div x-show="activeTab === 'reviews'">
                        @if($product->avis()->where('approuve', true)->count() > 0)
                            <div class="space-y-6">
                                @foreach($product->avis()->where('approuve', true)->with('user')->get() as $avis)
                                    <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium text-accent">{{ $avis->user->name }} {{ $avis->user->prenom }}</h4>
                                                <div class="flex text-yellow-400 my-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $avis->note)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ $avis->created_at ?$avis->created_at->format('d/m/Y') :'date non trouve' }}</span>
                                        </div>
                                        <p class="text-gray-700 mt-2">{{ $avis->commentaire }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-gray-400 mb-4">
                                    <i class="far fa-comment-dots fa-3x"></i>
                                </div>
                                <h3 class="text-lg font-medium text-accent mb-2">Aucun avis pour l'instant</h3>
                                <p class="text-gray-600">Soyez le premier à donner votre avis sur ce produit !</p>
                            </div>
                        @endif
                        
                        @auth
                            <div class="mt-8 bg-gray-50 p-6 rounded-lg">
                                <h3 class="text-lg font-medium text-accent mb-4">Laisser un avis</h3>
                                <form action="{{ route('products.review', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                                        <div class="flex text-2xl text-gray-400">
                                            <span class="cursor-pointer hover:text-yellow-400" onclick="setRating(1)"><i id="star1" class="far fa-star"></i></span>
                                            <span class="cursor-pointer hover:text-yellow-400" onclick="setRating(2)"><i id="star2" class="far fa-star"></i></span>
                                            <span class="cursor-pointer hover:text-yellow-400" onclick="setRating(3)"><i id="star3" class="far fa-star"></i></span>
                                            <span class="cursor-pointer hover:text-yellow-400" onclick="setRating(4)"><i id="star4" class="far fa-star"></i></span>
                                            <span class="cursor-pointer hover:text-yellow-400" onclick="setRating(5)"><i id="star5" class="far fa-star"></i></span>
                                        </div>
                                        <input type="hidden" name="note" id="rating" value="5">
                                    </div>
                                    <div class="mb-4">
                                        <label for="commentaire" class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                                        <textarea id="commentaire" name="commentaire" rows="4" 
                                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary"
                                                  required></textarea>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-200">
                                            Soumettre mon avis
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="mt-8 bg-gray-50 p-6 rounded-lg text-center">
                                <p class="text-gray-700 mb-3">Vous devez être connecté pour laisser un avis.</p>
                                <a href="{{ route('login') }}" class="inline-block bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-200">
                                    Se connecter
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        
        @if($relatedProducts->count() > 0)
            <div class="mt-12">
                <h2 class="font-playfair text-2xl font-bold text-accent mb-6">Produits similaires</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg h-full flex flex-col">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block relative">
                                <img src="{{ $relatedProduct->imagePrincipale ? asset('storage/' . $relatedProduct->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}" 
                                     alt="{{ $relatedProduct->nom }}" 
                                     class="w-full h-48 object-cover">
                            </a>
                            
                            <div class="p-4 flex-grow flex flex-col">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="font-playfair font-semibold text-accent hover:text-primary transition-colors duration-200 mb-1">
                                    {{ $relatedProduct->nom }}
                                </a>
                                
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 text-sm">
                                        @php
                                            $relatedRating = $relatedProduct->noteMoyenne();
                                            $relatedFullStars = floor($relatedRating);
                                            $relatedHalfStar = round($relatedRating) > $relatedFullStars;
                                        @endphp
                                        
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $relatedFullStars)
                                                <i class="fas fa-star"></i>
                                            @elseif($relatedHalfStar && $i == $relatedFullStars + 1)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                
                                <div class="mt-auto">
                                    @if($relatedProduct->prix_promo && $relatedProduct->prix_promo < $relatedProduct->prix)
                                        <div class="flex items-center">
                                            <span class="font-bold text-primary">{{ number_format($relatedProduct->prix_promo, 2) }} MAD</span>
                                            <span class="text-gray-500 text-sm line-through ml-2">{{ number_format($relatedProduct->prix, 2) }} MAD</span>
                                        </div>
                                    @else
                                        <span class="font-bold text-primary">{{ number_format($relatedProduct->prix, 2) }} MAD</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="px-4 pb-4">
                                <button 
                                    onclick="addToCart({{ $relatedProduct->id }}, '{{ $relatedProduct->nom }}', {{ $relatedProduct->prix_promo ?? $relatedProduct->prix }}, '{{ $relatedProduct->imagePrincipale ? asset('storage/' . $relatedProduct->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}')"
                                    class="w-full bg-accent hover:bg-primary text-white py-2 px-4 rounded transition-colors duration-200 flex items-center justify-center">
                                    <i class="fas fa-shopping-cart mr-2"></i> Ajouter
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function decrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }
    
    function incrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        const maxValue = parseInt(quantityInput.max);
        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
        }
    }
    
    function addToCartWithQuantity(productId, productName, price, image) {
        const quantity = parseInt(document.getElementById('quantity').value);
        if (window.addToCart) {
            for (let i = 0; i < quantity; i++) {
                window.addToCart(productId, productName, price, image);
            }
            showNotification(`${quantity} × ${productName} ajouté au panier`, 'success');
        } else {
            console.log(`Added ${quantity} of product ${productId} to cart`);
        }
    }
    
    function setRating(stars) {
        document.getElementById('rating').value = stars;
        
        for (let i = 1; i <= 5; i++) {
            const starElement = document.getElementById('star' + i);
            if (i <= stars) {
                starElement.className = 'fas fa-star';
            } else {
                starElement.className = 'far fa-star';
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        setRating(5);
    });
</script>
@endsection