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
    
</div>
@endsection