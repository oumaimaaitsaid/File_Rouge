<?php
// app/Http/Controllers/API/Admin/ReviewController.php
namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{

    //lister tout les avis par l admin
    public function index(Request $request)
    {
        $query = Avis::with(['user', 'produit']);
        
        if ($request->has('product_id') && !empty($request->product_id)) {
            $query->where('produit_id', $request->product_id);
        }
        
        if ($request->has('approved') && $request->approved !== null) {
            $query->where('approuve', $request->approved === 'true' || $request->approved === '1');
        }
        
        if ($request->has('rating') && !empty($request->rating)) {
            $query->where('note', $request->rating);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('commentaire', 'LIKE', "%{$search}%");
        }
        
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $perPage = $request->get('per_page', 10);
        $reviews = $query->paginate($perPage);
        
        $result = $reviews->map(function($review) {
            return [
                'id' => $review->id,
                'user' => [
                    'id' => $review->user->id,
                    'name' => $review->user->name,
                    'email' => $review->user->email
                ],
                'product' => [
                    'id' => $review->produit->id,
                    'name' => $review->produit->nom,
                    'slug' => $review->produit->slug
                ],
                'rating' => $review->note,
                'comment' => $review->commentaire,
                'approved' => (bool) $review->approuve,
                'created_at' => $review->created_at->format('Y-m-d H:i:s')
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $result,
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'from' => $reviews->firstItem(),
                'to' => $reviews->lastItem(),
            ]
        ]);
    }
    
//voir le détail d'un avis
    public function show($id)
    {
        try {
            $review = Avis::with(['user', 'produit'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $review->id,
                    'user' => [
                        'id' => $review->user->id,
                        'name' => $review->user->name,
                        'email' => $review->user->email
                    ],
                    'product' => [
                        'id' => $review->produit->id,
                        'name' => $review->produit->nom,
                        'slug' => $review->produit->slug
                    ],
                    'rating' => $review->note,
                    'comment' => $review->commentaire,
                    'approved' => (bool) $review->approuve,
                    'created_at' => $review->created_at->format('Y-m-d H:i:s')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Avis non trouvé',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    

    //approve ou rejite un avis
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approved' => 'required|boolean'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $review = Avis::findOrFail($id);
            
            $review->update([
                'approuve' => $request->approved
            ]);
            
            $status = $request->approved ? 'approuvé' : 'rejeté';
            
            return response()->json([
                'success' => true,
                'message' => "L'avis a été {$status} avec succès",
                'data' => [
                    'id' => $review->id,
                    'approved' => (bool) $review->approuve
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erreur lors de la mise à jour de l'avis",
                'error' => $e->getMessage()
            ], 500);
        }
    }
    


}