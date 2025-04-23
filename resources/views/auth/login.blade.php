@extends('layouts.app')

@section('title', 'Connexion - ' . config('app.name'))

@section('content')
<div class="min-h-screen py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <!-- Carte de connexion -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-8">
                    <!-- Logo et titre -->
                    <div class="text-center mb-8">
                        <h2 class="font-playfair text-2xl font-bold text-accent mb-1">Bienvenue</h2>
                        <p class="text-gray-500">Connectez-vous à votre compte</p>
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
                    
                    <!-- Formulaire de connexion -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                                    class="py-3 pl-10 pr-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                    placeholder="exemple@email.com" required autofocus>
                            </div>
                        </div>
                        
                        <!-- Mot de passe -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                                <a href="#" class="text-sm text-primary hover:text-primary-dark transition-colors duration-200">Mot de passe oublié?</a>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password" type="password" name="password" 
                                    class="py-3 pl-10 pr-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                    placeholder="••••••••" required>
                            </div>
                        </div>
                        
                        <!-- Se souvenir de moi -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">
                                    Se souvenir de moi
                                </label>
                            </div>
                        </div>
                        
                        <!-- Bouton de connexion -->
                        <div class="mb-6">
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                <i class="fas fa-sign-in-alt mr-2"></i> Connexion
                            </button>
                        </div>
                    </form>
                    
                    <!-- Séparateur -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">ou</span>
                        </div>
                    </div>
                    
                    <!-- Connexion avec réseaux sociaux -->
                    <div class="grid grid-cols-2 gap-4">
                        <a href="#" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200 flex items-center justify-center">
                            <i class="fab fa-google text-red-500 mr-2"></i> Google
                        </a>
                        <a href="#" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200 flex items-center justify-center">
                            <i class="fab fa-facebook-f text-blue-600 mr-2"></i> Facebook
                        </a>
                    </div>
                </div>
                
                <!-- Pied de carte -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-center">
                    <p class="text-sm text-gray-600">
                        Vous n'avez pas de compte ? 
                        <a href="{{ route('register') }}" class="font-medium text-primary hover:text-primary-dark transition-colors duration-200">
                            Inscrivez-vous
                        </a>
                    </p>
                </div>
            </div>
            
            <!-- Support -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-500">
                    Besoin d'aide ? <a href="#" class="font-medium text-gray-700 hover:text-primary transition-colors duration-200">Contactez-nous</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection