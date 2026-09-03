<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas públicas: registro y login no requieren JWT porque generan el token para el cliente.
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Estas rutas sí requieren JWT válido para conocer el usuario autenticado y cerrar/renovar la sesión.
    Route::middleware('autenticar.jwt')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

// Rutas protegidas del carrito y checkout.
// autenticar.jwt valida el token recibido; jwt.cart.owner asegura que el recurso pertenezca al usuario autenticado.
Route::middleware(['autenticar.jwt', 'jwt.cart.owner'])->group(function () {
    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::get('/{carrito_id}/{producto_id}', [ItemController::class, 'show']);
        Route::put('/{carrito_id}/{producto_id}', [ItemController::class, 'update']);
        Route::delete('/{carrito_id}/{producto_id}', [ItemController::class, 'destroy']);
    });

    Route::prefix('carritos')->group(function () {
        Route::get('/', [CarritoController::class, 'index']);
        Route::get('/{carrito}', [CarritoController::class, 'show']);
        Route::post('/{carrito}/empty', [CarritoController::class, 'empty']);
    });

    Route::prefix('compras')->group(function () {
        Route::get('/', [CompraController::class, 'index']);
        Route::put('/{compra}', [CompraController::class, 'update']);
        Route::delete('/{compra}', [CompraController::class, 'destroy']);
        Route::get('/{usuario}', [CompraController::class, 'getComprasByUsuario']);
        Route::post('/{usuario}/checkout', [CompraController::class, 'checkout']);
    });
});

Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'index']);
    Route::post('/', [UsuarioController::class, 'store']);
    Route::get('/{usuario}', [UsuarioController::class, 'show']);
    Route::put('/{usuario}', [UsuarioController::class, 'update']);
    Route::delete('/{usuario}', [UsuarioController::class, 'destroy']);
});

Route::prefix('productos')->group(function () {
    Route::get('/', [ProductoController::class, 'index']);
    Route::post('/', [ProductoController::class, 'store']);
    Route::get('/{producto}', [ProductoController::class, 'show']);
    Route::put('/{producto}', [ProductoController::class, 'update']);
    Route::delete('/{producto}', [ProductoController::class, 'destroy']);
});


Route::prefix('categorias')->group(function () {
    Route::get('/', [CategoriaController::class, 'index']);
    Route::post('/', [CategoriaController::class, 'store']);
    Route::get('/{categoria}', [CategoriaController::class, 'show']);
    Route::put('/{categoria}', [CategoriaController::class, 'update']);
    Route::delete('/{categoria}', [CategoriaController::class, 'destroy']);
});
