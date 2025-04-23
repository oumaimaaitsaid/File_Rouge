<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
        
        document.addEventListener('alpine:init', () => {
            Alpine.store('cart', {
                items: [],
                count: 0,
                total: 0,
                
                addItem(item, quantity = 1) {
                    const existingItem = this.items.find(i => i.id === item.id);
                    
                    if (existingItem) {
                        existingItem.quantity += quantity;
                    } else {
                        item.quantity = quantity;
                        this.items.push(item);
                    }
                    
                    this.updateCartState();
                },
                
                removeItem(itemId) {
                    this.items = this.items.filter(item => item.id !== itemId);
                    this.updateCartState();
                },
                
                updateCartState() {
                    this.count = this.items.reduce((total, item) => total + item.quantity, 0);
                    this.total = this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
                }
            });
        });
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    @include('partials.header')
    
    <main class="flex-grow">
        @if (session('success'))
            <div class="container mx-auto px-4 py-3">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container mx-auto px-4 py-3">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>
    
    @include('partials.footer')
    
    <div id="notification-container" class="fixed top-4 right-4 z-50 max-w-sm"></div>
    
    <script>
        function addToCart(productId, productName, price, image) {
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('quantity', 1); 
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartDisplay();
            
            showNotification(data.message || 'Produit ajouté au panier !', 'success');
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout au panier', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur lors de l\'ajout au panier', 'error');
    });
}

function updateCartDisplay() {
    fetch('/cart/info', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const cartCount = document.getElementById('cart-count');
        if (cartCount) {
            cartCount.textContent = data.itemCount;
            cartCount.style.display = data.itemCount > 0 ? 'flex' : 'none';
        }
        
        updateMiniCart(data);
    })
    .catch(error => {
        console.error('Erreur lors de la mise à jour du panier:', error);
    });
}

function updateMiniCart(cartData) {
    const miniCartContainer = document.getElementById('mini-cart-items');
    if (!miniCartContainer) return;
    
    miniCartContainer.innerHTML = '';
    
    if (cartData.items.length === 0) {
        miniCartContainer.innerHTML = '<div class="text-center py-4"><p class="text-gray-500">Votre panier est vide</p></div>';
        return;
    }
    
    cartData.items.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.className = 'flex items-center border-b border-gray-200 py-2';
        itemElement.innerHTML = `
            <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded">
            <div class="ml-3 flex-grow">
                <h4 class="text-sm font-medium">${item.name}</h4>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-600">
                        ${item.quantity} x ${item.price.toFixed(2)} MAD
                    </p>
                    <button onclick="removeCartItem(${item.id})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        miniCartContainer.appendChild(itemElement);
    });
    
    const totalElement = document.getElementById('cart-total');
    if (totalElement) {
        totalElement.textContent = `${cartData.total.toFixed(2)} MAD`;
    }
}

function removeCartItem(itemId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/cart/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur lors de la suppression de l\'article');
        }
        return response.json();
    })
    .then(data => {
        updateCartDisplay();
        showNotification('Article supprimé du panier', 'success');
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur lors de la suppression de l\'article', 'error');
    });
}

function showNotification(message, type = 'success') {
    const notificationContainer = document.getElementById('notification-container');
    if (!notificationContainer) {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'fixed top-4 right-4 z-50 max-w-sm';
        document.body.appendChild(container);
    }
    
    const notification = document.createElement('div');
    notification.className = `${type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700'} border-l-4 p-4 rounded shadow-md mb-3`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <p>${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-${type === 'success' ? 'green' : 'red'}-700">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.getElementById('notification-container').appendChild(notification);
    
    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.remove();
        }
    }, 1000);
}
    </script>
</body>
</html>