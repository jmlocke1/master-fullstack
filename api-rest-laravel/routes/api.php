<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas del API

    /*
     * Métodos HTTP comunes
     * 
     * GET: Conseguir datos o recursos
     * POST: Guardar datos o recursos o hacer lógica desde un formulario
     * PUT: Actualizar datos o recursos
     * DELETE: Eliminar datos o recursos
     */

    // Rutas de prueba
    Route::get('/usuario/pruebas', [UserController::class, 'pruebas']);
    Route::get('/entrada/pruebas', [PostController::class, 'pruebas']);
    Route::get('/categoria/pruebas', [CategoryController::class, 'pruebas']);
    
    // Rutas del controlador de usuario
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    
    Route::middleware(['api.auth'])->group(function(){
        Route::put('/user/update', [UserController::class, 'update']);
        Route::post('/user/upload', [UserController::class, 'upload']);
    });
    Route::get('/user/avatar/{filename}', [UserController::class, 'getImage']);
    Route::get('/user/detail/{id}', [UserController::class, 'detail']);
    
