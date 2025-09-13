<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Rotas públicas
Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Rotas protegidas por autenticação
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Autenticação
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'changePassword']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });

    // Rota simples para verificar se usuário está autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Dashboard - apenas usuários com permissão
    Route::group(['prefix' => 'dashboard', 'middleware' => 'permission:view_dashboard'], function () {
        Route::get('/', function () {
            return response()->json([
                'message' => 'Dashboard do Armazém 357',
                'data' => [
                    'total_orders' => 0,
                    'total_products' => 0,
                    'total_customers' => 0,
                    'revenue' => 0
                ]
            ]);
        });
    });

    // Administração - apenas admins
    Route::group(['prefix' => 'admin', 'middleware' => 'role:super_admin,admin'], function () {
        Route::get('/users', function () {
            return response()->json(['message' => 'Lista de usuários - apenas para admins']);
        })->middleware('permission:view_users');
        
        Route::get('/system-info', function () {
            return response()->json([
                'message' => 'Informações do sistema',
                'laravel_version' => app()->version(),
                'php_version' => PHP_VERSION,
                'environment' => app()->environment()
            ]);
        });
    });

    // Loja - rotas para clientes
    Route::group(['prefix' => 'store', 'middleware' => 'permission:browse_store'], function () {
        Route::get('/products', function () {
            return response()->json([
                'message' => 'Catálogo de grãos de café',
                'products' => []
            ]);
        });
    });
});

// Rotas de teste para desenvolvimento
Route::group(['prefix' => 'test'], function () {
    Route::get('/http', function() {
        return response('Test HTTP Response', 201)
            ->header('Content-Type', 'text/plain');
    });

    Route::get('/json', function() {
        return response()->json(['message' => 'Test JSON response'], 201);
    });

    Route::get('/status', function() {
        return response()->json([
            'message' => 'Test status code',
            'status' => 201
        ])->setStatusCode(201, 'Created');
    });
});
