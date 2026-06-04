<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;   //Esto importamos para poder usar las clases Auth para comparar password con hash guardada en la base Es el sistema de autenticación. Sabe cómo comparar una password con su hash

class AuthService{
    
    public function register(array $data) //Creamos un metodo llamado register, (array $data) Recibe datos validados
    {
        //Inserta un usuario en la bd, esto genera internamente un un INSERT INTO users(.....);
        $user = User::create     
        (
            [
            'name'=>$data['name'],                   //'name' => $data['name'] toma el valor validado en el request--- ESTO VIENE DESDE ***request->validated()***
            'last_name'=>$data['last_name'],
            'email'=>$data['email'],
            'password'=>$data['password'],
            'phone'=>$data['phone'] ?? null,     //si existe toma 'phone' sino null
            'birth_date'=>$data['birth_date'],
            'status'=>'active',                 //El usuario no decide esto
            'otp_code'=>rand(1000,9999),        //el backend controla estados intermos
            'otp_expires_at'=> now()->addMinutes(5)
            ]
        );
        return ['user' => $user];      //Devuelve el usuario recien creado
    }



    public function login(array $data): array{   //Recibe un array con los datos validados que vienen del Request y devuelve un array con el usuario y el token.
 
        //Intentamos autenticar email y password
        if(!Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])){    //Auth::attempt() hace dos cosas internamente: 1. Busca en la tabla users el registro con ese email 2. Compara la password ingresada con el hash guardado

            //Si no coincide largamos una exception con el codigo 401
            throw new \Exception('Credenciales incorrectas', 401);
        }

        //Auth::attempt es el metodo de laravel , que compara el password ingresdo con la hash guardada y devvuelve true o false


        //Si llegamos hasta aca s porque Auth::attempt() devolvió true, o sea las credenciales son correctas. , buscamos en la base de datos, el email y guardamos en nuestra variales el primero q encontramos
        $user = User::where('email', $data['email'])->first();
        $user->tokens()->delete();   //Para no acumular tokens viejos en la tabla- ->tokens() es una relación que te da Sanctum automáticamente cuando pusiste HasApiTokens en el modelo User.


        //generamos el nuevo tokens con sanctum
        $token = $user->createToken('auth_token')->plainTextToken;
        //→ Sanctum crea un nuevo registro en personal_access_tokens
        //→ Lo asocia a este usuario
        //→ 'auth_token' es solo el nombre del token, para identificarlo
        //->plainTextToken
          //  → Te da el token en texto plano para mandárselo al usuario
          //  → Ejemplo: "3|kLmN9xPqR7vWzYt..."   

        return[
            'user' =>$user,
            'token'=>$token,
        ];


        /*LoginRequest
        valida que llegaron email y password
        ↓
        AuthService::login()
        Auth::attempt()  →  compara credenciales
        Si falla         →  Exception 401
        Si pasa          →  busca usuario completo
                     →  borra tokens viejos
                     →  crea token nuevo con Sanctum
        return { user, token }
        ↓
        AuthController
        recibe { user, token }
        devuelve JSON 200
        ↓
        React Native
        guarda el token en el dispositivo
        lo manda en cada request siguiente*/

    }

}

?>