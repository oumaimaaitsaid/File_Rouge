@extends('layouts.app')

@section('title', $product->nom . ' - ' . config('app.name'))

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary">
                            <i class="fas fa-home mr-2"></i>
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                            <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-700 hover:text-primary">
                                Produits
                            </a>
                        </div>
                    </li>
                    @if($product->categorie)
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                            <a href="{{ route('categories.show', $product->categorie->slug) }}" class="text-sm font-medium text-gray-700 hover:text-primary">
                                {{ $product->categorie->nom }}
                            </a>
                        </div>
                    </li>
                    @endif
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
                            <span class="text-sm font-medium text-primary">
                                {{ $product->nom }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        
        
        
       
    </div>
</div>

@endsection