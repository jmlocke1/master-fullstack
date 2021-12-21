<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\PruebasController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Rutas de prueba
Route::get('/', function () {
    return view('welcome');
});

Route::get('/welcome', function () {
    return "Hola Mundo con Laravel";
});

Route::get('/pruebas/{nombre?}', function ($nombre = null) {
    $texto = "<h2>Texto desde una ruta.</h2>";
    $texto .= "<p>El nombre es: $nombre<p>";
    return view('pruebas', array(
        'texto' => $texto
    ));
});

Route::get('/animales', [PruebasController::class, 'index']);
Route::get('/test-orm', [PruebasController::class, 'testOrm']);

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
    Route::post('/api/register', [UserController::class, 'register']);
    Route::post('/api/login', [UserController::class, 'login']);