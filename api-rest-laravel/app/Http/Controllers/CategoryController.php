<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Http\Response;
use App\Utilities\Utilities;

class CategoryController extends Controller
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
        $categories = Category::all();
        return Utilities::message(200, true, '', ['categories' => $categories]);
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
        // Recoger los datos por post
        [$json, $params, $params_array] = Utilities::getDataFromPost($request);
        if(empty($params_array)){
            return Utilities::responseMessage(400, false, 'No se han enviado datos de categoría.');
        }
        $validate = $this->validateStore($params_array);
        if($validate->fails()){
            return Utilities::responseMessage(400, false, 'No se ha guardado la categoría.', ['errors' => $validate->errors()]);
        }

        // Guardar la categoría
        $category = new Category();
        $category->name = $params_array['name'];
        $category->save();
        return Utilities::responseMessage(200, true, 'Categoría almacenada correctamente', ['category' => $category]);
    }
    
    
    private function validateStore($params_array): \Illuminate\Validation\Validator {
        // Validar los datos
        $validate = \Validator::make($params_array, [
            'name' => 'required|unique:categories'
        ]);
        return $validate;
    }
    
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id): Response
    {
        try{
            $category = Category::findOrFail($id);
            return Utilities::responseMessage(200, true, '', ['category' => $category]);
        } catch (ModelNotFoundException $ex) {
            return Utilities::responseMessage(404, false, 'La categoría no existe');
        }
    }
    


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
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
        // Recoger json por post
        [$json, $params, $params_array] = Utilities::getDataFromPost($request);
        if(empty($params_array)){
            return Utilities::responseMessage(400, false, 'No se han enviado datos de categoría.');
        }
        // Validar los datos
        $validate = \Validator::make($params_array, [
            'name' => 'required'
        ]);
        if($validate->fails()){
            return Utilities::responseMessage(400, false, 'No se ha guardado la categoría.', ['errors' => $validate->errors()]);
        }
        // Quitar lo que no quiero actualizar
        unset($params_array['id']);
        unset($params_array['created_at']);
        // Actualizar el registro (categoría)
        //$category = Category::where('id', $id)->update($params_array);
        $category = Category::where('id', $id)->update($params_array);
        if($category){
            return Utilities::responseMessage(200, true, 
                'Categoría actualizada correctamente', ['category' => $params_array]);
        }else {
            return Utilities::responseMessage(400, false, 
                'La Categoría no se pudo actualizar');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
