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
//Route::get('/', function () {
//    return view('welcome');
//});
Route::view('/', 'welcome'); // Ruta que devuelve una vista. Es otra forma de escribir la ruta anterior

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
// Ruta con restricciones de expresiones regulares
Route::get('user/{id}/{name}', function ($id, $name) {
    return "Usuario con id {$id} y nombre {$name}";
})->where(['id' => '[0-9]+', 'name' => '[a-z]+[a-z0-9]+']);

Route::get('/animales', [PruebasController::class, 'index']);
Route::get('/test-orm', [PruebasController::class, 'testOrm']);

    
