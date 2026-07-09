<?php

use App\Http\Controllers\Api\AuthController; //Le dice a laravel voy a usar AuthController q esta en esta carpeta
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {       //Route es la clase que meneja todas las rutas HTTP en laravel, ::post esta ruta solo responde a solicitudes post(crear usuarios, enviar formularios, login, register)
    return response()->json([           //'/test' es la url del endpoint
        'message' => 'API funcionando correctamente'
    ]);
});

Route::post('/register', [AuthController::class, 'register']);  //AuthController::class, usar esta clase controller y 'register' ejecuta este metodo de la clase AuthController
//cuando alguien haga post en la ruta /api/register laravel hace esto AuthController->register()
//Osea cuando alguien haga POST a /resgiter, ejecuta ese metodo del controller

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register/cuidador', [AuthController::class, 'registerCaraker']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);