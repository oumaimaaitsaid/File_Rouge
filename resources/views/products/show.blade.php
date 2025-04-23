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
                    
                  
                    
                  
                    
                    
                    
                   
                </div>
            </div>
            
            
        </div>
        
       
    </div>
</div>

@endsection