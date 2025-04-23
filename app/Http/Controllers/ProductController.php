<?php
namespace App\Http\Controllers;
use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Liste des produits avec filtrage et pagination
    public function index(Request $request)
    {
        $query = Produit::where('disponible', true)
            ->with(['categorie', 'imagePrincipale']);
   
        // Filtrage par catégorie
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('categorie', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Tri des produits
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderByRaw('COALESCE(prix_promo, prix) ASC');
                    break;
                case 'price_desc':
                    $query->orderByRaw('COALESCE(prix_promo, prix) DESC');
                    break;
                case 'name_asc':
                    $query->orderBy('nom', 'asc');   
                    break;
                case 'name_desc':
                    $query->orderBy('nom', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'popular':
                    $query->withCount(['avis' => function($q) {
                        $q->where('approuve', true);
                    }])
                    ->orderByDesc('avis_count');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Recherche par nom ou description
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                ->orWhere('description', 'like', "%{$request->search}%")
                ->orWhere('ingredients', 'like', "%{$request->search}%");
            });
        }
        
        // Pagination
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);
        
        // Catégories pour le filtre
        $categories = \App\Models\Categorie::where('active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    // Affichage d'un produit spécifique
    public function show($slug)
    {
        $product = Produit::where('slug', $slug)
            ->with([
                'images', 
                'categorie', 
                'avis' => function($query) {
                    $query->where('approuve', true)->with('user');
                }
            ])
            ->firstOrFail();
        
        // Produits similaires (même catégorie)
        $relatedProducts = Produit::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('disponible', true)
            ->with(['imagePrincipale'])
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    // Produits en vedette
    public function featured()
    {
        $featuredProducts = Produit::where('featured', true)
            ->where('disponible', true)
            ->with(['imagePrincipale', 'categorie'])
            ->take(4)
            ->get();

        return view('products.featured', compact('featuredProducts'));
    }

    // Produits récents
    public function recent()
    {
        $recentProducts = Produit::where('disponible', true)
            ->with(['imagePrincipale', 'categorie'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('products.recent', compact('recentProducts'));
    }

    // Recherche de produits
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $products = Produit::where('disponible', true)
            ->where(function($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->orWhere('ingredients', 'LIKE', "%{$query}%");
            })
            ->with(['imagePrincipale', 'categorie'])
            ->paginate(12);

        return view('products.search', compact('products', 'query'));
    }

    // Page d'accueil du catalogue (combine featured et recent)
    public function catalog()
    {
        $featuredProducts = Produit::where('featured', true)
            ->where('disponible', true)
            ->with(['imagePrincipale', 'categorie'])
            ->take(4)
            ->get();
            
        $recentProducts = Produit::where('disponible', true)
            ->with(['imagePrincipale', 'categorie'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();
            
        $categories = \App\Models\Categorie::where('active', true)->get();

        return view('products.catalog', compact('featuredProducts', 'recentProducts', 'categories'));
    }


    public function home()
{
    // Récupérer les produits en vedette
    $featuredProducts = Produit::where('featured', true)
        ->where('disponible', true)
        ->with(['imagePrincipale', 'categorie'])
        ->take(4)
        ->get();
        
    // Récupérer les produits récents
    $recentProducts = Produit::where('disponible', true)
        ->with(['imagePrincipale', 'categorie'])
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();
        
    // Récupérer les catégories pour l'affichage
    $categories = Categorie::where('active', true)->take(4)->get();

    return view('home', compact('featuredProducts', 'recentProducts', 'categories'));
}
}