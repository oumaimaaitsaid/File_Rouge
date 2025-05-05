@extends('layouts.app')

@section('title', config('app.name') . ' - Accueil')

@section('content')
    <div class="relative h-screen min-h-[600px] max-h-[800px] overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url({{asset('storage/images/home/banner.jpg')}});">
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>
        
        <div class="container mx-auto px-4 h-full flex items-center relative z-10">
            <div class="max-w-2xl text-white">
                <h6 class="text-secondary font-medium tracking-widest uppercase mb-4">
                    PÂTISSERIE MAROCAINE ARTISANALE
                </h6>
                <h1 class="font-playfair text-5xl md:text-6xl font-bold leading-tight mb-6">
                    Savourez l'Authenticité des Gâteaux Traditionnels
                </h1>
                <p class="text-lg md:text-xl text-gray-200 mb-8 max-w-xl">
                    Découvrez nos pâtisseries marocaines confectionnées selon des recettes ancestrales, 
                    avec des ingrédients soigneusement sélectionnés pour vous offrir une expérience gustative exceptionnelle.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="inline-block bg-primary hover:bg-primary-dark text-white py-3 px-8 rounded-md font-semibold transition-colors duration-300">
                        Découvrir
                    </a>
                    <a href="#" class="inline-block border border-white hover:bg-white hover:text-accent py-3 px-8 rounded-md font-semibold transition-colors duration-300">
                        Notre Histoire
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Défilement indicateur -->
        <div class="absolute bottom-8 left-0 right-0 flex justify-center">
            <a href="#categories" class="text-white animate-bounce">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </div>
    
   <!-- Section Catégories -->
<section id="categories" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <!-- Titre de section -->
        <div class="text-center mb-12">
            <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">NOS SPÉCIALITÉS</h6>
            <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Catégories de Produits</h2>
            <div class="w-20 h-1 bg-primary mx-auto"></div>
        </div>
        
        <!-- Grille des catégories -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($categories as $category)
                <div class="group relative overflow-hidden rounded-lg shadow-md h-72 transition-transform duration-300 hover:-translate-y-2">
                    <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('images/placeholder.jpg') }}" 
                         alt="{{ $category->nom }}" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent">
                        <div class="absolute bottom-0 left-0 right-0 p-6 transition-all duration-300 group-hover:pb-10">
                            <h3 class="font-playfair text-xl font-bold text-white mb-1">{{ $category->nom }}</h3>
                            <p class="text-gray-200 text-sm mb-4 opacity-0 -translate-y-4 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0">
                                {{ $category->description ?? 'Découvrez notre sélection de produits' }}
                            </p>
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="inline-flex items-center text-white bg-primary px-4 py-2 rounded-md text-sm font-medium transition-colors duration-300 hover:bg-primary-dark">
                                Découvrir <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500">Aucune catégorie disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
    
    <!-- Section À propos -->
    <section class="py-16 bg-secondary">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Image avec effet de profondeur -->
                <div class="relative">
                    <div class="relative z-10 rounded-lg overflow-hidden shadow-xl">
                        <img src="https://images.unsplash.com/photo-1498654694080-47945e5d060f?q=80&w=2070&auto=format&fit=crop" alt="Préparation traditionnelle" class="w-full h-auto">
                    </div>
                    <!-- Éléments décoratifs -->
                    <div class="absolute w-64 h-64 bg-primary/10 rounded-full -bottom-10 -left-10 z-0"></div>
                    <div class="absolute w-32 h-32 bg-primary/20 rounded-full top-10 -right-5 z-0"></div>
                </div>
                
                <!-- Contenu -->
                <div>
                    <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">NOTRE SAVOIR-FAIRE</h6>
                    <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-6">L'Art de la Pâtisserie Marocaine</h2>
                    <div class="w-20 h-1 bg-primary mb-6"></div>
                    
                    <p class="text-accent-light text-lg leading-relaxed mb-6">
                        Découvrez l'héritage culinaire marocain à travers nos pâtisseries authentiques. 
                        Chaque gâteau est préparé à la main selon des recettes transmises de génération 
                        en génération par les maîtres pâtissiers de notre famille.
                    </p>
                    
                    <p class="text-accent-light text-lg leading-relaxed mb-8">
                        Nous utilisons uniquement des ingrédients de première qualité : amandes sélectionnées, 
                        miel pur, eau de fleur d'oranger naturelle et épices fraîchement moulues pour vous 
                        offrir des saveurs incomparables.
                    </p>
                    
                    <!-- Liste des ingrédients -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="flex items-center">
                            <div class="bg-white rounded-full p-2 shadow-md mr-3">
                                <i class="fas fa-seedling text-primary w-8 h-8 flex items-center justify-center"></i>
                            </div>
                            <span>Amandes premium</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-white rounded-full p-2 shadow-md mr-3">
                                <i class="fas fa-jar text-primary w-8 h-8 flex items-center justify-center"></i>
                            </div>
                            <span>Miel naturel</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-white rounded-full p-2 shadow-md mr-3">
                                <i class="fas fa-leaf text-primary w-8 h-8 flex items-center justify-center"></i>
                            </div>
                            <span>Fleur d'oranger</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-white rounded-full p-2 shadow-md mr-3">
                                <i class="fas fa-mortar-pestle text-primary w-8 h-8 flex items-center justify-center"></i>
                            </div>
                            <span>Épices fraîches</span>
                        </div>
                    </div>
                    
                    <!-- Bouton d'action -->
                    <a href="#" class="inline-flex items-center bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-md font-semibold transition-colors duration-300">
                        En savoir plus <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
   <!-- Section Produits vedettes -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <!-- Titre de section -->
        <div class="text-center mb-12">
            <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">NOUVEAUTÉS</h6>
            <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Nos Produits Vedettes</h2>
            <div class="w-20 h-1 bg-primary mx-auto"></div>
        </div>
        
        <!-- Grille des produits -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-lg h-full flex flex-col">
                    @if($product->prix_promo && $product->prix_promo <$product->prix)
                        <div class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">Promo</div>
                    @endif
                    
                    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
                        <img src="{{ $product->imagePrincipale ? asset('storage/' . $product->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}" 
                             alt="{{ $product->nom }}" 
                             class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button type="button" class="bg-white text-accent hover:text-primary transition-colors duration-200 rounded-full p-3 mx-1 transform hover:scale-110" title="Aperçu rapide">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" onclick="addToCart({{ $product->id }}, '{{ $product->nom }}', {{ $product->prix_promo ?? $product->prix }}, '{{ $product->imagePrincipale ? asset('storage/' . $product->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}')" 
                            class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded transition-colors duration-200">
    <i class="fas fa-shopping-cart mr-2"></i> Ajouter au panier
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
                            @if($product->prix_promo && $product->prix_promo <$product->prix)
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
                    <button type="button" onclick="addToCart({{ $product->id }}, '{{ $product->nom }}', {{ $product->prix_promo ?? $product->prix }}, '{{ $product->imagePrincipale ? asset('storage/' . $product->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}')" class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded transition-colors duration-200">
    <i class="fas fa-shopping-cart mr-2"></i> Ajouter au panier
</button>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500">Aucun produit vedette disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Bouton voir plus -->
        <div class="text-center mt-10">
            <a href="{{ route('products.index') }}" class="inline-flex items-center border-2 border-primary text-primary hover:bg-primary hover:text-white font-semibold py-3 px-6 rounded-md transition-colors duration-300">
                Voir tous nos produits <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
    
    <!-- Section Témoignages -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <!-- Titre de section -->
            <div class="text-center mb-12">
                <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">TÉMOIGNAGES</h6>
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Ce que nos clients disent</h2>
                <div class="w-20 h-1 bg-primary mx-auto"></div>
            </div>
            
            <!-- Grille des témoignages -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Témoignage 1 -->
                 @forelse($avis as $temoignage)
                <div class="bg-gray-50 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <!-- Icône de guillemet -->
                    <div class="text-primary opacity-20 mb-4">
                        <i class="fas fa-quote-left text-4xl"></i>
                    </div>
                    
                    <!-- Étoiles -->
                    <div class="flex text-yellow-400 mb-4">
                        @for($i =0 ;$i <$temoignage->note;$i++)
                        <i class="fas fa-star"></i>
                        @endfor
                        @for($i = $temoignage->note; $i < 5 ; $i++)
                        <i class="far fa-star"></i>
                        @endfor
                      
                    </div>
                    
                    <!-- Témoignage -->
                    <p class="text-gray-600 italic mb-6">
                        "{{ $temoignage->commentaire}}"
                    </p>
                    
                    <!-- Personne -->
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-gray-300 mr-4 flex items-center justify-center text-gray-500">
                        @if($temoignage->user && $temoignage->user->avatar)
                        <img src="{{ asset($temoignage->user->avatar) }}" alt="{{ $temoignage->user->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <i class="fas fa-user"></i>
                        @endif 
                        </div>
                        <div>
                            <h4 class="font-bold text-accent">{{ $temoignage->user ? $temoignage->user->name : 'Client' }}</h4>
                            <p class="text-gray-500 text-sm">{{ $temoignage->produit ? $temoignage->produit->nom : 'Client fidèle' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-500 py-8">
                    <p>Aucun témoignage disponible pour le moment.</p>
                </div>
            @endforelse
                
                
            </div>
            
        </div>
    </section>
    
    <!-- Section Call to Action -->
    <section class="py-20 bg-fixed bg-cover bg-center relative" style="background-image: url({{asset('storage/images/promotions/bannerPromotion.jpg')}});">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black opacity-60"></div>
        
        <!-- Contenu -->
        <div class="container mx-auto px-4 relative z-10 text-white text-center">
            <div class="max-w-3xl mx-auto">
                <h6 class="text-secondary font-semibold tracking-wider uppercase mb-2">OFFRE SPÉCIALE</h6>
                <h2 class="font-playfair text-3xl md:text-5xl font-bold mb-6">Commandez pour vos événements spéciaux</h2>
                <p class="text-lg md:text-xl text-gray-200 mb-8 mx-auto max-w-2xl">
                    Mariages, fêtes, célébrations... Bénéficiez de 15% de réduction pour toute commande spéciale
                    à partir de 20 personnes.
                </p>
                
                <!-- Badge promo -->
                <div class="inline-block bg-primary text-white rounded-full py-3 px-5 font-bold text-lg mb-8 transform rotate-3 shadow-lg">
                    -15% pour les événements
                </div>
                
                <!-- Boutons CTA -->
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#" class="inline-flex items-center bg-primary hover:bg-primary-dark text-white py-3 px-8 rounded-md font-semibold transition-colors duration-300">
                        Nous contacter <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="#" class="inline-flex items-center border-2 border-white hover:bg-white hover:text-accent text-white py-3 px-8 rounded-md font-semibold transition-colors duration-300">
                        Voir coffrets <i class="fas fa-gift ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection         
