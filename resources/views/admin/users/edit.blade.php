@extends('admin.layout')

@section('title', 'Modifier un utilisateur')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
        <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">Modifier un utilisateur</h2>
        <div class="w-20 h-1 bg-primary"></div>
    </div>
    
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
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
            
            
            
            <!-- Informations système -->
            <div class="mb-8 bg-gray-50 p-4 rounded-md">
                <h3 class="text-sm font-bold text-gray-700 mb-2">Informations système</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">ID:</span>
                        <span class="ml-2 font-medium">{{ $user->id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Date d'inscription:</span>
                        <span class="ml-2 font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Dernière mise à jour:</span>
                        <span class="ml-2 font-medium">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Nombre de commandes:</span>
                        <span class="ml-2 font-medium">{{ $user->commandes->count() }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Boutons de soumission -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
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