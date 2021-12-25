<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use App\Utilities\Password;
use App\Models\User;
class UserController extends Controller
{
    public function pruebas(Request $request){
        return "Acción de pruebas de USER-CONTROLLER";
    }
    
    public function register(Request $request){
        // Códigos http: https://developer.mozilla.org/es/docs/Web/HTTP/Status
        // Recoger los datos del usuario por post
        $json = $request->input('json', null);
        $params = json_decode($json); // objeto
        $params_array = json_decode($json, true); // array
        if(!empty($params) && !empty($params_array)){
            //Limpiar datos
            $params_array = array_map('trim', $params_array);
            // Validar datos
            $validate = \Validator::make($params_array,[
                'name'      => 'required|'. \Config\Config::VALIDATE_NAME,
                'surname'   => 'required|'. \Config\Config::VALIDATE_NAME,
                'email'     => 'required|email|unique:users', // Comprobar si el usuario existe ya (duplicado)
                'password'  => 'required',
            ]);
            
            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            }else{
                // Validación pasada correctamente
                
                // Cifrar la contraseña
                $password = Password::hash($params->password);
                
                // Crear el usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $password;
                $user->name = $params_array['name'];
                $user->role = \Config\Config::ROLE_USER;
                $user->save();
                
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
                );
            }
        }else{
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados no son correctos'
            );
        }
        
        // Cifrar la contraseña
        
        // Comprobar si el usuario existe ya (duplicado)
        
        // Crear el usuario
        
        return response()->json($data, $data['code']);
    }

    public function login(Request $request) {
        $jwtAuth = new \JwtAuth();
        // Recibir datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        // Validar esos datos
        $validate = \Validator::make($params_array,[
            'email'     => 'required|email', 
            'password'  => 'required',
        ]);

        if($validate->fails()){
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        }else{
            $getToken = !empty($params->getToken);
            $signup = $jwtAuth->signup($params->email, $params->password, $getToken);
        }
        // Cifrar la password (no se hace con mi implementación
        
        
        
        return $signup;
    }
    
    public function update(Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        
        if($checkToken){
            echo "<h1>Login correcto</h1>";
        }else{
            echo "<h1>Login incorrecto</h1>";
        }
        die();
    }
}
