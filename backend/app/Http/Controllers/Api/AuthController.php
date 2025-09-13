<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Password::min(8)],
                'phone' => 'nullable|string|max:20',
                'document' => 'nullable|string|max:20',
                'document_type' => 'nullable|in:cpf,cnpj',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:2',
                'zipcode' => 'nullable|string|max:10',
                'customer_type' => 'nullable|in:b2c,b2b' // Para determinar o tipo de cliente
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'document' => $validated['document'] ?? null,
                'document_type' => $validated['document_type'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'zipcode' => $validated['zipcode'] ?? null,
                'is_active' => true,
            ]);

            // Atribuir role baseado no tipo de cliente
            $customerType = $validated['customer_type'] ?? 'b2c';
            $roleName = $customerType === 'b2b' ? 'customer_b2b' : 'customer_b2c';
            $role = Role::where('name', $roleName)->first();
            
            if ($role) {
                $user->assignRole($role);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuário registrado com sucesso',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $user->permissions()->pluck('name')
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Login user
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciais inválidas'
                ], 401);
            }

            $user = Auth::user();
            
            // Verificar se usuário está ativo
            if (!$user->is_active) {
                return response()->json([
                    'message' => 'Usuário inativo. Entre em contato com o suporte.'
                ], 403);
            }

            // Atualizar último login
            $user->updateLastLogin();

            // Revogar tokens antigos (opcional - pode ser configurável)
            // $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login realizado com sucesso',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $user->permissions()->pluck('name')
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'document' => $user->document,
                'document_type' => $user->document_type,
                'address' => $user->address,
                'city' => $user->city,
                'state' => $user->state,
                'zipcode' => $user->zipcode,
                'is_active' => $user->is_active,
                'last_login_at' => $user->last_login_at,
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->permissions()->pluck('name')
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $user = $request->user();
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:2',
                'zipcode' => 'nullable|string|max:10',
            ]);

            $user->update($validated);

            return response()->json([
                'message' => 'Perfil atualizado com sucesso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'city' => $user->city,
                    'state' => $user->state,
                    'zipcode' => $user->zipcode,
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required',
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);

            $user = $request->user();

            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'message' => 'Senha atual incorreta'
                ], 422);
            }

            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return response()->json([
                'message' => 'Senha alterada com sucesso'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso'
        ]);
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout realizado em todos os dispositivos'
        ]);
    }
}
