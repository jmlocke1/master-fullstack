<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Utilities\Utilities;

class PostController extends Controller
{
    
    public function __construct() {
        $this->middleware('api.auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all()->load('category');
        return Utilities::responseMessage(200, true, 'Post listados', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Recoger datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        if(empty($params_array)){
            return Utilities::responseMessage(400, false, 'No se han enviado datos de post. Envía los datos correctamente.');
        }
        // Conseguir usuario identificado
        $user = \App\Helpers\JwtAuth::factory()->getAutenticateUserData($request);
        // Validar los datos
        $validate = \Validator::make($params_array, [
            'title' => 'required|unique:posts',
            'content' => 'required',
            'category_id' => 'required',
            'image' => 'required'
        ]);
        if($validate->fails()){
            return Utilities::responseMessage(400, false, 
                    'No se ha guardado el post, faltan datos o son erróneos.', 
                    ['errors' => $validate->errors()]);
        }
        // Guardar el artículo
        $post = new Post();
        $post->user_id = $user->sub;
        $post->category_id = $params->category_id;
        $post->title = $params->title;
        $post->content = $params->content;
        $post->image = $params->image;
        try{
            $result = $post->save();
            return Utilities::responseMessage(200, true, 'Post insertado correctamente.', [
                    'post' => $post,
                    'Resultado' => $result
                ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            return Utilities::responseMessage(400, false, 
                    'No se ha guardado el post, Error al insertar en la base de datos.', 
                    ['errors' => $ex]);
        }
        
        $result = $post->save();
        // Devolver el resultado
        return Utilities::responseMessage(200, true, 'Post insertado correctamente.', [
                    'post' => $post,
                    'Resultado' => $result
                ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try{
            $post = Post::findOrFail($id)->load('category');
            return Utilities::responseMessage(200, true, '', ['post' => $post]);
        } catch (ModelNotFoundException $ex) {
            return Utilities::responseMessage(404, false, 'La entrada no existe');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {
        // Recoger los datos por POST
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        if(empty($params_array)){
            return Utilities::responseMessage(400, false, 'Datos de post enviados incorrectamente.');
        }
        // Validar los datos
        $validate = \Validator::make($params_array, [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required'
        ]);
        if($validate->fails()){
            return Utilities::responseMessage(400, false, 'No se ha actualizado la entrada.', ['errors' => $validate->errors()]);
        }
        // Eliminar lo que no queremos actualizar
        unset($params_array['id']);
        unset($params_array['user_id']);
        unset($params_array['created_at']);
        unset($params_array['user']);
        
        // Conseguir usuario identificado
        $user = \App\Helpers\JwtAuth::factory()->getAutenticateUserData($request);
        // Actualizar el registro en concreto
        $post = Post::where('id', $id)
                ->where('user_id', $user->sub)
                ->first();
        if(empty($post)){
            return Utilities::responseMessage(404, false, 'El post no existe o no tienes derechos suficientes para borrarlo.');
        }
        $post->update($params_array);
        // Devolver algo
        return Utilities::message(200, true, 'Post actualizado correctamente', [
                    'post' => $post,
                    'changes' => $params_array
                ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int   $id
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Función para destruir un post. Solo tiene derecho a eliminar un post el
     * autor del mismo.
     * 
     * @param int $id
     * @param Request $request
     */
    public function destroy(int $id, Request $request)
    {
        $user = \App\Helpers\JwtAuth::factory()->getAutenticateUserData($request);
        // Conseguir el registro
        $post = Post::where('id', $id)
                ->where('user_id', $user->sub)
                ->first();
        if(empty($post)){
            return Utilities::responseMessage(404, false, 'El post no existe o no tienes derechos suficientes para borrarlo.');
        }
        // Borrarlo
        $post->delete();
        
        // Devolver algo
        return Utilities::message(200, true, 'Post borrado correctamente', ['post' => $post]);
    }
}
