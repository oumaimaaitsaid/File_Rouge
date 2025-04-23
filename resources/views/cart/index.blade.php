@extends('layouts.app')

@section('title', 'Mon Panier')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Mon Panier</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                {{ session('error') }}
            </div>
        @endif
        
        @if($cartItems->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-16 w-16 flex-shrink-0">
                                                <img class="h-16 w-16 object-cover rounded" 
                                                     src="{{ $item->produit->imagePrincipale ? asset('storage/' . $item->produit->imagePrincipale->chemin) : asset('images/placeholder.jpg') }}" 
                                                     alt="{{ $item->produit->nom }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('products.show', $item->produit->slug) }}" class="hover:text-primary">
                                                        {{ $item->produit->nom }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ number_format($item->prix_unitaire, 2) }} MAD</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                            @csrf
                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                            <div class="flex border border-gray-300 rounded-md">
                                                <button type="button" class="px-3 py-1 text-gray-500 hover:text-primary" onclick="decrementQuantity(this)">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" name="quantity" min="1" max="{{ $item->produit->stock }}" value="{{ $item->quantite }}" 
                                                       class="w-12 text-center border-x border-gray-300 focus:outline-none">
                                                <button type="button" class="px-3 py-1 text-gray-500 hover:text-primary" onclick="incrementQuantity(this)">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <button type="submit" class="ml-2 text-gray-500 hover:text-primary">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ number_format($item->prix_unitaire * $item->quantite, 2) }} MAD</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="p-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <form action="{{ route('promotions.validate') }}" method="POST" class="flex">
                            @csrf
                            <input type="text" name="code" placeholder="Code promo" class="border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary">
                            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-r-md hover:bg-primary-dark transition-colors duration-200">
                                Appliquer
                            </button>
                        </form>
                        
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors duration-200">
                                <i class="fas fa-trash-alt mr-2"></i> Vider le panier
                            </button>
                        </form>
                    </div>
                    
                    @if(session()->has('promo_code'))
                        <div class="bg-green-50 border border-green-300 p-3 rounded-md mb-4 flex justify-between items-center">
                            <div>
                                <p class="text-green-800 font-medium">
                                    Code promo appliqué: <span class="font-bold">{{ session('promo_code') }}</span>
                                </p>
                            </div>
                            <form action="{{ route('promotions.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-times"></i> Retirer
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    <div class="mt-4 bg-gray-50 p-4 rounded-md">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Sous-total:</span>
                            <span class="font-medium">{{ number_format($total, 2) }} MAD</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg border-t border-gray-300 pt-3 mt-3">
                            <span class="text-gray-800">Total:</span>
                            <span class="text-primary">{{ number_format($total, 2) }} MAD</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center text-gray-600 hover:text-primary transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Continuer les achats
                        </a>
                        <a href="{{ route('checkout.index') }}" class="bg-primary hover:bg-primary-dark text-white py-3 px-6 rounded-md font-semibold transition-colors duration-200">
                            Passer à la caisse
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-shopping-cart fa-3x"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Votre panier est vide</h3>
                <p class="text-gray-600 mb-6">Vous n'avez pas encore ajouté d'articles à votre panier.</p>
                <a href="{{ route('products.index') }}" class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md font-medium transition-colors duration-200">
                    Commencer vos achats
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    function decrementQuantity(button) {
        const input = button.parentElement.querySelector('input');
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    }
    
    function incrementQuantity(button) {
        const input = button.parentElement.querySelector('input');
        const currentValue = parseInt(input.value);
        const maxValue = parseInt(input.max);
        if (currentValue < maxValue) {
            input.value = currentValue + 1;
        }
    }
</script>
@endsection