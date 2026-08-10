<?php

use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('productos')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])->name("productos");
    Route::get('/crear', [ProductoController::class, 'create'])->name("productos.crear");
    Route::get('/{producto}/editar', [ProductoController::class, 'edit'])->name("productos.editar");
    Route::post('/', [ProductoController::class, 'store'])->name("productos");
    Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name("productos.eliminar");
    Route::put('/{producto}', [ProductoController::class, 'update'])->name("productos.actualizar");
});