<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Administration Tradition Sucrée</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    @yield('styles')
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-accent">
                <!-- Logo -->
                <div class="h-16 flex items-center px-4 border-b border-accent-light">
                    <a href="{{ route('admin.dashboard') }}" class="text-white font-playfair text-xl font-bold">
                        Tradition Sucrée
                    </a>
                </div>
                
                <!-- Navigation -->
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <nav class="flex-1 px-4 py-4 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'text-white hover:bg-accent-light' }}">
                            <i class="fas fa-tachometer-alt mr-3 text-white"></i>
                            Tableau de bord
                        </a>
                        
                        <!-- Produits dropdown -->
                        <div class="space-y-1">
                            <button type="button" class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.products.*') ? 'bg-primary text-white' : 'text-white hover:bg-accent-light' }}" aria-controls="sub-menu-1" aria-expanded="false">
                                <i class="fas fa-cookie mr-3 text-white"></i>
                                <span class="flex-1 text-left">Produits</span>
                                <i class="fas fa-chevron-down text-white text-xs"></i>
                            </button>
                            <div class="space-y-1 ml-7" id="sub-menu-1">
                                <a href="{{ route('admin.products.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-white hover:bg-accent-light">
                                    Liste des produits
                                </a>
                                <a href="{{ route('admin.products.create') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-white hover:bg-accent-light">
                                    Ajouter un produit
                                </a>
                            </div>
                        </div>
                        
                        <!-- Catégories -->
                        <a href="{{ route('admin.categories.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'bg-primary text-white' : 'text-white hover:bg-accent-light' }}">
                            <i class="fas fa-layer-group mr-3 text-white"></i>
                            Catégories
                        </a>
                        
                        <!-- Commandes -->
                        <a href="{{ route('admin.orders.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.orders.*') ? 'bg-primary text-white' : 'text-white hover:bg-accent-light' }}">
                            <i class="fas fa-shopping-cart mr-3 text-white"></i>
                            Commandes
                        </a>
                        
                        <!-- Utilisateurs -->
                        <a href="{{ route('admin.users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-primary text-white' : 'text-white hover:bg-accent-light' }}">
                            <i class="fas fa-users mr-3 text-white"></i>
                            Utilisateurs
                        </a>
                        
                        <!-- Promotions -->
                        <a href="{{ route('admin.promotions.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.promotions.*') ? 'bg-primary text-white' : 'text-white hover:bg-accent-light' }}">
                            <i class="fas fa-tag mr-3 text-white"></i>
                            Promotions
                        </a>
                        
                        <!-- Avis -->
                        <a href="{{ route('admin.reviews.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reviews.*') ? 'bg-primary text-white' : 'text-white hover:bg-accent-light' }}">
                            <i class="fas fa-star mr-3 text-white"></i>
                            Avis clients
                        </a>
                        
                        <!-- Statistiques -->
                        <a href="{{ route('admin.statistics') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.statistics*') ? 'bg-primary text-white' : 'text-white hover:bg-accent-light' }}">
                            <i class="fas fa-chart-line mr-3 text-white"></i>
                            Statistiques
                        </a>
                    </nav>
                </div>
                
                <div class="p-4 border-t border-accent-light">
                    <a href="{{ route('home') }}" target="_blank" class="group flex items-center text-sm font-medium text-white hover:text-secondary">
                        <i class="fas fa-external-link-alt mr-3"></i>
                        Voir le site
                    </a>
                </div>
            </div>
        </div>
        
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button type="button" class="px-4 md:hidden" aria-label="Menu">
                    <i class="fas fa-bars text-accent"></i>
                </button>
                
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex items-center">
                        <form class="w-full max-w-lg" action="#" method="GET">
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm" placeholder="Rechercher...">
                            </div>
                        </form>
                    </div>
                    
                    <div class="ml-4 flex items-center md:ml-6">
                        <button type="button" class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            <span class="sr-only">Notifications</span>
                            <i class="fas fa-bell"></i>
                        </button>
                        
                        <div class="ml-3 relative">
                            <div>
                                <button type="button" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Menu utilisateur</span>
                                    <div class="h-8 w-8 rounded-full bg-accent text-white flex items-center justify-center">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </button>
                            </div>
                            
                            <div class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    Votre profil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                    Paramètres
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-4 mt-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 mx-4 mt-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'playfair': ['"Playfair Display"', 'serif'],
                        'sans': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'primary': '#d97706',
                        'primary-dark': '#b45309',
                        'primary-light': '#f59e0b',
                        'secondary': '#f8f2e4',
                        'accent': '#3f3f46',
                        'accent-light': '#52525b',
                    }
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('#sidebar');
            const sidebarToggle = document.querySelector('#sidebar-toggle');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('hidden');
                });
            }
            
            const dropdownButtons = document.querySelectll('[aria-controls]');
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !expanded);
                    const targetId = this.getAttribute('aria-controls');
                    const target = document.getElementById(targetId);
                    
                    if (expanded) {
                        target.classList.add('hidden');
                    } else {
                        target.classList.remove('hidden');
                    }
                });
            });
            
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = userMenuButton?.nextElementSibling;
            
            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });
                
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>