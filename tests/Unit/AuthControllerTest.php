<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

it('simula el proveedor JWT al iniciar sesión sin firmar un token real', function () {
    JWTAuth::shouldReceive('attempt')
        ->once()
        ->with([
            'correo' => 'usuario@example.com',
            'password' => 'password123',
        ])
        ->andReturn('token-simulado');

    $request = Request::create('/api/auth/login', 'POST', [
        'correo' => 'usuario@example.com',
        'password' => 'password123',
    ]);

    $respuesta = (new AuthController)->login($request);

    expect($respuesta->getStatusCode())->toBe(200);
    expect($respuesta->getData(true))->toMatchArray([
        'token' => 'token-simulado',
        'token_type' => 'bearer',
    ]);
});
