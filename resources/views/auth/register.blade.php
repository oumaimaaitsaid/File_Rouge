@extends('layouts.app')

@section('title', 'Inscription - ' . config('app.name'))

@section('content')
<div class="min-h-screen py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Carte d'inscription -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Image latérale (visible uniquement sur Desktop) -->
                    <div class="hidden md:block md:w-1/2 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1611072172377-0cabc3addb30?q=80&w=1974&auto=format&fit=crop')">
                        <div class="h-full bg-primary bg-opacity-80 p-8 flex items-center">
                            <div>
                                <h3 class="text-white text-2xl font-bold mb-4">Rejoignez notre aventure gustative</h3>
                                <p class="text-white text-opacity-90 mb-6">
                                    Créez votre compte pour profiter d'avantages exclusifs et suivre vos commandes en toute simplicité.
                                </p>
                                <ul class="space-y-2">
                                    <li class="flex items-center text-white">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span>Suivi facile de vos commandes</span>
                                    </li>
                                    <li class="flex items-center text-white">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span>Accès aux offres exclusives</span>
                                    </li>
                                    <li class="flex items-center text-white">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span>Processus de commande simplifié</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                
                    <!-- Formulaire -->
                    <div class="md:w-1/2 px-6 py-8">
                        <!-- Titre -->
                        <div class="text-center mb-8">
                            <h2 class="font-playfair text-2xl font-bold text-accent mb-1">Créer un compte</h2>
                            <p class="text-gray-500">Rejoignez notre communauté gourmande</p>
                        </div>
                        
                        <!-- Messages d'erreur -->
                        @if ($errors->any())
                            <div class="bg-red-50 text-red-500 px-4 py-3 rounded-md mb-6">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Formulaire d'inscription -->
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <!-- Nom -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" 
                                    class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                    required autofocus>
                            </div>
                            
                            <!-- Prénom -->
                            <div class="mb-4">
                                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" 
                                    class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                    required>
                            </div>
                            
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                                    class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                    required>
                            </div>
                            
                            <!-- Téléphone -->
                            <div class="mb-4">
                                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                <input id="telephone" type="text" name="telephone" value="{{ old('telephone') }}" 
                                    class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                    required>
                            </div>
                            
                            <!-- Mot de passe -->
                            <div class="mb-4">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                                <input id="password" type="password" name="password" 
                                    class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                    required>
                            </div>
                            
                            <!-- Confirmation mot de passe -->
                            <div class="mb-6">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" 
                                    class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                    required>
                            </div>
                            
                            <!-- Conditions générales -->
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <input id="terms" type="checkbox" name="terms" required
                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded mt-1">
                                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                                        J'accepte les <a href="#" class="text-primary hover:text-primary-dark">conditions générales</a> et la <a href="#" class="text-primary hover:text-primary-dark">politique de confidentialité</a>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Bouton d'inscription -->
                            <div class="mb-6">
                                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                    <i class="fas fa-user-plus mr-2"></i> Créer mon compte
                                </button>
                            </div>
                        </form>
                        
                        <!-- Déjà un compte -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600">
                                Vous avez déjà un compte ? 
                                <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary-dark transition-colors duration-200">
                                    Connectez-vous
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection