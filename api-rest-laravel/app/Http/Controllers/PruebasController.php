<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
//use App\Models\User;

class PruebasController extends Controller
{
    public function index() {
        $titulo = "Animales";
        $animales = [ 'Perro', 'Gato', 'Tigre'];
        
        return view('pruebas.index', array(
            'titulo' => $titulo,
            'animales' => $animales
        ));
    }
    
    public function testorm() {
        $posts = Post::all();
//        foreach($posts as $postst){
//            echo "<h1>".$post->title."</h1>";
//            echo "<span style='color:gray;'>{$post->user->name} - {$post->category->name}</span>";
//            echo "<p>".$post->content."</p>";
//            echo '<hr>';
//        }
        
        $categories = Category::all();
        foreach($categories as $category){
            echo "<h1>".$category->name."</h1>";
            foreach($category->posts as $post){
                echo "<h1>".$post->title."</h1>";
                echo "<span style='color:gray;'>{$post->user->name} - {$post->category->name}</span>";
                echo "<p>".$post->content."</p>";
            }
            echo '<hr>';
        }
        die();
    }
    public function message(){
        $category = \App\Models\Category::findOrFail(1);
        echo '<pre>';
        echo \Utilities::responseMessage(200, true, '', ['category' => $category]);
        echo '</pre>';
    }
}
