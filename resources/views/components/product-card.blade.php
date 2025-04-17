@props(['product'])

<div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg h-full flex flex-col">
    <!-- Badge pour nouveau produit ou en promotion -->
    @if(isset($product['isNew']) && $product['isNew'])
        <div class="absolute top-3 right-3 bg-primary text-white text-xs font-bold px-2 py-1 rounded">
            Nouveau
        </div>
    @elseif(isset($product['prix_promo']) && $product['prix_promo'])
        <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">
            Promo
        </div>
    @endif
    
    <!-- Image du produit -->
    <a href="{{ url('/product', $product['slug']) }}" class="block relative">
        <img 
            src="{{ $product['image'] ?? asset('images/placeholder.jpg') }}" 
            alt="{{ $product['name'] ?? $product['nom'] }}" 
            class="w-full h-64 object-cover"
        >
        <!-- Overlay pour quick view -->
        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
            <button type="button" class="bg-white text-accent hover:text-primary transition-colors duration-200 rounded-full p-3 mx-1 transform hover:scale-110" title="Aperçu rapide">
                <i class="fas fa-eye"></i>
            </button>
            <button type="button" 
                @click.prevent="$store.cart.addItem({
                    id: {{ $product['id'] }}, 
                    name: '{{ $product['name'] ?? $product['nom'] }}', 
                    price: {{ isset($product['prix_promo']) && $product['prix_promo'] ? $product['prix_promo'] : $product['price'] ?? $product['prix'] }},
                    image: '{{ $product['image'] ?? asset('images/placeholder.jpg') }}'
                })"
                class="bg-white text-accent hover:text-primary transition-colors duration-200 rounded-full p-3 mx-1 transform hover:scale-110" 
                title="Ajouter au panier">
                <i class="fas fa-shopping-cart"></i>
            </button>
            <button type="button" class="bg-white text-accent hover:text-primary transition-colors duration-200 rounded-full p-3 mx-1 transform hover:scale-110" title="Ajouter aux favoris">
                <i class="far fa-heart"></i>
            </button>
        </div>
    </a>
    
    <!-- Informations produit -->
    <div class="p-4 flex-grow flex flex-col">
        <!-- Catégorie -->
        @if(isset($product['category']))
            <div class="text-xs text-gray-500 mb-1">{{ $product['category'] }}</div>
        @endif
        
        <!-- Nom du produit -->
        <a href="{{ url('/product', $product['slug']) }}" class="font-playfair font-semibold text-lg text-accent hover:text-primary transition-colors duration-200 mb-2 line-clamp-2">
            {{ $product['name'] ?? $product['nom'] }}
        </a>
        
        <!-- Note -->
        @if(isset($product['rating']) || isset($product['note']))
            <div class="flex items-center mb-2">
                @php
                    $rating = $product['rating'] ?? $product['note'] ?? 0;
                    $fullStars = floor($rating);
                    $halfStar = round($rating) > $fullStars;
                @endphp
                
                <div class="flex text-yellow-400">
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
                <span class="text-xs text-gray-500 ml-1">({{ isset($product['reviews_count']) ? $product['reviews_count'] : '0' }})</span>
            </div>
        @endif
        
        <!-- Description courte -->
        @if(isset($product['description']) || isset($product['short_description']))
            <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow">
                {{ $product['short_description'] ?? $product['description'] }}
            </p>
        @endif
        
        <!-- Prix -->
        <div class="mt-auto">
            @if(isset($product['prix_promo']) && $product['prix_promo'])
                <div class="flex items-center">
                    <span class="font-bold text-primary text-lg">{{ number_format($product['prix_promo'], 2) }} MAD</span>
                    <span class="text-gray-500 text-sm line-through ml-2">{{ number_format($product['prix'], 2) }} MAD</span>
                </div>
            @else
                <span class="font-bold text-primary text-lg">
                    {{ number_format($product['price'] ?? $product['prix'] ?? 0, 2) }} MAD
                </span>
            @endif
        </div>
    </div>
    
    <!-- Bouton ajouter au panier -->
    <div class="px-4 pb-4">
        <form action="{{ url('/cart/add') }}" method="POST" class="w-full">
            @csrf
            <input type="hidden" name="produit_id" value="{{ $product['id'] }}">
            <input type="hidden" name="quantite" value="1">
            <button type="submit" class="w-full bg-accent hover:bg-primary text-white py-2 px-4 rounded transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-shopping-cart mr-2"></i> Ajouter au panier
            </button>
        </form>
    </div>
</div>