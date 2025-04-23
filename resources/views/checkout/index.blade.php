@extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Finaliser votre commande</h1>
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Formulaire de paiement -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Informations de livraison</h2>
                    </div>
                    
                    <form action="{{ route('checkout.store') }}" method="POST" class="p-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="adresse_livraison" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                <input type="text" id="adresse_livraison" name="adresse_livraison" value="{{ old('adresse_livraison', Auth::user()->adresse) }}" required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                @error('adresse_livraison')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="ville_livraison" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                <input type="text" id="ville_livraison" name="ville_livraison" value="{{ old('ville_livraison', Auth::user()->ville) }}" required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                @error('ville_livraison')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="code_postal_livraison" class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                                <input type="text" id="code_postal_livraison" name="code_postal_livraison" value="{{ old('code_postal_livraison', Auth::user()->code_postal) }}" required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                @error('code_postal_livraison')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="pays_livraison" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                                <select id="pays_livraison" name="pays_livraison" required
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="Maroc" {{ old('pays_livraison', Auth::user()->pays) == 'Maroc' ? 'selected' : '' }}>Maroc</option>
                                    <option value="France" {{ old('pays_livraison', Auth::user()->pays) == 'France' ? 'selected' : '' }}>France</option>
                                    <option value="Belgique" {{ old('pays_livraison', Auth::user()->pays) == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                    <option value="Canada" {{ old('pays_livraison', Auth::user()->pays) == 'Canada' ? 'selected' : '' }}>Canada</option>
                                </select>
                                @error('pays_livraison')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="telephone_livraison" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                <input type="text" id="telephone_livraison" name="telephone_livraison" value="{{ old('telephone_livraison', Auth::user()->telephone) }}" required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                                @error('telephone_livraison')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (facultatif)</label>
                            <textarea id="notes" name="notes" rows="3" 
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-6 mb-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-4">Méthode de paiement</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="radio" id="carte" name="methode_paiement" value="carte" 
                                           {{ old('methode_paiement') == 'carte' ? 'checked' : '' }} required
                                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                    <label for="carte" class="ml-3 text-sm font-medium text-gray-700">
                                        Carte bancaire
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="paypal" name="methode_paiement" value="paypal" 
                                           {{ old('methode_paiement') == 'paypal' ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                    <label for="paypal" class="ml-3 text-sm font-medium text-gray-700">
                                        PayPal
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="virement" name="methode_paiement" value="virement" 
                                           {{ old('methode_paiement') == 'virement' ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                    <label for="virement" class="ml-3 text-sm font-medium text-gray-700">
                                        Virement bancaire
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="a_la_livraison" name="methode_paiement" value="a_la_livraison" 
                                           {{ old('methode_paiement', 'a_la_livraison') == 'a_la_livraison' ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                    <label for="a_la_livraison" class="ml-3 text-sm font-medium text-gray-700">
                                        Paiement à la livraison
                                    </label>
                                </div>
                            </div>
                           
                        </div>
                        
                        <div class="flex justify-between">
                            <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-primary transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i> Retour au panier
                            </a>
                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-md font-semibold transition-colors duration-200">
                                Confirmer la commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
           
        </div>
    </div>
</div>
@endsection