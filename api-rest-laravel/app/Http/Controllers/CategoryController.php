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
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
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
