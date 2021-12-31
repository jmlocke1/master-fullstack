<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use app\Utilities\Utilities;
use App\Models\User;

/**
 * Autenticación del usuario
 *
 * @author jmizq_z
 */
class JwtAuth {
    
    private $key;
    
    public function __construct() {
        $this->key = 'esto_es_una_clave_super_secreta_8896547843';
    }
    
    
    
    
    // Devolver los datos decodificados o el token, en función de un parámetro
    public function signup(string $email, string $password, bool $getToken = false) {
        // Buscar si existe el usuario con sus credenciales
        $user = User::where([
            'email' => $email
        ])->first();
        
        // Comprobar si son correctas
        $signup = (is_object($user) && \App\Utilities\Password::verify($password, $user->password));
        if(!$signup){
            return Utilities::responseMessage(400, false, 'Login incorrecto');
        }
        // Generar el token con los datos del usuario identificado
        $token = [
            'sub'       =>  $user->id, // sub, en Jwt, hace siempre referencia al id del usuario
            'email'     =>  $user->email,
            'name'      =>  $user->name,
            'surname'   =>  $user->surname,
            'iat'       =>  time(),                     // Tiempo de creación del token
            'exp'       =>  time() + (7 * 24 * 60 * 60) // Tiempo de expiración del token, en segundos (el ejemplo es una semana)
        ];
        $jwt = JWT::encode($token, $this->key, 'HS256');
        $decoded = JWT::decode($jwt, $this->key, ['HS256']);

        return Utilities::responseMessage(200, true, 'Usuario autentificado', [
                    'token' => $jwt,
                    'userdata' => $decoded,
                    'user' => $user
                ]);
        // Devolver los datos decodificados o el token, en función de un parámetro
        if($getToken){
            return Utilities::responseMessage(200, true, 'Token del usuario', ['token' => $jwt]);
        }else{
            return Utilities::responseMessage(200, true, 'Datos del usuario', ['datos' => $decoded]);
        }
    }
    
    public function checkToken($jwt, $getIdentity = false) {
        $auth = false;
        
        try{
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e){
            $auth = false;
        }
        
        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }else {
            $auth = false;
        }
        
        if($getIdentity){
            return $decoded;
        }
        return $auth;
    }
    
    /**
     * Decodifica los datos del usuario a partir del token que se
     * pasa por el request
     * 
     * @param Request $request
     * @return array
     */
    public function getAutenticateUserData(Request $request){
        $token = $request->header('Authorization');
        $user = $this->checkToken($token, true);
        return $user;
    }
    
    public static function factory(): JwtAuth {
        return new \JwtAuth();
    }
}
