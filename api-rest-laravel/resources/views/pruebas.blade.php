<?php 
// use Illuminate\Support\Str;
// require_once __DIR__.'/../../../util/Util.php';
use App\Utilities\Util as Utiles;
?>
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Prueba de vistas con Laravel</title>
</head>
<body>
    <p>Estoy escribiendo desde la vista</p>
    <?= $texto ?>
    <p>UUID generada: <?= Utiles::getUUID(); ?></p>
    
</body>
</html>