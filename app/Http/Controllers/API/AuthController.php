<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //Register a new user
    public function register (Request $request){
        $validator =Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'prenom'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|min:8|confirmed',
            'telephone'=>'required|string|max:255',
        ]);
        if($validator->fails()){
            return response()->([
                'success'=>false,
                'message'=>'Validation error',
                'error'=>$validator->errors()
            ],422)
        }
        $user = User::create([
            'name'=>$request->name,
            'prenom'=>$request->prenom, 
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'telephone'=>$request->telephone,
            'role'=>'client',
        ]);

        $toke= $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success'=>true,
            'message'=>'User created successfully',
            'data'=>[
                'user'=>$user,
                'access_token'=>$token,
                'token_type'=>'Bearer',
            ]
            ],201);
    }
}
