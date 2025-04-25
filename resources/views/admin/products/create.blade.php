@extends('admin.layout')

@section('title', 'Ajouter un produit')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Ajouter un produit</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        
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
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">Prix (MAD) <span class="text-red-500">*</span></label>
                    <input type="number" id="prix" name="prix" value="{{ old('prix') }}" required step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="prix_promo" class="block text-sm font-medium text-gray-700 mb-1">Prix promotionnel (MAD)</label>
                    <input type="number" id="prix_promo" name="prix_promo" value="{{ old('prix_promo') }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock <span class="text-red-500">*</span></label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
                
                <div>
                    <label for="poids" class="block text-sm font-medium text-gray-700 mb-1">Poids (grammes)</label>
                    <input type="number" id="poids" name="poids" value="{{ old('poids') }}" step="0.01" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                </div>
            </div>
        </div>
        
        <!-- Description et ingrédients -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-accent mb-4 border-b pb-2">Contenu</h3>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea id="description" name="description" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ old('description') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Décrivez le produit en détail pour informer les clients.</p>
            </div>
            
            <div>
                <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingrédients</label>
                <textarea id="ingredients" name="ingredients" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ old('ingredients') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Liste des ingrédients utilisés dans ce produit.</p>
            </div>
        </div>
        
        <!-- Images -->
        <div class="mb-8">
            <h3 class="text-lg font-bold text-accent mb-4 border-b pb-2">Images</h3>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Images du produit</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                <span>Télécharger des images</span>
                                <input id="images" name="images[]" type="file" class="sr-only" accept="image/*" multiple>
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, GIF jusqu'à 2MB
                        </p>
                    </div>
                </div>
                <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                <div class="mt-2">
                    <label for="image_principale" class="block text-sm font-medium text-gray-700">Image principale</label>
                    <select id="image_principale" name="image_principale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="">Première image téléchargée</option>
                    </select>
                </div>
            </div>
        </div>
        
      
        
       
    </form>
</div>
@endsection

