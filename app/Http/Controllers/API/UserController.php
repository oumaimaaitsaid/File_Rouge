<?php
// app/Http/Controllers/API/UserController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
//afficher le profile de l'utilisateur connecté
    public function profile()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'telephone' => $user->telephone,
                'adresse' => [
                    'adresse' => $user->addresse,
                    'ville' => $user->ville,
                    'code_postal' => $user->code_postal,
                    'pays' => $user->pays
                ],
                'role' => $user->role
            ]
        ]);
    }

//update profile
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'telephone' => 'sometimes|nullable|string|max:20',
            'addresse' => 'sometimes|nullable|string|max:255',
            'ville' => 'sometimes|nullable|string|max:100',
            'code_postal' => 'sometimes|nullable|string|max:20',
            'pays' => 'sometimes|nullable|string|max:100',
            'current_password' => 'sometimes|required|string',
            'new_password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            if ($request->has('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le mot de passe actuel est incorrect'
                    ], 400);
                }
                
                $user->password = Hash::make($request->new_password);
            }
            
            $fieldsToUpdate = ['name', 'prenom', 'telephone', 'addresse', 'ville', 'code_postal', 'pays'];
            foreach ($fieldsToUpdate as $field) {
                if ($request->has($field)) {
                    $user->$field = $request->$field;
                }
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Profil mis à jour avec succès',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'telephone' => $user->telephone,
                    'adresse' => [
                        'adresse' => $user->addresse,
                        'ville' => $user->ville,
                        'code_postal' => $user->code_postal,
                        'pays' => $user->pays
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}