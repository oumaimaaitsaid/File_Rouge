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
                <!-- Informations personnelles -->
                <section id="infos-perso" class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-accent">Informations personnelles</h2>
                        <p class="text-sm text-gray-500">Mettez à jour vos informations personnelles</p>
                    </div>
                    
                    <div class="p-6">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Nom -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                    <input id="name" type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                        required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Prénom -->
                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                    <input id="prenom" type="text" name="prenom" value="{{ old('prenom', Auth::user()->prenom) }}" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                        required>
                                    @error('prenom')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                                    <input id="email" type="email" name="email" value="{{ Auth::user()->email }}" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md bg-gray-50 shadow-sm cursor-not-allowed"
                                        disabled>
                                    <p class="mt-1 text-xs text-gray-500">Pour changer votre email, contactez le service client</p>
                                </div>
                                
                                <!-- Téléphone -->
                                <div>
                                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                    <input id="telephone" type="text" name="telephone" value="{{ old('telephone', Auth::user()->telephone) }}" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                    @error('telephone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <h3 class="font-medium text-lg text-accent mb-3">Adresse</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Adresse -->
                                <div class="md:col-span-2">
                                    <label for="addresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                                    <input id="addresse" type="text" name="addresse" value="{{ old('addresse', Auth::user()->addresse) }}" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                    @error('addresse')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Ville -->
                                <div>
                                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                                    <input id="ville" type="text" name="ville" value="{{ old('ville', Auth::user()->ville) }}" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                    @error('ville')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Code postal -->
                                <div>
                                    <label for="code_postal" class="block text-sm font-medium text-gray-700 mb-1">Code postal</label>
                                    <input id="code_postal" type="text" name="code_postal" value="{{ old('code_postal', Auth::user()->code_postal) }}" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                    @error('code_postal')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Pays -->
                                <div>
                                    <label for="pays" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                                    <select id="pays" name="pays" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                        <option value="Maroc" {{ Auth::user()->pays == 'Maroc' ? 'selected' : '' }}>Maroc</option>
                                        <option value="France" {{ Auth::user()->pays == 'France' ? 'selected' : '' }}>France</option>
                                        <option value="Belgique" {{ Auth::user()->pays == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                        <option value="Canada" {{ Auth::user()->pays == 'Canada' ? 'selected' : '' }}>Canada</option>
                                        <!-- Ajoutez d'autres pays selon vos besoins -->
                                    </select>
                                    @error('pays')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Bouton de mise à jour -->
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
                
                <!-- Sécurité (Mot de passe) -->
                <section id="security" class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-accent">Sécurité</h2>
                        <p class="text-sm text-gray-500">Modifiez votre mot de passe</p>
                    </div>
                    
                    <div class="p-6">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4 max-w-xl">
                                <!-- Mot de passe actuel -->
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                                    <input id="current_password" type="password" name="current_password" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                    @error('current_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Nouveau mot de passe -->
                                <div>
                                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                                    <input id="new_password" type="password" name="new_password" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                    <p class="mt-1 text-xs text-gray-500">Au moins 8 caractères, incluant lettres et chiffres</p>
                                    @error('new_password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Confirmation nouveau mot de passe -->
                                <div>
                                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
                                    <input id="new_password_confirmation" type="password" name="new_password_confirmation" 
                                        class="py-2 px-4 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                                </div>
                                
                                <!-- Bouton de mise à jour -->
                                <div class="pt-2">
                                    <button type="submit" class="inline-flex items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-200">
                                        <i class="fas fa-key mr-2"></i> Changer le mot de passe
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
                
                
            </div>
        </div>
    </div>
</div>
@endsection