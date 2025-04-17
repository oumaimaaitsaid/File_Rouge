@extends('layouts.app')

@section('title', 'Connexion - Tradition Sucrée')

@section('content')
<div class="bg-secondary py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary px-6 py-8 text-white">
                <h2 class="text-3xl font-playfair font-bold text-center">Connexion</h2>
                <p class="text-center mt-2">Accédez à votre compte Tradition Sucrée</p>
            </div>
            
            <div class="p-6">
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div id="error-message" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6"></div>
                
                <form id="login-form" class="login-form">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                        <input type="password" id="password" name="password" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        <div class="mt-1 text-right">
                            <a href="#" class="text-sm text-primary hover:text-primary-dark">Mot de passe oublié ?</a>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded text-primary focus:ring-primary">
                            <span class="ml-2 text-gray-700">Se souvenir de moi</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-300">
                        Se connecter
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600">Vous n'avez pas de compte ?</p>
                    <a href="{{ url('/register') }}" class="text-primary hover:text-primary-dark font-medium">
                        Créer un compte
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            errorMessage.classList.add('hidden');
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Connexion en cours...';
            fetch('/api/v1/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem('token', data.data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.data.user));
                    
                    window.location.href = '/';
                } else {
                    errorMessage.textContent = data.message || 'Les identifiants fournis ne correspondent pas à nos enregistrements.';
                    errorMessage.classList.remove('hidden');
                    
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            })
            .catch(error => {
                errorMessage.textContent = 'Une erreur est survenue lors de la connexion. Veuillez réessayer plus tard.';
                errorMessage.classList.remove('hidden');
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                console.error('Login error:', error);
            });
        });
    }
});
</script>
@endsection