@extends('layouts.app')

@section('title', 'Paiement par carte - ' . config('app.name'))

@section('content')
<div class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Paiement par carte bancaire</h1>
        
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Détails de la commande</h2>
                <p class="text-gray-600 mt-2">Commande #{{ $commande->numero_commande }}</p>
                <p class="text-gray-600">Total: <span class="font-bold">{{ number_format($commande->montant_total, 2) }} MAD</span></p>
            </div>
<div class="p-4 border-b border-gray-200">
    <h3 class="font-medium text-gray-800 mb-2">Articles commandés</h3>
    @foreach($commande->ligneCommandes as $ligne)
        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
            <div>
                <p class="font-medium">{{ $ligne->nom_produit }}</p>
                <p class="text-sm text-gray-500">{{ $ligne->quantite }} x {{ number_format($ligne->prix_unitaire, 2) }} MAD</p>
            </div>
            <p class="font-medium">{{ number_format($ligne->total, 2) }} MAD</p>
        </div>
    @endforeach
</div>
            <div class="p-6">
                <form action="{{ route('payment.stripe.process', $commande->id) }}" method="POST">
                    @csrf
                    <p class="mb-6 text-gray-700">Vous allez être redirigé vers Stripe pour finaliser votre paiement en toute sécurité.</p>
                    
                    <div class="flex justify-between">
                        <a href="{{ route('checkout.index') }}" class="text-gray-600 hover:text-primary transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-200">
                            Procéder au paiement
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="p-4 bg-gray-50 border-t border-gray-200 text-center text-sm text-gray-500">
                <p>Paiement sécurisé. Vos informations bancaires sont gérées directement par Stripe.</p>
                <div class="mt-2 flex justify-center">
                    <img src="https://stripe.com/img/v3/home/social.png" alt="Stripe" class="h-6">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection