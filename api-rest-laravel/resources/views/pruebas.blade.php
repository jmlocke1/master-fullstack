<?php
use App\Utilities\Utilities;
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
    <p>UUID generada: <?= Utilities::getUUID(); ?></p>
    
</body>
</html>