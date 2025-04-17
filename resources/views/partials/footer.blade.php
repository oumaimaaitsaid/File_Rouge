<footer class="bg-accent text-white pt-12 pb-6">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- À propos -->
            <div>
                <h3 class="font-playfair text-xl font-bold mb-4">Tradition Sucrée</h3>
                <p class="text-gray-300 mb-4">
                    Spécialiste des pâtisseries marocaines authentiques, confectionnées avec passion
                    selon des recettes ancestrales transmises de génération en génération.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                        <i class="fab fa-pinterest"></i>
                    </a>
                    <a href="#" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
            
            <!-- Menu rapide -->
            <div>
                <h3 class="font-playfair text-xl font-bold mb-4">Menu Rapide</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                            <i class="fas fa-angle-right mr-2 text-primary-light"></i>Accueil
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/catalog') }}" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                            <i class="fas fa-angle-right mr-2 text-primary-light"></i>Catalogue
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/about') }}" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                            <i class="fas fa-angle-right mr-2 text-primary-light"></i>À propos
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                            <i class="fas fa-angle-right mr-2 text-primary-light"></i>Contact
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/terms') }}" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                            <i class="fas fa-angle-right mr-2 text-primary-light"></i>Conditions générales
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Catégories -->
            <div>
                <h3 class="font-playfair text-xl font-bold mb-4">Nos Catégories</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/catalog', ['category' => 'gateaux-amandes']) }}" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                            <i class="fas fa-angle-right mr-2 text-primary-light"></i>Gâteaux aux Amandes
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/catalog', ['category' => 'gateaux-miel']) }}" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                            <i class="fas fa-angle-right mr-2 text-primary-light"></i>Gâteaux au Miel
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/catalog', ['category' => 'gateaux-feuilletes']) }}" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                            <i class="fas fa-angle-right mr-2 text-primary-light"></i>Gâteaux Feuilletés
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/catalog', ['category' => 'coffrets-cadeaux']) }}" class="text-gray-300 hover:text-primary-light transition-colors duration-200">
                            <i class="fas fa-angle-right mr-2 text-primary-light"></i>Coffrets Cadeaux
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div>
                <h3 class="font-playfair text-xl font-bold mb-4">Contactez-nous</h3>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary-light"></i>
                        <span class="text-gray-300">123 Rue du Commerce, Casablanca, Maroc</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone mr-3 text-primary-light"></i>
                        <span class="text-gray-300">+212 5 22 00 00 00</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-3 text-primary-light"></i>
                        <span class="text-gray-300">contact@tradition-sucree.ma</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-clock mr-3 text-primary-light"></i>
                        <span class="text-gray-300">Lun - Sam: 9h00 - 18h00</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Newsletter -->
        <div class="mt-10 mb-8 pt-8 border-t border-gray-700">
            <div class="max-w-xl mx-auto text-center">
                <h3 class="font-playfair text-xl font-bold mb-2">Newsletter</h3>
                <p class="text-gray-300 mb-4">Inscrivez-vous pour recevoir nos offres spéciales et nos nouveautés</p>
                <form action="{{ url('/newsletter/subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                    @csrf
                    <input type="email" name="email" placeholder="Votre adresse email" required
                        class="flex-grow px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-accent">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-md transition-colors duration-200">
                        S'inscrire
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="pt-6 border-t border-gray-700 text-center">
            <p class="text-gray-400">
                &copy; {{ date('Y') }} Tradition Sucrée. Tous droits réservés.
            </p>
        </div>
    </div>
</footer>