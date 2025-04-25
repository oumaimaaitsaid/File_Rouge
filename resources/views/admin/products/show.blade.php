@extends('admin.layout')

@section('title', 'Détails du produit')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">{{ $product->nom }}</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <!-- Actions rapides -->
    <div class="flex flex-wrap gap-3 mb-8">
        <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md transition-colors duration-300">
            <i class="fas fa-edit mr-2"></i> Modifier
        </a>
        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-accent hover:bg-accent-dark text-white rounded-md transition-colors duration-300">
            <i class="fas fa-eye mr-2"></i> Voir sur le site
        </a>
        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-300">
                <i class="fas fa-trash mr-2"></i> Supprimer
            </button>
        </form>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Colonne 1: Images et statut -->
        <div class="md:col-span-1">
            <!-- Galerie d'images -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Images</h3>
                </div>
                <div class="p-4">
                    @if($product->images->count() > 0)
                        <div class="mb-4">
                            @php
                                $principalImage = $product->imagePrincipale;
                            @endphp
                            @if($principalImage)
                                <div class="relative aspect-w-1 aspect-h-1 rounded-md overflow-hidden mb-4">
                                    <img src="{{ asset('storage/' . $principalImage->chemin) }}" alt="{{ $product->nom }}" class="object-cover w-full h-64">
                                    <div class="absolute top-2 right-2 bg-primary text-white text-xs font-bold px-2 py-1 rounded">
                                        Principale
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @if($product->images->count() > 1)
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($product->images as $image)
                                    @if(!$image->est_principale)
                                        <div class="relative aspect-w-1 aspect-h-1 rounded-md overflow-hidden">
                                            <img src="{{ asset('storage/' . $image->chemin) }}" alt="{{ $product->nom }}" class="object-cover w-full h-20">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="text-center text-gray-500 py-4">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <p>Aucune image disponible</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Statut et informations -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Statut</h3>
                </div>
                <div class="p-4">
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">Disponibilité:</span>
                            @if($product->disponible)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Disponible
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Indisponible
                                </span>
                            @endif
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">Mise en avant:</span>
                            @if($product->featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-star mr-1"></i> En vedette
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Standard
                                </span>
                            @endif
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">Stock:</span>
                            @if($product->stock > 10)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $product->stock }} unités
                                </span>
                            @elseif($product->stock > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $product->stock }} unités
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rupture de stock
                                </span>
                            @endif
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">Catégorie:</span>
                            <span class="font-medium">{{ $product->categorie->nom ?? 'Non catégorisé' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Informations système -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Informations système</h3>
                </div>
                <div class="p-4">
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">ID:</span>
                            <span class="font-medium">{{ $product->id }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">Slug:</span>
                            <span class="font-medium">{{ $product->slug }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">Créé le:</span>
                            <span class="font-medium">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span class="text-gray-600">Mis à jour le:</span>
                            <span class="font-medium">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Colonne 2: Détails du produit -->
        <div class="md:col-span-2">
            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Informations du produit</h3>
                </div>
                <div class="p-4">
                    <!-- Pricing -->
                    <div class="mb-6">
                        <h4 class="text-lg font-bold text-accent mb-2">Prix</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-3 rounded-md">
                                <div class="text-sm text-gray-500">Prix standard</div>
                                <div class="text-2xl font-bold text-accent">{{ number_format($product->prix, 2) }} MAD</div>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-md">
                                <div class="text-sm text-gray-500">Prix promotionnel</div>
                                @if($product->prix_promo)
                                    <div class="text-2xl font-bold text-primary">{{ number_format($product->prix_promo, 2) }} MAD</div>
                                    <div class="text-xs text-gray-500">
                                        Réduction : {{ number_format(100 - ($product->prix_promo * 100 / $product->prix), 1) }}%
                                    </div>
                                @else
                                    <div class="text-xl font-medium text-gray-400">Non défini</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Caractéristiques -->
                    <div class="mb-6">
                        <h4 class="text-lg font-bold text-accent mb-2">Caractéristiques</h4>
                        <div class="grid grid-cols-2 gap-4">
                            @if($product->poids)
                                <div class="bg-gray-50 p-3 rounded-md">
                                    <div class="text-sm text-gray-500">Poids</div>
                                    <div class="text-base font-medium">{{ number_format($product->poids, 0) }} g</div>
                                </div>
                            @endif
                            
                            @if($product->categorie)
                                <div class="bg-gray-50 p-3 rounded-md">
                                    <div class="text-sm text-gray-500">Catégorie</div>
                                    <div class="text-base font-medium">{{ $product->categorie->nom }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-6">
                        <h4 class="text-lg font-bold text-accent mb-2">Description</h4>
                        <div class="bg-gray-50 p-4 rounded-md prose max-w-none">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                    
                    <!-- Ingrédients -->
                    @if($product->ingredients)
                        <div>
                            <h4 class="text-lg font-bold text-accent mb-2">Ingrédients</h4>
                            <div class="bg-gray-50 p-4 rounded-md prose max-w-none">
                                {!! nl2br(e($product->ingredients)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Statistiques</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="text-sm text-gray-500 mb-1">Ventes totales</div>
                            <div class="text-2xl font-bold text-accent">
                                {{ $product->totalSales ?? 0 }}
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="text-sm text-gray-500 mb-1">Chiffre d'affaires</div>
                            <div class="text-2xl font-bold text-accent">
                                {{ number_format($product->totalRevenue ?? 0, 2) }} MAD
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="text-sm text-gray-500 mb-1">Note moyenne</div>
                            <div class="text-2xl font-bold text-accent flex items-center">
                                {{ number_format($product->averageRating ?? 0, 1) }}
                                <div class="ml-2 text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($product->averageRating ?? 0))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Avis clients -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-accent text-white p-4 flex justify-between items-center">
                    <h3 class="font-bold text-lg">Avis clients</h3>
                    <a href="{{ route('admin.reviews.index') }}?search={{ $product->nom }}" class="text-white hover:text-opacity-80 transition-colors duration-200">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if(isset($product->reviews) && $product->reviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($product->reviews as $review)
                                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center">
                                                <div class="text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->note)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span class="ml-2 text-sm font-medium text-gray-700">
                                                    {{ $review->user->name ?? 'Client' }}
                                                </span>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ $review->commentaire }}
                                            </p>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $review->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            @if($review->approuve)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Approuvé
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    En attente
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-4">
                            Aucun avis pour ce produit
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection