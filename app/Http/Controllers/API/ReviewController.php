<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    //enregistrer un avis
    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:500'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $product = Produit::findOrFail($productId);
            
            $existingReview = Avis::where('user_id', Auth::id())
                ->where('produit_id', $productId)
                ->first();
                
            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous avez déjà laissé un avis pour ce produit'
                ], 400);
            }
            
            $review = Avis::create([
                'user_id' => Auth::id(),
                'produit_id' => $productId,
                'note' => $request->rating,
                'commentaire' => $request->comment,
                'approuve' => false // L'avis doit être approuvé par un admin
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Merci pour votre avis ! Il sera visible après modération.',
                'data' => [
                    'id' => $review->id,
                    'rating' => $review->note,
                    'comment' => $review->commentaire,
                    'approved' => $review->approuve,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s')
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement de l\'avis',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:500'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $review = Avis::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
                        $review->update([
                'note' => $request->rating,
                'commentaire' => $request->comment,
                'approuve' => false 
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Votre avis a été mis à jour et sera visible après modération.',
                'data' => [
                    'id' => $review->id,
                    'rating' => $review->note,
                    'comment' => $review->commentaire,
                    'approved' => $review->approuve,
                    'updated_at' => $review->updated_at->format('Y-m-d H:i:s')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'avis',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
// supprimer un review
    public function destroy($id)
    {
        try {
            $review = Avis::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $review->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Votre avis a été supprimé'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'avis',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}