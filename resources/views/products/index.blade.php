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
            
            <div class="lg:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-4 mb-6 flex justify-between items-center">
                    <div>
                        <p class="text-gray-600">
                            Affichage de <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> à 
                            <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> sur 
                            <span class="font-medium">{{ $products->total() }}</span> produits
                        </p>
                    </div>
                    <div>
                        <select class="p-2 border border-gray-300 rounded-md" onchange="window.location.href = this.value">
                            <option value="{{ route('products.index', ['category' => request('category'), 'sort' => request('sort'), 'search' => request('search'), 'per_page' => 12]) }}" {{ request('per_page') == 12 || !request('per_page') ? 'selected' : '' }}>12 par page</option>
                            <option value="{{ route('products.index', ['category' => request('category'), 'sort' => request('sort'), 'search' => request('search'), 'per_page' => 24]) }}" {{ request('per_page') == 24 ? 'selected' : '' }}>24 par page</option>
                            <option value="{{ route('products.index', ['category' => request('category'), 'sort' => request('sort'), 'search' => request('search'), 'per_page' => 36]) }}" {{ request('per_page') == 36 ? 'selected' : '' }}>36 par page</option>
                        </select>
                    </div>
                </div>
                
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg h-full flex flex-col">
                                @if($product->prix_promo && $product->prix_promo < $product->prix)
                                    <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">Promo</div>
                                @endif
                                
                                <a href="{{ route('products.show', $product->slug) }}" class="block relative">
                                    <img src="{{ $product->imagePrincipale ? asset('storage/' . $product->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}" alt="{{ $product->nom }}" class="w-full h-64 object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <button type="button" class="bg-white text-accent hover:text-primary transition-colors duration-200 rounded-full p-3 mx-1 transform hover:scale-110" title="Aperçu rapide">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" 
                                            onclick="addToCart({{ $product->id }}, '{{ $product->nom }}', {{ $product->prix_promo ?? $product->prix }}, '{{ $product->imagePrincipale ? asset('storage/' . $product->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}')"
                                            class="bg-white text-accent hover:text-primary transition-colors duration-200 rounded-full p-3 mx-1 transform hover:scale-110" 
                                            title="Ajouter au panier">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                        <button type="button" class="bg-white text-accent hover:text-primary transition-colors duration-200 rounded-full p-3 mx-1 transform hover:scale-110" title="Ajouter aux favoris">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    </div>
                                </a>
                                
                                <div class="p-4 flex-grow flex flex-col">
                                    <a href="{{ route('products.show', $product->slug) }}" class="font-playfair font-semibold text-lg text-accent hover:text-primary transition-colors duration-200 mb-2 line-clamp-2">
                                        {{ $product->nom }}
                                    </a>
                                    
                                    <div class="flex items-center mb-2">
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
                                        <span class="text-xs text-gray-500 ml-1">({{ $product->avis()->where('approuve', true)->count() }})</span>
                                    </div>
                                    
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow">
                                        {{ $product->description }}
                                    </p>
                                    
                                    <div class="mt-auto">
                                        @if($product->prix_promo && $product->prix_promo < $product->prix)
                                            <div class="flex items-center">
                                                <span class="font-bold text-primary text-lg">{{ number_format($product->prix_promo, 2) }} MAD</span>
                                                <span class="text-gray-500 text-sm line-through ml-2">{{ number_format($product->prix, 2) }} MAD</span>
                                            </div>
                                        @else
                                            <span class="font-bold text-primary text-lg">{{ number_format($product->prix, 2) }} MAD</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="px-4 pb-4">
                                    <button 
                                        onclick="addToCart({{ $product->id }}, '{{ $product->nom }}', {{ $product->prix_promo ?? $product->prix }}, '{{ $product->imagePrincipale ? asset('storage/' . $product->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}')"
                                        class="w-full bg-accent hover:bg-primary text-white py-2 px-4 rounded transition-colors duration-200 flex items-center justify-center">
                                        <i class="fas fa-shopping-cart mr-2"></i> Ajouter au panier
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-md p-8 text-center">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-search fa-3x"></i>
                        </div>
                        <h3 class="text-xl font-bold text-accent mb-2">Aucun produit trouvé</h3>
                        <p class="text-gray-600">Essayez de modifier vos filtres ou d'effectuer une nouvelle recherche.</p>
                        <a href="{{ route('products.index') }}" class="inline-block mt-4 bg-primary text-white py-2 px-4 rounded hover:bg-primary-dark transition-colors duration-200">
                            Voir tous les produits
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection