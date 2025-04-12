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

   
}