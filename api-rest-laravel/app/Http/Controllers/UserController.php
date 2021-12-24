<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

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
                'name'      => 'required|regex:/^[a-zA-ZáéíóúÁÉÍÓÚÑñ\s]+$/',
                'surname'   => 'required|regex:/^[a-zA-ZáéíóúÁÉÍÓÚÑñ\s]+$/',
                'email'     => 'required|email',
                'password'  => 'required',
            ]);
    //        var_dump($validate);
    //        die();
            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            }else{
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente'
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
        return "Acción de login de usuario";
    }
}
