<header>
    <!-- Barre supérieure d'informations -->
    <div class="bg-accent text-white text-sm py-2">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <span class="mr-4"><i class="fas fa-phone mr-2"></i>+212 5 22 00 00 00</span>
                <span><i class="fas fa-envelope mr-2"></i>contact@tradition-sucree.ma</span>
            </div>
            <div class="flex items-center">
                <a href="#" class="mr-3 hover:text-primary-light transition-colors duration-200"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="mr-3 hover:text-primary-light transition-colors duration-200"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-primary-light transition-colors duration-200"><i class="fab fa-pinterest"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Navigation principale -->
    <nav class="bg-white shadow" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Tradition Sucrée" class="h-12 mr-3">
                    <span class="text-accent font-playfair text-xl font-bold">Tradition Sucrée</span>
                </a>
                
                <!-- Navigation desktop -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="font-medium text-accent hover:text-primary transition-colors duration-200 {{ Request::is('/') ? 'text-primary' : '' }}">Accueil</a>
                    <a href="{{ url('/catalog') }}" class="font-medium text-accent hover:text-primary transition-colors duration-200 {{ Request::is('catalog*') ? 'text-primary' : '' }}">Catalogue</a>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="font-medium text-accent hover:text-primary transition-colors duration-200 flex items-center">
                            Catégories <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute z-10 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <a href="{{ url('/catalog', ['category' => 'gateaux-amandes']) }}" class="block px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">Gâteaux aux Amandes</a>
                            <a href="{{ url('/catalog', ['category' => 'gateaux-miel']) }}" class="block px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">Gâteaux au Miel</a>
                            <a href="{{ url('/catalog', ['category' => 'gateaux-feuilletes']) }}" class="block px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">Gâteaux Feuilletés</a>
                            <a href="{{ url('/catalog', ['category' => 'coffrets-cadeaux']) }}" class="block px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">Coffrets Cadeaux</a>
                        </div>
                    </div>
                    <a href="{{ url('/about') }}" class="font-medium text-accent hover:text-primary transition-colors duration-200 {{ Request::is('about') ? 'text-primary' : '' }}">À propos</a>
                    <a href="{{ url('/contact') }}" class="font-medium text-accent hover:text-primary transition-colors duration-200 {{ Request::is('contact') ? 'text-primary' : '' }}">Contact</a>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center">
                    <!-- Recherche -->
                    <div class="relative mr-4" x-data="{ open: false }">
                        <button @click="open = !open" class="text-accent hover:text-primary transition-colors duration-200">
                            <i class="fas fa-search"></i>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg p-2 z-10">
                            <form action="{{ url('/catalog') }}" method="GET" class="flex">
                                <input type="text" name="search" placeholder="Rechercher..." class="flex-grow px-3 py-1 border border-gray-300 rounded-l focus:outline-none focus:ring-1 focus:ring-primary">
                                <button type="submit" class="bg-primary text-white px-3 py-1 rounded-r hover:bg-primary-dark transition-colors duration-200">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Panier -->
                    <div class="relative mr-4" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="text-accent hover:text-primary transition-colors duration-200 flex items-center">
                            <i class="fas fa-shopping-cart"></i>
                            <span x-show="$store.cart.count > 0" x-text="$store.cart.count" 
                                class="absolute -top-2 -right-2 bg-primary text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            </span>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg p-3 z-10">
                            <div x-show="$store.cart.count === 0" class="text-center py-4">
                                <p class="text-gray-500">Votre panier est vide</p>
                            </div>
                            <div x-show="$store.cart.count > 0">
                                <h3 class="font-medium mb-2">Votre Panier</h3>
                                <div class="max-h-64 overflow-y-auto">
                                    <template x-for="item in $store.cart.items" :key="item.id">
                                        <div class="flex items-center border-b border-gray-200 py-2">
                                            <img :src="item.image" :alt="item.name" class="w-12 h-12 object-cover rounded">
                                            <div class="ml-3 flex-grow">
                                                <h4 class="text-sm font-medium" x-text="item.name"></h4>
                                                <div class="flex items-center justify-between">
                                                    <p class="text-xs text-gray-600">
                                                        <span x-text="item.quantity"></span> x 
                                                        <span x-text="new Intl.NumberFormat('fr-MA', { style: 'currency', currency: 'MAD' }).format(item.price)"></span>
                                                    </p>
                                                    <button @click="$store.cart.removeItem(item.id)" class="text-red-500 hover:text-red-700">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div class="mt-3 pt-2 border-t border-gray-200">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="font-medium">Total</span>
                                        <span class="font-bold text-primary" x-text="new Intl.NumberFormat('fr-MA', { style: 'currency', currency: 'MAD' }).format($store.cart.total)"></span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ url('/cart') }}" class="bg-accent text-white text-sm py-2 px-3 rounded flex-grow text-center hover:bg-accent-light transition-colors duration-200">
                                            Voir panier
                                        </a>
                                        <a href="{{ url('/checkout') }}" class="bg-primary text-white text-sm py-2 px-3 rounded flex-grow text-center hover:bg-primary-dark transition-colors duration-200">
                                            Commander
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Compte utilisateur -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="text-accent hover:text-primary transition-colors duration-200">
                            <i class="fas fa-user"></i>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            @auth
                                <span class="block px-4 py-2 text-sm text-gray-500 border-b border-gray-200">Bonjour, {{ Auth::user()->name }}</span>
                                <a href="{{ url('/account/profile') }}" class="block px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">Mon Profil</a>
                                <a href="{{ url('/account/orders') }}" class="block px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">Mes Commandes</a>
                                <a href="{{ url('/account/addresses') }}" class="block px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">Mes Adresses</a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-200 mt-1">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">
                                        Déconnexion
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">Connexion</a>
                                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-accent hover:bg-primary hover:text-white">Inscription</a>
                            @endauth
                        </div>
                    </div>
                    
                    <!-- Toggle menu mobile -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="ml-4 text-accent lg:hidden">
                        <i x-bind:class="mobileMenuOpen ? 'fa-times' : 'fa-bars'" class="fas"></i>
                    </button>
                </div>
            </div>
            
            <!-- Menu mobile -->
            <div x-show="mobileMenuOpen" x-transition x-cloak class="lg:hidden py-3 border-t border-gray-200">
                <a href="{{ url('/') }}" class="block py-2 font-medium text-accent hover:text-primary transition-colors duration-200 {{ Request::is('/') ? 'text-primary' : '' }}">Accueil</a>
                <a href="{{ url('/catalog') }}" class="block py-2 font-medium text-accent hover:text-primary transition-colors duration-200 {{ Request::is('catalog*') ? 'text-primary' : '' }}">Catalogue</a>
                
                <div x-data="{ open: false }" class="py-2">
                    <button @click="open = !open" class="flex items-center w-full font-medium text-accent hover:text-primary transition-colors duration-200">
                        Catégories <i x-bind:class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas ml-1 text-xs"></i>
                    </button>
                    <div x-show="open" class="pl-4 mt-1 border-l-2 border-gray-200">
                        <a href="{{ url('/catalog', ['category' => 'gateaux-amandes']) }}" class="block py-1 text-sm text-accent hover:text-primary transition-colors duration-200">Gâteaux aux Amandes</a>
                        <a href="{{ url('/catalog', ['category' => 'gateaux-miel']) }}" class="block py-1 text-sm text-accent hover:text-primary transition-colors duration-200">Gâteaux au Miel</a>
                        <a href="{{ url('/catalog', ['category' => 'gateaux-feuilletes']) }}" class="block py-1 text-sm text-accent hover:text-primary transition-colors duration-200">Gâteaux Feuilletés</a>
                        <a href="{{ url('/catalog', ['category' => 'coffrets-cadeaux']) }}" class="block py-1 text-sm text-accent hover:text-primary transition-colors duration-200">Coffrets Cadeaux</a>
                    </div>
                </div>
                
                <a href="{{ url('/about') }}" class="block py-2 font-medium text-accent hover:text-primary transition-colors duration-200 {{ Request::is('about') ? 'text-primary' : '' }}">À propos</a>
                <a href="{{ url('/contact') }}" class="block py-2 font-medium text-accent hover:text-primary transition-colors duration-200 {{ Request::is('contact') ? 'text-primary' : '' }}">Contact</a>
            </div>
        </div>
    </nav>
</header>