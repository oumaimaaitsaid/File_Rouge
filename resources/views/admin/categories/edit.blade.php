@extends('admin.layout')

@section('title', 'Modifier une catégorie')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Modifier une catégorie</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
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
            
            <!-- Nom de la catégorie -->
            <div class="mb-6">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie <span class="text-red-500">*</span></label>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $category->nom) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                <p class="mt-1 text-xs text-gray-500">
                    Le nom sera utilisé pour générer le slug URL.
                </p>
            </div>
            
            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">{{ old('description', $category->description) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">
                    Une brève description de la catégorie qui sera affichée sur le site.
                </p>
            </div>
            
            <!-- Image actuelle -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Image actuelle</label>
                <div class="mt-1">
                    @if($category->image)
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->nom }}" class="w-40 h-40 object-cover rounded-md">
                            <div class="flex items-center">
                                <input type="checkbox" id="delete_image" name="delete_image" value="1" class="h-4 w-4 text-red-500 focus:ring-red-500 border-gray-300 rounded">
                                <label for="delete_image" class="ml-2 block text-sm text-red-500">
                                    Supprimer cette image
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="text-gray-500">Aucune image pour cette catégorie.</div>
                    @endif
                </div>
            </div>
            
            <!-- Nouvelle image -->
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">{{ $category->image ? 'Remplacer l\'image' : 'Ajouter une image' }}</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-dark focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                <span>Télécharger une image</span>
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, GIF jusqu'à 2MB
                        </p>
                    </div>
                </div>
                <div id="image-preview" class="mt-4 hidden">
                    <img id="preview-img" src="#" alt="Aperçu de la nouvelle image" class="w-40 h-40 object-cover rounded-md">
                </div>
            </div>
            
            <!-- Statut -->
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="active" name="active" value="1" {{ old('active', $category->active) ? 'checked' : '' }} class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="active" class="ml-2 block text-sm text-gray-700">
                        Catégorie active
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500 ml-6">
                    Les catégories inactives ne seront pas affichées sur le site.
                </p>
            </div>
            
            <!-- Informations système -->
            <div class="mb-6 bg-gray-50 p-4 rounded-md">
                <h3 class="text-sm font-bold text-gray-700 mb-2">Informations système</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">ID:</span>
                        <span class="ml-2 font-medium">{{ $category->id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Slug:</span>
                        <span class="ml-2 font-medium">{{ $category->slug }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Date de création:</span>
                        <span class="ml-2 font-medium">{{ $category->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Dernière mise à jour:</span>
                        <span class="ml-2 font-medium">{{ $category->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Boutons de soumission -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prévisualisation de l'image
        const inputImage = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        
        inputImage.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Gestion de la case à cocher pour supprimer l'image
        const deleteImageCheckbox = document.getElementById('delete_image');
        if (deleteImageCheckbox) {
            deleteImageCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    // Désactiver le champ de téléchargement de nouvelle image
                    inputImage.disabled = true;
                    imagePreview.classList.add('hidden');
                } else {
                    // Réactiver le champ
                    inputImage.disabled = false;
                }
            });
        }
    });
</script>
@endsection