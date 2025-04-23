@extends('layouts.app')

@section('title', 'Mon Profil - ' . config('app.name'))

@section('content')
<div class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="font-playfair text-3xl font-bold text-accent">Mon Profil</h1>
            <p class="text-gray-600">Gérez vos informations personnelles et préférences</p>
        </div>
        
        <!-- Messages flash -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif
        
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Menu latéral -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Informations utilisateur -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}{{ substr(Auth::user()->prenom, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold text-accent">{{ Auth::user()->name }} {{ Auth::user()->prenom }}</h3>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation profil -->
                    <nav class="p-4">
                        <ul class="space-y-1">
                            <li>
                                <a href="#infos-perso" class="flex items-center px-4 py-3 rounded-md bg-primary-light bg-opacity-10 text-primary font-medium">
                                    <i class="fas fa-user-circle mr-3 w-5 text-center"></i>
                                    <span>Informations personnelles</span>
                                </a>
                            </li>
                            <li>
                                <a href="#security" class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-lock mr-3 w-5 text-center"></i>
                                    <span>Sécurité</span>
                                </a>
                            </li>
                            <li>
                                <a href="#addresses" class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-map-marker-alt mr-3 w-5 text-center"></i>
                                    <span>Adresses</span>
                                </a>
                            </li>
                            <li>
                                <a href="#orders" class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-shopping-bag mr-3 w-5 text-center"></i>
                                    <span>Mes commandes</span>
                                </a>
                            </li>
                            <li>
                                <a href="#preferences" class="flex items-center px-4 py-3 rounded-md text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                                    <i class="fas fa-cog mr-3 w-5 text-center"></i>
                                    <span>Préférences</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    
                    <!-- Déconnexion -->
                    <div class="p-4 border-t border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-3 rounded-md text-red-600 hover:bg-red-50 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contenu principal -->
            <div class="lg:w-3/4 space-y-8">
              
                
              
                
                
            </div>
        </div>
    </div>
</div>
@endsection