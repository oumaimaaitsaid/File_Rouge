<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'telephone' => 'sometimes|nullable|string|max:20',
            'adresse' => 'sometimes|nullable|string|max:255',
            'ville' => 'sometimes|nullable|string|max:100',
            'code_postal' => 'sometimes|nullable|string|max:20',
            'pays' => 'sometimes|nullable|string|max:100',
            'current_password' => 'sometimes|required|string',
            'new_password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = Auth::user();
            
            if ($request->has('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return redirect()->back()
                        ->with('error', 'Le mot de passe actuel est incorrect')
                        ->withInput();
                }
                
                $user->password = Hash::make($request->new_password);
            }
            
            $fieldsToUpdate = ['name', 'prenom', 'telephone', 'adresse', 'ville', 'code_postal', 'pays'];
            foreach ($fieldsToUpdate as $field) {
                if ($request->has($field)) {
                    $user->$field = $request->$field;
                }
            }
            
            $user->save();
            
            return redirect()->route('profile.show')
                ->with('success', 'Profil mis à jour avec succès');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du profil: ' . $e->getMessage())
                ->withInput();
        }
    }
}