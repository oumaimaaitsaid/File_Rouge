@extends('layouts.app')

@section('title', 'Inscription - Tradition Sucrée')

@section('content')
<div class="bg-secondary py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-primary px-6 py-8 text-white">
                <h2 class="text-3xl font-playfair font-bold text-center">Créer un compte</h2>
                <p class="text-center mt-2">Rejoignez Tradition Sucrée et découvrez nos délicieuses pâtisseries</p>
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
                
                <form id="register-form" class="register-form">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">Nom</label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        
                        <div>
                            <label for="prenom" class="block text-gray-700 font-medium mb-2">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div class="mb-4">
                        <label for="telephone" class="block text-gray-700 font-medium mb-2">Téléphone</label>
                        <input type="tel" id="telephone" name="telephone" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                        <input type="password" id="password" name="password" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        <p class="text-xs text-gray-500 mt-1">Le mot de passe doit contenir au moins 8 caractères</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" name="terms" required class="rounded text-primary focus:ring-primary mt-1">
                            <span class="ml-2 text-gray-700 text-sm">
                                J'accepte les <a href="{{ url('/terms') }}" class="text-primary hover:underline">conditions générales</a> 
                                et la <a href="{{ url('/privacy') }}" class="text-primary hover:underline">politique de confidentialité</a>
                            </span>
                        </label>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-300">
                        Créer mon compte
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <p class="text-gray-600">Vous avez déjà un compte ?</p>
                    <a href="{{ url('/login') }}" class="text-primary hover:text-primary-dark font-medium">
                        Se connecter
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
    const registerForm = document.getElementById('register-form');
    const errorMessage = document.getElementById('error-message');
    
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            
            if (password !== passwordConfirmation) {
                errorMessage.textContent = 'Les mots de passe ne correspondent pas.';
                errorMessage.classList.remove('hidden');
                return;
            }
            
            errorMessage.classList.add('hidden');
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création du compte...';
            
            const formData = {
                name: document.getElementById('name').value,
                prenom: document.getElementById('prenom').value,
                email: document.getElementById('email').value,
                telephone: document.getElementById('telephone').value,
                password: password
            };
            
            fetch('/api/v1/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem('token', data.data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.data.user));
                    
                    window.location.href = '/';
                } else {
                    let errorMsg = data.message || 'Une erreur est survenue lors de l\'inscription.';
                    
                    if (data.error) {
                        errorMsg = Object.values(data.error).flat().join('<br>');
                    }
                    
                    errorMessage.innerHTML = errorMsg;
                    errorMessage.classList.remove('hidden');
                    
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            })
            .catch(error => {
                errorMessage.textContent = 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer plus tard.';
                errorMessage.classList.remove('hidden');
                
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                
                console.error('Registration error:', error);
            });
        });
    }
});
</script>
@endsection