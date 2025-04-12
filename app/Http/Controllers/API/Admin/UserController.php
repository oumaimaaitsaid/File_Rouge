<?php
namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //voir tous les utilisateurs
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'telephone' => $user->telephone,
                    'role' => $user->role,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s')
                ];
            }),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ]
        ]);
    }

// voir détail d un utilisateur
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            
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
                    'role' => $user->role,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s')
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé',
                'error' => $e->getMessage()
            ], 404);
        }
    }

//cree nouveau utilisateur
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'telephone' => 'nullable|string|max:20',
            'addresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'nullable|string|max:100',
            'role' => 'required|in:client,admin,partenaire'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telephone' => $request->telephone,
                'addresse' => $request->addresse,
                'ville' => $request->ville,
                'code_postal' => $request->code_postal,
                'pays' => $request->pays,
                'role' => $request->role
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

// update un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'sometimes|nullable|string|min:8',
            'telephone' => 'sometimes|nullable|string|max:20',
            'addresse' => 'sometimes|nullable|string|max:255',
            'ville' => 'sometimes|nullable|string|max:100',
            'code_postal' => 'sometimes|nullable|string|max:20',
            'pays' => 'sometimes|nullable|string|max:100',
            'role' => 'sometimes|required|in:client,admin,partenaire'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fieldsToUpdate = ['name', 'prenom', 'email', 'telephone', 'addresse', 'ville', 'code_postal', 'pays', 'role'];
            foreach ($fieldsToUpdate as $field) {
                if ($request->has($field)) {
                    $user->$field = $request->$field;
                }
            }
            
            if ($request->has('password') && !empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'telephone' => $user->telephone,
                    'role' => $user->role
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

  
}