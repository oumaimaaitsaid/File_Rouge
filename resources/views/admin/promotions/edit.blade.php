@extends('admin.layout')

@section('title', 'Modifier une promotion')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Modifier une promotion</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
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
                    <!-- Code promo -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Code promotion <span class="text-red-500">*</span></label>
                        <input type="text" id="code" name="code" value="{{ old('code', $promotion->code) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 uppercase">
                        <p class="mt-1 text-xs text-gray-500">
                            Code unique qui sera utilisé par les clients (ex: SUMMER2023).
                        </p>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                        <input type="text" id="description" name="description" value="{{ old('description', $promotion->description) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <p class="mt-1 text-xs text-gray-500">
                            Description courte qui sera affichée aux clients.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Détails de la remise -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-accent mb-4 border-b pb-2">Détails de la remise</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Type de remise -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type de remise <span class="text-red-500">*</span></label>
                        <select id="type" name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="pourcentage" {{ old('type', $promotion->type) == 'pourcentage' ? 'selected' : '' }}>Pourcentage (%)</option>
                            <option value="montant" {{ old('type', $promotion->type) == 'montant' ? 'selected' : '' }}>Montant fixe (MAD)</option>
                            <option value="livraison_gratuite" {{ old('type', $promotion->type) == 'livraison_gratuite' ? 'selected' : '' }}>Livraison gratuite</option>
                        </select>
                    </div>
                    
                    <!-- Valeur de la remise -->
                    <div id="valeur-container" class="{{ $promotion->type === 'livraison_gratuite' ? 'hidden' : '' }}">
                        <label for="valeur" class="block text-sm font-medium text-gray-700 mb-1">Valeur <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" id="valeur" name="valeur" value="{{ old('valeur', $promotion->valeur) }}" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 pr-12">
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <span id="valeur-suffix" class="text-gray-500 sm:text-sm">{{ $promotion->type === 'pourcentage' ? '%' : 'MAD' }}</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Montant ou pourcentage de la remise.
                        </p>
                    </div>
                    
                    <!-- Commande minimum -->
                    <div>
                        <label for="commande_minimum" class="block text-sm font-medium text-gray-700 mb-1">Commande minimum (MAD)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" id="commande_minimum" name="commande_minimum" value="{{ old('commande_minimum', $promotion->commande_minimum) }}" step="0.01" min="0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 pr-12">
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">MAD</span>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Montant minimum de commande pour appliquer la promotion.
                        </p>
                    </div>
                    
                    <!-- Limites d'utilisation -->
                    <div>
                        <label for="usage_maximum" class="block text-sm font-medium text-gray-700 mb-1">Nombre maximum d'utilisations</label>
                        <input type="number" id="usage_maximum" name="usage_maximum" value="{{ old('usage_maximum', $promotion->usage_maximum) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <p class="mt-1 text-xs text-gray-500">
                            Laissez vide pour un nombre illimité d'utilisations.
                        </p>
                    </div>
                    
                    <div>
                        <label for="usage_par_utilisateur" class="block text-sm font-medium text-gray-700 mb-1">Utilisations par client</label>
                        <input type="number" id="usage_par_utilisateur" name="usage_par_utilisateur" value="{{ old('usage_par_utilisateur', $promotion->usage_par_utilisateur) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <p class="mt-1 text-xs text-gray-500">
                            Nombre maximum d'utilisations par client. Laissez vide pour illimité.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Période de validité -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-accent mb-4 border-b pb-2">Période de validité</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date de début -->
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-1">Date de début <span class="text-red-500">*</span></label>
                        <input type="text" id="date_debut" name="date_debut" value="{{ old('date_debut', $promotion->date_debut) }}" required class="datepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <p class="mt-1 text-xs text-gray-500">
                            Date à partir de laquelle la promotion sera active.
                        </p>
                    </div>
                    
                    <!-- Date de fin -->
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                        <input type="text" id="date_fin" name="date_fin" value="{{ old('date_fin', $promotion->date_fin) }}" class="datepicker mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <p class="mt-1 text-xs text-gray-500">
                            Date de fin de la promotion. Laissez vide pour une durée illimitée.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Informations d'utilisation -->
            <div class="mb-8 bg-gray-50 p-4 rounded-md">
                <h3 class="text-sm font-bold text-gray-700 mb-2">Informations d'utilisation</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Nombre d'utilisations:</span>
                        <span class="ml-2 font-medium">{{ $promotion->utilisations()->count() ?? 0 }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Création:</span>
                        <span class="ml-2 font-medium">{{ $promotion->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Dernière modification:</span>
                        <span class="ml-2 font-medium">{{ $promotion->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Statut actuel:</span>
                        @php
                            $now = \Carbon\Carbon::now();
                            $isActive = $promotion->date_debut <= $now && 
                                      ($promotion->date_fin === null || $promotion->date_fin >= $now);
                        @endphp
                        
                        @if($isActive)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @elseif($promotion->date_debut > $now)
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                À venir
                            </span>
                        @else
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Expirée
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Boutons de soumission -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
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

