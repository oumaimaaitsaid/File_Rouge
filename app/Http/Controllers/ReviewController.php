<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Avis::with(['produit', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('reviews.index', compact('reviews'));
    }

    // Enregistrer un avis
    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'required|string|min:5|max:500'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $product = Produit::findOrFail($productId);
            
            $existingReview = Avis::where('user_id', Auth::id())
                ->where('produit_id', $productId)
                ->first();
                
            if ($existingReview) {
                return redirect()->back()
                    ->with('error', 'Vous avez déjà laissé un avis pour ce produit');
            }
            
            $review = Avis::create([
                'user_id' => Auth::id(),
                'produit_id' => $productId,
                'note' => $request->note,
                'commentaire' => $request->commentaire,
                'approuve' => false 
            ]);
            
            return redirect()->back()
                ->with('success', 'Merci pour votre avis ! Il sera visible après modération.');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement de l\'avis: ' . $e->getMessage());
        }
    }
    
    public function edit($id)
    {
        $review = Avis::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        $product = $review->produit;
        
        return view('reviews.edit', compact('review', 'product'));
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'required|string|min:5|max:500'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $review = Avis::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
                        
            $review->update([
                'note' => $request->note,
                'commentaire' => $request->commentaire,
                'approuve' => false 
            ]);
            
            return redirect()->route('user.reviews')
                ->with('success', 'Votre avis a été mis à jour et sera visible après modération.');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de l\'avis: ' . $e->getMessage());
        }
    }
    
    // Supprimer un avis
    public function destroy($id)
    {
        try {
            $review = Avis::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $review->delete();
            
            return redirect()->route('user.reviews')
                ->with('success', 'Votre avis a été supprimé');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'avis: ' . $e->getMessage());
        }
    }
    
    // Afficher les avis de l'utilisateur connecté
    public function userReviews()
    {
        $reviews = Avis::where('user_id', Auth::id())
            ->with('produit')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('reviews.user', compact('reviews'));
    }
}