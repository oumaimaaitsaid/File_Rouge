<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
   
    
    public function index(Request $request)
    {
        $query = Avis::with(['produit', 'user']);
        
        // Filtrage
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('approuve', $request->status === 'approved');
        }
        
        if ($request->has('rating') && $request->rating !== 'all') {
            $query->where('note', $request->rating);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('commentaire', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                  })
                  ->orWhereHas('produit', function($q) use ($request) {
                      $q->where('nom', 'like', "%{$request->search}%");
                  });
            });
        }
        
        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $reviews = $query->paginate(20);
        
        return view('admin.reviews.index', compact('reviews'));
    }
    
    public function approve($id)
    {
        try {
            $review = Avis::findOrFail($id);
            $review->update(['approuve' => true]);
            
            return redirect()->back()
                ->with('success', 'Avis approuvé avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'approbation de l\'avis: ' . $e->getMessage());
        }
    }
    
    public function reject($id)
    {
        try {
            $review = Avis::findOrFail($id);
            $review->update(['approuve' => false]);
            
            return redirect()->back()
                ->with('success', 'Avis rejeté avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors du rejet de l\'avis: ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            $review = Avis::findOrFail($id);
            $review->delete();
            
            return redirect()->back()
                ->with('success', 'Avis supprimé avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'avis: ' . $e->getMessage());
        }
    }
}