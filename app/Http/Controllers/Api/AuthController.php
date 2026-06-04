<?php

namespace App\Http\Controllers\Api;   //A que lugar carpetas pertenece esta clase

use App\Http\Controllers\Controller;  //Importa Controller
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;    //Importamos mi clase de validacion, entonces podemos hacer RegisterRequest $request. Para que mi controller reciba datos limpios
use App\Services\AuthService;       //Importamos mi clase de logica de nogocio
use Exception;


class AuthController extends Controller
{
    protected AuthService $authservice;    //Una propiedad del controller, para guardar la instancia del service

    public function __construct(AuthService $authservice)
    {
        $this->authservice = $authservice;   // Esto es Dependency Injection, Laravel crea el service y lo inyecta automaticamente es como: $this->authService = new AuthService(); 
    }

    public function register(RegisterRequest $request){       //Metodo endpoint, registra un usuario
        $user = $this->authservice->register($request->validated());     //$this->authService->register(...) llamada al metodo register() del service, requeste->validated() solo datos validos
        //$user guarda el usurio retornado por el service
        
        return response()->json([                       //Laravel genera respuesta JSON HTTP
        'message'=>'User registered successfully',
        'user' =>$user
        ], 201);
    }

    public function login(LoginRequest $request){

        try{
            $result = $this->authservice->login($request->Validated());

            return Response()->json([
                'message'=>'Login Succesfull',
                'user'=> $result['user'],
                'token'=>$result['token']
            ], 200);

        }catch(\Exception $e){
            return Response()->json([
                'message'=>$e->getMessage(),
            ], $e->getCode());
        }
    }



}
