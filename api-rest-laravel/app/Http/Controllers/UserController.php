<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Validator;
use App\Utilities\Password;
use App\Utilities\Utilities;
use App\Models\User;
class UserController extends Controller
{
    public function pruebas(Request $request){
        return "Acción de pruebas de USER-CONTROLLER";
    }
    
    public function register(Request $request){
        // Códigos http: https://developer.mozilla.org/es/docs/Web/HTTP/Status
        // Recoger los datos del usuario por post
        [$json, $params, $params_array] = Utilities::getDataFromPost($request);
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
        [$json, $params, $params_array] = Utilities::getDataFromPost($request);
        //return Utilities::responseMessage(200, true, 'Estamos en login', ['params' => $params_array]);
        // Validar esos datos
        $validate = \Validator::make($params_array,[
            'email'     => 'required|email', 
            'password'  => 'required',
        ]);

        if($validate->fails()){
            return Utilities::responseMessage(400, false, 'El usuario no se ha podido identificar', [
                        'errors' => $validate->errors()
                    ]);
        }else{
            $getToken = !empty($params->gettoken);
            $signup = $jwtAuth->signup($params->email, $params->password, $getToken);
            return $signup;
        }
    }
    
    public function update(Request $request){
        // Recoger los datos por post
        [$json, $params, $params_array] = Utilities::getDataFromPost($request);
        if(empty($params_array)){
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'No se han mandado datos para la actualización'
            );
        }else{
            // Actualizar usuario
            // Sacar usuario identificado
            $user = $request->user;

            // Validar datos
            $validate = \Validator::make($params_array, [
                'name'      => 'required|'. \Config\Config::VALIDATE_NAME,
                'surname'   => 'required|'. \Config\Config::VALIDATE_NAME,
                'email'     => 'required|email|unique:users,email,'.$user->sub.',id' // Comprobar si el usuario existe ya (duplicado)
            ]);
            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha actualizado',
                    'errors' => $validate->errors()
                );
            }else {
                // Quitar los campos que no quiero actualizar
                unset($params_array['id']);
                unset($params_array['role']);
                unset($params_array['password']);
                unset($params_array['created_at']);
                unset($params_array['remember_token']);
                unset($params_array['gettoken']);
                // Actualizar usuario en bbdd
                $user_update = User::where('id', $user->sub)->update($params_array);
                // Devolver array con resultado
                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'user' => $user,
                    'changes' => $params_array,
                    'Estado actualización' => $user_update
                );
            }
        }
        return response()->json($data, $data['code']);
    }
    
    public function upload(Request $request) {
        // Recoger datos de la petición
        $image = $request->file('file0');
        
        // Validación de imagen
        $validate = \Validator::make($request->all(), [
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif'
        ]);
        // Guardar imagen
        if(!$image || $validate->fails()){
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen'
            );
        }else{
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('users')->put($image_name, \File::get($image));
            $data = array (
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );
        }
        return response($data, $data['code']);
    }
    
    public function getImage($filename){
        $path = storage_path('app\\users\\' . $filename);
        $isset = \Storage::disk('users')->exists($filename);
        if($isset){
            // $file = \Storage::disk('users')->get($filename);
            $type = \File::mimeType($path);
            // return new Response($file, 200); // Lo que se indica en el video
            return response()->file($path, [
                'Content-Type' => $type
            ]); // Solución que indica Ángel Alexander Quiroz: https://www.udemy.com/course/master-en-desarrollo-web-full-stack-angular-node-laravel-symfony/learn/lecture/13167532#questions/8641706
        }else{
            $data = array (
                'code' => 400,
                'status' => 'error',
                'message' => 'La imagen no existe'
            );
            return response($data, $data['code']);
        }
    }
    
    public function detail($id){
        $user = User::find($id);
        
        if(is_object($user)){
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El usuario no existe.'
            );
        }
        return response($data, $data['code']);
    }
}
