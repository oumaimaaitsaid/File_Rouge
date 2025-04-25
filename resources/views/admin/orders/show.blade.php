@extends('admin.layout')

@section('title', 'Détails de la commande #' . $order->numero_commande)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h6 class="text-primary font-semibold tracking-wider uppercase mb-2">ADMINISTRATION</h6>
                <h2 class="font-playfair text-3xl md:text-4xl font-bold text-accent mb-4">
                    Commande #{{ $order->numero_commande }}
                </h2>
                <div class="w-20 h-1 bg-primary"></div>
            </div>
            
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition-colors duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
    
    <!-- Statut de la commande et Actions -->
    
</div>
@endsection