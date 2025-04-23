<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Afficher le formulaire d'inscription
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    
    // Traiter l'inscription
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone,
            'role' => 'client',
        ]);
        
        Auth::login($user);
        
        return redirect()->route('home')->with('success', 'Compte créé avec succès');
    }
    
    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    // Traiter la connexion
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        if (!Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return redirect()
                ->back()
                ->withErrors(['email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.'])
                ->withInput();
        }
        
        $request->session()->regenerate();
        
        return redirect()->intended(route('home'));
    }
    
    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Vous avez été déconnecté');
    }
}