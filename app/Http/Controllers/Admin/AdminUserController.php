<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
   
    
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filtrage
        if ($request->has('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('prenom', 'like', "%{$request->search}%");
            });
        }
        
        // Tri
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $users = $query->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function show($id)
    {
        $user = User::with('commandes')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:client,admin',
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $data = [
                'name' => $request->name,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'role' => $request->role,
                'telephone' => $request->telephone,
                'adresse' => $request->adresse,
                'ville' => $request->ville,
                'code_postal' => $request->code_postal,
                'pays' => $request->pays,
            ];
            
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            
            $user->update($data);
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur mis à jour avec succès');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de l\'utilisateur: ' . $e->getMessage())
                ->withInput();
        }
    }
    
   
}