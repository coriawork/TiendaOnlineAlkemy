<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CompraController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('items')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    //Añade un item al carrito
    Route::post('/', [ItemController::class, 'store']);
    Route::get('/{item}', [ItemController::class, 'show']);
    Route::put('/{item}', [ItemController::class, 'update']);
    Route::delete('/{item}', [ItemController::class, 'destroy']);
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

Route::prefix('carritos')->group(function () {
    // Vacia el carrito
    Route::get('/', [CarritoController::class, 'index']);
    Route::get('/{carrito}', [CarritoController::class, 'show']);
    Route::post('/{carrito}/empty', [CarritoController::class, 'empty']);
});

Route::prefix("compras")->group(function () {
    Route::get('/', [CompraController::class, 'index']);
    Route::put('/{compra}', [CompraController::class, 'update']);
    Route::delete('/{compra}', [CompraController::class, 'destroy']);

    Route::get('/{usuario}', [CompraController::class, 'getComprasByUsuario']);
    // Endpoint para realizar el checkout del carrito de un usuario
    Route::post('/{usuario}/checkout', [CompraController::class, 'checkout']);
});

Route::prefix('categorias')->group(function () {
    Route::get('/', [CategoriaController::class, 'index']);
    Route::post('/', [CategoriaController::class, 'store']);
    Route::get('/{categoria}', [CategoriaController::class, 'show']);
    Route::put('/{categoria}', [CategoriaController::class, 'update']);
    Route::delete('/{categoria}', [CategoriaController::class, 'destroy']);
});