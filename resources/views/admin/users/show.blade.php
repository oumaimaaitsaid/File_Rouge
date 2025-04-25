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
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Adresse</h3>
                </div>
                <div class="p-6">
                    @if($user->adresse)
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Adresse</h4>
                                <p class="text-base">{{ $user->adresse }}</p>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Ville</h4>
                                    <p class="text-base">{{ $user->ville ?? 'Non renseignée' }}</p>
                                </div>
                                
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Code postal</h4>
                                    <p class="text-base">{{ $user->code_postal ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Pays</h4>
                                <p class="text-base">{{ $user->pays ?? 'Maroc' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Aucune adresse renseignée</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Colonne 2-3: Activité de l'utilisateur -->
        <div class="md:col-span-2">
            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4">
                    <h3 class="font-bold text-lg">Statistiques</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="text-sm text-gray-500 mb-1">Commandes totales</div>
                            <div class="text-2xl font-bold text-accent">{{ $user->commandes->count() }}</div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="text-sm text-gray-500 mb-1">Montant total dépensé</div>
                            <div class="text-2xl font-bold text-accent">
                                {{ number_format($user->commandes->where('paiement_confirme', true)->sum('montant_total'), 2) }} MAD
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="text-sm text-gray-500 mb-1">Avis publiés</div>
                            <div class="text-2xl font-bold text-accent">{{ $user->avis->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Commandes récentes -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-accent text-white p-4 flex justify-between items-center">
                    <h3 class="font-bold text-lg">Commandes récentes</h3>
                    <a href="{{ route('admin.orders.index', ['search' => $user->email]) }}" class="text-white hover:text-secondary transition-colors duration-200">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Commande</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($user->commandes->sortByDesc('created_at')->take(5) as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary hover:text-primary-dark font-medium">
                                            {{ $order->numero_commande }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">
                                        {{ number_format($order->montant_total, 2) }} MAD
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($order->statut === 'en_attente')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                En attente
                                            </span>
                                        @elseif($order->statut === 'confirmee')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Confirmée
                                            </span>
                                        @elseif($order->statut === 'preparee')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                Préparée
                                            </span>
                                        @elseif($order->statut === 'expediee')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Expédiée
                                            </span>
                                        @elseif($order->statut === 'livree')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Livrée
                                            </span>
                                        @elseif($order->statut === 'annulee')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Annulée
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary hover:text-primary-dark">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        Cet utilisateur n'a pas encore passé de commande
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Avis publiés -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-accent text-white p-4 flex justify-between items-center">
                    <h3 class="font-bold text-lg">Avis publiés</h3>
                    <a href="{{ route('admin.reviews.index', ['search' => $user->email]) }}" class="text-white hover:text-secondary transition-colors duration-200">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="p-4">
                    @if($user->avis->count() > 0)
                        <div class="space-y-6">
                            @foreach($user->avis->sortByDesc('created_at')->take(3) as $review)
                                <div class="border-b pb-6 last:border-b-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-accent">
                                                    {{ $review->produit->nom ?? 'Produit supprimé' }}
                                                </div>
                                                <div class="ml-2 flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->note)
                                                            <i class="fas fa-star text-xs"></i>
                                                        @else
                                                            <i class="far fa-star text-xs"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600">{{ $review->commentaire }}</p>
                                            <p class="mt-1 text-xs text-gray-500">{{ $review->created_at->format('d/m/Y') }}</p>
                                        </div>
                                        <div>
                                            @if($review->approuve)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Approuvé
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    En attente
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($user->avis->count() > 3)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.reviews.index', ['search' => $user->email]) }}" class="text-primary hover:text-primary-dark font-medium">
                                    Voir tous les avis ({{ $user->avis->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center text-gray-500 py-4">
                            Cet utilisateur n'a pas encore publié d'avis
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection