@extends('admin.layout')

@section('title', 'Modifier un produit')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Modifier un produit</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Erreur</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Informations de base -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-accent mb-4 border-b pb-2">Informations de base</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom du produit <span class="text-red-500">*</span></label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $product->nom) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">Prix (MAD) <span class="text-red-500">*</span></label>
                    <input type="number" id="prix" name="prix" value="{{ old('prix', $product->prix) }}" required step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="prix_promo" class="block text-sm font-medium text-gray-700 mb-1">Prix promotionnel (MAD)</label>
                    <input type="number" id="prix_promo" name="prix_promo" value="{{ old('prix_promo', $product->prix_promo) }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock <span class="text-red-500">*</span></label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="poids" class="block text-sm font-medium text-gray-700 mb-1">Poids (grammes)</label>
                    <input type="number" id="poids" name="poids" value="{{ old('poids', $product->poids) }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
            </div>
        </div>
        
        <!-- Description et ingrédients -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-accent mb-4 border-b pb-2">Contenu</h3>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea id="description" name="description" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ old('description', $product->description) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Décrivez le produit en détail pour informer les clients.</p>
            </div>
            
            <div>
                <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingrédients</label>
                <textarea id="ingredients" name="ingredients" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ old('ingredients', $product->ingredients) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Liste des ingrédients utilisés dans ce produit.</p>
            </div>
        </div>
        
        <!-- Images existantes -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-accent mb-4 border-b pb-2">Images existantes</h3>
            
            @if($product->images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    @foreach($product->images as $image)
                        <div class="relative group bg-gray-100 rounded-md overflow-hidden">
                            <img src="{{ asset('storage/' . $image->chemin) }}" alt="{{ $product->nom }}" class="object-cover w-full h-64">
                            <div class="absolute inset-0 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 bg-black bg-opacity-50 transition-opacity">
                                <div class="flex items-center space-x-2 mb-2">
                                    <input type="radio" id="principal_{{ $image->id }}" name="image_principale" value="{{ $image->id }}" {{ $image->est_principale ? 'checked' : '' }} class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                    <label for="principal_{{ $image->id }}" class="text-white text-sm">Image principale</label>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" id="delete_{{ $image->id }}" name="delete_images[]" value="{{ $image->id }}" class="h-4 w-4 text-red-500 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="delete_{{ $image->id }}" class="text-white text-sm">Supprimer</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-500 text-center py-4">
                    Aucune image pour ce produit. Veuillez en ajouter ci-dessous.
                </div>
            @endif
        </div>
        
       
        
        
      
    </form>
</div>
@endsection

