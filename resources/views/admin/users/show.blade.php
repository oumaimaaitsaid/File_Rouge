@extends('admin.layout')

@section('title', 'Profil utilisateur - ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">
                    Profil de {{ $user->name }} {{ $user->prenom }}
                </h2>
                <div class="w-20 h-1 bg-primary"></div>
            </div>
            
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-md transition-colors duration-300">
                <i class="fas fa-edit mr-2"></i> Modifier le profil
            </a>
            
            <!-- Bouton pour voir les commandes de l'utilisateur -->
            <a href="{{ route('admin.orders.index', ['search' => $user->email]) }}" class="inline-flex items-center px-4 py-2 bg-accent hover:bg-accent-dark text-white rounded-md transition-colors duration-300">
                <i class="fas fa-shopping-cart mr-2"></i> Voir les commandes
            </a>
            
            @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-300">
                        <i class="fas fa-trash mr-2"></i> Supprimer le compte
                    </button>
                </form>
            @endif
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Colonne 1: Informations générales -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Informations personnelles</h3>
                </div>
                <div class="p-6">
                    <div class="flex justify-center mb-6">
                        <div class="h-24 w-24 rounded-full bg-accent text-white flex items-center justify-center text-3xl">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Nom complet</h4>
                            <p class="text-base font-medium">{{ $user->name }} {{ $user->prenom }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Email</h4>
                            <p class="text-base">{{ $user->email }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Téléphone</h4>
                            <p class="text-base">{{ $user->telephone ?? 'Non renseigné' }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Type de compte</h4>
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-user-shield mr-1"></i> Administrateur
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user mr-1"></i> Client
                                </span>
                            @endif
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Inscription</h4>
                            <p class="text-base">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Dernière mise à jour</h4>
                            <p class="text-base">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <!-- Colonne 2-3: Activité de l'utilisateur -->
        <div class="md:col-span-2">
            
        </div>
    </div>
</div>
@endsection